@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">
                <i class="fas fa-user-tag mr-2 text-primary"></i> Roles
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header bg-primary">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h3 class="card-title mb-0 text-white">
                <i class="fas fa-users-cog mr-2"></i> Gestión de roles
            </h3>
            <a href="{{ route('configuracion.roles.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus mr-1"></i> Nuevo rol
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th style="width: 100px;">Estado</th>
                        <th style="width: 260px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->IdRol }}</td>
                            <td>
                                <span class="font-weight-bold">{{ $role->Nombre }}</span>
                            </td>
                            <td>{{ $role->Descripcion ?: 'Sin descripción' }}</td>
                            <td class="text-center">
                                @if($role->Activo)
                                    <span class="badge badge-success px-2 py-1">Activo</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('configuracion.roles.show', $role->IdRol) }}" class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('configuracion.roles.edit', $role->IdRol) }}" class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('configuracion.roles.submenus', ['role' => $role->IdRol]) }}" class="btn btn-sm btn-success" title="Submenús">
                                        <i class="fas fa-sitemap"></i>
                                    </a>
                                    <form action="{{ route('configuracion.roles.destroy', $role->IdRol) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este rol?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                No hay roles registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
