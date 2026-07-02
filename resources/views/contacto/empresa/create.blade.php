@extends('adminlte::page')

@section('tituloPagina', 'Crear nueva persona')


@section('content')


<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-9">
                <h3 class="card-title">Agregar Empresa</h3>
            </div>
            <div class="col-md-3">
                <div class="float-right">
                    <a href="{{ route('contacto.empresa.index') }}" style="color:#0970d6; margin: 5px 0 0 0px;"><span class="fas fa-arrow-left fa-lg"></span>  Regresar</a>
                </div>
            </div>
        </div>
    </div>
        <div class="card m-3 ">

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
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
                    </div>
                </div>
                <p class="card-text">
                <form class="form-horizontal" role="form" method="POST" action="{{ route('contacto.empresa.store') }}">
                <!-- Add csrf token -->
                {{ csrf_field() }}

                @php
                    $telefonos = old('telefonos', [['numero' => '', 'id_conectividad' => '', 'id_operadora' => '']]);
                    $correos = old('correos', [['correo' => '']]);
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

                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-id-card fa-lg"
                            style="color: #0d4a87;"></i></span>
                    <input type="text" name="RUC" class="form-control form-control-lg @error('RUC') is-invalid @enderror"
                        placeholder="RUC" aria-label="RUC" aria-describedby="basic-addon1"
                        value="{{ old('RUC') }}" required>
                    @error('RUC')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-building fa-lg"
                            style="color: #0d4a87;"></i></span>
                    <input type="text" name="RazonSocial" class="form-control form-control-lg @error('RazonSocial') is-invalid @enderror"
                        placeholder="Razón Social" aria-label="Razón Social" aria-describedby="basic-addon1"
                        value="{{ old('RazonSocial') }}" required>
                    @error('RazonSocial')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-outline card-info">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-phone"></i> Teléfonos</h5>
                                <button type="button" id="addTelefono" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="telefonos-container">
                                    @foreach($telefonos as $index => $telefono)
                                        <div class="telefono-item border rounded p-3 mb-3">
                                            <div class="row align-items-end">
                                                <div class="col-md-5">
                                                    <label>Número</label>
                                                    <input type="text"
                                                        name="telefonos[{{ $index }}][numero]"
                                                        class="form-control {{ $errors->has("telefonos.$index.numero") ? 'is-invalid' : '' }}"
                                                        placeholder="Número"
                                                        value="{{ $telefono['numero'] ?? '' }}">
                                                    @error("telefonos.$index.numero")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Conectividad</label>
                                                    <select name="telefonos[{{ $index }}][id_conectividad]" class="form-control {{ $errors->has("telefonos.$index.id_conectividad") ? 'is-invalid' : '' }}">
                                                        <option value="">Conectividad</option>
                                                        <option value="1" {{ ($telefono['id_conectividad'] ?? '') == '1' ? 'selected' : '' }}>NA</option>
                                                        <option value="2" {{ ($telefono['id_conectividad'] ?? '') == '2' ? 'selected' : '' }}>Fijo</option>
                                                        <option value="3" {{ ($telefono['id_conectividad'] ?? '') == '3' ? 'selected' : '' }}>Móvil</option>
                                                    </select>
                                                    @error("telefonos.$index.id_conectividad")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Operadora</label>
                                                    <select name="telefonos[{{ $index }}][id_operadora]" class="form-control {{ $errors->has("telefonos.$index.id_operadora") ? 'is-invalid' : '' }}">
                                                        <option value="">Operadora</option>
                                                        @foreach($operadoras as $operadora)
                                                            <option value="{{ $operadora->IdOperadora }}"
                                                                {{ ($telefono['id_operadora'] ?? '') == $operadora->IdOperadora ? 'selected' : '' }}>
                                                                {{ $operadora->Nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error("telefonos.$index.id_operadora")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                @if($index > 0)
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger btn-block remove-telefono" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-outline card-success">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-envelope"></i> Correos</h5>
                                <button type="button" id="addCorreo" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="correos-container">
                                    @foreach($correos as $index => $correo)
                                        <div class="correo-item border rounded p-3 mb-3">
                                            <div class="input-group">
                                                <input type="email"
                                                    name="correos[{{ $index }}][correo]"
                                                    class="form-control {{ $errors->has("correos.$index.correo") ? 'is-invalid' : '' }}"
                                                    placeholder="Correo electrónico"
                                                    value="{{ $correo['correo'] ?? '' }}">
                                                @if($index > 0)
                                                    <button type="button" class="btn btn-danger remove-correo" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @error("correos.$index.correo")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card card-outline card-warning">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> Direcciones</h5>
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

                <br>
                <button class="btn btn-primary"><span class="fas fa-user-plus fa-lg"></span> Agregar</button>
                <a href="{{ route('contacto.empresa.index') }}" class="btn btn-secondary"><span class="fas fa-arrow-left fa-lg"></span> Cancelar</a>
            </form>

            </p>
        </div>

    </div>

@endsection

@section('js')
<script>
    let telefonoIndex = {{ count($telefonos) }};
    let correoIndex = {{ count($correos) }};
    let direccionIndex = {{ count($direcciones) }};
    const ubicaciones = @json($ubicaciones);
    const direccionTipos = @json($direccionTipos);
    const direccionesIniciales = @json(array_values($direcciones));

    const operadoraOptions = `
        <option value="">Operadora</option>
        @foreach($operadoras as $operadora)
            <option value="{{ $operadora->IdOperadora }}">{{ $operadora->Nombre }}</option>
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
                        <button type="button" class="btn btn-danger remove-direccion" title="Eliminar">
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
        const container = document.getElementById('telefonos-container');
        const item = document.createElement('div');
        item.className = 'telefono-item border rounded p-3 mb-3';
        item.innerHTML = `
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label>Número</label>
                    <input type="text" name="telefonos[${telefonoIndex}][numero]" class="form-control" placeholder="Número">
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
                        ${operadoraOptions}
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-block remove-telefono" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(item);
        telefonoIndex++;
    });

    document.getElementById('telefonos-container').addEventListener('click', function (event) {
        if (event.target.closest('.remove-telefono')) {
            event.target.closest('.telefono-item').remove();
        }
    });

    document.getElementById('addCorreo').addEventListener('click', function () {
        const container = document.getElementById('correos-container');
        const item = document.createElement('div');
        item.className = 'correo-item border rounded p-3 mb-3';
        item.innerHTML = `
            <div class="input-group">
                <input type="email" name="correos[${correoIndex}][correo]" class="form-control" placeholder="Correo electrónico">
                <button type="button" class="btn btn-danger remove-correo" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(item);
        correoIndex++;
    });

    document.getElementById('correos-container').addEventListener('click', function (event) {
        if (event.target.closest('.remove-correo')) {
            event.target.closest('.correo-item').remove();
        }
    });

    document.getElementById('addDireccion').addEventListener('click', function () {
        addDireccion();
    });

    direccionesIniciales.forEach((direccion) => addDireccion(direccion));

    document.getElementById('direcciones-container').addEventListener('click', function (event) {
        if (event.target.closest('.remove-direccion')) {
            event.target.closest('.direccion-item').remove();

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
@endsection
