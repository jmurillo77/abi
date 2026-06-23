@extends('adminlte::page')

@section('title', 'Editar Empresa')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-building text-primary"></i>
                Editar Empresa
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contacto</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.empresa.index') }}">Empresas</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">Editar Empresa</h3>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Se encontraron errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contacto.empresa.update', $empresa->IdEmpresa) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>RUC</label>
                <input type="text" name="RUC" class="form-control @error('RUC') is-invalid @enderror"
                    value="{{ old('RUC', $empresa->RUC) }}">
                @error('RUC')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label>Razón Social</label>
                <input type="text" name="RazonSocial" class="form-control @error('RazonSocial') is-invalid @enderror"
                    value="{{ old('RazonSocial', $empresa->RazonSocial) }}">
                @error('RazonSocial')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-info">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Teléfonos</h5>
                            <button type="button" id="addTelefono" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="telefonos-container">
                                @foreach(old('telefonos', $empresa->telefono_movils->map(function($telefono){
                                    return [
                                        'id' => $telefono->IdTelefonoMovil,
                                        'numero' => $telefono->Numero,
                                        'id_operadora' => $telefono->IdOperadora,
                                    ];
                                })->toArray()) as $index => $telefono)
                                    <div class="telefono-item border rounded p-3 mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-5">
                                                <label>Número</label>
                                                <input type="text" name="telefonos[{{ $index }}][numero]" class="form-control"
                                                    value="{{ $telefono['numero'] ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Operadora</label>
                                                <select name="telefonos[{{ $index }}][id_operadora]" class="form-control">
                                                    <option value="">Operadora</option>
                                                    @foreach($operadoras as $operadora)
                                                        <option value="{{ $operadora->IdOperadora }}"
                                                            {{ ($telefono['id_operadora'] ?? '') == $operadora->IdOperadora ? 'selected' : '' }}>
                                                            {{ $operadora->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="hidden" name="telefonos[{{ $index }}][id]" value="{{ $telefono['id'] ?? '' }}">
                                            @if($index > 0)
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-block remove-telefono">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-outline card-success">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Correos</h5>
                            <button type="button" id="addCorreo" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="correos-container">
                                @foreach(old('correos', $empresa->correos->map(function($correo){
                                    return [
                                        'id' => $correo->IdCorreo,
                                        'correo' => $correo->Correo,
                                    ];
                                })->toArray()) as $index => $correo)
                                    <div class="correo-item border rounded p-3 mb-3">
                                        <div class="input-group">
                                            <input type="email" name="correos[{{ $index }}][correo]" class="form-control"
                                                value="{{ $correo['correo'] ?? '' }}">
                                            <input type="hidden" name="correos[{{ $index }}][id]" value="{{ $correo['id'] ?? '' }}">
                                            @if($index > 0)
                                                <button type="button" class="btn btn-danger remove-correo">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-right">
                <a href="{{ route('contacto.empresa.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    let telefonoIndex = {{ count(old('telefonos', $empresa->telefono_movils)) }};
    let correoIndex = {{ count(old('correos', $empresa->correos)) }};

    document.getElementById('addTelefono').addEventListener('click', function () {
        const container = document.getElementById('telefonos-container');
        const item = document.createElement('div');
        item.className = 'telefono-item border rounded p-3 mb-3';
        item.innerHTML = `
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label>Número</label>
                    <input type="text" name="telefonos[${telefonoIndex}][numero]" class="form-control" placeholder="Número">
                </div>
                <div class="col-md-4">
                    <label>Operadora</label>
                    <select name="telefonos[${telefonoIndex}][id_operadora]" class="form-control">
                        <option value="">Operadora</option>
                        @foreach($operadoras as $operadora)
                            <option value="{{ $operadora->IdOperadora }}">{{ $operadora->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-block remove-telefono">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(item);
        telefonoIndex++;
    });

    document.getElementById('telefonos-container').addEventListener('click', function (event) {
        if (event.target.closest('.remove-telefono')) {
            event.target.closest('.telefono-item').remove();
        }
    });

    document.getElementById('addCorreo').addEventListener('click', function () {
        const container = document.getElementById('correos-container');
        const item = document.createElement('div');
        item.className = 'correo-item border rounded p-3 mb-3';
        item.innerHTML = `
            <div class="input-group">
                <input type="email" name="correos[${correoIndex}][correo]" class="form-control" placeholder="Correo electrónico">
                <button type="button" class="btn btn-danger remove-correo">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(item);
        correoIndex++;
    });

    document.getElementById('correos-container').addEventListener('click', function (event) {
        if (event.target.closest('.remove-correo')) {
            event.target.closest('.correo-item').remove();
        }
    });
</script>
@stop
