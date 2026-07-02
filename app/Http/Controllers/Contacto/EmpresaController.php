<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Empresa;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\Correo;
use App\Models\admin\Continente;
use App\Models\admin\Direccion;
use App\Models\admin\DireccionTipo;
use App\Models\admin\Parroquia;
use App\Models\admin\TelefonoTipoOperadora;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::with(['telefono_movils', 'correos', 'direcciones'])->get();
        return view('contacto.empresa.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operadoras = TelefonoTipoOperadora::all();
        $direccionTipos = DireccionTipo::orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();

        return view('contacto.empresa.create', compact('operadoras', 'ubicaciones', 'direccionTipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'RUC' => 'required|string|max:50',
            'RazonSocial' => 'required|string|max:255',
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

        $empresa = Empresa::create([
            'RUC' => $request->RUC,
            'RazonSocial' => $request->RazonSocial,
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
            $empresa->telefono_movils()->attach($telefonosIds);
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
                $empresa->correos()->attach($correosIds);
            }
        }

        $this->syncDirecciones($empresa->IdEmpresa, $this->persistDirecciones($request->input('direcciones', [])));

        return redirect()->route('contacto.empresa.edit', $empresa->IdEmpresa);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $empresa = Empresa::with(['telefono_movils', 'correos', 'direcciones.tipo', 'direcciones.parroquia.canton.provincia.pais.continente'])->findOrFail($id);
        return view('contacto.empresa.show', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empresa = Empresa::with(['telefono_movils', 'correos', 'direcciones.tipo', 'direcciones.parroquia.canton.provincia.pais.continente'])->findOrFail($id);
        $operadoras = TelefonoTipoOperadora::all();
        $direccionTipos = DireccionTipo::orderBy('Nombre')->get();
        $ubicaciones = $this->ubicacionesJerarquicas();

        return view('contacto.empresa.edit', compact('empresa', 'operadoras', 'ubicaciones', 'direccionTipos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $empresa = Empresa::with(['telefono_movils', 'correos', 'direcciones'])->findOrFail($id);

        $request->validate([
            'RUC' => 'required|string|max:50',
            'RazonSocial' => 'required|string|max:255',
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

        $empresa->update([
            'RUC' => $request->RUC,
            'RazonSocial' => $request->RazonSocial,
        ]);

        if ($request->has('telefonos')) {
            $telefonosIds = [];
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

            $empresa->telefono_movils()->sync($telefonosIds);
        }

        if ($request->has('correos')) {
            $correosIds = [];
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
                        $correosIds[] = $correo->IdCorreo;
                    }
                } else {
                    $correo = Correo::firstOrCreate(
                        ['Correo' => $correoData['correo']]
                    );
                    $correosIds[] = $correo->IdCorreo;
                }
            }

            $empresa->correos()->sync($correosIds);
        }

        $this->syncDirecciones($empresa->IdEmpresa, $this->persistDirecciones($request->input('direcciones', [])));

        return redirect()->route('contacto.empresa.edit', $empresa->IdEmpresa)
            ->with('success', 'Empresa actualizada correctamente');
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

    private function syncDirecciones(int $idEmpresa, array $direccionesIds): void
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
        $empresa = Empresa::findOrFail($id);
        $empresa->telefono_movils()->detach();
        $empresa->correos()->detach();
        $empresa->direcciones()->detach();
        $empresa->delete();

        return redirect()->route('contacto.empresa.index')
            ->with('success', 'Empresa eliminada correctamente');
    }
}
