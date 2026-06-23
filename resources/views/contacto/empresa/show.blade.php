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

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('contacto.empresa.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="{{ route('contacto.empresa.crear') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-outline card-secondary shadow">
                <div class="card-header bg-light">
                    <h3 class="card-title">Contactos relacionados</h3>
                </div>
                <div class="card-body p-0">
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
                                        <td>{{ $telefono->Numero }}</td>
                                        <td>{{ $telefono->operadora?->Nombre ?? 'No especificada' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted">
                                            Esta empresa no tiene teléfonos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-hover m-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Correo electrónico</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($empresa->correos as $correo)
                                    <tr>
                                        <td>{{ $correo->Correo }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-4 text-muted">
                                            Esta empresa no tiene correos registrados.
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
@endsection
