@extends('adminlte::page')

@section('title', 'Detalle Usuario')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Detalle Usuario</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('configuracion.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.users.index') }}">Usuarios</a></li>
                <li class="breadcrumb-item active">Detalle</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    @php
        $nombrePersona = trim((string) (($user->persona->Nombres ?? '').' '.($user->persona->Apellidos ?? '')));
        $subtituloPerfil = $nombrePersona !== '' ? $nombrePersona : 'Usuario #'.$user->id;
        $estadoVerificacion = $user->email_verified_at ? 'Verificado' : 'Pendiente';
        $estadoVerificacionClass = $user->email_verified_at ? 'badge-success' : 'badge-warning';
    @endphp

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-5x text-secondary"></i>
                    </div>

                    <h3 class="profile-username text-center mb-1">{{ $user->name }}</h3>
                    <p class="text-muted text-center mb-3">{{ $subtituloPerfil }}</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b>
                            <span class="float-right text-muted">{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Estado Email</b>
                            <span class="float-right badge {{ $estadoVerificacionClass }}">{{ $estadoVerificacion }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Rol</b>
                            <span class="float-right text-muted">{{ $user->role->Nombre ?? 'Sin rol' }}</span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('configuracion.users.index') }}" class="btn btn-secondary btn-sm">Volver</a>
                        <a href="{{ route('configuracion.users.edit', $user->id) }}" class="btn btn-primary btn-sm">Editar</a>
                        <a href="{{ route('configuracion.users.permissions.edit', $user->id) }}" class="btn btn-info btn-sm">Permisos</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-outline card-info shadow-sm mb-3">
                <div class="card-header">
                    <h3 class="card-title">Información General</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Creado en</strong>
                            <p class="mb-0 text-muted">{{ optional($user->created_at)->format('d/m/Y H:i') ?? 'No disponible' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Última actualización</strong>
                            <p class="mb-0 text-muted">{{ optional($user->updated_at)->format('d/m/Y H:i') ?? 'No disponible' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-secondary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Persona Relacionada</h3>
                </div>
                <div class="card-body">
                    @if($user->persona)
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Nombre completo</strong>
                                <p class="mb-0 text-muted">{{ $nombrePersona !== '' ? $nombrePersona : 'Sin nombre' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>DNI</strong>
                                <p class="mb-0 text-muted">{{ $user->persona->DNI ?? 'No disponible' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            Este usuario no tiene una persona vinculada.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop
