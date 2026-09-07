@extends('adminlte::page')

@section('title', 'Crear nuevo cliente')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-user-plus text-primary"></i>
                Nuevo Cliente
            </h1>
        </div>

        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.cliente.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
@php
    $tipoCliente = old('tipo_cliente', 'persona');
    $telefonosIniciales = old('telefonos', []);
    $correosIniciales = old('correos', []);
    $direccionesIniciales = old('direcciones', []);
    $telefonosEmpresaIniciales = old('empresa_telefonos', []);
    $correosEmpresaIniciales = old('empresa_correos', []);
    $direccionesEmpresaIniciales = old('empresa_direcciones', []);
@endphp

<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">Agregar Cliente</h3>
    </div>

    <form method="POST" action="{{ route('ventas.cliente.store') }}">
        @csrf

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Se encontraron errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="hidden" name="tipo_cliente" id="tipo_cliente_hidden" value="{{ $tipoCliente }}">

            <div class="mb-4">
                <div class="btn-group btn-group-toggle w-100" role="group">
                    <button type="button" class="btn btn-lg flex-fill {{ $tipoCliente === 'persona' ? 'btn-primary' : 'btn-outline-primary' }}" data-tipo="persona" onclick="setClienteTipo('persona')">
                        <i class="fas fa-user"></i> Persona natural
                    </button>
                    <button type="button" class="btn btn-lg flex-fill {{ $tipoCliente === 'empresa' ? 'btn-primary' : 'btn-outline-primary' }}" data-tipo="empresa" onclick="setClienteTipo('empresa')">
                        <i class="fas fa-building"></i> Empresa
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>Email de contacto</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" maxlength="150" placeholder="Se usa para notificaciones del pedido (opcional)">
            </div>

            <div id="persona-form" class="cliente-form {{ $tipoCliente === 'persona' ? '' : 'd-none' }}">
                <div class="form-group">
                    <label>Documento</label>
                    <input type="text" name="dni" class="form-control" value="{{ old('dni') }}" {{ $tipoCliente === 'persona' ? 'required' : '' }}>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Nombres</label>
                        <input type="text" name="nombres" class="form-control" value="{{ old('nombres') }}" {{ $tipoCliente === 'persona' ? 'required' : '' }}>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" {{ $tipoCliente === 'persona' ? 'required' : '' }}>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card card-outline card-info h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-phone"></i> Teléfonos</h5>
                                <button type="button" id="addTelefono" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="card-body">
                                <div id="telefonos-container"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-success h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-envelope"></i> Correos</h5>
                                <button type="button" id="addCorreo" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="card-body">
                                <div id="correos-container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card card-outline card-warning">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Direcciones</h5>
                                <button type="button" id="addDireccion" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="card-body">
                                <div id="direcciones-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="empresa-form" class="cliente-form {{ $tipoCliente === 'empresa' ? '' : 'd-none' }}">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>RUC</label>
                        <input type="text" name="RUC" class="form-control" value="{{ old('RUC') }}" {{ $tipoCliente === 'empresa' ? 'required' : '' }}>
                    </div>

                    <div class="form-group col-md-8">
                        <label>Razón Social</label>
                        <input type="text" name="RazonSocial" class="form-control" value="{{ old('RazonSocial') }}" {{ $tipoCliente === 'empresa' ? 'required' : '' }}>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card card-outline card-info h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-phone"></i> Teléfonos</h5>
                                <button type="button" id="addTelefonoEmpresa" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="card-body">
                                <div id="telefonos-empresa-container"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-success h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-envelope"></i> Correos</h5>
                                <button type="button" id="addCorreoEmpresa" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="card-body">
                                <div id="correos-empresa-container"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card card-outline card-warning">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Direcciones</h5>
                                <button type="button" id="addDireccionEmpresa" class="btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                            </div>
                            <div class="card-body">
                                <div id="direcciones-empresa-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('ventas.cliente.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>
    </form>
</div>
@stop

@section('js')
@include('contacto.cliente._repeater_scripts')

<script>
const telefonosPersona = createTelefonoRepeater('telefonos-container', 'telefonos', @json(array_values($telefonosIniciales)));
const correosPersona = createCorreoRepeater('correos-container', 'correos', @json(array_values($correosIniciales)));
const direccionesPersona = createDireccionRepeater('direcciones-container', 'direcciones', @json(array_values($direccionesIniciales)));

const telefonosEmpresa = createTelefonoRepeater('telefonos-empresa-container', 'empresa_telefonos', @json(array_values($telefonosEmpresaIniciales)));
const correosEmpresa = createCorreoRepeater('correos-empresa-container', 'empresa_correos', @json(array_values($correosEmpresaIniciales)));
const direccionesEmpresa = createDireccionRepeater('direcciones-empresa-container', 'empresa_direcciones', @json(array_values($direccionesEmpresaIniciales)));

document.getElementById('addTelefono').addEventListener('click', () => telefonosPersona.addRow());
document.getElementById('addCorreo').addEventListener('click', () => correosPersona.addRow());
document.getElementById('addDireccion').addEventListener('click', () => direccionesPersona.addRow());
document.getElementById('addTelefonoEmpresa').addEventListener('click', () => telefonosEmpresa.addRow());
document.getElementById('addCorreoEmpresa').addEventListener('click', () => correosEmpresa.addRow());
document.getElementById('addDireccionEmpresa').addEventListener('click', () => direccionesEmpresa.addRow());

function setClienteTipo(value) {
    const hiddenTipo = document.getElementById('tipo_cliente_hidden');
    const personaForm = document.getElementById('persona-form');
    const empresaForm = document.getElementById('empresa-form');
    const tabs = document.querySelectorAll('[data-tipo]');

    if (!hiddenTipo || !personaForm || !empresaForm) {
        return;
    }

    hiddenTipo.value = value;
    const isPersona = value === 'persona';
    personaForm.classList.toggle('d-none', !isPersona);
    empresaForm.classList.toggle('d-none', isPersona);

    // Los campos ocultos no deben quedar 'required': el navegador puede bloquear el submit sin avisar
    ['dni', 'nombres', 'apellidos'].forEach((name) => {
        const field = personaForm.querySelector(`[name="${name}"]`);
        if (field) field.required = isPersona;
    });
    ['RUC', 'RazonSocial'].forEach((name) => {
        const field = empresaForm.querySelector(`[name="${name}"]`);
        if (field) field.required = !isPersona;
    });

    tabs.forEach((tab) => {
        const isActive = tab.dataset.tipo === value;
        tab.classList.toggle('btn-primary', isActive);
        tab.classList.toggle('btn-outline-primary', !isActive);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    setClienteTipo(document.getElementById('tipo_cliente_hidden')?.value || 'persona');
});
</script>
@stop
