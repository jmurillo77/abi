@extends('adminlte::page')

@section('title', 'Detalle de Empresa')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Detalle de Empresa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contacto</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contacto.empresa.index') }}">Empresas</a></li>
                    <li class="breadcrumb-item active">{{ $empresa->RazonSocial }}</li>
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
                <div class="card-body box-profile">
                    <div class="text-center mb-3">
                        <i class="fas fa-building fa-5x text-secondary"></i>
                    </div>

                    <h3 class="profile-username text-center font-weight-bold">
                        {{ $empresa->RazonSocial }}
                    </h3>

                    <p class="text-muted text-center">
                        <span class="badge badge-info px-2 py-1.5">RUC: {{ $empresa->RUC }}</span>
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>ID Empresa</b>
                            <span class="float-right text-secondary font-weight-bold">{{ $empresa->IdEmpresa }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Teléfono</b>
                            <span class="float-right text-secondary font-weight-bold">
                                {{ optional($empresa->telefono_movils->first())->Numero ?? 'No registrado' }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Correo</b>
                            <span class="float-right text-secondary font-weight-bold">
                                {{ optional($empresa->correos->first())->Correo ?? 'No registrado' }}
                            </span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-between gap-2">
                        <a href="{{ route('contacto.empresa.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('contacto.empresa.edit', $empresa->IdEmpresa) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('contacto.empresa.crear') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nueva
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

                        <div class="active tab-pane" id="tab-telefonos">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover m-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Teléfono</th>
                                            <th>Operadora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($empresa->telefono_movils as $telefono)
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
                                                    <i class="fas fa-info-circle mr-1"></i> Esta empresa no tiene números telefónicos registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab-correos">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover m-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Dirección de Correo Electrónico</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($empresa->correos as $correo)
                                            <tr>
                                                <td class="font-weight-bold text-info">
                                                    <i class="fas fa-envelope-open-text mr-1"></i>
                                                    {{ $correo->Correo }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center py-4 text-muted">
                                                    <i class="fas fa-info-circle mr-1"></i> Esta empresa no tiene correos electrónicos registrados.
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
