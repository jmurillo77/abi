<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Continente;
use App\Models\admin\Pais;
use App\Models\admin\Provincia;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProvinciaController extends Controller
{
    public function index()
    {
        $provincias = Provincia::with('pais.continente')
            ->orderBy('Nombre')
            ->get();

        $ubicaciones = Continente::with('paises.provincias')
            ->orderBy('Nombre')
            ->get();

        return view('contacto.provincia.index', compact('provincias', 'ubicaciones'));
    }

    public function create()
    {
        $paises = Pais::orderBy('Nombre')->get();

        return view('contacto.provincia.create', compact('paises'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:matriz.provincia,Nombre',
            'id_pais' => 'nullable|exists:matriz.pais,IdPais',
        ]);

        Provincia::create([
            'Nombre' => $validated['nombre'],
            'IdPais' => $validated['id_pais'] ?? null,
        ]);

        return redirect()->route('contacto.provincia.index')
            ->with('success', 'Provincia creada correctamente.');
    }

    public function show(string $provincia)
    {
        $provincia = Provincia::with('pais')->findOrFail($provincia);

        return view('contacto.provincia.show', compact('provincia'));
    }

    public function edit(string $provincia)
    {
        $provincia = Provincia::findOrFail($provincia);
        $paises = Pais::orderBy('Nombre')->get();

        return view('contacto.provincia.edit', compact('provincia', 'paises'));
    }

    public function update(Request $request, string $provincia)
    {
        $provincia = Provincia::findOrFail($provincia);

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('matriz.provincia', 'Nombre')->ignore($provincia->IdProvincia, 'IdProvincia'),
            ],
            'id_pais' => 'nullable|exists:matriz.pais,IdPais',
        ]);

        $provincia->update([
            'Nombre' => $validated['nombre'],
            'IdPais' => $validated['id_pais'] ?? null,
        ]);

        return redirect()->route('contacto.provincia.index')
            ->with('success', 'Provincia actualizada correctamente.');
    }

    public function destroy(string $provincia)
    {
        $provincia = Provincia::findOrFail($provincia);

        try {
            $provincia->delete();
        } catch (QueryException $exception) {
            return redirect()->route('contacto.provincia.index')
                ->with('error', 'No se puede eliminar la provincia porque tiene registros relacionados.');
        }

        return redirect()->route('contacto.provincia.index')
            ->with('success', 'Provincia eliminada correctamente.');
    }
}
