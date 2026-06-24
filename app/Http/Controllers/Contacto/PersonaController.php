<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\TelefonoTipoOperadora;
use App\Models\admin\Correo;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$personas = persona::all();
        $personas = Persona::with(['telefono_movils', 'correos'])->get();
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
            'fecha_nacimiento' => 'nullable|date',
            'telefonos' => 'required|array|min:1',
            'telefonos.*.numero' => 'required|max:20|distinct',
            'telefonos.*.id_operadora' => 'nullable|exists:matriz.telefono_tipo_operadoras,IdOperadora',
            'correos' => 'nullable|array',
            'correos.*.correo' => 'nullable|email|max:255',
        ]);

        $persona = Persona::create([
            'DNI' => $request->dni,
            'Nombres' => $request->nombres,
            'Apellidos' => $request->apellidos,
            'FechaNacimiento' => $request->fecha_nacimiento ?: null,
        ]);

        $telefonosIds = [];
        foreach ($request->telefonos as $telefonoData) {
            if (empty($telefonoData['numero'])) {
                continue;
            }

            $telefono = TelefonoMovil::firstOrCreate(
                ['Numero' => $telefonoData['numero']],
                ['IdOperadora' => $telefonoData['id_operadora'] ?? null]
            );

            if (!empty($telefonoData['id_operadora']) && $telefono->IdOperadora !== $telefonoData['id_operadora']) {
                $telefono->update(['IdOperadora' => $telefonoData['id_operadora']]);
            }

            $telefonosIds[] = $telefono->IdTelefonoMovil;
        }

        if (!empty($telefonosIds)) {
            $persona->telefono_movils()->attach($telefonosIds);
        }

        if ($request->filled('correos')) {
            $correosIds = [];

            foreach ($request->correos as $correoData) {
                if (!empty($correoData['correo'])) {
                    $correo = Correo::firstOrCreate(
                        ['Correo' => $correoData['correo']]
                    );

                    $correosIds[] = $correo->IdCorreo;
                }
            }

            if (!empty($correosIds)) {
                $persona->correos()->attach($correosIds);
            }
        }

        return redirect()
            ->route('contacto.persona.index')
            ->with('success', 'Persona creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        //$persona = Persona::findOrFail($IdPersona);
        return view('contacto.persona.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $persona = Persona::with([
            'telefono_movils',
            'correos'
        ])->findOrFail($id);

        $operadoras = TelefonoTipoOperadora::all();

        return view(
            'contacto.persona.edit',
            compact('persona', 'operadoras')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'dni' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'fecha_nacimiento' => 'nullable|date',
            'telefonos' => 'nullable|array',
            'telefonos.*.id' => 'nullable|integer|exists:matriz.telefono_movils,IdTelefonoMovil',
            'telefonos.*.numero' => 'required_with:telefonos.*.id|max:20|distinct',
            'telefonos.*.id_operadora' => 'nullable|exists:matriz.telefono_tipo_operadoras,IdOperadora',
            'correos' => 'nullable|array',
            'correos.*.id' => 'nullable|integer|exists:matriz.correos,IdCorreo',
            'correos.*.correo' => 'nullable|email|max:255',
        ]);

        $persona = Persona::with(['telefono_movils', 'correos'])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Actualizar persona
        |--------------------------------------------------------------------------
        */
        $persona->update([
            'DNI' => $request->dni,
            'Nombres' => $request->nombres,
            'Apellidos' => $request->apellidos,
            'FechaNacimiento' => $request->fecha_nacimiento ?: null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | TELÉFONOS EXISTENTES
        |--------------------------------------------------------------------------
        */
        /*
        |
        | Actualizar teléfonos
        |--------------------------------------------------------------------------
        */

        $telefonosIds = [];

        if ($request->telefonos) {
            foreach ($request->telefonos as $telefonoData) {
                if (empty($telefonoData['numero'])) {
                    continue;
                }

                if (!empty($telefonoData['id'])) {
                    $telefono = TelefonoMovil::find($telefonoData['id']);

                    if ($telefono) {
                        if ($telefono->Numero !== $telefonoData['numero']) {
                            $existing = TelefonoMovil::where('Numero', $telefonoData['numero'])->first();
                            if ($existing) {
                                $telefono = $existing;
                            } else {
                                $telefono->Numero = $telefonoData['numero'];
                            }
                        }

                        if (!empty($telefonoData['id_operadora']) && $telefono->IdOperadora !== $telefonoData['id_operadora']) {
                            $telefono->IdOperadora = $telefonoData['id_operadora'];
                        }

                        $telefono->save();
                        $telefonosIds[] = $telefono->IdTelefonoMovil;
                    }
                } else {
                    $telefono = TelefonoMovil::firstOrCreate(
                        ['Numero' => $telefonoData['numero']],
                        ['IdOperadora' => $telefonoData['id_operadora'] ?? null]
                    );

                    if (!empty($telefonoData['id_operadora']) && $telefono->IdOperadora !== $telefonoData['id_operadora']) {
                        $telefono->update(['IdOperadora' => $telefonoData['id_operadora']]);
                    }

                    $telefonosIds[] = $telefono->IdTelefonoMovil;
                }
            }
        }

        $persona->telefono_movils()->sync($telefonosIds);

        /*
        |--------------------------------------------------------------------------
        | CORREOS
        |--------------------------------------------------------------------------
        */
        $correosIdsActuales = [];

        if ($request->correos) {
            foreach ($request->correos as $correoData) {
                if (empty($correoData['correo'])) {
                    continue;
                }

                if (!empty($correoData['id'])) {
                    $correo = Correo::find($correoData['id']);

                    if ($correo) {
                        $correo->update([
                            'Correo' => $correoData['correo'],
                        ]);

                        $correosIdsActuales[] = $correo->IdCorreo;
                    }
                } else {
                    $nuevoCorreo = Correo::firstOrCreate(
                        ['Correo' => $correoData['correo']]
                    );

                    $persona->correos()->attach($nuevoCorreo->IdCorreo);
                    $correosIdsActuales[] = $nuevoCorreo->IdCorreo;
                }
            }
        }

        $persona->correos()
            ->wherePivotNotIn('IdCorreo', $correosIdsActuales)
            ->detach();

        return redirect()
            ->route('contacto.persona.index')
            ->with('success', 'Persona actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
