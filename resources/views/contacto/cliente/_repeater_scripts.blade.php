<script>
const ubicaciones = @json($ubicaciones);
const direccionTipos = @json($direccionTipos);
const operadorasList = @json($operadoras->map(fn ($o) => ['id' => $o->IdOperadora, 'nombre' => $o->Nombre])->values());
const rutasList = @json($rutas->map(fn ($r) => [
    'id' => $r->id,
    'label' => $r->Nombre . ' — ' . optional($r->Fecha)->format('d/m/Y') . ' (' . $r->EstadoLabel . ')',
])->values());

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

function operadoraOptionsHtml(selectedId) {
    return buildOptions(operadorasList, selectedId, 'id', (o) => o.nombre, 'Operadora');
}

function rutaOptionsHtml(selectedId) {
    return buildOptions(rutasList, selectedId, 'id', (r) => r.label, 'Sin recorrido asignado');
}

/**
 * Repetidor genérico: crea filas a partir de una plantilla, gestiona el índice de
 * Laravel (prefix[N][campo]), el botón de quitar (oculto cuando solo queda 1 fila)
 * y los datos iniciales (edición) o una fila vacía por defecto (creación).
 */
function createRepeater({ containerId, prefix, itemSelector, removeSelector, rowHtml, afterRender, initial }) {
    const container = document.getElementById(containerId);
    if (!container) {
        return { addRow: () => {} };
    }

    let index = 0;

    const updateRemoveVisibility = () => {
        const filas = container.querySelectorAll(itemSelector);
        filas.forEach((fila) => {
            const wrapper = fila.querySelector('.remove-wrapper');
            if (wrapper) {
                wrapper.classList.toggle('d-none', filas.length <= 1);
            }
        });
    };

    const addRow = (values = {}) => {
        const i = index++;
        container.insertAdjacentHTML('beforeend', rowHtml(i, prefix, values));
        const fila = container.lastElementChild;
        if (typeof afterRender === 'function') {
            afterRender(fila, values);
        }
        updateRemoveVisibility();
    };

    container.addEventListener('click', (e) => {
        if (e.target.closest(removeSelector)) {
            e.target.closest(itemSelector).remove();
            updateRemoveVisibility();
        }
    });

    const datosIniciales = initial && initial.length ? initial : [{}];
    datosIniciales.forEach(addRow);

    return { addRow: () => addRow({}) };
}

function telefonoRowHtml(i, prefix, values) {
    return `
        <div class="telefono-item border rounded p-2 mb-2">
            <div class="row align-items-center no-gutters">
                <div class="col-6 pr-1">
                    <input type="text" name="${prefix}[${i}][numero]" class="form-control form-control-sm" value="${values.numero || ''}" placeholder="Número">
                </div>
                <div class="col-5 pl-1">
                    <select name="${prefix}[${i}][id_operadora]" class="form-control form-control-sm">${operadoraOptionsHtml(values.id_operadora)}</select>
                </div>
                <div class="col-1 pl-1 remove-wrapper">
                    <button type="button" class="btn btn-danger btn-sm removeTelefono w-100" title="Quitar"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
    `;
}

function correoRowHtml(i, prefix, values) {
    return `
        <div class="correo-item border rounded p-2 mb-2">
            <div class="row align-items-center no-gutters">
                <div class="col">
                    <input type="email" name="${prefix}[${i}][correo]" class="form-control form-control-sm" value="${values.correo || ''}" placeholder="Correo electrónico">
                </div>
                <div class="col-auto pl-2 remove-wrapper">
                    <button type="button" class="btn btn-danger btn-sm removeCorreo" title="Quitar"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
    `;
}

function direccionRowHtml(i, prefix, values) {
    return `
        <div class="direccion-item border rounded p-3 mb-3">
            <input type="hidden" name="${prefix}[${i}][id]" class="direccion-id" value="${values.id || ''}">
            <div class="form-row">
                <div class="form-group col-md-3 mb-2">
                    <label class="small mb-1">Dirección / referencia</label>
                    <input type="text" name="${prefix}[${i}][nombre]" class="form-control form-control-sm direccion-nombre" value="${values.nombre || ''}" placeholder="Calle principal, numeración, referencia">
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="small mb-1">Tipo</label>
                    <select name="${prefix}[${i}][id_direccion_tipo]" class="form-control form-control-sm direccion-tipo"></select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="small mb-1"><i class="fas fa-route"></i> Recorrido por defecto</label>
                    <select name="${prefix}[${i}][id_ruta]" class="form-control form-control-sm direccion-ruta">${rutaOptionsHtml(values.id_ruta)}</select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="small mb-1"><i class="fas fa-map-marked-alt"></i> Ubicación (Google Maps)</label>
                    <input type="text" name="${prefix}[${i}][ubicacion]" class="form-control form-control-sm direccion-ubicacion" value="${values.ubicacion || ''}" placeholder="Enlace o coordenadas lat,lng">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-6 col-md mb-2">
                    <label class="small mb-1">Continente</label>
                    <select name="${prefix}[${i}][id_continente]" class="form-control form-control-sm direccion-continente"></select>
                </div>
                <div class="form-group col-6 col-md mb-2">
                    <label class="small mb-1">País</label>
                    <select name="${prefix}[${i}][id_pais]" class="form-control form-control-sm direccion-pais"></select>
                </div>
                <div class="form-group col-6 col-md mb-2">
                    <label class="small mb-1">Provincia</label>
                    <select name="${prefix}[${i}][id_provincia]" class="form-control form-control-sm direccion-provincia"></select>
                </div>
                <div class="form-group col-6 col-md mb-2">
                    <label class="small mb-1">Cantón</label>
                    <select name="${prefix}[${i}][id_canton]" class="form-control form-control-sm direccion-canton"></select>
                </div>
                <div class="form-group col-6 col-md mb-2">
                    <label class="small mb-1">Parroquia</label>
                    <select name="${prefix}[${i}][id_parroquia]" class="form-control form-control-sm direccion-parroquia"></select>
                </div>
            </div>
            <div class="text-right remove-wrapper">
                <button type="button" class="btn btn-outline-danger btn-sm removeDireccion"><i class="fas fa-trash"></i> Quitar dirección</button>
            </div>
        </div>
    `;
}

function hydrateDireccionItem(item, values = {}) {
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

function createTelefonoRepeater(containerId, prefix, initial = []) {
    return createRepeater({
        containerId,
        prefix,
        itemSelector: '.telefono-item',
        removeSelector: '.removeTelefono',
        rowHtml: telefonoRowHtml,
        initial,
    });
}

function createCorreoRepeater(containerId, prefix, initial = []) {
    return createRepeater({
        containerId,
        prefix,
        itemSelector: '.correo-item',
        removeSelector: '.removeCorreo',
        rowHtml: correoRowHtml,
        initial,
    });
}

function createDireccionRepeater(containerId, prefix, initial = []) {
    return createRepeater({
        containerId,
        prefix,
        itemSelector: '.direccion-item',
        removeSelector: '.removeDireccion',
        rowHtml: direccionRowHtml,
        afterRender: hydrateDireccionItem,
        initial,
    });
}
</script>
