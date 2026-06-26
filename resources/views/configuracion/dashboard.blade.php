@extends('adminlte::page')

@section('title', 'AbiSyS')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-cogs"></i> Configuración
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('menu') }}">Menú</a>
                </li>
                <li class="breadcrumb-item active">
                    Configuración
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
            'titulo' => 'Menu',
            'total' => $TotalMenu,
            'color' => 'info',
            'icono' => 'fas fa-user',
            'ruta' => 'configuracion.menus.index'
        ],
    ];
@endphp

<div class="container-fluid">

    {{-- Tarjetas principales --}}
    <div class="row mb-3">
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
                                <td>Menu</td>
                                <td>{{ $TotalMenu }}</td>
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
                    {{-- <a href="{{ route('contacto.persona.create') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-user-plus"></i> Nueva Persona
                    </a>

                    <a href="{{ route('contacto.empresa.create') }}" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-building"></i> Nueva Empresa
                    </a>

                    <a href="{{ route('menu') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-home"></i> Volver al Menú
                    </a>
                     --}}
                </div>
            </div>
        </div>
    </div>

</div>

@stop