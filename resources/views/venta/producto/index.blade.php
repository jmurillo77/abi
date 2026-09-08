@extends('adminlte::page')

@section('title', 'Productos')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css') }}">
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-boxes text-primary"></i> Gestión de Productos
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Productos</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de Productos</h3>
        @submenuCan('create', 'ventas.producto.index')
            <a href="{{ route('ventas.producto.crear') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        @endsubmenuCan
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
                <label for="filtroTipo" class="mb-1">Tipo de producto</label>
                <select id="filtroTipo" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <option value="MATERIA_PRIMA">Materia prima</option>
                    <option value="ELABORADO">Elaborado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtroMenu" class="mb-1">Tipo de menú</label>
                <select id="filtroMenu" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <option value="ALMUERZO">Almuerzo</option>
                    <option value="PIQUEO">Piqueo</option>
                    <option value="AMBOS">Ambos</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end mt-2 mt-md-0">
                <button id="limpiarFiltros" type="button" class="btn btn-outline-secondary btn-sm w-100">Limpiar filtros</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="productos" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Unidad</th>
                        <th>Costo</th>
                        <th>Stock</th>
                        <th>Receta</th>
                        <th>Menú</th>
                        <th>Tipo Menú</th>
                        <th>Activo</th>
                        <th width="170">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr>
                            <td>{{ $producto->IdProducto }}</td>
                            <td>{{ $producto->Nombre }}</td>
                            <td>{{ $producto->TipoProducto }}</td>
                            <td>{{ $producto->UnidadMedida ?: '-' }}</td>
                            <td>{{ is_null($producto->CostoUnitario) ? '-' : number_format((float) $producto->CostoUnitario, 2) }}</td>
                            <td>{{ is_null($producto->StockActual) ? '-' : number_format((float) $producto->StockActual, 2) }}</td>
                            <td>{{ $producto->UsaReceta === 'S' ? 'Sí' : 'No' }}</td>
                            <td>{{ $producto->UsaMenu === 'S' ? 'Sí' : 'No' }}</td>
                            <td>{{ $producto->TipoMenu ?: '-' }}</td>
                            <td>
                                <span class="badge badge-{{ (int) $producto->Activo === 1 ? 'success' : 'secondary' }}">
                                    {{ (int) $producto->Activo === 1 ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('ventas.producto.show', $producto->IdProducto) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @submenuCan('edit', 'ventas.producto.index')
                                    <a href="{{ route('ventas.producto.edit', $producto->IdProducto) }}" class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endsubmenuCan
                                @submenuCan('delete', 'ventas.producto.index')
                                    <form action="{{ route('ventas.producto.destroy', $producto->IdProducto) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Desea eliminar este producto?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endsubmenuCan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">No hay productos registrados.</td>
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
<script src="{{ asset('vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/buttons/js/buttons.html5.min.js') }}"></script>

<script>
$(function () {
    const tabla = $('#productos').DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        }
    });

    $('#filtroTipo').on('change', function () {
        const tipo = $(this).val();
        tabla.column(2).search(tipo ? '^' + $.fn.dataTable.util.escapeRegex(tipo) + '$' : '', true, false).draw();
    });

    $('#filtroMenu').on('change', function () {
        const menu = $(this).val();
        tabla.column(8).search(menu ? '^' + $.fn.dataTable.util.escapeRegex(menu) + '$' : '', true, false).draw();
    });

    $('#limpiarFiltros').on('click', function () {
        $('#filtroTipo').val('');
        $('#filtroMenu').val('');
        tabla.search('').columns().search('').draw();
    });
});
</script>
@stop
