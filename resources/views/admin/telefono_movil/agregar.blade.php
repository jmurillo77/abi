@extends('adminlte::page')

@section('title', 'Nuevo Teléfono Móvil')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1><i class="fas fa-phone text-primary"></i> Nuevo Teléfono Móvil</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.telefono_movil.index') }}">Teléfonos móviles</a></li>
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
            <h3 class="card-title">Datos del teléfono</h3>
        </div>

        <form action="{{ route('contacto.telefono_movil.store') }}" method="POST">
            @csrf

            <div class="card-body">
                <div class="form-group">
                    <label for="Numero">Número</label>
                    <input type="text" id="Numero" name="Numero" class="form-control @error('Numero') is-invalid @enderror" value="{{ old('Numero') }}" maxlength="10" required>
                    @error('Numero')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="IdOperadora">Operadora</label>
                    <select id="IdOperadora" name="IdOperadora" class="form-control @error('IdOperadora') is-invalid @enderror" required>
                        <option value="">Seleccione una operadora</option>
                        @foreach($operadoras as $operadora)
                            <option value="{{ $operadora->IdOperadora }}" @selected((string) old('IdOperadora') === (string) $operadora->IdOperadora)>{{ $operadora->Nombre }}</option>
                        @endforeach
                    </select>
                    @error('IdOperadora')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-check mb-2">
                    <input type="hidden" name="PhoneValido" value="0">
                    <input type="checkbox" id="PhoneValido" name="PhoneValido" value="1" class="form-check-input" @checked(old('PhoneValido', '1') === '1')>
                    <label class="form-check-label" for="PhoneValido">Teléfono válido</label>
                </div>

                <div class="form-check">
                    <input type="hidden" name="WhatsappValido" value="0">
                    <input type="checkbox" id="WhatsappValido" name="WhatsappValido" value="1" class="form-check-input" @checked(old('WhatsappValido', '1') === '1')>
                    <label class="form-check-label" for="WhatsappValido">WhatsApp válido</label>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('contacto.telefono_movil.index') }}" class="btn btn-secondary">
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
