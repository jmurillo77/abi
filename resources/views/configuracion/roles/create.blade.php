@extends('adminlte::page')

@section('title', 'Crear rol')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Crear Rol</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Configuración</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.roles.index') }}">Roles</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('configuracion.roles.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="Nombre">Nombre</label>
                <input type="text" name="Nombre" id="Nombre" class="form-control" value="{{ old('Nombre') }}" required>
                @error('Nombre')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="Descripcion">Descripción</label>
                <textarea name="Descripcion" id="Descripcion" class="form-control" rows="3">{{ old('Descripcion') }}</textarea>
                @error('Descripcion')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="Activo" id="Activo" value="1" class="form-check-input" {{ old('Activo', true) ? 'checked' : '' }}>
                <label for="Activo" class="form-check-label">Activo</label>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('configuracion.roles.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop
