<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Empresa;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\Correo;
use App\Models\admin\TelefonoTipoOperadora;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::with(['telefono_movils', 'correos'])->get();
        return view('contacto.empresa.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operadoras = TelefonoTipoOperadora::all();
        return view('contacto.empresa.create', compact('operadoras'));
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
                    $correo = Correo::create([
                        'Correo' => $correoData['correo'],
                    ]);
                    $correosIds[] = $correo->IdCorreo;
                }
            }

            if (!empty($correosIds)) {
                $empresa->correos()->attach($correosIds);
            }
        }

        return redirect()->route('contacto.empresa.show', $empresa->IdEmpresa);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $empresa = Empresa::with(['telefono_movils', 'correos'])->findOrFail($id);
        return view('contacto.empresa.show', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empresa = Empresa::with(['telefono_movils', 'correos'])->findOrFail($id);
        $operadoras = TelefonoTipoOperadora::all();

        return view('contacto.empresa.edit', compact('empresa', 'operadoras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $empresa = Empresa::with(['telefono_movils', 'correos'])->findOrFail($id);

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
                    $correo = Correo::create([
                        'Correo' => $correoData['correo'],
                    ]);
                    $correosIds[] = $correo->IdCorreo;
                }
            }

            $empresa->correos()->sync($correosIds);
        }

        return redirect()->route('contacto.empresa.show', $empresa->IdEmpresa)
            ->with('success', 'Empresa actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->telefono_movils()->detach();
        $empresa->correos()->detach();
        $empresa->delete();

        return redirect()->route('contacto.empresa.index')
            ->with('success', 'Empresa eliminada correctamente');
    }
}
