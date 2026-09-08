@extends('adminlte::page')

@section('title', 'Nueva Ruta')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1><i class="fas fa-route text-primary"></i> Nueva Ruta</h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.ruta.index') }}">Rutas</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">Registrar Ruta</h3>
    </div>

    <form action="{{ route('ventas.ruta.store') }}" method="POST">
        @csrf

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Se encontraron errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group col-md-8">
                    <label>Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="Nombre" class="form-control" value="{{ old('Nombre') }}" maxlength="150" required placeholder="Ej. Ruta Centro - Mediodía">
                </div>
                <div class="form-group col-md-4">
                    <label>Fecha <span class="text-danger">*</span></label>
                    <input type="date" name="Fecha" class="form-control" value="{{ old('Fecha', now()->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="Estado" class="form-control">
                    @foreach(['PLANIFICADA' => 'Planificada', 'EN_CURSO' => 'En curso', 'FINALIZADA' => 'Finalizada', 'CANCELADA' => 'Cancelada'] as $value => $label)
                        <option value="{{ $value }}" {{ old('Estado', 'PLANIFICADA') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="Observaciones" class="form-control" rows="3" maxlength="500">{{ old('Observaciones') }}</textarea>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('ventas.ruta.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>
    </form>
</div>
@stop
