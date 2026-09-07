@extends('adminlte::page')

@section('title', 'Detalle del Cliente')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1><i class="fas fa-user-tie text-primary"></i> Cliente #{{ $cliente->IdClientes }}</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.cliente.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
@php
    $relacionado = $cliente->TipoCliente === 'empresa' ? $cliente->empresa : $cliente->persona;
    $telefonos = $relacionado?->telefono_movils ?? collect();
    $correos = $relacionado?->correos ?? collect();
    $direcciones = $relacionado?->direcciones ?? collect();
    $esEmpresa = $cliente->TipoCliente === 'empresa';
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline shadow">
            <div class="card-body box-profile text-center">
                <div class="mb-3">
                    <i class="{{ $esEmpresa ? 'fas fa-building' : 'fas fa-user-circle' }} fa-6x text-primary"></i>
                </div>

                <h3 class="profile-username text-center">{{ $cliente->NombreRelacionado }}</h3>

                <p class="text-muted text-center mb-2">
                    <span class="badge {{ $esEmpresa ? 'badge-info' : 'badge-primary' }}">
                        <i class="{{ $esEmpresa ? 'fas fa-building' : 'fas fa-user' }}"></i> {{ $cliente->TipoLabel }}
                    </span>
                    @if($cliente->Activo)
                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Activo</span>
                    @else
                        <span class="badge badge-secondary"><i class="fas fa-ban"></i> Inactivo</span>
                    @endif
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b><i class="fas fa-hashtag text-muted"></i> ID Cliente</b>
                        <span class="float-right">{{ $cliente->IdClientes }}</span>
                    </li>
                    <li class="list-group-item">
                        <b><i class="fas fa-id-card text-muted"></i> Documento</b>
                        <span class="float-right">{{ $cliente->Documento ?: '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b><i class="fas fa-envelope text-muted"></i> Email</b>
                        <span class="float-right">{{ $cliente->Email ?: '-' }}</span>
                    </li>
                    @if(!$esEmpresa && $cliente->persona?->FechaNacimiento)
                        <li class="list-group-item">
                            <b><i class="fas fa-birthday-cake text-muted"></i> Nacimiento</b>
                            <span class="float-right">{{ \Illuminate\Support\Carbon::parse($cliente->persona->FechaNacimiento)->format('d/m/Y') }}</span>
                        </li>
                    @endif
                </ul>

                @submenuCan('edit', 'ventas.cliente.index')
                    <a href="{{ route('ventas.cliente.edit', $cliente->IdClientes) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-edit"></i> Editar Cliente
                    </a>
                @endsubmenuCan
                <a href="{{ route('ventas.cliente.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-info shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-phone"></i> Teléfonos</h3>
            </div>
            <div class="card-body p-0">
                @if($telefonos->isEmpty())
                    <p class="text-muted p-3 mb-0"><i class="fas fa-info-circle"></i> No hay teléfonos registrados.</p>
                @else
                    <table class="table table-striped mb-0">
                        <tbody>
                            @foreach($telefonos as $telefono)
                                <tr>
                                    <td class="pl-3"><i class="fas fa-mobile-alt text-muted"></i> {{ $telefono->Numero }}</td>
                                    <td>{{ $telefono->operadora?->Nombre ?? '-' }}</td>
                                    <td class="text-right pr-3">
                                        @if($telefono->WhatsappValido)
                                            <span class="badge badge-success"><i class="fab fa-whatsapp"></i> WhatsApp</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="card card-outline card-success shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-envelope"></i> Correos</h3>
            </div>
            <div class="card-body p-0">
                @if($correos->isEmpty())
                    <p class="text-muted p-3 mb-0"><i class="fas fa-info-circle"></i> No hay correos registrados.</p>
                @else
                    <table class="table table-striped mb-0">
                        <tbody>
                            @foreach($correos as $correo)
                                <tr>
                                    <td class="pl-3"><i class="fas fa-at text-muted"></i> {{ $correo->Correo }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="card card-outline card-warning shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> Direcciones</h3>
            </div>
            <div class="card-body p-0">
                @if($direcciones->isEmpty())
                    <p class="text-muted p-3 mb-0"><i class="fas fa-info-circle"></i> No hay direcciones registradas.</p>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($direcciones as $direccion)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <i class="fas fa-map-marker-alt text-warning"></i>
                                        {{ $direccion->Nombre ?: 'Sin referencia' }}
                                        @if($direccion->GoogleMapsUrl)
                                            <a href="{{ $direccion->GoogleMapsUrl }}" target="_blank" rel="noopener" class="ml-1" title="Ver en Google Maps">
                                                <i class="fas fa-map-marked-alt"></i>
                                            </a>
                                        @endif
                                        <br>
                                        <small class="text-muted">
                                            {{ collect([
                                                $direccion->parroquia?->Nombre,
                                                $direccion->parroquia?->ciudad?->Nombre,
                                            ])->filter()->implode(', ') ?: 'Ubicación no especificada' }}
                                        </small>
                                        <br>
                                        <small>
                                            <i class="fas fa-route text-muted"></i>
                                            @if($direccion->ruta)
                                                <a href="{{ route('ventas.ruta.show', $direccion->ruta->id) }}">{{ $direccion->ruta->Nombre }}</a>
                                            @else
                                                <span class="text-muted">Sin recorrido asignado</span>
                                            @endif
                                        </small>
                                    </div>
                                    @if($direccion->tipo)
                                        <span class="badge badge-warning">{{ $direccion->tipo->Nombre }}</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

