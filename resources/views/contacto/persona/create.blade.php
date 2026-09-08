@extends('adminlte::page')

@section('title', 'Crear nueva persona')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-user-plus text-primary"></i>
                Nueva Persona
            </h1>
        </div>

        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.persona.index') }}">Personas</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="card card-outline card-primary shadow">

    <div class="card-header">
        <h3 class="card-title">Agregar Persona</h3>
    </div>

    <form method="POST" action="{{ route('contacto.persona.store') }}">
        @csrf

        <div class="card-body">

            @php
                $direcciones = old('direcciones', [[
                    'nombre' => '',
                    'id_direccion_tipo' => '',
                    'id_continente' => '',
                    'id_pais' => '',
                    'id_provincia' => '',
                    'id_canton' => '',
                    'id_parroquia' => '',
                ]]);
            @endphp

            {{-- Errores --}}
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

            {{-- Datos personales --}}
            <div class="form-group">
                <label>Documento</label>
                <input type="text" name="dni" class="form-control" value="{{ old('dni') }}" required>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nombres</label>
                    <input type="text" name="nombres" class="form-control" value="{{ old('nombres') }}" required>
                </div>

                <div class="form-group col-md-6">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento"
                           class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                           value="{{ old('fecha_nacimiento') }}">
                    @error('fecha_nacimiento')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            {{-- Teléfonos y Correos --}}
            <div class="row mt-4">

                {{-- TELÉFONOS --}}
                <div class="col-md-6">
                    <div class="card card-outline card-info">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-phone"></i> Teléfonos
                            </h5>

                            <button type="button" id="addTelefono" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <div id="telefonos-container">

                                <div class="telefono-item border rounded p-3 mb-3">
                                    <div class="row align-items-end">

                                        <div class="col-md-5">
                                            <label>Número</label>
                                            <input type="text"
                                                name="telefonos[0][numero]"
                                                class="form-control"
                                                placeholder="Número">
                                        </div>

                                        <div class="col-md-3">
                                            <label>Conectividad</label>
                                            <select name="telefonos[0][id_conectividad]" class="form-control">
                                                <option value="">Conectividad</option>
                                                <option value="1">NA</option>
                                                <option value="2">Fijo</option>
                                                <option value="3">Móvil</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Operadora</label>
                                            <select name="telefonos[0][id_operadora]" class="form-control">
                                                <option value="">Operadora</option>
                                                @foreach($operadoras as $operadora)
                                                    <option value="{{ $operadora->IdOperadora }}">
                                                        {{ $operadora->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                {{-- CORREOS --}}
                <div class="col-md-6">
                    <div class="card card-outline card-success">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-envelope"></i> Correos
                            </h5>

                            <button type="button" id="addCorreo" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <div id="correos-container">

                                <div class="correo-item border rounded p-3 mb-3">
                                    <input type="email"
                                           name="correos[0][correo]"
                                           class="form-control"
                                           placeholder="Correo electrónico">
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- DIRECCIONES --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card card-outline card-warning">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt"></i> Direcciones
                            </h5>

                            <button type="button" id="addDireccion" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <div id="direcciones-container"></div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('contacto.persona.index') }}" class="btn btn-secondary">
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
<script>
let telefonoIndex = 1;
let correoIndex = 1;
let direccionIndex = {{ count($direcciones) }};
const ubicaciones = @json($ubicaciones);
const direccionTipos = @json($direccionTipos);
const direccionesIniciales = @json(array_values($direcciones));

const operadoras = `
@foreach($operadoras as $operadora)
<option value="{{ $operadora->IdOperadora }}">
    {{ $operadora->Nombre }}
</option>
@endforeach
`;

function buildOptions(items, selectedValue, valueKey, labelBuilder, placeholder) {
    const options = [`<option value="">${placeholder}</option>`];

    items.forEach((item) => {
        const selected = String(selectedValue || '') === String(item[valueKey]) ? 'selected' : '';
        options.push(`<option value="${item[valueKey]}" ${selected}>${labelBuilder(item)}</option>`);
    });

    return options.join('');
}

function findContinente(idContinente) {
    return ubicaciones.find((continente) => String(continente.IdContinente) === String(idContinente));
}

function findPais(continente, idPais) {
    return (continente?.paises || []).find((pais) => String(pais.IdPais) === String(idPais));
}

function findProvincia(pais, idProvincia) {
    return (pais?.provincias || []).find((provincia) => String(provincia.IdProvincia) === String(idProvincia));
}

function findCanton(provincia, idCanton) {
    return (provincia?.cantones || []).find((canton) => String(canton.IdCiudad) === String(idCanton));
}

function direccionTemplate(index, canRemove) {
    return `
        <div class="direccion-item border rounded p-3 mb-3" data-index="${index}">
            <div class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <label>Dirección</label>
                    <input type="text" name="direcciones[${index}][nombre]" class="form-control direccion-nombre" placeholder="Calle principal, numeración, referencia">
                </div>
                <div class="col-md-3 mb-2">
                    <label>Tipo</label>
                    <select name="direcciones[${index}][id_direccion_tipo]" class="form-control direccion-tipo"></select>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Continente</label>
                    <select name="direcciones[${index}][id_continente]" class="form-control direccion-continente"></select>
                </div>
                <div class="col-md-4 mb-2">
                    <label>País</label>
                    <select name="direcciones[${index}][id_pais]" class="form-control direccion-pais"></select>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Provincia</label>
                    <select name="direcciones[${index}][id_provincia]" class="form-control direccion-provincia"></select>
                </div>
                <div class="col-md-4 mb-2">
                    <label>Cantón</label>
                    <select name="direcciones[${index}][id_canton]" class="form-control direccion-canton"></select>
                </div>
                <div class="col-md-3 mb-2">
                    <label>Parroquia</label>
                    <select name="direcciones[${index}][id_parroquia]" class="form-control direccion-parroquia"></select>
                </div>
                <div class="col-md-1 mb-2 ${canRemove ? '' : 'd-none'}">
                    <button type="button" class="btn btn-danger btn-sm removeDireccion w-100">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function hydrateDireccionItem(item, values = {}) {
    item.querySelector('.direccion-nombre').value = values.nombre || '';
    const tipoSelect = item.querySelector('.direccion-tipo');
    tipoSelect.innerHTML = buildOptions(direccionTipos, values.id_direccion_tipo, 'IdDireccionTipo', (tipo) => tipo.Nombre, 'Seleccione un tipo');

    const continenteSelect = item.querySelector('.direccion-continente');
    const paisSelect = item.querySelector('.direccion-pais');
    const provinciaSelect = item.querySelector('.direccion-provincia');
    const cantonSelect = item.querySelector('.direccion-canton');
    const parroquiaSelect = item.querySelector('.direccion-parroquia');

    const renderContinentes = () => {
        continenteSelect.innerHTML = buildOptions(ubicaciones, values.id_continente, 'IdContinente', (continente) => continente.Nombre, 'Seleccione un continente');
    };

    const renderPaises = (selectedContinenteId, selectedPaisId = '') => {
        const continente = findContinente(selectedContinenteId);
        paisSelect.innerHTML = buildOptions(continente?.paises || [], selectedPaisId, 'IdPais', (pais) => pais.Nombre, 'Seleccione un país');
        return continente;
    };

    const renderProvincias = (continente, selectedPaisId, selectedProvinciaId = '') => {
        const pais = findPais(continente, selectedPaisId);
        provinciaSelect.innerHTML = buildOptions(pais?.provincias || [], selectedProvinciaId, 'IdProvincia', (provincia) => provincia.Nombre, 'Seleccione una provincia');
        return pais;
    };

    const renderCantones = (pais, selectedProvinciaId, selectedCantonId = '') => {
        const provincia = findProvincia(pais, selectedProvinciaId);
        cantonSelect.innerHTML = buildOptions(provincia?.cantones || [], selectedCantonId, 'IdCiudad', (canton) => canton.Nombre, 'Seleccione un cantón');
        return provincia;
    };

    const renderParroquias = (provincia, selectedCantonId, selectedParroquiaId = '') => {
        const canton = findCanton(provincia, selectedCantonId);
        parroquiaSelect.innerHTML = buildOptions(canton?.parroquias || [], selectedParroquiaId, 'IdParroquia', (parroquia) => parroquia.Nombre, 'Seleccione una parroquia');
    };

    renderContinentes();
    const continente = renderPaises(values.id_continente, values.id_pais);
    const pais = renderProvincias(continente, values.id_pais, values.id_provincia);
    const provincia = renderCantones(pais, values.id_provincia, values.id_canton);
    renderParroquias(provincia, values.id_canton, values.id_parroquia);

    continenteSelect.addEventListener('change', function () {
        const selectedContinente = renderPaises(this.value);
        const selectedPais = renderProvincias(selectedContinente, '');
        const selectedProvincia = renderCantones(selectedPais, '');
        renderParroquias(selectedProvincia, '');
    });

    paisSelect.addEventListener('change', function () {
        const continenteSeleccionado = findContinente(continenteSelect.value);
        const selectedPais = renderProvincias(continenteSeleccionado, this.value);
        const selectedProvincia = renderCantones(selectedPais, '');
        renderParroquias(selectedProvincia, '');
    });

    provinciaSelect.addEventListener('change', function () {
        const continenteSeleccionado = findContinente(continenteSelect.value);
        const paisSeleccionado = findPais(continenteSeleccionado, paisSelect.value);
        const selectedProvincia = renderCantones(paisSeleccionado, this.value);
        renderParroquias(selectedProvincia, '');
    });

    cantonSelect.addEventListener('change', function () {
        const continenteSeleccionado = findContinente(continenteSelect.value);
        const paisSeleccionado = findPais(continenteSeleccionado, paisSelect.value);
        const provinciaSeleccionada = findProvincia(paisSeleccionado, provinciaSelect.value);
        renderParroquias(provinciaSeleccionada, this.value);
    });
}

function addDireccion(values = {}) {
    const container = document.getElementById('direcciones-container');
    const canRemove = container.children.length > 0;
    container.insertAdjacentHTML('beforeend', direccionTemplate(direccionIndex, canRemove));
    const item = container.lastElementChild;
    hydrateDireccionItem(item, values);

    if (container.children.length === 2) {
        const firstRemove = container.firstElementChild.querySelector('.col-md-1.mb-2');
        if (firstRemove) {
            firstRemove.classList.remove('d-none');
        }
    }

    direccionIndex++;
}

document.getElementById('addTelefono').addEventListener('click', function () {
    let html = `
        <div class="telefono-item border rounded p-3 mb-3">
            <div class="row align-items-end">

                <div class="col-md-4">
                    <label>Número</label>
                    <input type="text"
                        name="telefonos[${telefonoIndex}][numero]"
                        class="form-control"
                        placeholder="Número">
                </div>

                <div class="col-md-3">
                    <label>Conectividad</label>
                    <select name="telefonos[${telefonoIndex}][id_conectividad]" class="form-control">
                        <option value="">Conectividad</option>
                        <option value="1">NA</option>
                        <option value="2">Fijo</option>
                        <option value="3">Móvil</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Operadora</label>
                    <select name="telefonos[${telefonoIndex}][id_operadora]" class="form-control">
                        <option value="">Operadora</option>
                        ${operadoras}
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm removeTelefono w-100">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>

            </div>
        </div>
    `;

    document.getElementById('telefonos-container').insertAdjacentHTML('beforeend', html);
    telefonoIndex++;
});

document.getElementById('addCorreo').addEventListener('click', function () {
    let html = `
        <div class="correo-item border rounded p-3 mb-3">
            <input type="email"
                   name="correos[${correoIndex}][correo]"
                   class="form-control mb-2"
                   placeholder="Correo electrónico">

            <button type="button" class="btn btn-danger btn-sm removeCorreo w-100">
                Eliminar
            </button>
        </div>
    `;

    document.getElementById('correos-container').insertAdjacentHTML('beforeend', html);
    correoIndex++;
});

document.getElementById('addDireccion').addEventListener('click', function () {
    addDireccion();
});

direccionesIniciales.forEach((direccion) => addDireccion(direccion));

document.addEventListener('click', function(e) {
    if (e.target.closest('.removeTelefono')) {
        e.target.closest('.telefono-item').remove();
    }

    if (e.target.closest('.removeCorreo')) {
        e.target.closest('.correo-item').remove();
    }

    if (e.target.closest('.removeDireccion')) {
        e.target.closest('.direccion-item').remove();

        const container = document.getElementById('direcciones-container');
        if (container.children.length === 1) {
            const removeWrapper = container.firstElementChild.querySelector('.col-md-1.mb-2');
            if (removeWrapper) {
                removeWrapper.classList.add('d-none');
            }
        }
    }
});
</script>
@stop