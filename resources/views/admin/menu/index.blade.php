@extends('adminlte::page')

@section('title', 'Menús')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-th-large text-primary"></i> Gestión de Menús
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                    <a href="{{ route('menu') }}">Menú</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Configuración</a></li>
                <li class="breadcrumb-item active">Menús</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Listado de Menús</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('configuracion.submenus.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list"></i> Submenús
            </a>
            <a href="{{ route('configuracion.menus.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Menú
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
            <table id="menus" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Ruta</th>
                        <th>Icono</th>
                        <th>Menú padre</th>
                        <th>Orden</th>
                        <th>Activo</th>
                        <th width="170">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td>{{ $menu->IdMenu }}</td>
                            <td>{{ $menu->Titulo }}</td>
                            <td>{{ $menu->Ruta ?: '-' }}</td>
                            <td>{{ $menu->Icono ?: '-' }}</td>
                            <td>{{ $menu->parent?->Titulo ?? '-' }}</td>
                            <td>{{ $menu->Orden ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ (int) ($menu->Activo ?? 0) === 1 ? 'success' : 'secondary' }}">
                                    {{ (int) ($menu->Activo ?? 0) === 1 ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('configuracion.menus.show', $menu->IdMenu) }}" class="btn btn-sm btn-info" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('configuracion.menus.edit', $menu->IdMenu) }}" class="btn btn-sm btn-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('configuracion.menus.destroy', $menu->IdMenu) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este menú?');">
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
                            <td colspan="8" class="text-center">No hay menús registrados.</td>
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
    $('#menus').DataTable({
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
