<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Continente;
use Illuminate\Http\Request;

class ContinenteController extends Controller
{
    public function index()
    {
        $continentes = Continente::orderBy('Nombre')->get();
        return view('contacto.continente.index', compact('continentes'));
    }

    public function create()
    {
        return view('contacto.continente.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:matriz.continentes,Nombre',
        ]);

        Continente::create([
            'Nombre' => $validated['nombre'],
        ]);

        return redirect()->route('contacto.continente.index')
            ->with('success', 'Continente creado correctamente.');
    }

    public function show(string $continente)
    {
        $continente = Continente::findOrFail($continente);

        return view('contacto.continente.show', compact('continente'));
    }

    public function edit(string $continente)
    {
        $continente = Continente::findOrFail($continente);

        return view('contacto.continente.edit', compact('continente'));
    }

    public function update(Request $request, string $continente)
    {
        $continente = Continente::findOrFail($continente);

        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:matriz.continentes,Nombre,'.$continente->IdContinente.',IdContinente',
        ]);

        $continente->update([
            'Nombre' => $validated['nombre'],
        ]);

        return redirect()->route('contacto.continente.index')
            ->with('success', 'Continente actualizado correctamente.');
    }

    public function destroy(string $continente)
    {
        $continente = Continente::findOrFail($continente);
        $continente->delete();

        return redirect()->route('contacto.continente.index')
            ->with('success', 'Continente eliminado correctamente.');
    }
}
