@extends('adminlte::page')

@section('title', 'Editar Persona')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>
                <i class="fas fa-user-edit text-primary mr-2"></i>Editar Persona
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.persona.index') }}">Personas</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">

    @php
        $direcciones = old('direcciones', $persona->direcciones->map(function ($direccion) {
            return [
                'id' => $direccion->IdDireccion,
                'nombre' => $direccion->Nombre,
                'id_direccion_tipo' => $direccion->IdDireccionTipo,
                'id_continente' => $direccion->parroquia?->canton?->provincia?->pais?->continente?->IdContinente,
                'id_pais' => $direccion->parroquia?->canton?->provincia?->pais?->IdPais,
                'id_provincia' => $direccion->parroquia?->canton?->provincia?->IdProvincia,
                'id_canton' => $direccion->parroquia?->canton?->IdCiudad,
                'id_parroquia' => $direccion->IdParroquia,
            ];
        })->toArray());

        if (empty($direcciones)) {
            $direcciones = [[
                'id' => '',
                'nombre' => '',
                'id_direccion_tipo' => '',
                'id_continente' => '',
                'id_pais' => '',
                'id_provincia' => '',
                'id_canton' => '',
                'id_parroquia' => '',
            ]];
        }
    @endphp

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="icon fas fa-check"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="icon fas fa-exclamation-triangle"></i> Revisa los campos marcados. No se pudo guardar la persona.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('contacto.persona.update', $persona->IdPersona) }}" method="POST" autocomplete="off">
        @csrf
        @method('PUT')

        {{-- Sección 1: Datos Personales Maestros --}}
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-header bg-light">
                <h3 class="card-title font-weight-bold text-dark">
                    <i class="fas fa-id-card text-muted mr-1"></i> Datos Personales
                </h3>
            </div>

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="dni">DNI / Documento</label>
                        <input type="text" id="dni" name="dni" 
                               class="form-control bg-white @error('dni') is-invalid @enderror" 
                               value="{{ old('dni', $persona->DNI) }}" readonly>
                        @error('dni')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="nombres">Nombres <span class="text-danger">*</span></label>
                        <input type="text" id="nombres" name="nombres" 
                               class="form-control @error('nombres') is-invalid @enderror" 
                               value="{{ old('nombres', $persona->Nombres) }}" required>
                        @error('nombres')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                        <input type="text" id="apellidos" name="apellidos" 
                               class="form-control @error('apellidos') is-invalid @enderror" 
                               value="{{ old('apellidos', $persona->Apellidos) }}" required>
                        @error('apellidos')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                               class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                               value="{{ old('fecha_nacimiento', $persona->FechaNacimiento ? date('Y-m-d', strtotime($persona->FechaNacimiento)) : '') }}">
                        @error('fecha_nacimiento')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

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
                            @foreach($persona->telefono_movils as $index => $telefono)
                                <div class="telefono-item border rounded p-3 mb-3">
                                    <input type="hidden" 
                                        name="telefonos[{{ $index }}][id]" 
                                        value="{{ $telefono->IdTelefonoMovil }}">

                                    <div class="row align-items-end">

                                        <div class="col-md-5">
                                            <label>Número</label>
                                            <input type="text"
                                                name="telefonos[{{ $index }}][numero]"
                                                class="form-control"
                                                value="{{ $telefono->Numero }}">
                                        </div>

                                        <div class="col-md-3">
                                            <label>Conectividad</label>
                                            <select name="telefonos[{{ $index }}][id_conectividad]" class="form-control">
                                                <option value="">Conectividad</option>
                                                <option value="1" {{ $telefono->IdConectividad == 1 ? 'selected' : '' }}>NA</option>
                                                <option value="2" {{ $telefono->IdConectividad == 2 ? 'selected' : '' }}>Fijo</option>
                                                <option value="3" {{ $telefono->IdConectividad == 3 ? 'selected' : '' }}>Móvil</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Operadora</label>
                                            <select name="telefonos[{{ $index }}][id_operadora]" class="form-control">
                                                <option value="">Operadora</option>
                                                @foreach($operadoras as $operadora)
                                                    <option value="{{ $operadora->IdOperadora }}"
                                                        {{ $telefono->IdOperadora == $operadora->IdOperadora ? 'selected' : '' }}>
                                                        {{ $operadora->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm removeTelefono w-100">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
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
                                @foreach($persona->correos as $index => $correo)
                                    <div class="correo-item border rounded p-3 mb-3">

                                        <input type="hidden" 
                                            name="correos[{{ $index }}][id]" 
                                            value="{{ $correo->IdCorreo }}">

                                        <div class="d-flex gap-2">
                                            <input type="email"
                                                name="correos[{{ $index }}][correo]"
                                                class="form-control"
                                                value="{{ $correo->Correo }}">

                                            <button type="button" class="btn btn-danger btn-sm removeCorreo">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                    </div>
                                @endforeach
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

        {{-- Pie de Formulario / Acciones --}}
        <div class="card-footer bg-transparent border-0 text-right mt-4 px-0">
            <a href="{{ route('contacto.persona.show', $persona->IdPersona) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
let telefonoIndex = {{ $persona->telefono_movils->count() }};
let correoIndex = {{ $persona->correos->count() }};
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
            <input type="hidden" name="direcciones[${index}][id]" class="direccion-id">
            <div class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <label>Dirección</label>
                    <input type="text" name="direcciones[${index}][nombre]" class="form-control direccion-nombre" placeholder="Calle principal, numeración, referencia">
                </div>
                <div class="col-md-3 mb-2">
                    <label>Tipo</label>
                    <select name="direcciones[${index}][id_direccion_tipo]" class="form-control direccion-tipo" required></select>
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
    item.querySelector('.direccion-id').value = values.id || '';
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