@extends('adminlte::page')

@section('title', 'Detalle del rol')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Detalle del Rol</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Configuración</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.roles.index') }}">Roles</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">ID</dt>
            <dd class="col-sm-9">{{ $role->IdRol }}</dd>

            <dt class="col-sm-3">Nombre</dt>
            <dd class="col-sm-9">{{ $role->Nombre }}</dd>

            <dt class="col-sm-3">Descripción</dt>
            <dd class="col-sm-9">{{ $role->Descripcion ?: '-' }}</dd>

            <dt class="col-sm-3">Activo</dt>
            <dd class="col-sm-9">
                @if($role->Activo)
                    <span class="badge badge-success">Sí</span>
                @else
                    <span class="badge badge-secondary">No</span>
                @endif
            </dd>
        </dl>

        <a href="{{ route('configuracion.roles.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('configuracion.roles.edit', $role->IdRol) }}" class="btn btn-primary">Editar</a>
    </div>
</div>
@stop
