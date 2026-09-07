<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\matriz\Continente;
use App\Models\matriz\Correo;
use App\Models\matriz\Direccion;
use App\Models\matriz\DireccionTipo;
use App\Models\matriz\Empresa;
use App\Models\matriz\Persona;
use App\Models\matriz\TelefonoMovil;
use App\Models\matriz\TelefonoTipoOperadora;
use App\Models\negocio\Ruta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with(['persona', 'empresa'])
            ->orderBy('id', 'desc')
            ->get();

        return view('contacto.cliente.index', compact('clientes'));
    }

    public function create()
    {
        $operadoras = TelefonoTipoOperadora::all();
        $direccionTipos = DireccionTipo::orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();
        $rutas = $this->rutasDisponibles();

        return view('contacto.cliente.create', compact('operadoras', 'direccionTipos', 'ubicaciones', 'rutas'));
    }

    public static function normalizeIdOperadora($value): int
    {
        if ($value === null || trim((string) $value) === '' || (string) $value === '0') {
            return 1;
        }

        return (int) $value;
    }

    public function store(Request $request)
    {
        $tipoCliente = $request->input('tipo_cliente', 'persona');

        if ($tipoCliente === 'empresa') {
            $request->validate([
                'RUC' => 'required|string|max:50',
                'RazonSocial' => 'required|string|max:255',
                'empresa_telefonos' => 'nullable|array',
                'empresa_telefonos.*.numero' => 'nullable|max:20|distinct',
                'empresa_telefonos.*.id_operadora' => 'nullable|exists:matriz.telefono_tipo_operadoras,IdOperadora',
                'empresa_correos' => 'nullable|array',
                'empresa_correos.*.correo' => 'nullable|email|max:255',
                'empresa_direcciones' => 'nullable|array',
                'empresa_direcciones.*.nombre' => 'nullable|string|max:200',
                'empresa_direcciones.*.id_direccion_tipo' => 'nullable|exists:matriz.direccion_tipo,IdDireccionTipo',
                'empresa_direcciones.*.id_parroquia' => 'nullable|exists:matriz.parroquia,IdParroquia',
                'empresa_direcciones.*.ubicacion' => 'nullable|string|max:500',
                'empresa_direcciones.*.id_ruta' => 'nullable|exists:negocio.rutas,id',
                'email' => 'nullable|email|max:150',
            ]);

            $empresa = Empresa::create([
                'RUC' => $request->RUC,
                'RazonSocial' => $request->RazonSocial,
            ]);

            $telefonosIds = $this->guardarTelefonos($request->input('empresa_telefonos', []));
            $correosIds = $this->guardarCorreos($request->input('empresa_correos', []));
            $this->syncDireccionesForEmpresa($empresa->IdEmpresa, $this->persistDirecciones($request->input('empresa_direcciones', [])));

            if (! empty($telefonosIds)) {
                $empresa->telefono_movils()->attach($telefonosIds);
            }

            if (! empty($correosIds)) {
                $empresa->correos()->attach($correosIds);
            }

            $email = $request->input('email') ?: ($request->input('empresa_correos')
                ? collect($request->input('empresa_correos'))->pluck('correo')->filter()->first()
                : null);

            $cliente = Cliente::create([
                'TipoCliente' => 'empresa',
                'IdPersona' => null,
                'IdEmpresa' => $empresa->IdEmpresa,
                'Nombre' => $empresa->RazonSocial,
                'Documento' => $empresa->RUC,
                'Email' => $email,
                'Activo' => true,
            ]);

            return redirect()->route('ventas.cliente.show', $cliente->IdClientes)
                ->with('success', 'Cliente registrado correctamente.');
        }

        $request->validate([
            'dni' => 'required|max:20|unique:matriz.personas,DNI',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'telefonos' => 'nullable|array',
            'telefonos.*.numero' => 'nullable|max:20|distinct',
            'telefonos.*.id_operadora' => 'nullable|exists:matriz.telefono_tipo_operadoras,IdOperadora',
            'correos' => 'nullable|array',
            'correos.*.correo' => 'nullable|email|max:255',
            'direcciones' => 'nullable|array',
            'direcciones.*.nombre' => 'nullable|string|max:200',
            'direcciones.*.id_direccion_tipo' => 'nullable|exists:matriz.direccion_tipo,IdDireccionTipo',
            'direcciones.*.id_parroquia' => 'nullable|exists:matriz.parroquia,IdParroquia',
            'direcciones.*.ubicacion' => 'nullable|string|max:500',
            'direcciones.*.id_ruta' => 'nullable|exists:negocio.rutas,id',
            'email' => 'nullable|email|max:150',
        ]);

        $persona = Persona::create([
            'DNI' => $request->dni,
            'Nombres' => $request->nombres,
            'Apellidos' => $request->apellidos,
            'FechaNacimiento' => $request->fecha_nacimiento ?: null,
        ]);

        $telefonosIds = $this->guardarTelefonos($request->input('telefonos', []));
        $correosIds = $this->guardarCorreos($request->input('correos', []));

        if (! empty($telefonosIds)) {
            $persona->telefono_movils()->attach($telefonosIds);
        }

        if (! empty($correosIds)) {
            $persona->correos()->attach($correosIds);
        }

        $this->syncDirecciones($persona->IdPersona, $this->persistDirecciones($request->input('direcciones', [])));

        $email = $request->input('email') ?: ($request->filled('correos')
            ? collect($request->correos)->pluck('correo')->filter()->first()
            : null);

        $cliente = Cliente::create([
            'TipoCliente' => 'persona',
            'IdPersona' => $persona->IdPersona,
            'IdEmpresa' => null,
            'Nombre' => trim(($persona->Nombres ?? '') . ' ' . ($persona->Apellidos ?? '')) ?: null,
            'Documento' => $persona->DNI,
            'Email' => $email,
            'Activo' => true,
        ]);

        return redirect()->route('ventas.cliente.show', $cliente->IdClientes)
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show(string $id)
    {
        $cliente = Cliente::with([
            'persona.telefono_movils.operadora',
            'persona.correos',
            'persona.direcciones.tipo',
            'persona.direcciones.parroquia.ciudad',
            'persona.direcciones.ruta',
            'empresa.telefono_movils.operadora',
            'empresa.correos',
            'empresa.direcciones.tipo',
            'empresa.direcciones.parroquia.ciudad',
            'empresa.direcciones.ruta',
        ])->findOrFail($id);

        return view('contacto.cliente.show', compact('cliente'));
    }

    public function edit(string $id)
    {
        $cliente = Cliente::with([
            'persona.telefono_movils.operadora',
            'persona.correos',
            'persona.direcciones.tipo',
            'persona.direcciones.parroquia.ciudad.provincia.pais.continente',
            'persona.direcciones.ruta',
            'empresa.telefono_movils.operadora',
            'empresa.correos',
            'empresa.direcciones.tipo',
            'empresa.direcciones.parroquia.ciudad.provincia.pais.continente',
            'empresa.direcciones.ruta',
        ])->findOrFail($id);

        $operadoras = TelefonoTipoOperadora::all();
        $direccionTipos = DireccionTipo::orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();
        $rutas = $this->rutasParaFormulario($cliente);

        return view('contacto.cliente.edit', compact('cliente', 'operadoras', 'direccionTipos', 'ubicaciones', 'rutas'));
    }

    public function update(Request $request, string $id)
    {
        $cliente = Cliente::with(['persona', 'empresa'])->findOrFail($id);

        if ($cliente->TipoCliente === 'empresa') {
            $empresa = $cliente->empresa;

            if (! $empresa) {
                abort(404);
            }

            $request->validate([
                'RUC' => ['required', 'string', 'max:50', Rule::unique('matriz.empresas', 'RUC')->ignore($empresa->IdEmpresa, 'IdEmpresa')],
                'RazonSocial' => ['required', 'string', 'max:255'],
                'empresa_telefonos' => ['nullable', 'array'],
                'empresa_telefonos.*.numero' => ['nullable', 'max:20', 'distinct'],
                'empresa_telefonos.*.id_operadora' => ['nullable', 'exists:matriz.telefono_tipo_operadoras,IdOperadora'],
                'empresa_correos' => ['nullable', 'array'],
                'empresa_correos.*.correo' => ['nullable', 'email', 'max:255'],
                'empresa_direcciones' => ['nullable', 'array'],
                'empresa_direcciones.*.nombre' => ['nullable', 'string', 'max:200'],
                'empresa_direcciones.*.id_direccion_tipo' => ['nullable', 'exists:matriz.direccion_tipo,IdDireccionTipo'],
                'empresa_direcciones.*.id_parroquia' => ['nullable', 'exists:matriz.parroquia,IdParroquia'],
                'empresa_direcciones.*.ubicacion' => ['nullable', 'string', 'max:500'],
                'empresa_direcciones.*.id_ruta' => ['nullable', 'exists:negocio.rutas,id'],
                'email' => ['nullable', 'email', 'max:150'],
                'activo' => ['nullable', 'boolean'],
            ]);

            $empresa->update([
                'RUC' => $request->RUC,
                'RazonSocial' => $request->RazonSocial,
            ]);

            $telefonosIds = $this->guardarTelefonos($request->input('empresa_telefonos', []));
            $correosIds = $this->guardarCorreos($request->input('empresa_correos', []));
            $this->syncDireccionesForEmpresa($empresa->IdEmpresa, $this->persistDirecciones($request->input('empresa_direcciones', [])));
            $this->syncTelefonosEmpresa($empresa->IdEmpresa, $telefonosIds);
            $this->syncCorreosEmpresa($empresa->IdEmpresa, $correosIds);

            $cliente->update([
                'Nombre' => $empresa->RazonSocial,
                'Documento' => $empresa->RUC,
                'Email' => $request->input('email') ?: null,
                'Activo' => $request->boolean('activo', true),
            ]);

            return redirect()->route('ventas.cliente.show', $cliente->IdClientes)
                ->with('success', 'Cliente actualizado correctamente.');
        }

        $persona = $cliente->persona;

        if (! $persona) {
            abort(404);
        }

        $request->validate([
            'dni' => ['required', 'max:20', Rule::unique('matriz.personas', 'DNI')->ignore($persona->IdPersona, 'IdPersona')],
            'nombres' => ['required', 'max:100'],
            'apellidos' => ['required', 'max:100'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'telefonos' => ['nullable', 'array'],
            'telefonos.*.numero' => ['nullable', 'max:20', 'distinct'],
            'telefonos.*.id_operadora' => ['nullable', 'exists:matriz.telefono_tipo_operadoras,IdOperadora'],
            'correos' => ['nullable', 'array'],
            'correos.*.correo' => ['nullable', 'email', 'max:255'],
            'direcciones' => ['nullable', 'array'],
            'direcciones.*.nombre' => ['nullable', 'string', 'max:200'],
            'direcciones.*.id_direccion_tipo' => ['nullable', 'exists:matriz.direccion_tipo,IdDireccionTipo'],
            'direcciones.*.id_parroquia' => ['nullable', 'exists:matriz.parroquia,IdParroquia'],
            'direcciones.*.ubicacion' => ['nullable', 'string', 'max:500'],
            'direcciones.*.id_ruta' => ['nullable', 'exists:negocio.rutas,id'],
            'email' => ['nullable', 'email', 'max:150'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $persona->update([
            'DNI' => $request->dni,
            'Nombres' => $request->nombres,
            'Apellidos' => $request->apellidos,
            'FechaNacimiento' => $request->fecha_nacimiento ?: null,
        ]);

        $telefonosIds = $this->guardarTelefonos($request->input('telefonos', []));
        $correosIds = $this->guardarCorreos($request->input('correos', []));
        $this->syncDirecciones($persona->IdPersona, $this->persistDirecciones($request->input('direcciones', [])));
        $this->syncTelefonos($persona->IdPersona, $telefonosIds);
        $this->syncCorreos($persona->IdPersona, $correosIds);

        $cliente->update([
            'Nombre' => trim(($persona->Nombres ?? '') . ' ' . ($persona->Apellidos ?? '')) ?: null,
            'Documento' => $persona->DNI,
            'Email' => $request->input('email') ?: null,
            'Activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('ventas.cliente.show', $cliente->IdClientes)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    private function ubicacionesJerarquicas()
    {
        return Continente::with('paises.provincias.cantones.parroquias')
            ->orderBy('Nombre')
            ->get();
    }

    /**
     * Recorridos que tiene sentido asignar por defecto a un cliente (se excluyen
     * los ya finalizados o cancelados).
     */
    private function rutasDisponibles()
    {
        return Ruta::whereIn('Estado', ['PLANIFICADA', 'EN_CURSO'])
            ->orderBy('Fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Igual que rutasDisponibles(), pero garantiza que cualquier ruta ya asignada a
     * una dirección de este cliente quede en la lista aunque esté finalizada o
     * cancelada, para no perderla por accidente al editar si no aparecía como opción.
     */
    private function rutasParaFormulario(Cliente $cliente)
    {
        $rutas = $this->rutasDisponibles();

        $entidad = $cliente->TipoCliente === 'empresa' ? $cliente->empresa : $cliente->persona;
        $idsRutasAsignadas = ($entidad?->direcciones ?? collect())->pluck('IdRuta')->filter()->unique();

        foreach ($idsRutasAsignadas as $idRuta) {
            if (! $rutas->contains('id', $idRuta)) {
                $rutaActual = Ruta::find($idRuta);

                if ($rutaActual) {
                    $rutas->push($rutaActual);
                }
            }
        }

        return $rutas;
    }

    private function guardarTelefonos(array $telefonos): array
    {
        $telefonosIds = [];

        foreach ($telefonos as $telefonoData) {
            if (empty($telefonoData['numero'])) {
                continue;
            }

            $idOperadora = self::normalizeIdOperadora($telefonoData['id_operadora'] ?? null);
            $telefono = TelefonoMovil::firstOrCreate(
                ['Numero' => $telefonoData['numero']],
                ['IdOperadora' => $idOperadora]
            );

            if ((int) $telefono->IdOperadora !== $idOperadora) {
                $telefono->update(['IdOperadora' => $idOperadora]);
            }

            $telefonosIds[] = $telefono->IdTelefonoMovil;
        }

        return array_values(array_unique($telefonosIds));
    }

    private function guardarCorreos(array $correos): array
    {
        $correosIds = [];

        foreach ($correos as $correoData) {
            if (! empty($correoData['correo'])) {
                $correo = Correo::firstOrCreate(['Correo' => $correoData['correo']]);
                $correosIds[] = $correo->IdCorreo;
            }
        }

        return array_values(array_unique($correosIds));
    }

    private function persistDirecciones(array $direcciones): array
    {
        $direccionesIds = [];

        foreach ($direcciones as $direccionData) {
            $nombre = trim((string) ($direccionData['nombre'] ?? ''));
            $idDireccionTipo = $direccionData['id_direccion_tipo'] ?? null;
            $idParroquia = $direccionData['id_parroquia'] ?? null;
            $hasAnyData = $nombre !== ''
                || !empty($idDireccionTipo)
                || !empty($idParroquia)
                || !empty($direccionData['ubicacion'])
                || !empty($direccionData['id_continente'])
                || !empty($direccionData['id_pais'])
                || !empty($direccionData['id_provincia'])
                || !empty($direccionData['id_canton'])
                || !empty($direccionData['id']);

            if (! $hasAnyData) {
                continue;
            }

            $payload = [
                'Nombre' => $nombre !== '' ? $nombre : null,
                'IdDireccionTipo' => !empty($idDireccionTipo) ? $idDireccionTipo : null,
                'IdParroquia' => !empty($idParroquia) ? $idParroquia : null,
                'Ubicacion' => trim((string) ($direccionData['ubicacion'] ?? '')) ?: null,
                'IdRuta' => $direccionData['id_ruta'] ?? null,
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

    private function syncDireccionesForEmpresa(int $idEmpresa, array $direccionesIds): void
    {
        DB::connection('matriz')->transaction(function () use ($idEmpresa, $direccionesIds) {
            $pivot = DB::connection('matriz')->table('empresa_direccion');
            $pivot->where('IdEmpresa', $idEmpresa)->delete();

            if (empty($direccionesIds)) {
                return;
            }

            $now = now();
            $rows = array_map(function ($idDireccion) use ($idEmpresa, $now) {
                return [
                    'IdEmpresa' => $idEmpresa,
                    'IdDireccion' => $idDireccion,
                    'Eliminado' => 'N',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $direccionesIds);

            $pivot->insert($rows);
        });
    }

    private function syncTelefonos(int $idPersona, array $telefonosIds): void
    {
        DB::connection('matriz')->transaction(function () use ($idPersona, $telefonosIds) {
            $pivot = DB::connection('matriz')->table('persona_telefono_movils');
            $pivot->where('IdPersona', $idPersona)->delete();

            if (empty($telefonosIds)) {
                return;
            }

            $now = now();
            $pivot->insert(array_map(fn ($idTelefono) => [
                'IdPersona' => $idPersona,
                'IdTelefonoMovil' => $idTelefono,
                'created_at' => $now,
                'updated_at' => $now,
            ], $telefonosIds));
        });
    }

    private function syncCorreos(int $idPersona, array $correosIds): void
    {
        DB::connection('matriz')->transaction(function () use ($idPersona, $correosIds) {
            $pivot = DB::connection('matriz')->table('persona_correos');
            $pivot->where('IdPersona', $idPersona)->delete();

            if (empty($correosIds)) {
                return;
            }

            $now = now();
            $pivot->insert(array_map(fn ($idCorreo) => [
                'IdPersona' => $idPersona,
                'IdCorreo' => $idCorreo,
                'created_at' => $now,
                'updated_at' => $now,
            ], $correosIds));
        });
    }

    private function syncTelefonosEmpresa(int $idEmpresa, array $telefonosIds): void
    {
        DB::connection('matriz')->transaction(function () use ($idEmpresa, $telefonosIds) {
            $pivot = DB::connection('matriz')->table('empresa_telefono_movils');
            $pivot->where('IdEmpresa', $idEmpresa)->delete();

            if (empty($telefonosIds)) {
                return;
            }

            $now = now();
            $pivot->insert(array_map(fn ($idTelefono) => [
                'IdEmpresa' => $idEmpresa,
                'IdTelefonoMovil' => $idTelefono,
                'created_at' => $now,
                'updated_at' => $now,
            ], $telefonosIds));
        });
    }

    private function syncCorreosEmpresa(int $idEmpresa, array $correosIds): void
    {
        DB::connection('matriz')->transaction(function () use ($idEmpresa, $correosIds) {
            $pivot = DB::connection('matriz')->table('empresa_correos');
            $pivot->where('IdEmpresa', $idEmpresa)->delete();

            if (empty($correosIds)) {
                return;
            }

            $now = now();
            $pivot->insert(array_map(fn ($idCorreo) => [
                'IdEmpresa' => $idEmpresa,
                'IdCorreo' => $idCorreo,
                'created_at' => $now,
                'updated_at' => $now,
            ], $correosIds));
        });
    }

    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('ventas.cliente.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
