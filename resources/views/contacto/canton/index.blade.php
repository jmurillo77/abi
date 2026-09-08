@extends('adminlte::page')

@section('title', 'Cantones')

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
                <i class="fas fa-map text-primary"></i> Gestión de Cantones
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item active">Cantones</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de Cantones</h3>
        @submenuCan('create', 'contacto.canton.index')
            <a href="{{ route('contacto.canton.crear') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Cantón
            </a>
        @endsubmenuCan
    </div>

    <div class="card-body">
        @php
            $continentesFiltro = $cantones->pluck('provincia.pais.continente.Nombre')->filter()->unique()->sort()->values();
            $paisesFiltro = $cantones->pluck('provincia.pais.Nombre')->filter()->unique()->sort()->values();
            $provinciasFiltro = $cantones->pluck('provincia.Nombre')->filter()->unique()->sort()->values();
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
            <div class="col-md-3 d-flex align-items-end mt-2 mt-md-0">
                <button id="limpiarFiltros" type="button" class="btn btn-outline-secondary btn-sm w-100">
                    Limpiar filtros
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="cantones" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Continente</th>
                        <th>País</th>
                        <th>Provincia</th>
                        <th width="170">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cantones as $canton)
                        <tr>
                            <td>{{ $canton->IdCiudad }}</td>
                            <td>{{ $canton->Nombre }}</td>
                            <td>{{ $canton->provincia?->pais?->continente?->Nombre ?? 'Sin continente' }}</td>
                            <td>{{ $canton->provincia?->pais?->Nombre ?? 'Sin país' }}</td>
                            <td>{{ $canton->provincia?->Nombre ?? 'Sin provincia' }}</td>
                            <td class="text-center">
                                <a href="{{ route('contacto.canton.show', $canton->IdCiudad) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @submenuCan('edit', 'contacto.canton.index')
                                    <a href="{{ route('contacto.canton.edit', $canton->IdCiudad) }}" class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endsubmenuCan
                                @submenuCan('delete', 'contacto.canton.index')
                                    <form action="{{ route('contacto.canton.destroy', $canton->IdCiudad) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Desea eliminar este cantón?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endsubmenuCan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay cantones registrados.</td>
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
    const tabla = $('#cantones').DataTable({
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

    $('#limpiarFiltros').on('click', function () {
        $('#filtroContinente').val('');
        $('#filtroPais').val('');
        $('#filtroProvincia').val('');
        tabla.search('').columns().search('').draw();
    });
});
</script>
@stop
