@extends('adminlte::page')

@section('title', 'Editar Cantón')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-edit text-primary"></i> Editar Cantón
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.canton.index') }}">Cantones</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    @php
        $selectedContinente = old('id_continente', $canton->provincia?->pais?->continente?->IdContinente);
        $selectedPais = old('id_pais', $canton->provincia?->pais?->IdPais);
        $selectedProvincia = old('id_provincia', $canton->IdProvincia);
    @endphp

    <div class="card-header">
        <h3 class="card-title">Actualizar Cantón</h3>
    </div>

    <form action="{{ route('contacto.canton.update', $canton->IdCiudad) }}" method="POST">
        @csrf
        @method('PUT')

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

            <div class="form-group">
                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $canton->Nombre) }}" maxlength="50" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="id_continente">Continente</label>
                    <select id="id_continente" name="id_continente" class="form-control"></select>
                </div>
                <div class="form-group col-md-4">
                    <label for="id_pais">País</label>
                    <select id="id_pais" name="id_pais" class="form-control"></select>
                </div>
                <div class="form-group col-md-4">
                    <label for="id_provincia">Provincia</label>
                    <select id="id_provincia" name="id_provincia" class="form-control"></select>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('contacto.canton.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
const ubicaciones = @json($ubicaciones);
const selectedContinente = @json($selectedContinente);
const selectedPais = @json($selectedPais);
const selectedProvincia = @json($selectedProvincia);

const continenteSelect = document.getElementById('id_continente');
const paisSelect = document.getElementById('id_pais');
const provinciaSelect = document.getElementById('id_provincia');

function buildOptions(items, selectedValue, valueKey, labelKey, placeholder) {
    const options = [`<option value="">${placeholder}</option>`];

    items.forEach((item) => {
        const selected = String(selectedValue || '') === String(item[valueKey]) ? 'selected' : '';
        options.push(`<option value="${item[valueKey]}" ${selected}>${item[labelKey]}</option>`);
    });

    return options.join('');
}

function findContinente(id) {
    return ubicaciones.find((continente) => String(continente.IdContinente) === String(id));
}

function findPais(continente, id) {
    return (continente?.paises || []).find((pais) => String(pais.IdPais) === String(id));
}

function renderContinentes() {
    continenteSelect.innerHTML = buildOptions(ubicaciones, selectedContinente, 'IdContinente', 'Nombre', 'Seleccione un continente');
}

function renderPaises(continenteId, selected = '') {
    const continente = findContinente(continenteId);
    paisSelect.innerHTML = buildOptions(continente?.paises || [], selected, 'IdPais', 'Nombre', 'Seleccione un país');
    return continente;
}

function renderProvincias(continente, paisId, selected = '') {
    const pais = findPais(continente, paisId);
    provinciaSelect.innerHTML = buildOptions(pais?.provincias || [], selected, 'IdProvincia', 'Nombre', 'Seleccione una provincia');
}

renderContinentes();
const continente = renderPaises(selectedContinente, selectedPais);
renderProvincias(continente, selectedPais, selectedProvincia);

continenteSelect.addEventListener('change', function () {
    const continente = renderPaises(this.value);
    renderProvincias(continente, '');
});

paisSelect.addEventListener('change', function () {
    const continente = findContinente(continenteSelect.value);
    renderProvincias(continente, this.value);
});
</script>
@stop
