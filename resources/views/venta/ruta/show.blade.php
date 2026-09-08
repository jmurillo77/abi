@extends('adminlte::page')

@section('title', 'Detalle de Ruta')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-route text-primary"></i> {{ $ruta->Nombre }}
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.ruta.index') }}">Rutas</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
    $badges = [
        'PLANIFICADA' => 'warning',
        'EN_CURSO' => 'info',
        'FINALIZADA' => 'success',
        'CANCELADA' => 'secondary',
    ];
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-primary shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Información de la Ruta</h3>
                @submenuCan('edit', 'ventas.ruta.index')
                    <a href="{{ route('ventas.ruta.edit', $ruta->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endsubmenuCan
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Fecha</dt>
                    <dd class="col-sm-7">{{ optional($ruta->Fecha)->format('d/m/Y') }}</dd>

                    <dt class="col-sm-5">Estado</dt>
                    <dd class="col-sm-7">
                        <span class="badge badge-{{ $badges[$ruta->Estado] ?? 'secondary' }}">{{ $ruta->EstadoLabel }}</span>
                    </dd>

                    <dt class="col-sm-5">Pedidos asignados</dt>
                    <dd class="col-sm-7">{{ $ruta->pedidos->count() }}</dd>

                    <dt class="col-sm-5">Total de la ruta</dt>
                    <dd class="col-sm-7"><strong>${{ number_format((float) $ruta->pedidos->sum('Total'), 2) }}</strong></dd>

                    <dt class="col-sm-5">Observaciones</dt>
                    <dd class="col-sm-7">{{ $ruta->Observaciones ?: '-' }}</dd>
                </dl>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('ventas.ruta.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="card card-outline card-success shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus"></i> Agregar pedidos</h3>
            </div>
            <form action="{{ route('ventas.ruta.asignar-pedidos', $ruta->id) }}" method="POST">
                @csrf
                <div class="card-body" style="max-height: 320px; overflow-y: auto;">
                    @if($pedidosDisponibles->isEmpty())
                        <p class="text-muted mb-0"><i class="fas fa-info-circle"></i> No hay pedidos con dirección de envío disponibles para asignar.</p>
                    @else
                        @foreach($pedidosDisponibles as $pedido)
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" name="pedidos[]" value="{{ $pedido->id }}" id="pedido-{{ $pedido->id }}">
                                <label class="form-check-label" for="pedido-{{ $pedido->id }}">
                                    #{{ $pedido->id }} - {{ $pedido->cliente->NombreRelacionado ?? '-' }}
                                    <br><small class="text-muted">{{ optional($pedido->Fecha)->format('d/m/Y H:i') }} · ${{ number_format((float) $pedido->Total, 2) }}</small>
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
                @if($pedidosDisponibles->isNotEmpty())
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Asignar seleccionados
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-info shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list"></i> Pedidos de la ruta</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Dirección de entrega</th>
                            <th>Estado</th>
                            <th class="text-right">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ruta->pedidos as $pedido)
                            <tr>
                                <td><a href="{{ route('ventas.pedido.show', $pedido->id) }}">#{{ $pedido->id }}</a></td>
                                <td>{{ $pedido->cliente->NombreRelacionado ?? '-' }}</td>
                                <td>
                                    {{ $pedido->direccion->Etiqueta ?? 'Sin dirección' }}
                                    @if($pedido->direccion?->GoogleMapsUrl)
                                        <a href="{{ $pedido->direccion->GoogleMapsUrl }}" target="_blank" rel="noopener" title="Ver en Google Maps">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </a>
                                    @endif
                                </td>
                                <td><span class="badge badge-{{ $badges[$pedido->Estado] ?? 'secondary' }}">{{ $pedido->Estado }}</span></td>
                                <td class="text-right">${{ number_format((float) $pedido->Total, 2) }}</td>
                                <td class="text-right">
                                    <form action="{{ route('ventas.ruta.quitar-pedido', [$ruta->id, $pedido->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Quitar este pedido de la ruta?')" title="Quitar de la ruta">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Esta ruta no tiene pedidos asignados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
