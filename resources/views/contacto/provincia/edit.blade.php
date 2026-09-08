@extends('adminlte::page')

@section('title', 'Editar Provincia')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-edit text-primary"></i> Editar Provincia
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.provincia.index') }}">Provincias</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">Actualizar Provincia</h3>
    </div>

    <form action="{{ route('contacto.provincia.update', $provincia->IdProvincia) }}" method="POST">
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
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $provincia->Nombre) }}" maxlength="50" required>
            </div>

            <div class="form-group">
                <label for="id_pais">País</label>
                <select id="id_pais" name="id_pais" class="form-control">
                    <option value="">Seleccione un país</option>
                    @foreach($paises as $pais)
                        <option value="{{ $pais->IdPais }}" {{ (string) old('id_pais', $provincia->IdPais) === (string) $pais->IdPais ? 'selected' : '' }}>
                            {{ $pais->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('contacto.provincia.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>
@stop
