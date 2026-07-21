@extends('adminlte::page')

@section('title', 'Agregar Submenú')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-plus-circle text-primary"></i> Nuevo Submenú
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Configuración</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.submenus.index') }}">Submenús</a></li>
                <li class="breadcrumb-item active">Nuevo</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">Crear registro de submenú</h3>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <h6 class="mb-2"><i class="icon fas fa-exclamation-triangle"></i> Se encontraron errores:</h6>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('configuracion.submenus.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label for="IdMenu">Menú</label>
                    <select name="IdMenu" id="IdMenu" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($menus as $menu)
                            <option value="{{ $menu->IdMenu }}" @selected(old('IdMenu') == $menu->IdMenu)>{{ $menu->Titulo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="Titulo">Título</label>
                    <input type="text" class="form-control" name="Titulo" id="Titulo" value="{{ old('Titulo') }}" required>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="Ruta">Ruta</label>
                    <input type="text" class="form-control" name="Ruta" id="Ruta" value="{{ old('Ruta') }}" placeholder="Ej: contacto.persona.index">
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="Icono">Icono</label>
                    <input type="text" class="form-control" name="Icono" id="Icono" value="{{ old('Icono') }}" placeholder="Ej: fas fa-users|#0d9488">
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="Orden">Orden</label>
                    <input type="number" class="form-control" name="Orden" id="Orden" value="{{ old('Orden', 0) }}">
                </div>

                <div class="col-md-6 form-group mb-3 d-flex align-items-center">
                    <div class="custom-control custom-switch mt-4">
                        <input type="checkbox" class="custom-control-input" id="Activo" name="Activo" value="1" @checked(old('Activo', 1))>
                        <label class="custom-control-label" for="Activo">Activo</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('configuracion.submenus.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
