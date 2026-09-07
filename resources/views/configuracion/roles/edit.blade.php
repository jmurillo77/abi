@extends('adminlte::page')

@section('title', 'Editar rol')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Editar Rol</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Configuración</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.roles.index') }}">Roles</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('configuracion.roles.update', $role->IdRol) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="Nombre">Nombre</label>
                <input type="text" name="Nombre" id="Nombre" class="form-control" value="{{ old('Nombre', $role->Nombre) }}" required>
                @error('Nombre')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="Descripcion">Descripción</label>
                <textarea name="Descripcion" id="Descripcion" class="form-control" rows="3">{{ old('Descripcion', $role->Descripcion) }}</textarea>
                @error('Descripcion')
                    <small class="text-danger d-block">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="Activo" id="Activo" value="1" class="form-check-input" {{ old('Activo', $role->Activo) ? 'checked' : '' }}>
                <label for="Activo" class="form-check-label">Activo</label>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('configuracion.roles.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@stop
