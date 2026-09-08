@extends('adminlte::page')

@section('title', 'Editar Empresa')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-building text-primary"></i>
                Editar Empresa
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contacto</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.empresa.index') }}">Empresas</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    @php
        $direcciones = old('direcciones', $empresa->direcciones->map(function ($direccion) {
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

    <div class="card-header">
        <h3 class="card-title">Editar Empresa</h3>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Se encontraron errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contacto.empresa.update', $empresa->IdEmpresa) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>RUC</label>
                <input type="text" name="RUC" class="form-control @error('RUC') is-invalid @enderror"
                    value="{{ old('RUC', $empresa->RUC) }}">
                @error('RUC')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label>Razón Social</label>
                <input type="text" name="RazonSocial" class="form-control @error('RazonSocial') is-invalid @enderror"
                    value="{{ old('RazonSocial', $empresa->RazonSocial) }}">
                @error('RazonSocial')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline card-info">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Teléfonos</h5>
                            <button type="button" id="addTelefono" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="telefonos-container">
                                @foreach(old('telefonos', $empresa->telefono_movils->map(function($telefono){
                                    return [
                                        'id' => $telefono->IdTelefonoMovil,
                                        'numero' => $telefono->Numero,
                                        'id_operadora' => $telefono->IdOperadora,
                                    ];
                                })->toArray()) as $index => $telefono)
                                    <div class="telefono-item border rounded p-3 mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-5">
                                                <label>Número</label>
                                                <input type="text" name="telefonos[{{ $index }}][numero]" class="form-control"
                                                    value="{{ $telefono['numero'] ?? '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Operadora</label>
                                                <select name="telefonos[{{ $index }}][id_operadora]" class="form-control">
                                                    <option value="">Operadora</option>
                                                    @foreach($operadoras as $operadora)
                                                        <option value="{{ $operadora->IdOperadora }}"
                                                            {{ ($telefono['id_operadora'] ?? '') == $operadora->IdOperadora ? 'selected' : '' }}>
                                                            {{ $operadora->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input type="hidden" name="telefonos[{{ $index }}][id]" value="{{ $telefono['id'] ?? '' }}">
                                            @if($index > 0)
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-block remove-telefono">
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
                            <h5 class="mb-0">Correos</h5>
                            <button type="button" id="addCorreo" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="correos-container">
                                @foreach(old('correos', $empresa->correos->map(function($correo){
                                    return [
                                        'id' => $correo->IdCorreo,
                                        'correo' => $correo->Correo,
                                    ];
                                })->toArray()) as $index => $correo)
                                    <div class="correo-item border rounded p-3 mb-3">
                                        <div class="input-group">
                                            <input type="email" name="correos[{{ $index }}][correo]" class="form-control"
                                                value="{{ $correo['correo'] ?? '' }}">
                                            <input type="hidden" name="correos[{{ $index }}][id]" value="{{ $correo['id'] ?? '' }}">
                                            @if($index > 0)
                                                <button type="button" class="btn btn-danger remove-correo">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
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
                            <h5 class="mb-0">Direcciones</h5>
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

            <div class="mt-4 text-right">
                <a href="{{ route('contacto.empresa.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    let telefonoIndex = {{ count(old('telefonos', $empresa->telefono_movils)) }};
    let correoIndex = {{ count(old('correos', $empresa->correos)) }};
    let direccionIndex = {{ count($direcciones) }};
    const ubicaciones = @json($ubicaciones);
    const direccionTipos = @json($direccionTipos);
    const direccionesIniciales = @json(array_values($direcciones));

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
                        <button type="button" class="btn btn-danger remove-direccion">
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
        const container = document.getElementById('telefonos-container');
        const item = document.createElement('div');
        item.className = 'telefono-item border rounded p-3 mb-3';
        item.innerHTML = `
            <div class="row align-items-end">
                <div class="col-md-5">
                    <label>Número</label>
                    <input type="text" name="telefonos[${telefonoIndex}][numero]" class="form-control" placeholder="Número">
                </div>
                <div class="col-md-4">
                    <label>Operadora</label>
                    <select name="telefonos[${telefonoIndex}][id_operadora]" class="form-control">
                        <option value="">Operadora</option>
                        @foreach($operadoras as $operadora)
                            <option value="{{ $operadora->IdOperadora }}">{{ $operadora->Nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-block remove-telefono">
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
                <button type="button" class="btn btn-danger remove-correo">
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
@stop
