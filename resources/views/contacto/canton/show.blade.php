@extends('adminlte::page')

@section('title', 'Detalle Cantón')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-map text-primary"></i> Detalle Cantón
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.canton.index') }}">Cantones</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-info shadow">
    <div class="card-header">
        <h3 class="card-title">Información del Cantón</h3>
    </div>

    <div class="card-body">
        <p><strong>ID:</strong> {{ $canton->IdCiudad }}</p>
        <p><strong>Nombre:</strong> {{ $canton->Nombre }}</p>
        <p><strong>Provincia:</strong> {{ $canton->provincia?->Nombre ?? 'Sin provincia' }}</p>
        <p><strong>País:</strong> {{ $canton->provincia?->pais?->Nombre ?? 'Sin país' }}</p>
    </div>

    <div class="card-footer text-right">
        <a href="{{ route('contacto.canton.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="{{ route('contacto.canton.edit', $canton->IdCiudad) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
</div>
@stop
