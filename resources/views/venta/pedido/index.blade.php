@extends('adminlte::page')

@section('title', 'Pedidos')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-boxes text-primary"></i> Gestión de Pedidos
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Pedidos</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">Listado de Pedidos</h3>
        <div class="card-tools">
            @submenuCan('create', 'ventas.pedido.index')
                <a href="{{ route('ventas.pedido.crear') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Pedido
                </a>
            @endsubmenuCan
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="buscarPedido" class="mb-1">Buscar</label>
                <input type="text" id="buscarPedido" class="form-control form-control-sm" placeholder="Cliente, ID o estado...">
            </div>
            <div class="col-md-2">
                <label for="filtroEstado" class="mb-1">Estado</label>
                <select id="filtroEstado" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="EN_PREPARACION">En preparación</option>
                    <option value="ENTREGADO">Entregado</option>
                    <option value="CANCELADO">Cancelado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="filtroFechaDesde" class="mb-1">Desde</label>
                <input type="date" id="filtroFechaDesde" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-2">
                <label for="filtroFechaHasta" class="mb-1">Hasta</label>
                <input type="date" id="filtroFechaHasta" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end mt-2 mt-md-0">
                <button id="limpiarFiltros" type="button" class="btn btn-outline-secondary btn-sm w-100">Limpiar</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="pedidos" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Ubicación</th>
                        <th>Recorrido</th>
                        <th>Total</th>
                        <th width="170">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->cliente->NombreRelacionado ?? '-' }}</td>
                            <td data-fecha="{{ optional($pedido->Fecha)->format('Y-m-d') }}">{{ optional($pedido->Fecha)->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $badges = [
                                        'PENDIENTE' => 'warning',
                                        'EN_PREPARACION' => 'info',
                                        'ENTREGADO' => 'success',
                                        'CANCELADO' => 'secondary',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $badges[$pedido->Estado] ?? 'secondary' }}">{{ $pedido->Estado }}</span>
                            </td>
                            <td>
                                @if($pedido->direccion?->GoogleMapsUrl)
                                    <a href="{{ $pedido->direccion->GoogleMapsUrl }}" target="_blank" rel="noopener" title="{{ $pedido->direccion->Etiqueta }}">
                                        <i class="fas fa-map-marker-alt text-danger"></i> Ver mapa
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($pedido->ruta)
                                    <a href="{{ route('ventas.ruta.show', $pedido->ruta->id) }}">{{ $pedido->ruta->Nombre }}</a>
                                @else
                                    <span class="text-muted">Sin asignar</span>
                                @endif
                            </td>
                            <td>${{ number_format((float) $pedido->Total, 2) }}</td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('ventas.pedido.show', $pedido->id) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @submenuCan('edit', 'ventas.pedido.index')
                                    <a href="{{ route('ventas.pedido.edit', $pedido->id) }}" class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endsubmenuCan
                                @submenuCan('delete', 'ventas.pedido.index')
                                    <form action="{{ route('ventas.pedido.destroy', $pedido->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Desea eliminar este pedido?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endsubmenuCan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay pedidos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(function () {
    const tabla = $('#pedidos').DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        dom: 'rtip',
        order: [[0, 'desc']],
        columnDefs: [{ orderable: false, searchable: false, targets: -1 }],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        }
    });

    $('#buscarPedido').on('input', function () {
        tabla.search(this.value).draw();
    });

    $('#filtroEstado').on('change', function () {
        const estado = $(this).val();
        tabla.column(3).search(estado ? '^' + $.fn.dataTable.util.escapeRegex(estado) + '$' : '', true, false).draw();
    });

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        const desde = $('#filtroFechaDesde').val();
        const hasta = $('#filtroFechaHasta').val();
        const fecha = $(tabla.row(dataIndex).node()).find('td').eq(2).data('fecha');

        if (desde && fecha < desde) {
            return false;
        }

        if (hasta && fecha > hasta) {
            return false;
        }

        return true;
    });

    // Aplica de inmediato el filtro de fecha (Desde/Hasta ya vienen con la fecha de hoy)
    tabla.draw();

    $('#filtroFechaDesde, #filtroFechaHasta').on('change', function () {
        tabla.draw();
    });

    $('#limpiarFiltros').on('click', function () {
        $('#buscarPedido').val('');
        $('#filtroEstado').val('');
        $('#filtroFechaDesde').val('');
        $('#filtroFechaHasta').val('');
        tabla.search('').columns().search('').draw();
    });
});
</script>
@stop
