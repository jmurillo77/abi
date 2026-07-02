@extends('adminlte::page')

@section('title', 'Agregar Menú')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Agregar Menú</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.menus.index') }}">Menús</a></li>
                <li class="breadcrumb-item active">Agregar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('configuracion.menus.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="Titulo">Título</label>
                <input type="text" class="form-control" name="Titulo" id="Titulo" value="{{ old('Titulo') }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="Ruta">Ruta</label>
                <input type="text" class="form-control" name="Ruta" id="Ruta" value="{{ old('Ruta') }}">
            </div>
            <div class="form-group mb-3">
                <label for="Icono">Icono</label>
                <input type="text" class="form-control" name="Icono" id="Icono" value="{{ old('Icono') }}">
            </div>
            <div class="form-group mb-3">
                <label for="parent_id">Menú Padre</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">-- Ninguno --</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->IdMenu }}" @selected(old('parent_id') == $parent->IdMenu)>{{ $parent->Titulo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="Orden">Orden</label>
                <input type="number" class="form-control" name="Orden" id="Orden" value="{{ old('Orden') }}">
            </div>
            <button class="btn btn-primary">Guardar</button>
            <a href="{{ route('configuracion.menus.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
