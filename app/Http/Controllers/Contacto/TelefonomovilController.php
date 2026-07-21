<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\TelefonoTipoOperadora;
use App\Models\matriz\TelefonoMovil;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TelefonomovilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $numeros = TelefonoMovil::with(['operadora', 'personas', 'empresas'])
            ->orderByDesc('IdTelefonoMovil')
            ->get();

        return view('admin.telefono_movil.index', compact('numeros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operadoras = TelefonoTipoOperadora::orderBy('Nombre')->get();

        return view('admin.telefono_movil.agregar', compact('operadoras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Numero' => 'required|string|max:10|unique:matriz.telefono_movils,Numero',
            'IdOperadora' => 'required|exists:matriz.telefono_tipo_operadoras,IdOperadora',
            'PhoneValido' => 'nullable|boolean',
            'WhatsappValido' => 'nullable|boolean',
        ]);

        $telefono = TelefonoMovil::create([
            'Numero' => $validated['Numero'],
            'IdOperadora' => $validated['IdOperadora'],
            'PhoneValido' => $request->boolean('PhoneValido') ? '1' : '0',
            'WhatsappValido' => $request->boolean('WhatsappValido') ? '1' : '0',
        ]);

        return redirect()
            ->route('contacto.telefono_movil.edit', $telefono->IdTelefonoMovil)
            ->with('success', 'Teléfono móvil creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $telefono = TelefonoMovil::with(['operadora', 'personas', 'empresas'])->findOrFail($id);

        return view('admin.telefono_movil.show', compact('telefono'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $telefono = TelefonoMovil::with(['operadora', 'personas', 'empresas'])->findOrFail($id);
        $operadoras = TelefonoTipoOperadora::orderBy('Nombre')->get();

        return view('admin.telefono_movil.actualizar', compact('telefono', 'operadoras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $telefono = TelefonoMovil::findOrFail($id);

        $validated = $request->validate([
            'Numero' => [
                'required',
                'string',
                'max:10',
                Rule::unique('matriz.telefono_movils', 'Numero')->ignore($telefono->IdTelefonoMovil, 'IdTelefonoMovil'),
            ],
            'IdOperadora' => 'required|exists:matriz.telefono_tipo_operadoras,IdOperadora',
            'PhoneValido' => 'nullable|boolean',
            'WhatsappValido' => 'nullable|boolean',
        ]);

        $telefono->update([
            'Numero' => $validated['Numero'],
            'IdOperadora' => $validated['IdOperadora'],
            'PhoneValido' => $request->boolean('PhoneValido') ? '1' : '0',
            'WhatsappValido' => $request->boolean('WhatsappValido') ? '1' : '0',
        ]);

        return redirect()
            ->route('contacto.telefono_movil.edit', $telefono->IdTelefonoMovil)
            ->with('success', 'Teléfono móvil actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $telefono = TelefonoMovil::with(['personas', 'empresas'])->findOrFail($id);

        if ($telefono->personas->isNotEmpty() || $telefono->empresas->isNotEmpty()) {
            return redirect()
                ->route('contacto.telefono_movil.index')
                ->with('error', 'No se puede eliminar el teléfono porque está relacionado con personas o empresas.');
        }

        try {
            $telefono->delete();
        } catch (QueryException) {
            return redirect()
                ->route('contacto.telefono_movil.index')
                ->with('error', 'No se pudo eliminar el teléfono porque tiene relaciones activas en otros módulos.');
        }

        return redirect()
            ->route('contacto.telefono_movil.index')
            ->with('success', 'Teléfono móvil eliminado correctamente.');
    }
}
