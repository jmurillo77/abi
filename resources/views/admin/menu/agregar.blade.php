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
                <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menús</a></li>
                <li class="breadcrumb-item active">Agregar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('menus.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="Nombre">Nombre</label>
                <input type="text" class="form-control" name="Nombre" id="Nombre" value="{{ old('Nombre') }}" required>
            </div>
            <div class="form-group mb-3">
                <label for="Url">URL</label>
                <input type="text" class="form-control" name="Url" id="Url" value="{{ old('Url') }}">
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
                        <option value="{{ $parent->IdMenu }}" @selected(old('parent_id') == $parent->IdMenu)>{{ $parent->Nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="order">Orden</label>
                <input type="number" class="form-control" name="order" id="order" value="{{ old('order') }}">
            </div>
            <button class="btn btn-primary">Guardar</button>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
