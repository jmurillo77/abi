@extends('adminlte::page')

@section('title', 'Submenús')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-stream text-primary"></i> Gestión de Submenús
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Configuración</a></li>
                <li class="breadcrumb-item active">Submenús</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de Submenús</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('configuracion.menus.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-th-large"></i> Menús
            </a>
            <a href="{{ route('configuracion.submenus.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Submenú
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table id="submenus" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Menú</th>
                        <th>Título</th>
                        <th>Ruta</th>
                        <th>Icono</th>
                        <th>Orden</th>
                        <th>Activo</th>
                        <th width="170">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submenus as $submenu)
                        <tr>
                            <td>{{ $submenu->IdSubMenu }}</td>
                            <td>{{ $submenu->menu?->Titulo ?? '-' }}</td>
                            <td>{{ $submenu->Titulo }}</td>
                            <td>{{ $submenu->Ruta ?: '-' }}</td>
                            <td>{{ $submenu->Icono ?: '-' }}</td>
                            <td>{{ $submenu->Orden ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ (int) $submenu->Activo === 1 ? 'success' : 'secondary' }}">
                                    {{ (int) $submenu->Activo === 1 ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('configuracion.submenus.show', $submenu->IdSubMenu) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('configuracion.submenus.edit', $submenu->IdSubMenu) }}" class="btn btn-sm btn-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('configuracion.submenus.destroy', $submenu->IdSubMenu) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este submenú?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay submenús registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(function () {
    $('#submenus').DataTable({
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        }
    });
});
</script>
@stop
