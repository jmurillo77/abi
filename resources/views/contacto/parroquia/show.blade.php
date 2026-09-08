@extends('adminlte::page')

@section('title', 'Detalle Parroquia')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-map-pin text-primary"></i> Detalle de Parroquia
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.parroquia.index') }}">Parroquias</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-info shadow">
    <div class="card-header">
        <h3 class="card-title">Información de la Parroquia</h3>
    </div>

    <div class="card-body">
        <p><strong>ID:</strong> {{ $parroquia->IdParroquia }}</p>
        <p><strong>Nombre:</strong> {{ $parroquia->Nombre }}</p>
        <p><strong>Cantón:</strong> {{ $parroquia->canton?->Nombre ?? 'Sin cantón' }}</p>
        <p><strong>Provincia:</strong> {{ $parroquia->canton?->provincia?->Nombre ?? 'Sin provincia' }}</p>
        <p><strong>País:</strong> {{ $parroquia->canton?->provincia?->pais?->Nombre ?? 'Sin país' }}</p>
        <p><strong>Continente:</strong> {{ $parroquia->canton?->provincia?->pais?->continente?->Nombre ?? 'Sin continente' }}</p>
    </div>

    <div class="card-footer text-right">
        <a href="{{ route('contacto.parroquia.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="{{ route('contacto.parroquia.edit', $parroquia->IdParroquia) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
</div>
@stop
