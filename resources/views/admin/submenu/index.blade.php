@extends('adminlte::page')

@section('title', 'Submenús')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Submenús</h1>
        </div>
        <div class="col-sm-6">
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
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Lista de Submenús</h3>
        <a href="{{ route('configuracion.submenus.create') }}" class="btn btn-primary">Nuevo Submenú</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Menú</th>
                    <th>Título</th>
                    <th>Ruta</th>
                    <th>Icono</th>
                    <th>Orden</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submenus as $submenu)
                    <tr>
                        <td>{{ $submenu->IdSubMenu }}</td>
                        <td>{{ $submenu->menu?->Titulo ?? '-' }}</td>
                        <td>{{ $submenu->Titulo }}</td>
                        <td>{{ $submenu->Ruta ?? '-' }}</td>
                        <td>{{ $submenu->Icono ?? '-' }}</td>
                        <td>{{ $submenu->Orden ?? '-' }}</td>
                        <td>{{ (int) $submenu->Activo === 1 ? 'Sí' : 'No' }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('configuracion.submenus.show', $submenu->IdSubMenu) }}" class="btn btn-sm btn-info">Ver</a>
                            <a href="{{ route('configuracion.submenus.edit', $submenu->IdSubMenu) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('configuracion.submenus.destroy', $submenu->IdSubMenu) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar este submenú?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay submenús registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
