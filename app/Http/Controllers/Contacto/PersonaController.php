<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\TelefonoTipoOperadora;
use App\Models\admin\Continente;
use App\Models\admin\Direccion;
use App\Models\admin\DireccionTipo;
use App\Models\admin\Parroquia;
use App\Models\matriz\Correo;
use App\Models\matriz\Persona;
use App\Models\matriz\TelefonoMovil;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$personas = persona::all();
        $personas = Persona::with(['telefono_movils', 'correos', 'direcciones'])->get();
        return view('contacto.persona.index', compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operadoras = TelefonoTipoOperadora::all();
        $direccionTipos = DireccionTipo::orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();

        return view('contacto.persona.create', compact('operadoras', 'ubicaciones', 'direccionTipos'));
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
            'direcciones' => 'nullable|array',
            'direcciones.*.nombre' => 'nullable|string|max:200',
            'direcciones.*.id_direccion_tipo' => 'nullable|exists:matriz.direccion_tipo,IdDireccionTipo',
            'direcciones.*.id_parroquia' => 'nullable|exists:matriz.parroquia,IdParroquia',
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

        $this->syncDirecciones($persona->IdPersona, $this->persistDirecciones($request->input('direcciones', [])));

        return redirect()
            ->route('contacto.persona.edit', $persona->IdPersona)
            ->with('success', 'Persona creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        $persona->load(['telefono_movils.operadora', 'correos', 'direcciones.tipo', 'direcciones.parroquia.canton.provincia.pais.continente']);
        return view('contacto.persona.show', compact('persona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $persona = Persona::with([
            'telefono_movils',
            'correos',
            'direcciones.tipo',
            'direcciones.parroquia.canton.provincia.pais.continente'
        ])->findOrFail($id);

        $operadoras = TelefonoTipoOperadora::all();
        $direccionTipos = DireccionTipo::orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();

        return view(
            'contacto.persona.edit',
            compact('persona', 'operadoras', 'ubicaciones', 'direccionTipos')
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
            'direcciones' => 'nullable|array',
            'direcciones.*.id' => 'nullable|integer|exists:matriz.direccion,IdDireccion',
            'direcciones.*.nombre' => 'nullable|string|max:200',
            'direcciones.*.id_direccion_tipo' => 'nullable|exists:matriz.direccion_tipo,IdDireccionTipo',
            'direcciones.*.id_parroquia' => 'nullable|exists:matriz.parroquia,IdParroquia',
        ]);

        $persona = Persona::with(['telefono_movils', 'correos', 'direcciones'])->findOrFail($id);

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

        /*
        |--------------------------------------------------------------------------
        | DIRECCIONES
        |--------------------------------------------------------------------------
        */
        $this->syncDirecciones($persona->IdPersona, $this->persistDirecciones($request->input('direcciones', [])));

        return redirect()
            ->route('contacto.persona.edit', $persona->IdPersona)
            ->with('success', 'Persona actualizada correctamente');
    }

    private function ubicacionesJerarquicas()
    {
        return Continente::with('paises.provincias.cantones.parroquias')
            ->orderBy('Nombre')
            ->get();
    }

    private function persistDirecciones(array $direcciones): array
    {
        $direccionesIds = [];

        foreach ($direcciones as $index => $direccionData) {
            $nombre = trim((string) ($direccionData['nombre'] ?? ''));
            $idDireccionTipo = $direccionData['id_direccion_tipo'] ?? null;
            $idParroquia = $direccionData['id_parroquia'] ?? null;
            $hasAnyData = $nombre !== ''
                || !empty($idDireccionTipo)
                || !empty($idParroquia)
                || !empty($direccionData['id_continente'])
                || !empty($direccionData['id_pais'])
                || !empty($direccionData['id_provincia'])
                || !empty($direccionData['id_canton'])
                || !empty($direccionData['id']);

            if (!$hasAnyData) {
                continue;
            }

            if (empty($idDireccionTipo)) {
                throw ValidationException::withMessages([
                    "direcciones.$index.id_direccion_tipo" => 'Selecciona un tipo para guardar la dirección.',
                ]);
            }

            $payload = [
                'Nombre' => $nombre !== '' ? $nombre : null,
                'IdDireccionTipo' => $idDireccionTipo,
                'IdParroquia' => !empty($idParroquia) ? $idParroquia : null,
            ];

            if (!empty($direccionData['id'])) {
                $direccion = Direccion::find($direccionData['id']);

                if ($direccion) {
                    $direccion->update($payload);
                    $direccionesIds[] = $direccion->IdDireccion;
                    continue;
                }
            }

            $direccion = Direccion::firstOrCreate($payload);
            $direccionesIds[] = $direccion->IdDireccion;
        }

        return array_values(array_unique($direccionesIds));
    }

    private function syncDirecciones(int $idPersona, array $direccionesIds): void
    {
        DB::connection('matriz')->transaction(function () use ($idPersona, $direccionesIds) {
            $pivot = DB::connection('matriz')->table('persona_direccion');

            $pivot->where('IdPersona', $idPersona)->delete();

            if (empty($direccionesIds)) {
                return;
            }

            $now = now();
            $rows = array_map(function ($idDireccion) use ($idPersona, $now) {
                return [
                    'IdPersona' => $idPersona,
                    'IdDireccion' => $idDireccion,
                    'Eliminado' => 'N',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $direccionesIds);

            $pivot->insert($rows);
        });
    }

    private function parroquiasConJerarquia()
    {
        return Parroquia::with('canton.provincia.pais.continente')
            ->orderBy('Nombre')
            ->get();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
