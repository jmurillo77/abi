@extends('adminlte::page')

@section('title', 'Detalle Continente')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-globe-americas text-primary"></i> Detalle Continente
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menu</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.continente.index') }}">Continentes</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-info shadow">
    <div class="card-header">
        <h3 class="card-title">Informacion del Continente</h3>
    </div>

    <div class="card-body">
        <p><strong>ID:</strong> {{ $continente->IdContinente }}</p>
        <p><strong>Nombre:</strong> {{ $continente->Nombre }}</p>
    </div>

    <div class="card-footer text-right">
        <a href="{{ route('contacto.continente.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('contacto.continente.edit', $continente->IdContinente) }}" class="btn btn-primary">Editar</a>
    </div>
</div>
@stop
