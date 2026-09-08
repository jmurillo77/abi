@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">
                <i class="fas fa-user-plus text-primary mr-2"></i>Crear Usuario
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.users.index') }}">Usuarios</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('configuracion.users.store') }}" method="POST">
        @csrf

        <div class="card card-outline card-primary shadow-sm mb-3">
            <div class="card-header">
                <h3 class="card-title">Datos de Cuenta</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nombre de usuario</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-info shadow-sm mb-3">
            <div class="card-header">
                <h3 class="card-title">Relación y Acceso</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="IdRol">Rol</label>
                            <select name="IdRol" id="IdRol" class="form-control">
                                <option value="">Seleccione un rol</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->IdRol }}" {{ (string) old('IdRol') === (string) $role->IdRol ? 'selected' : '' }}>
                                        {{ $role->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="IdPersona">Persona</label>
                            <select name="IdPersona" id="IdPersona" class="form-control">
                                <option value="">Seleccione una persona</option>
                                @foreach($personas as $persona)
                                    @php
                                        $nombrePersona = trim(($persona->Nombres ?? '').' '.($persona->Apellidos ?? ''));
                                    @endphp
                                    <option value="{{ $persona->IdPersona }}" data-nombre="{{ $nombrePersona }}" {{ (string) old('IdPersona') === (string) $persona->IdPersona ? 'selected' : '' }}>
                                        {{ $nombrePersona }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-secondary shadow-sm mb-3">
            <div class="card-header">
                <h3 class="card-title">Seguridad</h3>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb-3">
            <a href="{{ route('configuracion.users.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar usuario</button>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const personaSelect = document.getElementById('IdPersona');
    const nameInput = document.getElementById('name');
    let lastAutoName = '';

    const syncNameFromPersona = () => {
        const selectedOption = personaSelect.options[personaSelect.selectedIndex];
        const personaName = (selectedOption?.dataset?.nombre || '').trim();

        if (!personaName) {
            return;
        }

        if (nameInput.value.trim() === '' || nameInput.value.trim() === lastAutoName) {
            nameInput.value = personaName;
            lastAutoName = personaName;
        }
    };

    personaSelect.addEventListener('change', syncNameFromPersona);

    if (personaSelect.value) {
        syncNameFromPersona();
    }
});
</script>
@stop
