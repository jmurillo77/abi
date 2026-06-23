@extends('adminlte::page')

@section('title', 'Perfil de Persona | AbiSyS')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    <i class="fas fa-id-card text-primary"></i> Detalle de la Persona
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contacto.persona.index') }}">Personas</a></li>
                    <li class="breadcrumb-item active">{{ $persona->DNI }}</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        
        {{-- Tarjeta Izquierda: Información de Perfil Principal --}}
        <div class="col-md-4">
            <div class="card card-primary card-outline shadow">
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-5x text-secondary"></i>
                    </div>

                    <h3 class="profile-username text-center font-weight-bold">
                        {{ $persona->Nombres }} {{ $persona->Apellidos }}
                    </h3>

                    <p class="text-muted text-center">
                        <span class="badge badge-info px-2 py-1.5">DNI: {{ $persona->DNI }}</span>
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Fecha de Nacimiento</b> 
                            <span class="float-right text-secondary font-weight-bold">
                                {{ $persona->FechaNacimiento ? date('d/m/Y', strtotime($persona->FechaNacimiento)) : 'No registrada' }}
                            </span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('contacto.persona.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="{{ route('contacto.persona.edit', $persona->IdPersona) }}" class="btn btn-warning text-white">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta Derecha: Datos de Contacto Avanzados (Pestañas para Teléfonos y Correos) --}}
        <div class="col-md-8">
            <div class="card card-outline card-secondary shadow">
                <div class="card-header p-2 bg-light">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tab-telefonos" data-toggle="tab">
                                <i class="fas fa-phone-alt text-success mr-1"></i> Teléfonos Móviles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab-correos" data-toggle="tab">
                                <i class="fas fa-envelope text-info mr-1"></i> Correos Electrónicos
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body p-0">
                    <div class="tab-content">
                        
                        {{-- PESTAÑA 1: TELÉFONOS --}}
                        <div class="active tab-pane" id="tab-telefonos">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover m-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Número de Teléfono</th>
                                            <th>Operadora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($persona->telefono_movils as $telefono)
                                            <tr>
                                                <td class="font-weight-bold text-success">
                                                    <i class="fab fa-whatsapp mr-1"></i>
                                                    {{ $telefono->Numero }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary p-2">
                                                        <i class="fas fa-network-wired mr-1"></i>
                                                        {{ $telefono->operadora?->Nombre ?? 'No especificada' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center py-4 text-muted">
                                                    <i class="fas fa-info-circle mr-1"></i> Esta persona no tiene números telefónicos registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- PESTAÑA 2: CORREOS --}}
                        <div class="tab-pane" id="tab-correos">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover m-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Dirección de Correo Electrónico</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Cambia 'correos' si el nombre de tu relación en el modelo Persona es diferente --}}
                                        @forelse($persona->correos as $correo)
                                            <tr>
                                                <td class="font-weight-bold text-info">
                                                    <i class="fas fa-envelope-open-text mr-1"></i>
                                                    {{ $correo->Correo }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center py-4 text-muted">
                                                    <i class="fas fa-info-circle mr-1"></i> Esta persona no tiene correos electrónicos registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
