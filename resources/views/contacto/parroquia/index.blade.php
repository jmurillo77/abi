@extends('adminlte::page')

@section('title', 'Parroquias')

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
                <i class="fas fa-map-pin text-primary"></i> Gestión de Parroquias
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item active">Parroquias</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de Parroquias</h3>
        @submenuCan('create', 'contacto.parroquia.index')
            <a href="{{ route('contacto.parroquia.crear') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Parroquia
            </a>
        @endsubmenuCan
    </div>

    <div class="card-body">
        @php
            $continentesFiltro = $parroquias->pluck('canton.provincia.pais.continente.Nombre')->filter()->unique()->sort()->values();
            $paisesFiltro = $parroquias->pluck('canton.provincia.pais.Nombre')->filter()->unique()->sort()->values();
            $provinciasFiltro = $parroquias->pluck('canton.provincia.Nombre')->filter()->unique()->sort()->values();
            $cantonesFiltro = $parroquias->pluck('canton.Nombre')->filter()->unique()->sort()->values();
        @endphp

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row mb-3">
            <div class="col-md-3">
                <label for="filtroContinente" class="mb-1">Continente</label>
                <select id="filtroContinente" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    @foreach($continentesFiltro as $continenteNombre)
                        <option value="{{ $continenteNombre }}">{{ $continenteNombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-2 mt-md-0">
                <label for="filtroPais" class="mb-1">País</label>
                <select id="filtroPais" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    @foreach($paisesFiltro as $paisNombre)
                        <option value="{{ $paisNombre }}">{{ $paisNombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-2 mt-md-0">
                <label for="filtroProvincia" class="mb-1">Provincia</label>
                <select id="filtroProvincia" class="form-control form-control-sm">
                    <option value="">Todas</option>
                    @foreach($provinciasFiltro as $provinciaNombre)
                        <option value="{{ $provinciaNombre }}">{{ $provinciaNombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-2 mt-md-0">
                <label for="filtroCanton" class="mb-1">Cantón</label>
                <select id="filtroCanton" class="form-control form-control-sm">
                    <option value="">Todos</option>
                    @foreach($cantonesFiltro as $cantonNombre)
                        <option value="{{ $cantonNombre }}">{{ $cantonNombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 d-flex align-items-end">
                <button id="limpiarFiltros" type="button" class="btn btn-outline-secondary btn-sm w-100">
                    Limpiar filtros
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="parroquias" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Continente</th>
                        <th>País</th>
                        <th>Provincia</th>
                        <th>Cantón</th>
                        <th width="170">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parroquias as $parroquia)
                        <tr>
                            <td>{{ $parroquia->IdParroquia }}</td>
                            <td>{{ $parroquia->Nombre }}</td>
                            <td>{{ $parroquia->canton?->provincia?->pais?->continente?->Nombre ?? 'Sin continente' }}</td>
                            <td>{{ $parroquia->canton?->provincia?->pais?->Nombre ?? 'Sin país' }}</td>
                            <td>{{ $parroquia->canton?->provincia?->Nombre ?? 'Sin provincia' }}</td>
                            <td>{{ $parroquia->canton?->Nombre ?? 'Sin cantón' }}</td>
                            <td class="text-center">
                                <a href="{{ route('contacto.parroquia.show', $parroquia->IdParroquia) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @submenuCan('edit', 'contacto.parroquia.index')
                                    <a href="{{ route('contacto.parroquia.edit', $parroquia->IdParroquia) }}" class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endsubmenuCan
                                @submenuCan('delete', 'contacto.parroquia.index')
                                    <form action="{{ route('contacto.parroquia.destroy', $parroquia->IdParroquia) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Desea eliminar esta parroquia?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endsubmenuCan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay parroquias registradas.</td>
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
    const tabla = $('#parroquias').DataTable({
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

    $('#filtroContinente').on('change', function () {
        const value = $(this).val();
        tabla.column(2).search(value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '', true, false).draw();
    });

    $('#filtroPais').on('change', function () {
        const value = $(this).val();
        tabla.column(3).search(value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '', true, false).draw();
    });

    $('#filtroProvincia').on('change', function () {
        const value = $(this).val();
        tabla.column(4).search(value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '', true, false).draw();
    });

    $('#filtroCanton').on('change', function () {
        const value = $(this).val();
        tabla.column(5).search(value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '', true, false).draw();
    });

    $('#limpiarFiltros').on('click', function () {
        $('#filtroContinente').val('');
        $('#filtroPais').val('');
        $('#filtroProvincia').val('');
        $('#filtroCanton').val('');
        tabla.search('').columns().search('').draw();
    });
});
</script>
@stop
