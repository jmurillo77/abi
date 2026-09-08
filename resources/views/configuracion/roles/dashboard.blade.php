@extends('adminlte::page')

@section('title', 'Administración de roles')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Administración de roles</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Configuración</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalRoles }}</h3>
                <p>Roles</p>
            </div>
            <div class="icon"><i class="fas fa-user-shield"></i></div>
            <a href="{{ route('configuracion.roles.index') }}" class="small-box-footer">
                Ver roles <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $rolesActivos }}</h3>
                <p>Roles activos</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('configuracion.roles.index') }}" class="small-box-footer">
                Administrar <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Resumen</h3>
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span>Catálogo de roles del sistema</span>
            <a href="{{ route('configuracion.roles.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Nuevo rol
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Activo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->Nombre }}</td>
                            <td>{{ $role->Descripcion ?: '-' }}</td>
                            <td>
                                @if($role->Activo)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
