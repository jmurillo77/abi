<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\matriz\Direccion;
use App\Models\negocio\Pedido;
use App\Models\negocio\Producto;
use App\Models\negocio\Ruta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    // Un producto pedido como "porción" cuesta un porcentaje del precio completo.
    private const FACTOR_PORCION = 0.6;

    public function index()
    {
        $pedidos = Pedido::with(['cliente', 'direccion', 'ruta'])->orderBy('id', 'desc')->get();

        return view('venta.pedido.index', compact('pedidos'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('Nombre')->get();
        $rutas = $this->rutasDisponibles();
        [$menuDia, $aLaCarta, $porciones] = $this->productosDisponibles();

        return view('venta.pedido.create', compact('clientes', 'rutas', 'menuDia', 'aLaCarta', 'porciones'));
    }

    public function direccionesCliente(Cliente $cliente)
    {
        $direcciones = $cliente->direccionesEnvio()
            ->map(fn ($direccion, $index) => [
                'id' => $direccion->IdDireccion,
                'etiqueta' => $direccion->Etiqueta,
                'principal' => $index === 0,
                'ubicacion' => $direccion->Ubicacion,
                'google_maps_url' => $direccion->GoogleMapsUrl,
                'id_ruta_sugerida' => $this->rutaSugeridaParaDireccion($direccion),
            ])
            ->values();

        return response()->json(['direcciones' => $direcciones]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $pedido = $this->guardarPedidoConDetalles($validated, $request->user()?->email);

        return redirect()->route('ventas.pedido.show', $pedido->id)
            ->with('success', 'Pedido registrado correctamente.');
    }

    public function show(string $id)
    {
        $pedido = Pedido::with(['cliente', 'direccion.tipo', 'direccion.parroquia.ciudad', 'detalles', 'ruta'])->findOrFail($id);

        return view('venta.pedido.show', compact('pedido'));
    }

    public function edit(string $id)
    {
        $pedido = Pedido::with(['cliente', 'detalles'])->findOrFail($id);
        $clientes = Cliente::orderBy('Nombre')->get();
        $direcciones = $pedido->cliente ? $pedido->cliente->direccionesEnvio() : collect();
        $rutas = $this->rutasParaEdicion($pedido);

        return view('venta.pedido.edit', compact('pedido', 'clientes', 'direcciones', 'rutas'));
    }

    public function update(Request $request, string $id)
    {
        $pedido = Pedido::findOrFail($id);

        $validated = $request->validate([
            'IdCliente' => ['required', 'exists:negocio.clientes,id'],
            'IdDireccion' => ['nullable', 'integer'],
            'IdRuta' => ['nullable', 'exists:negocio.rutas,id'],
            'Estado' => ['required', 'in:PENDIENTE,EN_PREPARACION,ENTREGADO,CANCELADO'],
            'Observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $pedido->update([
            'IdCliente' => $validated['IdCliente'],
            'IdDireccion' => $validated['IdDireccion'] ?? null,
            'IdRuta' => $validated['IdRuta'] ?? null,
            'Estado' => $validated['Estado'],
            'Observaciones' => $validated['Observaciones'] ?? null,
            'uUser' => $request->user()?->email,
        ]);

        return redirect()->route('ventas.pedido.show', $pedido->id)
            ->with('success', 'Pedido actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->delete();

        return redirect()->route('ventas.pedido.index')
            ->with('success', 'Pedido eliminado correctamente.');
    }

    private function productosDisponibles(): array
    {
        // "A la carta" es el catálogo completo (precio íntegro). "Menú del día" y
        // "Porciones" son subconjuntos según TipoMenu: ALMUERZO = set fijo del día,
        // PIQUEO = pensado para vender por porción (con el descuento FACTOR_PORCION),
        // AMBOS aparece en los dos. UsaMenu ya no distingue nada dentro del nivel MENU
        // (todo producto MENU lo tiene en 'S'), por eso no se usa aquí.
        $productos = Producto::where('Activo', 1)
            ->where('Eliminado', 'N')
            ->where('TipoProducto', 'MENU')
            ->orderBy('Nombre')
            ->get();

        $menuDia = $productos->whereIn('TipoMenu', ['ALMUERZO', 'AMBOS'])->values();
        $porciones = $productos->whereIn('TipoMenu', ['PIQUEO', 'AMBOS'])->values();

        return [$menuDia, $productos, $porciones];
    }

    /**
     * Rutas que todavía tiene sentido asignar a un pedido nuevo (se excluyen las
     * ya finalizadas o canceladas), de la más reciente a la más antigua.
     */
    private function rutasDisponibles()
    {
        return Ruta::whereIn('Estado', ['PLANIFICADA', 'EN_CURSO'])
            ->orderBy('Fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Igual que rutasDisponibles(), pero garantiza que la ruta ya asignada al pedido
     * quede en la lista aunque esté finalizada/cancelada, para no perderla por
     * accidente al guardar el formulario de edición si no aparecía como opción.
     */
    private function rutasParaEdicion(Pedido $pedido)
    {
        $rutas = $this->rutasDisponibles();

        if ($pedido->IdRuta && ! $rutas->contains('id', $pedido->IdRuta)) {
            $rutaActual = Ruta::find($pedido->IdRuta);

            if ($rutaActual) {
                $rutas->push($rutaActual);
            }
        }

        return $rutas;
    }

    /**
     * Recorrido por defecto para una dirección. Prioridad:
     * 1) el recorrido asignado directamente a la dirección (Direccion.IdRuta),
     * 2) el último recorrido usado para esta misma dirección en pedidos anteriores,
     * 3) el último recorrido usado por cualquier otra dirección de la misma parroquia.
     * Los pasos 2 y 3 son el respaldo para direcciones sin recorrido asignado todavía
     * (por ejemplo, registradas antes de que existiera este campo).
     */
    private function rutaSugeridaParaDireccion(Direccion $direccion): ?int
    {
        if ($direccion->IdRuta && Ruta::whereIn('Estado', ['PLANIFICADA', 'EN_CURSO'])->where('id', $direccion->IdRuta)->exists()) {
            return (int) $direccion->IdRuta;
        }

        $rutaPorDireccion = Pedido::where('IdDireccion', $direccion->IdDireccion)
            ->whereNotNull('IdRuta')
            ->whereHas('ruta', fn ($q) => $q->whereIn('Estado', ['PLANIFICADA', 'EN_CURSO']))
            ->orderByDesc('Fecha')
            ->orderByDesc('id')
            ->value('IdRuta');

        if ($rutaPorDireccion) {
            return (int) $rutaPorDireccion;
        }

        if (! $direccion->IdParroquia) {
            return null;
        }

        $idsDireccionesParroquia = Direccion::where('IdParroquia', $direccion->IdParroquia)->pluck('IdDireccion');

        $rutaPorParroquia = Pedido::whereIn('IdDireccion', $idsDireccionesParroquia)
            ->whereNotNull('IdRuta')
            ->whereHas('ruta', fn ($q) => $q->whereIn('Estado', ['PLANIFICADA', 'EN_CURSO']))
            ->orderByDesc('Fecha')
            ->orderByDesc('id')
            ->value('IdRuta');

        return $rutaPorParroquia ? (int) $rutaPorParroquia : null;
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'IdCliente' => ['required', 'exists:negocio.clientes,id'],
            'IdDireccion' => ['nullable', 'integer'],
            'IdRuta' => ['nullable', 'exists:negocio.rutas,id'],
            'Observaciones' => ['nullable', 'string', 'max:500'],
            'TipoEnvio' => ['required', 'in:NINGUNO,GLOBAL,POR_ITEM'],
            'CostoEnvio' => ['nullable', 'numeric', 'min:0', 'max:9999'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_producto' => ['required', 'exists:negocio.producto,IdProducto'],
            'items.*.tipo_item' => ['required', 'in:MENU_DIA,CARTA,PORCION'],
            'items.*.cantidad' => ['required', 'numeric', 'min:0.1'],
            'items.*.costo_envio' => ['nullable', 'numeric', 'min:0', 'max:9999'],
        ]);
    }

    private function guardarPedidoConDetalles(array $validated, ?string $usuario = null): Pedido
    {
        return DB::connection('negocio')->transaction(function () use ($validated, $usuario) {
            $tipoEnvio = $validated['TipoEnvio'];
            $costoEnvioGlobal = $tipoEnvio === 'GLOBAL' ? (float) ($validated['CostoEnvio'] ?? 0) : 0;

            $pedido = Pedido::create([
                'IdCliente' => $validated['IdCliente'],
                'IdDireccion' => $validated['IdDireccion'] ?? null,
                'IdRuta' => $validated['IdRuta'] ?? null,
                'Fecha' => now(),
                'Estado' => 'PENDIENTE',
                'Total' => 0,
                'TipoEnvio' => $tipoEnvio,
                'CostoEnvio' => $costoEnvioGlobal,
                'Observaciones' => $validated['Observaciones'] ?? null,
                'cUser' => $usuario,
            ]);

            $total = $costoEnvioGlobal;

            foreach ($validated['items'] as $item) {
                // El precio y el nombre siempre se recalculan desde el producto en servidor,
                // nunca se confía en un valor enviado por el cliente.
                $producto = Producto::findOrFail($item['id_producto']);
                $precioUnitario = $item['tipo_item'] === 'PORCION'
                    ? round((float) $producto->CostoUnitario * self::FACTOR_PORCION, 2)
                    : (float) $producto->CostoUnitario;

                $cantidad = (float) $item['cantidad'];
                $costoEnvioItem = $tipoEnvio === 'POR_ITEM' ? (float) ($item['costo_envio'] ?? 0) : 0;
                $subtotal = round($precioUnitario * $cantidad, 2);
                $total += $subtotal + $costoEnvioItem;

                $pedido->detalles()->create([
                    'IdProducto' => $producto->IdProducto,
                    'Nombre' => $producto->Nombre,
                    'TipoItem' => $item['tipo_item'],
                    'Cantidad' => $cantidad,
                    'PrecioUnitario' => $precioUnitario,
                    'Subtotal' => $subtotal,
                    'CostoEnvio' => $costoEnvioItem,
                ]);
            }

            $pedido->update(['Total' => $total]);

            return $pedido;
        });
    }
}
