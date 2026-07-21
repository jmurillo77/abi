<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\matriz\Correo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CorreoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $correos = Correo::query()
            ->with(['personas', 'empresas'])
            ->orderByDesc('IdCorreo')
            ->get();

        return view('admin.correo.index', compact('correos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.correo.agregar');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Correo' => 'required|email|max:200|unique:matriz.correos,Correo',
            'Valido' => 'nullable|boolean',
        ]);

        $correo = Correo::create([
            'Correo' => $validated['Correo'],
            'Valido' => $request->boolean('Valido') ? '1' : '0',
        ]);

        return redirect()
            ->route('contacto.correo.edit', $correo->IdCorreo)
            ->with('success', 'Correo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $correo = Correo::with(['personas', 'empresas'])->findOrFail($id);

        return view('admin.correo.show', compact('correo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $correo = Correo::with(['personas', 'empresas'])->findOrFail($id);

        return view('admin.correo.actualizar', compact('correo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $correo = Correo::findOrFail($id);

        $validated = $request->validate([
            'Correo' => [
                'required',
                'email',
                'max:200',
                Rule::unique('matriz.correos', 'Correo')->ignore($correo->IdCorreo, 'IdCorreo'),
            ],
            'Valido' => 'nullable|boolean',
        ]);

        $correo->update([
            'Correo' => $validated['Correo'],
            'Valido' => $request->boolean('Valido') ? '1' : '0',
        ]);

        return redirect()
            ->route('contacto.correo.edit', $correo->IdCorreo)
            ->with('success', 'Correo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $correo = Correo::with(['personas', 'empresas'])->findOrFail($id);

        if ($correo->personas->isNotEmpty() || $correo->empresas->isNotEmpty()) {
            return redirect()
                ->route('contacto.correo.index')
                ->with('error', 'No se puede eliminar el correo porque está relacionado con personas o empresas.');
        }

        try {
            $correo->delete();
        } catch (QueryException) {
            return redirect()
                ->route('contacto.correo.index')
                ->with('error', 'No se pudo eliminar el correo porque tiene relaciones activas en otros módulos.');
        }

        return redirect()
            ->route('contacto.correo.index')
            ->with('success', 'Correo eliminado correctamente.');
    }
}
