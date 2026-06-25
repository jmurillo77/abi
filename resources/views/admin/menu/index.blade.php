@extends('adminlte::page')

@section('title', 'Menús')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Menús</h1>
        </div>
        <div class="col-sm-6">
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
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Lista de Menús</h3>
        <a href="{{ route('configuracion.menus.create') }}" class="btn btn-primary">Nuevo Menú</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>URL</th>
                    <th>Icono</th>
                    <th>Padre</th>
                    <th>Orden</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menus as $menu)
                    <tr>
                        <td>{{ $menu->IdMenu }}</td>
                        <td>{{ $menu->Titulo }}</td>
                        <td>{{ $menu->Ruta }}</td>
                        <td>{{ $menu->Icono }}</td>
                        <td>{{ $menu->parent?->Nombre ?? '-' }}</td>
                        <td>{{ $menu->order }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('configuracion.menus.show', $menu->IdMenu) }}" class="btn btn-sm btn-info">Ver</a>
                            <a href="{{ route('configuracion.menus.edit', $menu->IdMenu) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('configuracion.menus.destroy', $menu->IdMenu) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar este menú?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
