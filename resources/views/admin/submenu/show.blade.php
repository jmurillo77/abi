@extends('adminlte::page')

@section('title', 'Detalle de Submenú')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-stream text-primary"></i> Detalle de Submenú
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Configuración</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.submenus.index') }}">Submenús</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body box-profile">
                <div class="text-center mb-3">
                    <i class="fas fa-sitemap fa-3x text-secondary"></i>
                </div>

                <h3 class="profile-username text-center font-weight-bold">{{ $submenu->Titulo }}</h3>

                <p class="text-muted text-center mb-3">
                    <span class="badge badge-{{ (int) $submenu->Activo === 1 ? 'success' : 'secondary' }} px-2 py-1">
                        {{ (int) $submenu->Activo === 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>ID</b>
                        <span class="float-right">{{ $submenu->IdSubMenu }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Menú Padre</b>
                        <span class="float-right">{{ $submenu->menu?->Titulo ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Orden</b>
                        <span class="float-right">{{ $submenu->Orden ?? '-' }}</span>
                    </li>
                </ul>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('configuracion.submenus.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <a href="{{ route('configuracion.submenus.edit', $submenu->IdSubMenu) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Información del Submenú</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted mb-1">Título</label>
                        <div class="font-weight-bold">{{ $submenu->Titulo }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted mb-1">Ruta</label>
                        <div>{{ $submenu->Ruta ?: '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted mb-1">Ícono</label>
                        <div>
                            @if($submenu->Icono)
                                <i class="{{ explode('|', $submenu->Icono)[0] }} mr-1"></i>
                            @endif
                            {{ $submenu->Icono ?: '-' }}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted mb-1">Estado</label>
                        <div>
                            <span class="badge badge-{{ (int) $submenu->Activo === 1 ? 'success' : 'secondary' }}">
                                {{ (int) $submenu->Activo === 1 ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
