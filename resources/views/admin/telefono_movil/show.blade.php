@extends('adminlte::page')

@section('title', 'Detalle de Teléfono Móvil')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-phone text-primary"></i> Detalle del Teléfono Móvil
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.telefono_movil.index') }}">Teléfonos móviles</a></li>
                <li class="breadcrumb-item active">{{ $telefono->Numero }}</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline shadow">
                <div class="card-body">
                    <h3 class="profile-username text-center font-weight-bold">{{ $telefono->Numero }}</h3>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Operadora</b>
                            <span class="float-right">{{ $telefono->operadora?->Nombre ?? 'Sin operadora' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Teléfono válido</b>
                            <span class="float-right">{{ $telefono->PhoneValido === '1' ? 'Sí' : 'No' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>WhatsApp válido</b>
                            <span class="float-right">{{ $telefono->WhatsappValido === '1' ? 'Sí' : 'No' }}</span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('contacto.telefono_movil.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="{{ route('contacto.telefono_movil.edit', $telefono->IdTelefonoMovil) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-outline card-secondary shadow mb-4">
                <div class="card-header">
                    <h3 class="card-title">Personas relacionadas</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped m-0">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nombres</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($telefono->personas as $persona)
                                <tr>
                                    <td>{{ $persona->DNI }}</td>
                                    <td>{{ $persona->Nombres }} {{ $persona->Apellidos }}</td>
                                    <td>
                                        <a href="{{ route('contacto.persona.show', $persona->IdPersona) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Sin personas relacionadas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-outline card-secondary shadow">
                <div class="card-header">
                    <h3 class="card-title">Empresas relacionadas</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped m-0">
                        <thead>
                            <tr>
                                <th>RUC</th>
                                <th>Razón social</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($telefono->empresas as $empresa)
                                <tr>
                                    <td>{{ $empresa->RUC }}</td>
                                    <td>{{ $empresa->RazonSocial }}</td>
                                    <td>
                                        <a href="{{ route('contacto.empresa.show', $empresa->IdEmpresa) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Sin empresas relacionadas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
