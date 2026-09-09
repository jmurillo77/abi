@extends('adminlte::page')

@section('title', 'Nuevo Correo')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1><i class="fas fa-envelope text-primary"></i> Nuevo Correo</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.correo.index') }}">Correos</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            Revisa los campos del formulario.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-outline card-primary shadow">
        <div class="card-header">
            <h3 class="card-title">Datos del correo</h3>
        </div>

        <form action="{{ route('contacto.correo.store') }}" method="POST">
            @csrf

            <div class="card-body">
                <div class="form-group">
                    <label for="Correo">Correo</label>
                    <input type="email" id="Correo" name="Correo" class="form-control @error('Correo') is-invalid @enderror" value="{{ old('Correo') }}" maxlength="200" required>
                    @error('Correo')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-check">
                    <input type="hidden" name="Valido" value="0">
                    <input type="checkbox" id="Valido" name="Valido" value="1" class="form-check-input" @checked(old('Valido', '1') === '1')>
                    <label class="form-check-label" for="Valido">Correo válido</label>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('contacto.correo.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@stop
