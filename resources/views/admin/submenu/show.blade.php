@extends('adminlte::page')

@section('title', 'Detalle de Submenú')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Detalle de Submenú</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.submenus.index') }}">Submenús</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <ul class="list-group mb-3">
            <li class="list-group-item"><strong>ID:</strong> {{ $submenu->IdSubMenu }}</li>
            <li class="list-group-item"><strong>Menú:</strong> {{ $submenu->menu?->Titulo ?? '-' }}</li>
            <li class="list-group-item"><strong>Título:</strong> {{ $submenu->Titulo }}</li>
            <li class="list-group-item"><strong>Ruta:</strong> {{ $submenu->Ruta ?? '-' }}</li>
            <li class="list-group-item"><strong>Icono:</strong> {{ $submenu->Icono ?? '-' }}</li>
            <li class="list-group-item"><strong>Orden:</strong> {{ $submenu->Orden ?? '-' }}</li>
            <li class="list-group-item"><strong>Activo:</strong> {{ (int) $submenu->Activo === 1 ? 'Sí' : 'No' }}</li>
        </ul>

        <a href="{{ route('configuracion.submenus.edit', $submenu->IdSubMenu) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('configuracion.submenus.index') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection
