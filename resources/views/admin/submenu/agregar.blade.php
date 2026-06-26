@extends('adminlte::page')

@section('title', 'Agregar Submenú')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Agregar Submenú</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.submenus.index') }}">Submenús</a></li>
                <li class="breadcrumb-item active">Agregar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('configuracion.submenus.store') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="IdMenu">Menú</label>
                <select name="IdMenu" id="IdMenu" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->IdMenu }}" @selected(old('IdMenu') == $menu->IdMenu)>{{ $menu->Titulo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="Titulo">Título</label>
                <input type="text" class="form-control" name="Titulo" id="Titulo" value="{{ old('Titulo') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="Ruta">Ruta</label>
                <input type="text" class="form-control" name="Ruta" id="Ruta" value="{{ old('Ruta') }}" placeholder="Ej: contacto.persona.index">
            </div>

            <div class="form-group mb-3">
                <label for="Icono">Icono</label>
                <input type="text" class="form-control" name="Icono" id="Icono" value="{{ old('Icono') }}" placeholder="Ej: fas fa-users|#0d9488">
            </div>

            <div class="form-group mb-3">
                <label for="Orden">Orden</label>
                <input type="number" class="form-control" name="Orden" id="Orden" value="{{ old('Orden', 0) }}">
            </div>

            <div class="form-group mb-3">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="Activo" name="Activo" value="1" @checked(old('Activo', 1))>
                    <label class="custom-control-label" for="Activo">Activo</label>
                </div>
            </div>

            <button class="btn btn-primary">Guardar</button>
            <a href="{{ route('configuracion.submenus.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
