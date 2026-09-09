<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use App\Models\Negocio\Producto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('Categoria')->orderBy('Nombre')->get();

        $materiasPrimas = $productos->where('TipoProducto', 'MATERIA_PRIMA')->values();
        $recetas = $productos->where('TipoProducto', 'RECETA')->values();
        $menus = $productos->where('TipoProducto', 'MENU')->values();

        return view('venta.producto.index', [
            'materiasPrimas' => $materiasPrimas,
            'recetas' => $recetas,
            'menus' => $menus,
            'categoriasMateriaPrima' => Producto::CATEGORIAS_MATERIA_PRIMA,
            'categoriasReceta' => Producto::CATEGORIAS_RECETA,
            'categoriasMenu' => Producto::CATEGORIAS_MENU,
        ]);
    }

    public function create()
    {
        return view('venta.producto.create', $this->datosFormulario());
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $producto = Producto::create($this->payloadFromValidated($validated));

        $this->sincronizarIngredientes($producto, $request->input('ingredientes', []));

        return redirect()
            ->route('ventas.producto.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(string $id)
    {
        $producto = Producto::with(['ingredientes.insumo', 'usadoComoInsumoEn.producto'])->findOrFail($id);

        return view('venta.producto.show', compact('producto'));
    }

    public function edit(string $id)
    {
        $producto = Producto::with('ingredientes')->findOrFail($id);

        return view('venta.producto.edit', $this->datosFormulario($producto) + compact('producto'));
    }

    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);
        $validated = $this->validateRequest($request, $producto->IdProducto);

        $producto->update($this->payloadFromValidated($validated));

        $this->sincronizarIngredientes($producto, $request->input('ingredientes', []));

        return redirect()
            ->route('ventas.producto.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $producto = Producto::findOrFail($id);

        try {
            $producto->delete();
        } catch (QueryException $exception) {
            return redirect()
                ->route('ventas.producto.index')
                ->with('error', 'No se puede eliminar el producto porque tiene registros relacionados.');
        }

        return redirect()
            ->route('ventas.producto.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    /**
     * Datos comunes para los formularios de crear/editar: catálogo de categorías por nivel
     * y la lista de posibles insumos (materia prima + recetas) para armar la composición.
     */
    private function datosFormulario(?Producto $producto = null): array
    {
        $insumos = Producto::whereIn('TipoProducto', ['MATERIA_PRIMA', 'RECETA'])
            ->where('Activo', 1)
            ->when($producto, fn ($query) => $query->where('IdProducto', '!=', $producto->IdProducto))
            ->orderBy('Nombre')
            ->get(['IdProducto', 'Nombre', 'TipoProducto', 'UnidadMedida', 'CostoUnitario']);

        return [
            'categoriasMateriaPrima' => Producto::CATEGORIAS_MATERIA_PRIMA,
            'categoriasReceta' => Producto::CATEGORIAS_RECETA,
            'categoriasMenu' => Producto::CATEGORIAS_MENU,
            'insumos' => $insumos,
        ];
    }

    protected function validateRequest(Request $request, ?int $id = null): array
    {
        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:120',
                Rule::unique('negocio.producto', 'Nombre')->ignore($id, 'IdProducto'),
            ],
            'descripcion' => 'nullable|string|max:500',
            'tipo_producto' => 'required|in:MATERIA_PRIMA,RECETA,MENU',
            'categoria' => 'nullable|string|max:60',
            'unidad_medida' => 'nullable|string|max:30',
            'costo_unitario' => 'nullable|numeric|min:0',
            'stock_actual' => 'nullable|numeric|min:0',
            'porcentaje_merma' => 'nullable|numeric|min:0|max:100',
            'rendimiento_cantidad' => 'nullable|numeric|min:0',
            'rendimiento_unidad' => 'nullable|string|max:30',
            'usa_receta' => 'nullable|boolean',
            'usa_menu' => 'nullable|boolean',
            'tipo_menu' => 'nullable|in:ALMUERZO,PIQUEO,AMBOS',
            'activo' => 'nullable|boolean',
            'ingredientes' => 'nullable|array',
            'ingredientes.*.id_insumo' => 'nullable|exists:negocio.producto,IdProducto',
            'ingredientes.*.cantidad' => 'nullable|numeric|min:0.001',
            'ingredientes.*.unidad_medida' => 'nullable|string|max:30',
        ];

        $validated = $request->validate($rules);

        // El campo "Tipo de menú" solo aplica (y solo se muestra en el formulario) para
        // el nivel MENU: es lo que usa la toma de pedidos para armar las pestañas de
        // Menú del día / Porciones, así que depende únicamente del nivel, no del switch
        // "usa_menu" (que es independiente y no debe poder vaciarlo por accidente).
        if ($validated['tipo_producto'] !== 'MENU') {
            $validated['tipo_menu'] = null;
        }

        // La subcategoría solo tiene sentido dentro del catálogo definido para ese nivel.
        $categoriasValidas = match ($validated['tipo_producto']) {
            'MATERIA_PRIMA' => array_keys(Producto::CATEGORIAS_MATERIA_PRIMA),
            'RECETA' => array_keys(Producto::CATEGORIAS_RECETA),
            'MENU' => array_keys(Producto::CATEGORIAS_MENU),
            default => [],
        };

        if (! in_array($validated['categoria'] ?? null, $categoriasValidas, true)) {
            $validated['categoria'] = null;
        }

        if ($validated['tipo_producto'] !== 'MATERIA_PRIMA') {
            $validated['porcentaje_merma'] = null;
        }

        if ($validated['tipo_producto'] !== 'RECETA') {
            $validated['rendimiento_cantidad'] = null;
            $validated['rendimiento_unidad'] = null;
        }

        return $validated;
    }

    protected function payloadFromValidated(array $validated): array
    {
        return [
            'Nombre' => $validated['nombre'],
            'Descripcion' => $validated['descripcion'] ?? null,
            'TipoProducto' => $validated['tipo_producto'],
            'Categoria' => $validated['categoria'] ?? null,
            'UnidadMedida' => $validated['unidad_medida'] ?? null,
            'CostoUnitario' => array_key_exists('costo_unitario', $validated) ? $validated['costo_unitario'] : null,
            'StockActual' => array_key_exists('stock_actual', $validated) ? $validated['stock_actual'] : null,
            'PorcentajeMerma' => $validated['porcentaje_merma'] ?? null,
            'RendimientoCantidad' => $validated['rendimiento_cantidad'] ?? null,
            'RendimientoUnidad' => $validated['rendimiento_unidad'] ?? null,
            'UsaReceta' => ! empty($validated['usa_receta']) ? 'S' : 'N',
            'UsaMenu' => ! empty($validated['usa_menu']) ? 'S' : 'N',
            'TipoMenu' => $validated['tipo_menu'] ?? null,
            'Activo' => ! empty($validated['activo']) ? 1 : 0,
        ];
    }

    private function sincronizarIngredientes(Producto $producto, array $ingredientes): void
    {
        DB::connection('negocio')->transaction(function () use ($producto, $ingredientes) {
            $producto->ingredientes()->delete();

            foreach ($ingredientes as $ingrediente) {
                if (empty($ingrediente['id_insumo']) || empty($ingrediente['cantidad'])) {
                    continue;
                }

                $producto->ingredientes()->create([
                    'IdInsumo' => $ingrediente['id_insumo'],
                    'Cantidad' => $ingrediente['cantidad'],
                    'UnidadMedida' => $ingrediente['unidad_medida'] ?? null,
                ]);
            }
        });
    }
}

