<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Continente;
use App\Models\admin\Pais;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaisController extends Controller
{
    public function index()
    {
        $paises = Pais::with('continente')
            ->orderBy('Nombre')
            ->get();

        $ubicaciones = Continente::with('paises')
            ->orderBy('Nombre')
            ->get();

        return view('contacto.pais.index', compact('paises', 'ubicaciones'));
    }

    public function create()
    {
        $continentes = Continente::orderBy('Nombre')->get();

        return view('contacto.pais.create', compact('continentes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:matriz.pais,Nombre',
            'id_continente' => 'nullable|exists:matriz.continentes,IdContinente',
        ]);

        Pais::create([
            'Nombre' => $validated['nombre'],
            'IdContinente' => $validated['id_continente'] ?? null,
        ]);

        return redirect()
            ->route('contacto.pais.index')
            ->with('success', 'País creado correctamente.');
    }

    public function show(string $id)
    {
        $pais = Pais::with('continente')->findOrFail($id);

        return view('contacto.pais.show', compact('pais'));
    }

    public function edit(string $id)
    {
        $pais = Pais::findOrFail($id);
        $continentes = Continente::orderBy('Nombre')->get();

        return view('contacto.pais.edit', compact('pais', 'continentes'));
    }

    public function update(Request $request, string $id)
    {
        $pais = Pais::findOrFail($id);

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('matriz.pais', 'Nombre')->ignore($pais->IdPais, 'IdPais'),
            ],
            'id_continente' => 'nullable|exists:matriz.continentes,IdContinente',
        ]);

        $pais->update([
            'Nombre' => $validated['nombre'],
            'IdContinente' => $validated['id_continente'] ?? null,
        ]);

        return redirect()
            ->route('contacto.pais.index')
            ->with('success', 'País actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $pais = Pais::findOrFail($id);

        try {
            $pais->delete();
        } catch (QueryException $exception) {
            return redirect()
                ->route('contacto.pais.index')
                ->with('error', 'No se puede eliminar el país porque tiene registros relacionados.');
        }

        return redirect()
            ->route('contacto.pais.index')
            ->with('success', 'País eliminado correctamente.');
    }
}
