<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Canton;
use App\Models\admin\Continente;
use App\Models\admin\Parroquia;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParroquiaController extends Controller
{
    public function index()
    {
        $parroquias = Parroquia::with('canton.provincia.pais.continente')
            ->orderBy('Nombre')
            ->get();

        return view('contacto.parroquia.index', compact('parroquias'));
    }

    public function create()
    {
        $cantones = Canton::with('provincia.pais')->orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();

        return view('contacto.parroquia.create', compact('cantones', 'ubicaciones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:matriz.parroquia,Nombre',
            'id_canton' => 'nullable|exists:matriz.ciudad,IdCiudad',
        ]);

        Parroquia::create([
            'Nombre' => $validated['nombre'],
            'IdCiudad' => $validated['id_canton'] ?? null,
        ]);

        return redirect()->route('contacto.parroquia.index')
            ->with('success', 'Parroquia creada correctamente.');
    }

    public function show(string $parroquia)
    {
        $parroquia = Parroquia::with('canton.provincia.pais.continente')->findOrFail($parroquia);

        return view('contacto.parroquia.show', compact('parroquia'));
    }

    public function edit(string $parroquia)
    {
        $parroquia = Parroquia::findOrFail($parroquia);
        $cantones = Canton::with('provincia.pais')->orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();

        return view('contacto.parroquia.edit', compact('parroquia', 'cantones', 'ubicaciones'));
    }

    public function update(Request $request, string $parroquia)
    {
        $parroquia = Parroquia::findOrFail($parroquia);

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('matriz.parroquia', 'Nombre')->ignore($parroquia->IdParroquia, 'IdParroquia'),
            ],
            'id_canton' => 'nullable|exists:matriz.ciudad,IdCiudad',
        ]);

        $parroquia->update([
            'Nombre' => $validated['nombre'],
            'IdCiudad' => $validated['id_canton'] ?? null,
        ]);

        return redirect()->route('contacto.parroquia.index')
            ->with('success', 'Parroquia actualizada correctamente.');
    }

    public function destroy(string $parroquia)
    {
        $parroquia = Parroquia::findOrFail($parroquia);

        try {
            $parroquia->delete();
        } catch (QueryException $exception) {
            return redirect()->route('contacto.parroquia.index')
                ->with('error', 'No se puede eliminar la parroquia porque tiene registros relacionados.');
        }

        return redirect()->route('contacto.parroquia.index')
            ->with('success', 'Parroquia eliminada correctamente.');
    }

    private function ubicacionesJerarquicas()
    {
        return Continente::with('paises.provincias.cantones')
            ->orderBy('Nombre')
            ->get();
    }
}
