@extends('adminlte::page')

@section('title', 'Crear nueva persona')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-user-plus text-primary"></i>
                Nueva Persona
            </h1>
        </div>

        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.persona.index') }}">Personas</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="card card-outline card-primary shadow">

    <div class="card-header">
        <h3 class="card-title">Agregar Persona</h3>
    </div>

    <form method="POST" action="{{ route('contacto.persona.store') }}">
        @csrf

        <div class="card-body">

            {{-- Errores --}}
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

            {{-- Datos personales --}}
            <div class="form-group">
                <label>Documento</label>
                <input type="text" name="dni" class="form-control" value="{{ old('dni') }}" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nombres</label>
                    <input type="text" name="nombres" class="form-control" value="{{ old('nombres') }}" required>
                </div>

                <div class="form-group col-md-6">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento"
                           class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                           value="{{ old('fecha_nacimiento') }}">
                    @error('fecha_nacimiento')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            {{-- Teléfonos y Correos --}}
            <div class="row mt-4">

                {{-- TELÉFONOS --}}
                <div class="col-md-6">
                    <div class="card card-outline card-info">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-phone"></i> Teléfonos
                            </h5>

                            <button type="button" id="addTelefono" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <div id="telefonos-container">

                                <div class="telefono-item border rounded p-3 mb-3">
                                    <div class="row align-items-end">

                                        <div class="col-md-5">
                                            <label>Número</label>
                                            <input type="text"
                                                name="telefonos[0][numero]"
                                                class="form-control"
                                                placeholder="Número">
                                        </div>

                                        <div class="col-md-3">
                                            <label>Conectividad</label>
                                            <select name="telefonos[0][id_conectividad]" class="form-control">
                                                <option value="">Conectividad</option>
                                                <option value="1">NA</option>
                                                <option value="2">Fijo</option>
                                                <option value="3">Móvil</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Operadora</label>
                                            <select name="telefonos[0][id_operadora]" class="form-control">
                                                <option value="">Operadora</option>
                                                @foreach($operadoras as $operadora)
                                                    <option value="{{ $operadora->IdOperadora }}">
                                                        {{ $operadora->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                {{-- CORREOS --}}
                <div class="col-md-6">
                    <div class="card card-outline card-success">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-envelope"></i> Correos
                            </h5>

                            <button type="button" id="addCorreo" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <div id="correos-container">

                                <div class="correo-item border rounded p-3 mb-3">
                                    <input type="email"
                                           name="correos[0][correo]"
                                           class="form-control"
                                           placeholder="Correo electrónico">
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('contacto.persona.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>

    </form>

</div>

@stop

@section('js')
<script>
let telefonoIndex = 1;
let correoIndex = 1;

const operadoras = `
@foreach($operadoras as $operadora)
<option value="{{ $operadora->IdOperadora }}">
    {{ $operadora->Nombre }}
</option>
@endforeach
`;

document.getElementById('addTelefono').addEventListener('click', function () {
    let html = `
        <div class="telefono-item border rounded p-3 mb-3">
            <div class="row align-items-end">

                <div class="col-md-4">
                    <label>Número</label>
                    <input type="text"
                        name="telefonos[${telefonoIndex}][numero]"
                        class="form-control"
                        placeholder="Número">
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
                        <option value="">Operadora</option>
                        ${operadoras}
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm removeTelefono w-100">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>

            </div>
        </div>
    `;

    document.getElementById('telefonos-container').insertAdjacentHTML('beforeend', html);
    telefonoIndex++;
});

document.getElementById('addCorreo').addEventListener('click', function () {
    let html = `
        <div class="correo-item border rounded p-3 mb-3">
            <input type="email"
                   name="correos[${correoIndex}][correo]"
                   class="form-control mb-2"
                   placeholder="Correo electrónico">

            <button type="button" class="btn btn-danger btn-sm removeCorreo w-100">
                Eliminar
            </button>
        </div>
    `;

    document.getElementById('correos-container').insertAdjacentHTML('beforeend', html);
    correoIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.removeTelefono')) {
        e.target.closest('.telefono-item').remove();
    }

    if (e.target.closest('.removeCorreo')) {
        e.target.closest('.correo-item').remove();
    }
});
</script>
@stop