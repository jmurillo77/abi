@extends('adminlte::page')

@section('title', 'Editar País')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-edit text-primary"></i> Editar País
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.pais.index') }}">Países</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">Actualizar País</h3>
    </div>

    <form action="{{ route('contacto.pais.update', $pais->IdPais) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
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

            <div class="form-group">
                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="form-control @error('nombre') is-invalid @enderror"
                    value="{{ old('nombre', $pais->Nombre) }}"
                    maxlength="50"
                    required>
                @error('nombre')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_continente">Continente</label>
                <select id="id_continente" name="id_continente" class="form-control @error('id_continente') is-invalid @enderror">
                    <option value="">Seleccione un continente</option>
                    @foreach($continentes as $continente)
                        <option value="{{ $continente->IdContinente }}" {{ (string) old('id_continente', $pais->IdContinente) === (string) $continente->IdContinente ? 'selected' : '' }}>
                            {{ $continente->Nombre }}
                        </option>
                    @endforeach
                </select>
                @error('id_continente')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('contacto.pais.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>
@stop
