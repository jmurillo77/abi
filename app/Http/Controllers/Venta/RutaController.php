<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use App\Models\negocio\Pedido;
use App\Models\negocio\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        $rutas = Ruta::withCount('pedidos')->orderBy('Fecha', 'desc')->orderBy('id', 'desc')->get();

        return view('venta.ruta.index', compact('rutas'));
    }

    public function create()
    {
        return view('venta.ruta.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $ruta = Ruta::create($validated + ['cUser' => $request->user()?->email]);

        return redirect()->route('ventas.ruta.show', $ruta->id)
            ->with('success', 'Ruta creada correctamente.');
    }

    public function show(string $id)
    {
        $ruta = Ruta::with(['pedidos.cliente', 'pedidos.direccion'])->findOrFail($id);

        $pedidosDisponibles = Pedido::with('cliente')
            ->whereNotNull('IdDireccion')
            ->whereNull('IdRuta')
            ->orderBy('Fecha', 'desc')
            ->get();

        return view('venta.ruta.show', compact('ruta', 'pedidosDisponibles'));
    }

    public function edit(string $id)
    {
        $ruta = Ruta::findOrFail($id);

        return view('venta.ruta.edit', compact('ruta'));
    }

    public function update(Request $request, string $id)
    {
        $ruta = Ruta::findOrFail($id);
        $validated = $this->validateRequest($request);

        $ruta->update($validated + ['uUser' => $request->user()?->email]);

        return redirect()->route('ventas.ruta.show', $ruta->id)
            ->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->pedidos()->update(['IdRuta' => null]);
        $ruta->delete();

        return redirect()->route('ventas.ruta.index')
            ->with('success', 'Ruta eliminada correctamente.');
    }

    public function asignarPedidos(Request $request, string $id)
    {
        $ruta = Ruta::findOrFail($id);

        $validated = $request->validate([
            'pedidos' => ['required', 'array', 'min:1'],
            'pedidos.*' => ['exists:negocio.pedidos,id'],
        ]);

        Pedido::whereIn('id', $validated['pedidos'])
            ->whereNull('IdRuta')
            ->update(['IdRuta' => $ruta->id]);

        return redirect()->route('ventas.ruta.show', $ruta->id)
            ->with('success', 'Pedidos asignados a la ruta.');
    }

    public function quitarPedido(string $id, string $pedidoId)
    {
        $ruta = Ruta::findOrFail($id);

        Pedido::where('id', $pedidoId)->where('IdRuta', $ruta->id)->update(['IdRuta' => null]);

        return redirect()->route('ventas.ruta.show', $ruta->id)
            ->with('success', 'Pedido removido de la ruta.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'Nombre' => ['required', 'string', 'max:150'],
            'Fecha' => ['required', 'date'],
            'Estado' => ['required', 'in:PLANIFICADA,EN_CURSO,FINALIZADA,CANCELADA'],
            'Observaciones' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
