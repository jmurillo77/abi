@extends('adminlte::page')

@section('title', 'Detalle de Producto')

@php
    $nivelBadge = match ($producto->TipoProducto) {
        'MATERIA_PRIMA' => 'secondary',
        'RECETA' => 'info',
        'MENU' => 'success',
        default => 'secondary',
    };
@endphp

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-box text-primary"></i> Detalle de Producto
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.producto.index') }}">Productos</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-info shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ $producto->Nombre }}</h3>
            <span class="badge badge-{{ $nivelBadge }}">{{ $producto->TipoProductoLabel }}</span>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <strong>ID:</strong>
                    <p>{{ $producto->IdProducto }}</p>
                </div>

                <div class="col-md-3">
                    <strong>Categoría:</strong>
                    <p>{{ $producto->CategoriaLabel ?: '-' }}</p>
                </div>

                <div class="col-md-2">
                    <strong>Unidad:</strong>
                    <p>{{ $producto->UnidadMedida ?: '-' }}</p>
                </div>

                <div class="col-md-2">
                    <strong>Costo unitario:</strong>
                    <p>{{ is_null($producto->CostoUnitario) ? '-' : number_format((float) $producto->CostoUnitario, 2) }}</p>
                </div>

                <div class="col-md-3">
                    <strong>Activo:</strong>
                    <p>
                        <span class="badge badge-{{ (int) $producto->Activo === 1 ? 'success' : 'secondary' }}">
                            {{ (int) $producto->Activo === 1 ? 'Sí' : 'No' }}
                        </span>
                    </p>
                </div>

                @if($producto->TipoProducto === 'MATERIA_PRIMA')
                    <div class="col-md-2">
                        <strong>Stock actual:</strong>
                        <p>{{ is_null($producto->StockActual) ? '-' : number_format((float) $producto->StockActual, 2) }}</p>
                    </div>

                    <div class="col-md-2">
                        <strong>Merma:</strong>
                        <p>{{ is_null($producto->PorcentajeMerma) ? '-' : number_format((float) $producto->PorcentajeMerma, 2) . '%' }}</p>
                    </div>
                @endif

                @if($producto->TipoProducto === 'RECETA')
                    <div class="col-md-3">
                        <strong>Rendimiento:</strong>
                        <p>
                            @if($producto->RendimientoCantidad)
                                {{ number_format((float) $producto->RendimientoCantidad, 2) }} {{ $producto->RendimientoUnidad }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                @endif

                @if($producto->TipoProducto === 'MENU')
                    <div class="col-md-3">
                        <strong>Tipo menú:</strong>
                        <p>{{ $producto->TipoMenu ?: '-' }}</p>
                    </div>
                @endif

                <div class="col-md-2">
                    <strong>Usa en recetas:</strong>
                    <p>
                        <span class="badge badge-{{ $producto->UsaReceta === 'S' ? 'success' : 'secondary' }}">
                            {{ $producto->UsaReceta === 'S' ? 'Sí' : 'No' }}
                        </span>
                    </p>
                </div>

                <div class="col-md-2">
                    <strong>Usa en menú:</strong>
                    <p>
                        <span class="badge badge-{{ $producto->UsaMenu === 'S' ? 'success' : 'secondary' }}">
                            {{ $producto->UsaMenu === 'S' ? 'Sí' : 'No' }}
                        </span>
                    </p>
                </div>

                <div class="col-md-12">
                    <strong>Descripción:</strong>
                    <p>{{ $producto->Descripcion ?: '-' }}</p>
                </div>
            </div>

            @if($producto->TipoProducto !== 'MATERIA_PRIMA')
                <hr>
                <h5><i class="fas fa-list-ul"></i> Composición (ingredientes / sub-recetas)</h5>

                @if($producto->ingredientes->isEmpty())
                    <p class="text-muted">Este producto no tiene ingredientes registrados.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Insumo / Sub-receta</th>
                                    <th>Nivel</th>
                                    <th>Cantidad</th>
                                    <th>Unidad</th>
                                    <th>Costo estimado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($producto->ingredientes as $ingrediente)
                                    <tr>
                                        <td>{{ $ingrediente->insumo->Nombre ?? '-' }}</td>
                                        <td>{{ $ingrediente->insumo->TipoProductoLabel ?? '-' }}</td>
                                        <td>{{ number_format((float) $ingrediente->Cantidad, 3) }}</td>
                                        <td>{{ $ingrediente->UnidadMedida ?: '-' }}</td>
                                        <td>{{ is_null($ingrediente->CostoEstimado) ? '-' : number_format($ingrediente->CostoEstimado, 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif

            @if($producto->usadoComoInsumoEn->isNotEmpty())
                <hr>
                <h5><i class="fas fa-sitemap"></i> Se usa como ingrediente en</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Producto</th>
                                <th>Nivel</th>
                                <th>Cantidad usada</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($producto->usadoComoInsumoEn as $uso)
                                <tr>
                                    <td>
                                        <a href="{{ route('ventas.producto.show', $uso->producto->IdProducto) }}">{{ $uso->producto->Nombre ?? '-' }}</a>
                                    </td>
                                    <td>{{ $uso->producto->TipoProductoLabel ?? '-' }}</td>
                                    <td>{{ number_format((float) $uso->Cantidad, 3) }}</td>
                                    <td>{{ $uso->UnidadMedida ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('ventas.producto.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            @submenuCan('edit', 'ventas.producto.index')
                <a href="{{ route('ventas.producto.edit', $producto->IdProducto) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
            @endsubmenuCan
        </div>
    </div>
</div>
@stop
