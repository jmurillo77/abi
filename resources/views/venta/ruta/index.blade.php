@extends('adminlte::page')

@section('title', 'Rutas de Entrega')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-route text-primary"></i> Rutas de Entrega
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Rutas</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">Listado de Rutas</h3>
        <div class="card-tools">
            @submenuCan('create', 'ventas.ruta.index')
                <a href="{{ route('ventas.ruta.crear') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Ruta
                </a>
            @endsubmenuCan
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Pedidos</th>
                        <th width="170">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rutas as $ruta)
                        <tr>
                            <td>{{ $ruta->id }}</td>
                            <td>{{ $ruta->Nombre }}</td>
                            <td>{{ optional($ruta->Fecha)->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $badges = [
                                        'PLANIFICADA' => 'warning',
                                        'EN_CURSO' => 'info',
                                        'FINALIZADA' => 'success',
                                        'CANCELADA' => 'secondary',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $badges[$ruta->Estado] ?? 'secondary' }}">{{ $ruta->EstadoLabel }}</span>
                            </td>
                            <td>{{ $ruta->pedidos_count }}</td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('ventas.ruta.show', $ruta->id) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @submenuCan('edit', 'ventas.ruta.index')
                                    <a href="{{ route('ventas.ruta.edit', $ruta->id) }}" class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endsubmenuCan
                                @submenuCan('delete', 'ventas.ruta.index')
                                    <form action="{{ route('ventas.ruta.destroy', $ruta->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Desea eliminar esta ruta? Los pedidos asignados quedarán sin ruta.')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endsubmenuCan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay rutas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
