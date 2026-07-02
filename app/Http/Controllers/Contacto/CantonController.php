<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Canton;
use App\Models\admin\Continente;
use App\Models\admin\Provincia;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CantonController extends Controller
{
    public function index()
    {
        $cantones = Canton::with('provincia.pais.continente')
            ->orderBy('Nombre')
            ->get();

        return view('contacto.canton.index', compact('cantones'));
    }

    public function create()
    {
        $provincias = Provincia::with('pais')->orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();

        return view('contacto.canton.create', compact('provincias', 'ubicaciones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:matriz.ciudad,Nombre',
            'id_provincia' => 'nullable|exists:matriz.provincia,IdProvincia',
        ]);

        Canton::create([
            'Nombre' => $validated['nombre'],
            'IdProvincia' => $validated['id_provincia'] ?? null,
        ]);

        return redirect()->route('contacto.canton.index')
            ->with('success', 'Cantón creado correctamente.');
    }

    public function show(string $canton)
    {
        $canton = Canton::with('provincia.pais')->findOrFail($canton);

        return view('contacto.canton.show', compact('canton'));
    }

    public function edit(string $canton)
    {
        $canton = Canton::findOrFail($canton);
        $provincias = Provincia::with('pais')->orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();

        return view('contacto.canton.edit', compact('canton', 'provincias', 'ubicaciones'));
    }

    public function update(Request $request, string $canton)
    {
        $canton = Canton::findOrFail($canton);

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('matriz.ciudad', 'Nombre')->ignore($canton->IdCiudad, 'IdCiudad'),
            ],
            'id_provincia' => 'nullable|exists:matriz.provincia,IdProvincia',
        ]);

        $canton->update([
            'Nombre' => $validated['nombre'],
            'IdProvincia' => $validated['id_provincia'] ?? null,
        ]);

        return redirect()->route('contacto.canton.index')
            ->with('success', 'Cantón actualizado correctamente.');
    }

    public function destroy(string $canton)
    {
        $canton = Canton::findOrFail($canton);

        try {
            $canton->delete();
        } catch (QueryException $exception) {
            return redirect()->route('contacto.canton.index')
                ->with('error', 'No se puede eliminar el cantón porque tiene registros relacionados.');
        }

        return redirect()->route('contacto.canton.index')
            ->with('success', 'Cantón eliminado correctamente.');
    }

    private function ubicacionesJerarquicas()
    {
        return Continente::with('paises.provincias')
            ->orderBy('Nombre')
            ->get();
    }
}
