@extends('adminlte::page')

@section('tituloPagina', 'Crear nueva persona')


@section('content')


<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-9">
                <h3 class="card-title">Agregar Empresa</h3>
            </div>
            <div class="col-md-3">
                <div class="float-right">
                    <a href="{{ route('contacto.empresa.index') }}" style="color:#0970d6; margin: 5px 0 0 0px;"><span class="fas fa-arrow-left fa-lg"></span>  Regresar</a>
                </div>
            </div>
        </div>
    </div>
        <div class="card m-3 ">

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Se encontraron errores:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <p class="card-text">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('contacto.empresa.store') }}">
                <!-- Add csrf token -->
                {{ csrf_field() }}

                @php
                    $telefonos = old('telefonos', [['numero' => '', 'id_conectividad' => '', 'id_operadora' => '']]);
                    $correos = old('correos', [['correo' => '']]);
                @endphp

                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-id-card fa-lg"
                            style="color: #0d4a87;"></i></span>
                    <input type="text" name="RUC" class="form-control form-control-lg @error('RUC') is-invalid @enderror"
                        placeholder="RUC" aria-label="RUC" aria-describedby="basic-addon1"
                        value="{{ old('RUC') }}" required>
                    @error('RUC')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-building fa-lg"
                            style="color: #0d4a87;"></i></span>
                    <input type="text" name="RazonSocial" class="form-control form-control-lg @error('RazonSocial') is-invalid @enderror"
                        placeholder="Razón Social" aria-label="Razón Social" aria-describedby="basic-addon1"
                        value="{{ old('RazonSocial') }}" required>
                    @error('RazonSocial')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-outline card-info">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-phone"></i> Teléfonos</h5>
                                <button type="button" id="addTelefono" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="telefonos-container">
                                    @foreach($telefonos as $index => $telefono)
                                        <div class="telefono-item border rounded p-3 mb-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-5">
                                                    <label>Número</label>
                                                    <input type="text"
                                                        name="telefonos[{{ $index }}][numero]"
                                                        class="form-control {{ $errors->has("telefonos.$index.numero") ? 'is-invalid' : '' }}"
                                                        placeholder="Número"
                                                        value="{{ $telefono['numero'] ?? '' }}">
                                                    @error("telefonos.$index.numero")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Conectividad</label>
                                                    <select name="telefonos[{{ $index }}][id_conectividad]" class="form-control {{ $errors->has("telefonos.$index.id_conectividad") ? 'is-invalid' : '' }}">
                                                        <option value="">Conectividad</option>
                                                        <option value="1" {{ ($telefono['id_conectividad'] ?? '') == '1' ? 'selected' : '' }}>NA</option>
                                                        <option value="2" {{ ($telefono['id_conectividad'] ?? '') == '2' ? 'selected' : '' }}>Fijo</option>
                                                        <option value="3" {{ ($telefono['id_conectividad'] ?? '') == '3' ? 'selected' : '' }}>Móvil</option>
                                                    </select>
                                                    @error("telefonos.$index.id_conectividad")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Operadora</label>
                                                    <select name="telefonos[{{ $index }}][id_operadora]" class="form-control {{ $errors->has("telefonos.$index.id_operadora") ? 'is-invalid' : '' }}">
                                                        <option value="">Operadora</option>
                                                        @foreach($operadoras as $operadora)
                                                            <option value="{{ $operadora->IdOperadora }}"
                                                                {{ ($telefono['id_operadora'] ?? '') == $operadora->IdOperadora ? 'selected' : '' }}>
                                                                {{ $operadora->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error("telefonos.$index.id_operadora")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @if($index > 0)
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger btn-block remove-telefono" title="Eliminar">
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
                                <h5 class="mb-0"><i class="fas fa-envelope"></i> Correos</h5>
                                <button type="button" id="addCorreo" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="correos-container">
                                    @foreach($correos as $index => $correo)
                                        <div class="correo-item border rounded p-3 mb-3">
                                            <div class="input-group">
                                                <input type="email"
                                                    name="correos[{{ $index }}][correo]"
                                                    class="form-control {{ $errors->has("correos.$index.correo") ? 'is-invalid' : '' }}"
                                                    placeholder="Correo electrónico"
                                                    value="{{ $correo['correo'] ?? '' }}">
                                                @if($index > 0)
                                                    <button type="button" class="btn btn-danger remove-correo" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @error("correos.$index.correo")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
                <button class="btn btn-primary"><span class="fas fa-user-plus fa-lg"></span> Agregar</button>
                <a href="{{ route('contacto.empresa.index') }}" class="btn btn-secondary"><span class="fas fa-arrow-left fa-lg"></span> Cancelar</a>
            </form>

            </p>
        </div>

    </div>

@endsection

@section('js')
<script>
    let telefonoIndex = {{ count($telefonos) }};
    let correoIndex = {{ count($correos) }};

    const operadoraOptions = `
        <option value="">Operadora</option>
        @foreach($operadoras as $operadora)
            <option value="{{ $operadora->IdOperadora }}">{{ $operadora->Nombre }}</option>
        @endforeach
    `;

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
                <div class="col-md-3">
                    <label>Conectividad</label>
                    <select name="telefonos[${telefonoIndex}][id_conectividad]" class="form-control">
                        <option value="">Conectividad</option>
                        <option value="1">NA</option>
                        <option value="2">Fijo</option>
                        <option value="3">Móvil</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Operadora</label>
                    <select name="telefonos[${telefonoIndex}][id_operadora]" class="form-control">
                        ${operadoraOptions}
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-block remove-telefono" title="Eliminar">
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
                <button type="button" class="btn btn-danger remove-correo" title="Eliminar">
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
@endsection
