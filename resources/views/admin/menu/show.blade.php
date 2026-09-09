@extends('adminlte::page')

@section('title', 'Detalle de Menú')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-bars text-primary"></i> Detalle de Menú
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Configuración</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.menus.index') }}">Menús</a></li>
                <li class="breadcrumb-item active">{{ $menu->Titulo }}</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body box-profile">
                <div class="text-center mb-3">
                    <i class="fas fa-layer-group fa-4x text-secondary"></i>
                </div>

                <h3 class="profile-username text-center font-weight-bold">{{ $menu->Titulo }}</h3>

                <p class="text-muted text-center mb-3">
                    <span class="badge badge-{{ (int) $menu->Activo === 1 ? 'success' : 'secondary' }} px-2 py-1">
                        {{ (int) $menu->Activo === 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>ID</b>
                        <span class="float-right">{{ $menu->IdMenu }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Ruta</b>
                        <span class="float-right text-muted">{{ $menu->Ruta ?: '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Ícono</b>
                        <span class="float-right text-muted">{{ $menu->Icono ?: '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Padre</b>
                        <span class="float-right">{{ $menu->parent?->Titulo ?? '-' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Orden</b>
                        <span class="float-right">{{ $menu->Orden ?? '-' }}</span>
                    </li>
                </ul>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('configuracion.menus.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <a href="{{ route('configuracion.menus.edit', $menu->IdMenu) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header">
                <h3 class="card-title">Submenús Relacionados</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-0">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Ruta</th>
                                <th>Orden</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menu->children->sortBy('Orden') as $child)
                                <tr>
                                    <td>{{ $child->IdSubMenu }}</td>
                                    <td>{{ $child->Titulo }}</td>
                                    <td>{{ $child->Ruta ?: '-' }}</td>
                                    <td>{{ $child->Orden ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ (int) $child->Activo === 1 ? 'success' : 'secondary' }}">
                                            {{ (int) $child->Activo === 1 ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Este menú no tiene submenús relacionados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
