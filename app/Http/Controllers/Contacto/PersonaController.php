<?php

namespace app\Http\Controllers\contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\TelefonoTipoOperadora;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personas = persona::all();
        return view('contacto.persona.index', compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operadoras = TelefonoTipoOperadora::all();
        return view('contacto.persona.create', compact('operadoras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
        'dni' => 'required|max:20|unique:matriz.personas,DNI',
        'nombres' => 'required|max:100',
        'apellidos' => 'required|max:100',
        'numero' => 'required|max:20',
        'id_conectividad' => 'nullable|exists:matriz.telefono_tipo_conectividads,IdConectividad',
        'id_operadora' => 'nullable|exists:matriz.telefono_tipo_operadoras,IdOperadora',
    ]);

    // Crear persona
    $persona = Persona::create([
        'DNI' => $request->dni,
        'Nombres' => $request->nombres,
        'Apellidos' => $request->apellidos,
    ]);

    // Crear teléfono
    $telefono = TelefonoMovil::create([
        'Numero' => $request->numero,
        'IdConectividad' => $request->id_conectividad ?: null,
        'IdOperadora' => $request->id_operadora ?: null,
    ]);

    // Relacionar en tabla pivote
    $persona->telefono_movils()->attach($telefono->IdTelefonoMovil);

    return redirect()
        ->route('contacto.persona.index')
        ->with('success', 'Persona creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $IdPersona)
    {
        $persona = Persona::find($IdPersona);
        return view('contacto.persona.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
