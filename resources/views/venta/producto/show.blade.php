@extends('adminlte::page')

@section('title', 'Detalle de Producto')

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
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
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
        <div class="card-header">
            <h3 class="card-title">Información del Producto</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>ID:</strong>
                    <p>{{ $producto->IdProducto }}</p>
                </div>

                <div class="col-md-3">
                    <strong>Nombre:</strong>
                    <p>{{ $producto->Nombre }}</p>
                </div>

                <div class="col-md-3">
                    <strong>Tipo:</strong>
                    <p>{{ $producto->TipoProducto }}</p>
                </div>

                <div class="col-md-3">
                    <strong>Unidad:</strong>
                    <p>{{ $producto->UnidadMedida ?: '-' }}</p>
                </div>

                <div class="col-md-3">
                    <strong>Costo unitario:</strong>
                    <p>{{ is_null($producto->CostoUnitario) ? '-' : number_format((float) $producto->CostoUnitario, 2) }}</p>
                </div>

                <div class="col-md-3">
                    <strong>Stock actual:</strong>
                    <p>{{ is_null($producto->StockActual) ? '-' : number_format((float) $producto->StockActual, 2) }}</p>
                </div>

                <div class="col-md-2">
                    <strong>Usa receta:</strong>
                    <p>
                        <span class="badge badge-{{ $producto->UsaReceta === 'S' ? 'success' : 'secondary' }}">
                            {{ $producto->UsaReceta === 'S' ? 'Sí' : 'No' }}
                        </span>
                    </p>
                </div>

                <div class="col-md-2">
                    <strong>Usa menú:</strong>
                    <p>
                        <span class="badge badge-{{ $producto->UsaMenu === 'S' ? 'success' : 'secondary' }}">
                            {{ $producto->UsaMenu === 'S' ? 'Sí' : 'No' }}
                        </span>
                    </p>
                </div>

                <div class="col-md-2">
                    <strong>Tipo menú:</strong>
                    <p>{{ $producto->TipoMenu ?: '-' }}</p>
                </div>

                <div class="col-md-12">
                    <strong>Descripción:</strong>
                    <p>{{ $producto->Descripcion ?: '-' }}</p>
                </div>

                <div class="col-md-2">
                    <strong>Activo:</strong>
                    <p>
                        <span class="badge badge-{{ (int) $producto->Activo === 1 ? 'success' : 'secondary' }}">
                            {{ (int) $producto->Activo === 1 ? 'Sí' : 'No' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('ventas.producto.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('ventas.producto.edit', $producto->IdProducto) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
        </div>
    </div>
</div>
@stop
