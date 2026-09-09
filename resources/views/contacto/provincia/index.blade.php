@extends('adminlte::page')

@section('title', 'Provincias')

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
                <i class="fas fa-map-marked-alt text-primary"></i> Gestión de Provincias
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item active">Provincias</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de Provincias</h3>
        @submenuCan('create', 'contacto.provincia.index')
            <a href="{{ route('contacto.provincia.crear') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nueva Provincia
            </a>
        @endsubmenuCan
    </div>

    <div class="card-body">
        @php
            $continentesFiltro = $provincias
                ->pluck('pais.continente.Nombre')
                ->filter()
                ->unique()
                ->sort()
                ->values();
            $paisesFiltro = $provincias
                ->pluck('pais.Nombre')
                ->filter()
                ->unique()
                ->sort()
                ->values();
        @endphp

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filtroContinente" class="mb-1">Continente</label>
                <select id="filtroContinente" class="form-control form-control-sm"></select>
            </div>
            <div class="col-md-4 mt-2 mt-md-0">
                <label for="filtroPais" class="mb-1">País</label>
                <select id="filtroPais" class="form-control form-control-sm"></select>
            </div>
            <div class="col-md-2 d-flex align-items-end mt-2 mt-md-0">
                <button id="limpiarFiltros" type="button" class="btn btn-outline-secondary btn-sm w-100">
                    Limpiar filtros
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table id="provincias" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Continente</th>
                        <th>País</th>
                        <th width="170">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($provincias as $provincia)
                        <tr>
                            <td>{{ $provincia->IdProvincia }}</td>
                            <td>{{ $provincia->Nombre }}</td>
                            <td>{{ $provincia->pais?->continente?->Nombre ?? 'Sin continente' }}</td>
                            <td>{{ $provincia->pais?->Nombre ?? 'Sin país' }}</td>
                            <td class="text-center">
                                <a href="{{ route('contacto.provincia.show', $provincia->IdProvincia) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @submenuCan('edit', 'contacto.provincia.index')
                                    <a href="{{ route('contacto.provincia.edit', $provincia->IdProvincia) }}" class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endsubmenuCan
                                @submenuCan('delete', 'contacto.provincia.index')
                                    <form action="{{ route('contacto.provincia.destroy', $provincia->IdProvincia) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Desea eliminar esta provincia?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endsubmenuCan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay provincias registradas.</td>
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
    const ubicaciones = @json($ubicaciones);
    const tabla = $('#provincias').DataTable({
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

    const continenteSelect = $('#filtroContinente');
    const paisSelect = $('#filtroPais');

    const buildOptions = (items, valueKey, labelKey, placeholder) => {
        const options = [`<option value="">${placeholder}</option>`];

        items.forEach((item) => {
            options.push(`<option value="${item[valueKey]}">${item[labelKey]}</option>`);
        });

        return options.join('');
    };

    const findContinente = (nombre) => ubicaciones.find((continente) => String(continente.Nombre) === String(nombre));

    const renderContinentes = () => {
        continenteSelect.html(buildOptions(ubicaciones, 'Nombre', 'Nombre', 'Todos'));
    };

    const renderPaises = (continenteNombre) => {
        const continente = findContinente(continenteNombre);
        paisSelect.html(buildOptions(continente?.paises || [], 'Nombre', 'Nombre', 'Todos'));
    };

    renderContinentes();
    paisSelect.html(buildOptions([], 'Nombre', 'Nombre', 'Todos'));

    $('#filtroContinente').on('change', function () {
        const continente = $(this).val();
        renderPaises(continente);
        tabla.column(2).search(continente ? '^' + $.fn.dataTable.util.escapeRegex(continente) + '$' : '', true, false).draw();
        tabla.column(3).search('', true, false).draw();
    });

    $('#filtroPais').on('change', function () {
        const pais = $(this).val();
        tabla.column(3).search(pais ? '^' + $.fn.dataTable.util.escapeRegex(pais) + '$' : '', true, false).draw();
    });

    $('#limpiarFiltros').on('click', function () {
        $('#filtroContinente').val('');
        $('#filtroPais').val('');
        tabla.search('').columns().search('').draw();
        paisSelect.html(buildOptions([], 'Nombre', 'Nombre', 'Todos'));
    });
});
</script>
@stop
