@extends('adminlte::page')

@section('title', 'Editar Cliente')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1><i class="fas fa-edit text-primary"></i> Editar Cliente</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.cliente.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
@php
    $esEmpresa = $cliente->TipoCliente === 'empresa';
    $entidad = $esEmpresa ? $cliente->empresa : $cliente->persona;
    $prefTel = $esEmpresa ? 'empresa_telefonos' : 'telefonos';
    $prefCorreo = $esEmpresa ? 'empresa_correos' : 'correos';
    $prefDir = $esEmpresa ? 'empresa_direcciones' : 'direcciones';

    $telefonosIniciales = old($prefTel, ($entidad?->telefono_movils ?? collect())->map(fn ($t) => [
        'numero' => $t->Numero,
        'id_operadora' => $t->IdOperadora,
    ])->values()->all());

    $correosIniciales = old($prefCorreo, ($entidad?->correos ?? collect())->map(fn ($c) => [
        'correo' => $c->Correo,
    ])->values()->all());

    $direccionesIniciales = old($prefDir, ($entidad?->direcciones ?? collect())->map(function ($direccion) {
        $ciudad = $direccion->parroquia?->ciudad;
        $provincia = $ciudad?->provincia;
        $pais = $provincia?->pais;

        return [
            'id' => $direccion->IdDireccion,
            'nombre' => $direccion->Nombre,
            'id_direccion_tipo' => $direccion->IdDireccionTipo,
            'id_parroquia' => $direccion->IdParroquia,
            'id_canton' => $ciudad?->IdCiudad,
            'id_provincia' => $provincia?->IdProvincia,
            'id_pais' => $pais?->IdPais,
            'id_continente' => $pais?->continente?->IdContinente,
            'ubicacion' => $direccion->Ubicacion,
        ];
    })->values()->all());
@endphp

<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">
            Actualizar Cliente
            <span class="badge {{ $esEmpresa ? 'badge-info' : 'badge-primary' }}">
                <i class="{{ $esEmpresa ? 'fas fa-building' : 'fas fa-user' }}"></i> {{ $esEmpresa ? 'Empresa' : 'Persona' }}
            </span>
        </h3>
    </div>

    <form method="POST" action="{{ route('ventas.cliente.update', $cliente->IdClientes) }}">
        @csrf
        @method('PUT')

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Se encontraron errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($esEmpresa)
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>RUC</label>
                        <input type="text" name="RUC" class="form-control" value="{{ old('RUC', $entidad->RUC ?? '') }}" required>
                    </div>

                    <div class="form-group col-md-8">
                        <label>Razón Social</label>
                        <input type="text" name="RazonSocial" class="form-control" value="{{ old('RazonSocial', $entidad->RazonSocial ?? '') }}" required>
                    </div>
                </div>
            @else
                <div class="form-group">
                    <label>Documento</label>
                    <input type="text" name="dni" class="form-control" value="{{ old('dni', $entidad->DNI ?? '') }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Nombres</label>
                        <input type="text" name="nombres" class="form-control" value="{{ old('nombres', $entidad->Nombres ?? '') }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', $entidad->Apellidos ?? '') }}" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $entidad->FechaNacimiento ?? '') }}">
                    </div>

                    <div class="form-group col-md-8 position-relative">
                        <label for="empresaBuscar"><i class="fas fa-building"></i> Lugar de trabajo</label>
                        <div class="input-group">
                            <input type="text" id="empresaBuscar" class="form-control"
                                   placeholder="Buscar empresa por razón social o RUC..." autocomplete="off">
                            <div class="input-group-append">
                                <button type="button" id="empresaLimpiar" class="btn btn-outline-secondary" title="Quitar selección">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="id_empresa" name="id_empresa" value="{{ old('id_empresa', $entidad->IdEmpresa ?? '') }}">
                        <div id="empresaResultados" class="list-group position-absolute w-100 shadow-sm" style="z-index:1000; max-height:220px; overflow-y:auto; display:none;"></div>
                    </div>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->Email) }}" maxlength="150">
                </div>
                <div class="form-group col-md-3">
                    <label>Estado</label>
                    <select name="activo" class="form-control">
                        <option value="1" {{ old('activo', $cliente->Activo) ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !old('activo', $cliente->Activo) ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card card-outline card-info h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-phone"></i> Teléfonos</h5>
                            <button type="button" id="addTelefono" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="card-body">
                            <div id="telefonos-container"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-outline card-success h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-envelope"></i> Correos</h5>
                            <button type="button" id="addCorreo" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="card-body">
                            <div id="correos-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-outline card-warning">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Direcciones</h5>
                            <button type="button" id="addDireccion" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="card-body">
                            <div id="direcciones-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('ventas.cliente.show', $cliente->IdClientes) }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar
            </button>
        </div>
    </form>
</div>
@stop

@section('js')
@include('contacto.cliente._repeater_scripts')

<script>
const telefonos = createTelefonoRepeater('telefonos-container', @json($prefTel), @json(array_values($telefonosIniciales)));
const correos = createCorreoRepeater('correos-container', @json($prefCorreo), @json(array_values($correosIniciales)));
const direcciones = createDireccionRepeater('direcciones-container', @json($prefDir), @json(array_values($direccionesIniciales)));

document.getElementById('addTelefono').addEventListener('click', () => telefonos.addRow());
document.getElementById('addCorreo').addEventListener('click', () => correos.addRow());
document.getElementById('addDireccion').addEventListener('click', () => direcciones.addRow());

// --- Búsqueda y selección de empresa (lugar de trabajo del cliente-persona) ---
const empresaBuscar = document.getElementById('empresaBuscar');

if (empresaBuscar) {
    const empresasData = @json($empresas->map(fn ($e) => ['id' => $e->IdEmpresa, 'nombre' => (string) $e->RazonSocial, 'ruc' => (string) $e->RUC]));
    const empresaResultados = document.getElementById('empresaResultados');
    const idEmpresaInput = document.getElementById('id_empresa');

    const etiquetaEmpresa = (empresa) => empresa.nombre + (empresa.ruc ? ' (' + empresa.ruc + ')' : '');

    const renderResultadosEmpresa = (lista) => {
        if (lista.length === 0) {
            empresaResultados.innerHTML = '<div class="list-group-item text-muted">Sin coincidencias</div>';
        } else {
            empresaResultados.innerHTML = lista.slice(0, 15).map((e) => `
                <button type="button" class="list-group-item list-group-item-action" data-id="${e.id}">${etiquetaEmpresa(e)}</button>
            `).join('');
        }
        empresaResultados.style.display = 'block';
    };

    empresaBuscar.addEventListener('input', function () {
        idEmpresaInput.value = '';
        const texto = this.value.trim().toLowerCase();

        if (texto.length === 0) {
            empresaResultados.style.display = 'none';
            return;
        }

        const filtrados = empresasData.filter((e) => e.nombre.toLowerCase().includes(texto) || e.ruc.toLowerCase().includes(texto));
        renderResultadosEmpresa(filtrados);
    });

    empresaBuscar.addEventListener('focus', function () {
        if (this.value.trim().length > 0) {
            empresaResultados.style.display = 'block';
        }
    });

    document.getElementById('empresaLimpiar').addEventListener('click', function () {
        idEmpresaInput.value = '';
        empresaBuscar.value = '';
        empresaResultados.style.display = 'none';
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#empresaResultados') && e.target !== empresaBuscar) {
            empresaResultados.style.display = 'none';
        }

        if (e.target.closest('#empresaResultados button')) {
            const btn = e.target.closest('button');
            const empresa = empresasData.find((e) => String(e.id) === btn.dataset.id);
            idEmpresaInput.value = empresa.id;
            empresaBuscar.value = etiquetaEmpresa(empresa);
            empresaResultados.style.display = 'none';
        }
    });

    if (idEmpresaInput.value) {
        const empresaInicial = empresasData.find((e) => String(e.id) === String(idEmpresaInput.value));
        if (empresaInicial) {
            empresaBuscar.value = etiquetaEmpresa(empresaInicial);
        }
    }
}
</script>
@stop
