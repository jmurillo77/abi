<?php

namespace App\Http\Controllers\Venta;

use App\Http\Controllers\Controller;
use App\Models\Negocio\Producto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderByRaw("CASE WHEN TipoProducto = 'MATERIA_PRIMA' THEN 0 ELSE 1 END")
            ->orderBy('Nombre')
            ->get();

        return view('venta.producto.index', compact('productos'));
    }

    public function create()
    {
        return view('venta.producto.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        Producto::create($this->payloadFromValidated($validated));

        return redirect()
            ->route('ventas.producto.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(string $id)
    {
        $producto = Producto::findOrFail($id);

        return view('venta.producto.show', compact('producto'));
    }

    public function edit(string $id)
    {
        $producto = Producto::findOrFail($id);

        return view('venta.producto.edit', compact('producto'));
    }

    public function update(Request $request, string $id)
    {
        $producto = Producto::findOrFail($id);
        $validated = $this->validateRequest($request, $producto->IdProducto);

        $producto->update($this->payloadFromValidated($validated));

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
            'tipo_producto' => 'required|in:MATERIA_PRIMA,ELABORADO',
            'unidad_medida' => 'nullable|string|max:30',
            'costo_unitario' => 'nullable|numeric|min:0',
            'stock_actual' => 'nullable|numeric|min:0',
            'usa_receta' => 'nullable|boolean',
            'usa_menu' => 'nullable|boolean',
            'tipo_menu' => 'nullable|in:ALMUERZO,PIQUEO,AMBOS',
            'activo' => 'nullable|boolean',
        ];

        $validated = $request->validate($rules);

        if (! $request->boolean('usa_menu')) {
            $validated['tipo_menu'] = null;
        }

        return $validated;
    }

    protected function payloadFromValidated(array $validated): array
    {
        return [
            'Nombre' => $validated['nombre'],
            'Descripcion' => $validated['descripcion'] ?? null,
            'TipoProducto' => $validated['tipo_producto'],
            'UnidadMedida' => $validated['unidad_medida'] ?? null,
            'CostoUnitario' => array_key_exists('costo_unitario', $validated) ? $validated['costo_unitario'] : null,
            'StockActual' => array_key_exists('stock_actual', $validated) ? $validated['stock_actual'] : null,
            'UsaReceta' => ! empty($validated['usa_receta']) ? 'S' : 'N',
            'UsaMenu' => ! empty($validated['usa_menu']) ? 'S' : 'N',
            'TipoMenu' => $validated['tipo_menu'] ?? null,
            'Activo' => ! empty($validated['activo']) ? 1 : 0,
        ];
    }
}
