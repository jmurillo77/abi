@extends('adminlte::page')

@section('title', 'Clientes')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css') }}">
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1><i class="fas fa-user-tie text-primary"></i> Gestión de Clientes</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item active">Clientes</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Lista de Clientes</h3>
        @submenuCan('create', 'ventas.cliente.index')
            <a href="{{ route('ventas.cliente.crear') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Cliente
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
                <label for="filtroTipo" class="mb-1">Tipo</label>
                <select id="filtroTipo" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <option value="Persona">Persona</option>
                    <option value="Empresa">Empresa</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="filtroEstado" class="mb-1">Estado</label>
                <select id="filtroEstado" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button id="limpiarFiltros" type="button" class="btn btn-outline-secondary btn-sm w-100">Limpiar filtros</button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="clientes" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th width="180">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->IdClientes }}</td>
                            <td>
                                <span class="badge {{ $cliente->TipoCliente === 'empresa' ? 'badge-info' : 'badge-primary' }}">
                                    <i class="{{ $cliente->TipoCliente === 'empresa' ? 'fas fa-building' : 'fas fa-user' }}"></i>
                                    {{ $cliente->TipoCliente === 'empresa' ? 'Empresa' : 'Persona' }}
                                </span>
                            </td>
                            <td>{{ $cliente->NombreRelacionado }}</td>
                            <td>{{ $cliente->Documento ?: '-' }}</td>
                            <td>{{ $cliente->Email ?: '-' }}</td>
                            <td>
                                @if($cliente->Activo)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('ventas.cliente.show', $cliente->IdClientes) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @submenuCan('edit', 'ventas.cliente.index')
                                    <a href="{{ route('ventas.cliente.edit', $cliente->IdClientes) }}" class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endsubmenuCan
                                @submenuCan('delete', 'ventas.cliente.index')
                                    <form action="{{ route('ventas.cliente.destroy', $cliente->IdClientes) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Desea eliminar este cliente?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endsubmenuCan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No hay clientes registrados.</td></tr>
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
    const tabla = $('#clientes').DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        }
    });

    // Búsqueda por subcadena (sin anclas ^$): las celdas traen íconos/HTML anidado y
    // DataTables no recorta los espacios internos al extraer el texto para filtrar.
    const exactFilter = function (columna, valor) {
        tabla.column(columna).search(valor ? $.fn.dataTable.util.escapeRegex(valor) : '', true, false).draw();
    };

    $('#filtroTipo').on('change', function () { exactFilter(1, $(this).val()); });
    $('#filtroEstado').on('change', function () { exactFilter(5, $(this).val()); });

    $('#limpiarFiltros').on('click', function () {
        $('#filtroTipo').val('');
        $('#filtroEstado').val('');
        tabla.search('').columns().search('').draw();
    });
});
</script>
@stop
