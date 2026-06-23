@extends('adminlte::page')

@section('title', 'Detalle de Menú')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Detalle de Menú</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menús</a></li>
                <li class="breadcrumb-item active">{{ $menu->Nombre }}</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <ul class="list-group">
                    <li class="list-group-item"><strong>ID:</strong> {{ $menu->IdMenu }}</li>
                    <li class="list-group-item"><strong>Nombre:</strong> {{ $menu->Nombre }}</li>
                    <li class="list-group-item"><strong>URL:</strong> {{ $menu->Url ?? '-' }}</li>
                    <li class="list-group-item"><strong>Icono:</strong> {{ $menu->Icono ?? '-' }}</li>
                    <li class="list-group-item"><strong>Padre:</strong> {{ $menu->parent?->Nombre ?? '-' }}</li>
                    <li class="list-group-item"><strong>Orden:</strong> {{ $menu->order ?? '-' }}</li>
                </ul>
            </div>
            <div class="col-md-6">
                <a href="{{ route('menus.edit', $menu->IdMenu) }}" class="btn btn-warning mb-2">Editar</a>
                <a href="{{ route('menus.index') }}" class="btn btn-secondary mb-2">Volver</a>
                @if($menu->children->count())
                    <div class="card mt-3">
                        <div class="card-header">Submenús</div>
                        <div class="card-body">
                            <ul>
                                @foreach($menu->children as $child)
                                    <li>{{ $child->Nombre }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
