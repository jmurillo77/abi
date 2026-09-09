@extends('adminlte::page')

@section('title', 'Crear Parroquia')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-plus-circle text-primary"></i> Nueva Parroquia
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.parroquia.index') }}">Parroquias</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    @php
        $selectedContinente = old('id_continente', '');
        $selectedPais = old('id_pais', '');
        $selectedProvincia = old('id_provincia', '');
        $selectedCanton = old('id_canton', '');
    @endphp

    <div class="card-header">
        <h3 class="card-title">Registrar Parroquia</h3>
    </div>

    <form action="{{ route('contacto.parroquia.store') }}" method="POST">
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

            <div class="form-group">
                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" maxlength="50" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="id_continente">Continente</label>
                    <select id="id_continente" name="id_continente" class="form-control"></select>
                </div>
                <div class="form-group col-md-3">
                    <label for="id_pais">País</label>
                    <select id="id_pais" name="id_pais" class="form-control"></select>
                </div>
                <div class="form-group col-md-3">
                    <label for="id_provincia">Provincia</label>
                    <select id="id_provincia" name="id_provincia" class="form-control"></select>
                </div>
                <div class="form-group col-md-3">
                    <label for="id_canton">Cantón</label>
                    <select id="id_canton" name="id_canton" class="form-control"></select>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('contacto.parroquia.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
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
const selectedCanton = @json($selectedCanton);

const continenteSelect = document.getElementById('id_continente');
const paisSelect = document.getElementById('id_pais');
const provinciaSelect = document.getElementById('id_provincia');
const cantonSelect = document.getElementById('id_canton');

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

function findProvincia(pais, id) {
    return (pais?.provincias || []).find((provincia) => String(provincia.IdProvincia) === String(id));
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
    return pais;
}

function renderCantones(pais, provinciaId, selected = '') {
    const provincia = findProvincia(pais, provinciaId);
    cantonSelect.innerHTML = buildOptions(provincia?.cantones || [], selected, 'IdCiudad', 'Nombre', 'Seleccione un cantón');
}

renderContinentes();
const continente = renderPaises(selectedContinente, selectedPais);
const pais = renderProvincias(continente, selectedPais, selectedProvincia);
renderCantones(pais, selectedProvincia, selectedCanton);

continenteSelect.addEventListener('change', function () {
    const continente = renderPaises(this.value);
    const pais = renderProvincias(continente, '');
    renderCantones(pais, '');
});

paisSelect.addEventListener('change', function () {
    const continente = findContinente(continenteSelect.value);
    const pais = renderProvincias(continente, this.value);
    renderCantones(pais, '');
});

provinciaSelect.addEventListener('change', function () {
    const continente = findContinente(continenteSelect.value);
    const pais = findPais(continente, paisSelect.value);
    renderCantones(pais, this.value);
});
</script>
@stop
