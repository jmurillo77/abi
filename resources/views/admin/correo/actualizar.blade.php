@extends('adminlte::page')

@section('title', 'Editar Correo')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-envelope text-primary"></i> Editar Correo
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.correo.index') }}">Correos</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

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

    <div class="row">
        <div class="col-md-7">
            <div class="card card-outline card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title">Datos del correo</h3>
                </div>
                <form action="{{ route('contacto.correo.update', $correo->IdCorreo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="form-group">
                            <label for="Correo">Correo</label>
                            <input type="email" id="Correo" name="Correo" class="form-control @error('Correo') is-invalid @enderror" value="{{ old('Correo', $correo->Correo) }}" maxlength="200" required>
                            @error('Correo')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-check">
                            <input type="hidden" name="Valido" value="0">
                            <input type="checkbox" id="Valido" name="Valido" value="1" class="form-check-input" @checked(old('Valido', $correo->Valido) === '1' || old('Valido') === '1')>
                            <label class="form-check-label" for="Valido">Correo válido</label>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('contacto.correo.show', $correo->IdCorreo) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-outline card-warning shadow">
                <div class="card-header">
                    <h3 class="card-title">Relaciones activas</h3>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Personas:</strong> {{ $correo->personas->count() }}</p>
                    <p class="mb-0"><strong>Empresas:</strong> {{ $correo->empresas->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
