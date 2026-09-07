@extends('adminlte::page')

@section('title', 'Detalle del Pedido')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-box-open text-primary"></i> Pedido #{{ $pedido->id }}
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.pedido.index') }}">Pedidos</a></li>
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
        'PENDIENTE' => 'warning',
        'EN_PREPARACION' => 'info',
        'ENTREGADO' => 'success',
        'CANCELADO' => 'secondary',
    ];
@endphp

<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-primary shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Información del Pedido</h3>
                @submenuCan('edit', 'ventas.pedido.index')
                    <a href="{{ route('ventas.pedido.edit', $pedido->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endsubmenuCan
            </div>

            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-5">Cliente</dt>
                    <dd class="col-sm-7">{{ $pedido->cliente->NombreRelacionado ?? '-' }}</dd>

                    <dt class="col-sm-5">Documento</dt>
                    <dd class="col-sm-7">{{ $pedido->cliente->Documento ?? '-' }}</dd>

                    <dt class="col-sm-5">Fecha</dt>
                    <dd class="col-sm-7">{{ optional($pedido->Fecha)->format('d/m/Y H:i') }}</dd>

                    <dt class="col-sm-5">Estado</dt>
                    <dd class="col-sm-7">
                        <span class="badge badge-{{ $badges[$pedido->Estado] ?? 'secondary' }}">{{ $pedido->Estado }}</span>
                    </dd>

                    <dt class="col-sm-5">Dirección de envío</dt>
                    <dd class="col-sm-7">
                        {{ $pedido->direccion->Etiqueta ?? 'Sin dirección' }}
                        @if($pedido->direccion?->GoogleMapsUrl)
                            <a href="{{ $pedido->direccion->GoogleMapsUrl }}" target="_blank" rel="noopener" class="ml-1" title="Ver en Google Maps">
                                <i class="fas fa-map-marked-alt"></i> Ver en mapa
                            </a>
                        @endif
                    </dd>

                    <dt class="col-sm-5">Tipo de envío</dt>
                    <dd class="col-sm-7">{{ $pedido->TipoEnvioLabel }}</dd>

                    <dt class="col-sm-5">Ruta de entrega</dt>
                    <dd class="col-sm-7">
                        @if($pedido->ruta)
                            <a href="{{ route('ventas.ruta.show', $pedido->ruta->id) }}">{{ $pedido->ruta->Nombre }}</a>
                        @else
                            <span class="text-muted">Sin asignar</span>
                        @endif
                    </dd>

                    @if($pedido->TipoEnvio === 'GLOBAL')
                        <dt class="col-sm-5">Costo de envío</dt>
                        <dd class="col-sm-7">${{ number_format((float) $pedido->CostoEnvio, 2) }}</dd>
                    @endif

                    <dt class="col-sm-5">Total</dt>
                    <dd class="col-sm-7"><strong>${{ number_format((float) $pedido->Total, 2) }}</strong></dd>

                    <dt class="col-sm-5">Observaciones</dt>
                    <dd class="col-sm-7">{{ $pedido->Observaciones ?: '-' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-info shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list"></i> Productos del pedido</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th class="text-right">Precio Unit.</th>
                            <th class="text-right">Subtotal</th>
                            @if($pedido->TipoEnvio === 'POR_ITEM')
                                <th class="text-right">Envío</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedido->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->Nombre }}</td>
                                <td><span class="badge badge-light">{{ $detalle->TipoItemLabel }}</span></td>
                                <td>{{ $detalle->Cantidad }}</td>
                                <td class="text-right">${{ number_format((float) $detalle->PrecioUnitario, 2) }}</td>
                                <td class="text-right">${{ number_format((float) $detalle->Subtotal, 2) }}</td>
                                @if($pedido->TipoEnvio === 'POR_ITEM')
                                    <td class="text-right">${{ number_format((float) $detalle->CostoEnvio, 2) }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Este pedido no tiene productos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="{{ $pedido->TipoEnvio === 'POR_ITEM' ? 5 : 4 }}" class="text-right">Total</th>
                            <th class="text-right">${{ number_format((float) $pedido->Total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="text-right">
    <a href="{{ route('ventas.pedido.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@stop
