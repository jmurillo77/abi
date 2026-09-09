@extends('adminlte::page')

@section('title', 'AbiSyS')

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
                <i class="fas fa-envelope text-primary"></i> Gestión de Correos
            </h1>
        </div>

        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('menu') }}">Menú</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('contacto.dashboard') }}">Contactos</a>
                </li>
                <li class="breadcrumb-item active">
                    Correos
                </li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="card card-outline card-primary shadow">

    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Lista de correos</h3>

            @submenuCan('create', 'contacto.correo.index')
                <a href="{{ route('contacto.correo.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo correo
                </a>
            @endsubmenuCan
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <table id="correos" class="table table-hover table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Correo</th>
                    <th>Personas</th>
                    <th>Empresas</th>
                    <th width="140">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($correos as $correo)
                    <tr>
                        <td>{{ $correo->IdCorreo }}</td>
                        <td>{{ $correo->Correo }}</td>
                        <td>
                            @forelse($correo->personas as $persona)
                                <span class="badge badge-info mr-1 mb-1">{{ trim($persona->Nombres.' '.$persona->Apellidos) }}</span>
                            @empty
                                <span class="badge badge-light border">Sin personas</span>
                            @endforelse
                        </td>
                        <td>
                            @forelse($correo->empresas as $empresa)
                                <span class="badge badge-secondary mr-1 mb-1">{{ $empresa->RazonSocial }}</span>
                            @empty
                                <span class="badge badge-light border">Sin empresas</span>
                            @endforelse
                        </td>
                        <td class="text-center">
                            <a href="{{ route('contacto.correo.show', $correo->IdCorreo) }}"
                               class="btn btn-sm btn-info"
                               title="Ver">
                                <i class="fas fa-eye"></i>
                            </a>
                            @submenuCan('edit', 'contacto.correo.index')
                                <a href="{{ route('contacto.correo.edit', $correo->IdCorreo) }}"
                                   class="btn btn-sm btn-primary"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endsubmenuCan

                            @submenuCan('delete', 'contacto.correo.index')
                                @if($correo->personas->isEmpty() && $correo->empresas->isEmpty())
                                    <form action="{{ route('contacto.correo.destroy', $correo->IdCorreo) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Eliminar"
                                                onclick="return confirm('¿Eliminar este correo?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-secondary" title="Tiene relaciones activas" disabled>
                                        <i class="fas fa-link"></i>
                                    </button>
                                @endif
                            @endsubmenuCan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
    $('#correos').DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>
@stop
