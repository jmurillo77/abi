@extends('adminlte::page')

@section('title', 'Detalle de País')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-flag text-primary"></i> Detalle de País
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.pais.index') }}">Países</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-info shadow">
        <div class="card-header">
            <h3 class="card-title">Información del País</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>ID:</strong>
                    <p>{{ $pais->IdPais }}</p>
                </div>

                <div class="col-md-4">
                    <strong>Nombre:</strong>
                    <p>{{ $pais->Nombre }}</p>
                </div>

                <div class="col-md-4">
                    <strong>Continente:</strong>
                    <p>{{ $pais->continente?->Nombre ?? 'Sin continente' }}</p>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('contacto.pais.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('contacto.pais.edit', $pais->IdPais) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>
</div>
@stop
