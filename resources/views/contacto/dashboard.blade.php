@extends('adminlte::page')

@section('title', 'AbiSyS')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-address-book"></i> Dashboard de Contactos
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('menu') }}">Menú</a>
                </li>
                <li class="breadcrumb-item active">
                    Contactos
                </li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')

@php
    $cards = [
        [
            'titulo' => 'Personas',
            'total' => $TotalPersona,
            'color' => 'info',
            'icono' => 'fas fa-user',
            'ruta' => 'contacto.persona.index'
        ],
        [
            'titulo' => 'Empresas',
            'total' => $TotalEmpresa,
            'color' => 'success',
            'icono' => 'fas fa-building',
            'ruta' => 'contacto.empresa.index'
        ],
        [
            'titulo' => 'Teléfonos',
            'total' => $TotalTelefono ?? 0,
            'color' => 'warning',
            'icono' => 'fas fa-phone',
            'ruta' => 'contacto.telefono_movil.index'
        ],
        [
            'titulo' => 'Correos',
            'total' => $TotalCorreo ?? 0,
            'color' => 'danger',
            'icono' => 'fas fa-envelope',
            'ruta' => 'contacto.correo.index'
        ],
    ];
@endphp

<div class="container-fluid">

    {{-- Tarjetas principales --}}
    <div class="row">
        @foreach($cards as $card)
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="small-box bg-{{ $card['color'] }}">
                    <div class="inner">
                        <h3>{{ $card['total'] }}</h3>
                        <p>{{ $card['titulo'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="{{ $card['icono'] }}"></i>
                    </div>
                    <a href="{{ route($card['ruta']) }}" class="small-box-footer">
                        Ver detalle <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Resumen rápido --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        Resumen General
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Módulo</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Personas</td>
                                <td>{{ $TotalPersona }}</td>
                            </tr>
                            <tr>
                                <td>Empresas</td>
                                <td>{{ $TotalEmpresa }}</td>
                            </tr>
                            <tr>
                                <td>Teléfonos</td>
                                <td>{{ $TotalTelefono ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>Correos</td>
                                <td>{{ $TotalCorreo ?? 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Accesos rápidos --}}
        <div class="col-md-4">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">
                        Accesos rápidos
                    </h3>
                </div>
                <div class="card-body">
                    @submenuCan('view', 'contacto.provincia.index')
                        <a href="{{ route('contacto.provincia.index') }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-map-marked-alt"></i> Ir a Provincias
                        </a>
                    @endsubmenuCan

                    <a href="{{ route('menu') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-home"></i> Volver al Menú
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@stop