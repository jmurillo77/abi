@extends('adminlte::page')

@section('title', 'Toma de Pedido')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-plus-circle text-primary"></i> Toma de Pedido
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Ventas</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.pedido.index') }}">Pedidos</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<form id="formPedido" action="{{ route('ventas.pedido.store') }}" method="POST">
    @csrf
    <div id="itemsContainer"></div>

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

    <div class="row">
        <div class="col-md-4">
            <div class="card card-outline card-primary shadow">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user"></i> Datos del pedido</h3>
                </div>
                <div class="card-body">
                    <div class="form-group position-relative">
                        <label for="clienteBuscar">Cliente <span class="text-danger">*</span></label>
                        <input type="text" id="clienteBuscar" class="form-control" placeholder="Buscar por nombre o documento..." autocomplete="off">
                        <input type="hidden" id="IdCliente" name="IdCliente" value="{{ old('IdCliente') }}">
                        <div id="clienteResultados" class="list-group position-absolute w-100 shadow-sm" style="z-index:1000; max-height:220px; overflow-y:auto; display:none;"></div>
                        <a href="{{ route('ventas.cliente.crear') }}" target="_blank" class="small">
                            <i class="fas fa-plus"></i> Registrar nuevo cliente
                        </a>
                    </div>

                    <div class="form-group" id="direccionWrapper" style="display:none;">
                        <label for="IdDireccion">Dirección de envío</label>
                        <select id="IdDireccion" name="IdDireccion" class="form-control"></select>
                        <p class="small mt-1 mb-0" id="direccionUbicacion" style="display:none;">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                            <a href="#" id="direccionUbicacionLink" target="_blank" rel="noopener">Ver ubicación en el mapa</a>
                        </p>
                    </div>
                    <p class="text-muted small" id="direccionVacia" style="display:none;">
                        <i class="fas fa-info-circle"></i> Este cliente no tiene direcciones registradas.
                    </p>

                    <div class="form-group">
                        <label for="IdRuta">Recorrido</label>
                        <select id="IdRuta" name="IdRuta" class="form-control">
                            <option value="">Sin recorrido asignado</option>
                            @foreach($rutas as $ruta)
                                <option value="{{ $ruta->id }}" {{ (string) old('IdRuta') === (string) $ruta->id ? 'selected' : '' }}>
                                    {{ $ruta->Nombre }} — {{ optional($ruta->Fecha)->format('d/m/Y') }} ({{ $ruta->EstadoLabel }})
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Se sugiere automáticamente según la dirección de envío; puede cambiarlo.</small>
                    </div>

                    <div class="form-group">
                        <label for="Observaciones">Observaciones</label>
                        <textarea id="Observaciones" name="Observaciones" class="form-control" rows="2" maxlength="500">{{ old('Observaciones') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="TipoEnvio">Costo de envío</label>
                        <select id="TipoEnvio" name="TipoEnvio" class="form-control">
                            <option value="NINGUNO" {{ old('TipoEnvio', 'NINGUNO') === 'NINGUNO' ? 'selected' : '' }}>Sin costo de envío</option>
                            <option value="GLOBAL" {{ old('TipoEnvio') === 'GLOBAL' ? 'selected' : '' }}>Global (para todo el pedido)</option>
                            <option value="POR_ITEM" {{ old('TipoEnvio') === 'POR_ITEM' ? 'selected' : '' }}>Por producto</option>
                        </select>
                    </div>

                    <div class="form-group" id="envioGlobalWrapper" style="display:none;">
                        <label for="CostoEnvio">Valor del envío</label>
                        <input type="number" id="CostoEnvio" name="CostoEnvio" class="form-control" min="0" step="any" value="{{ old('CostoEnvio', 0) }}">
                    </div>
                </div>
            </div>

            <div class="card card-outline card-success shadow">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-receipt"></i> Resumen del pedido</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th class="text-right">Subtotal</th>
                                <th>Envío</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="carritoBody">
                            <tr id="carritoVacio">
                                <td colspan="5" class="text-center text-muted py-3">
                                    <i class="fas fa-shopping-cart"></i> Aún no hay productos agregados
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th class="text-right" id="carritoTotal">$0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('ventas.pedido.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Registrar Pedido
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-outline card-info shadow">
                <div class="card-header p-2">
                    <ul class="nav nav-pills nav-justified" id="tabsProductos">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" data-tab="menu-dia">
                                <i class="fas fa-utensils"></i> Menú del día
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-tab="a-la-carta">
                                <i class="fas fa-concierge-bell"></i> A la carta
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-tab="porciones">
                                <i class="fas fa-mug-hot"></i> Porciones
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div id="panel-menu-dia" class="panel-productos">
                        @include('venta.pedido._productos_tabla', ['productos' => $menuDia, 'tipoItem' => 'MENU_DIA', 'factor' => 1, 'vacioMensaje' => 'No hay productos marcados como menú del día.'])
                    </div>

                    <div id="panel-a-la-carta" class="panel-productos d-none">
                        @include('venta.pedido._productos_tabla', ['productos' => $aLaCarta, 'tipoItem' => 'CARTA', 'factor' => 1, 'vacioMensaje' => 'No hay productos disponibles a la carta.'])
                    </div>

                    <div id="panel-porciones" class="panel-productos d-none">
                        @include('venta.pedido._productos_tabla', ['productos' => $porciones, 'tipoItem' => 'PORCION', 'factor' => 0.6, 'vacioMensaje' => 'No hay productos marcados para venta por porción (Tipo de menú = Piqueo o Ambos).'])
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@stop

@section('js')
<script>
let carrito = [];
const clientesData = @json($clientes->map(fn ($c) => ['id' => $c->id, 'nombre' => $c->NombreRelacionado, 'documento' => (string) $c->Documento]));
const direccionesUrlBase = '{{ url('ventas/pedido/clientes') }}';

document.querySelectorAll('#tabsProductos [data-tab]').forEach((tab) => {
    tab.addEventListener('click', function () {
        document.querySelectorAll('#tabsProductos [data-tab]').forEach((t) => t.classList.remove('active'));
        document.querySelectorAll('.panel-productos').forEach((p) => p.classList.add('d-none'));
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.tab).classList.remove('d-none');
    });
});

// --- Búsqueda y selección de cliente ---
const clienteBuscar = document.getElementById('clienteBuscar');
const clienteResultados = document.getElementById('clienteResultados');
const idClienteInput = document.getElementById('IdCliente');

function etiquetaCliente(cliente) {
    return cliente.nombre + (cliente.documento ? ' (' + cliente.documento + ')' : '');
}

function renderResultadosCliente(lista) {
    if (lista.length === 0) {
        clienteResultados.innerHTML = '<div class="list-group-item text-muted">Sin coincidencias</div>';
    } else {
        clienteResultados.innerHTML = lista.slice(0, 15).map((c) => `
            <button type="button" class="list-group-item list-group-item-action" data-id="${c.id}">${etiquetaCliente(c)}</button>
        `).join('');
    }
    clienteResultados.style.display = 'block';
}

clienteBuscar.addEventListener('input', function () {
    idClienteInput.value = '';
    ocultarDireccion();
    const texto = this.value.trim().toLowerCase();

    if (texto.length === 0) {
        clienteResultados.style.display = 'none';
        return;
    }

    const filtrados = clientesData.filter((c) => c.nombre.toLowerCase().includes(texto) || c.documento.toLowerCase().includes(texto));
    renderResultadosCliente(filtrados);
});

clienteBuscar.addEventListener('focus', function () {
    if (this.value.trim().length > 0) {
        clienteResultados.style.display = 'block';
    }
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('#clienteResultados') && e.target !== clienteBuscar) {
        clienteResultados.style.display = 'none';
    }

    if (e.target.closest('#clienteResultados button')) {
        const btn = e.target.closest('button');
        const cliente = clientesData.find((c) => String(c.id) === btn.dataset.id);
        seleccionarCliente(cliente);
    }
});

function seleccionarCliente(cliente) {
    idClienteInput.value = cliente.id;
    clienteBuscar.value = etiquetaCliente(cliente);
    clienteResultados.style.display = 'none';
    cargarDirecciones(cliente.id);
}

// --- Direcciones de envío, ubicación y recorrido sugerido ---
let direccionesInfo = {};

function actualizarUbicacionYRuta(idDireccion) {
    const info = direccionesInfo[idDireccion];
    const ubicacionWrapper = document.getElementById('direccionUbicacion');
    const ubicacionLink = document.getElementById('direccionUbicacionLink');

    if (info && info.google_maps_url) {
        ubicacionLink.href = info.google_maps_url;
        ubicacionWrapper.style.display = 'block';
    } else {
        ubicacionWrapper.style.display = 'none';
    }

    // El recorrido sugerido solo se aplica si hay uno disponible para esta dirección;
    // el usuario siempre puede cambiarlo manualmente después.
    if (info && info.id_ruta_sugerida) {
        document.getElementById('IdRuta').value = String(info.id_ruta_sugerida);
    }
}

function ocultarDireccion() {
    document.getElementById('direccionWrapper').style.display = 'none';
    document.getElementById('direccionVacia').style.display = 'none';
    document.getElementById('IdDireccion').innerHTML = '';
    document.getElementById('direccionUbicacion').style.display = 'none';
    direccionesInfo = {};
}

function cargarDirecciones(idCliente) {
    fetch(`${direccionesUrlBase}/${idCliente}/direcciones`)
        .then((resp) => resp.json())
        .then((data) => {
            const select = document.getElementById('IdDireccion');
            const direcciones = data.direcciones || [];
            direccionesInfo = {};

            if (direcciones.length === 0) {
                ocultarDireccion();
                document.getElementById('direccionVacia').style.display = 'block';
                return;
            }

            select.innerHTML = direcciones.map((d) => {
                direccionesInfo[d.id] = d;
                return `<option value="${d.id}" ${d.principal ? 'selected' : ''}>${d.etiqueta}${d.principal ? ' (Principal)' : ''}</option>`;
            }).join('');
            document.getElementById('direccionVacia').style.display = 'none';
            document.getElementById('direccionWrapper').style.display = 'block';

            actualizarUbicacionYRuta(select.value);
        });
}

document.getElementById('IdDireccion').addEventListener('change', function () {
    actualizarUbicacionYRuta(this.value);
});

// --- Tipo de envío ---
const tipoEnvioSelect = document.getElementById('TipoEnvio');
const envioGlobalWrapper = document.getElementById('envioGlobalWrapper');
const costoEnvioGlobalInput = document.getElementById('CostoEnvio');

tipoEnvioSelect.addEventListener('change', function () {
    envioGlobalWrapper.style.display = this.value === 'GLOBAL' ? 'block' : 'none';
    renderCarrito();
});

costoEnvioGlobalInput.addEventListener('input', actualizarTotal);

// --- Carrito de productos ---
document.querySelectorAll('.btnAgregar').forEach((btn) => {
    btn.addEventListener('click', function () {
        const cantidadInput = document.getElementById('cantidad-' + this.dataset.tipo + '-' + this.dataset.id);
        const cantidad = parseFloat(cantidadInput.value || '1');

        if (!cantidad || cantidad <= 0) {
            cantidadInput.focus();
            return;
        }

        carrito.push({
            id_producto: this.dataset.id,
            nombre: this.dataset.nombre,
            tipo_item: this.dataset.tipo,
            tipo_label: this.dataset.tipoLabel,
            precio: parseFloat(this.dataset.precio),
            cantidad: cantidad,
            costo_envio: 0,
        });

        cantidadInput.value = 1;
        renderCarrito();
    });
});

function renderCarrito() {
    const body = document.getElementById('carritoBody');
    body.innerHTML = '';

    if (carrito.length === 0) {
        body.innerHTML = `
            <tr id="carritoVacio">
                <td colspan="5" class="text-center text-muted py-3">
                    <i class="fas fa-shopping-cart"></i> Aún no hay productos agregados
                </td>
            </tr>
        `;
        actualizarTotal();
        return;
    }

    const porItem = tipoEnvioSelect.value === 'POR_ITEM';

    carrito.forEach((item, index) => {
        const subtotal = item.precio * item.cantidad;
        const envioCelda = porItem
            ? `<input type="number" class="form-control form-control-sm envioItemInput" data-index="${index}" min="0" step="any" value="${item.costo_envio || 0}">`
            : '<span class="text-muted">-</span>';

        body.insertAdjacentHTML('beforeend', `
            <tr>
                <td>
                    ${item.nombre}
                    <br><span class="badge badge-light">${item.tipo_label}</span>
                </td>
                <td>${item.cantidad}</td>
                <td class="text-right">$${subtotal.toFixed(2)}</td>
                <td>${envioCelda}</td>
                <td class="text-right">
                    <button type="button" class="btn btn-xs btn-danger btnQuitar" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `);
    });

    document.querySelectorAll('.btnQuitar').forEach((btn) => {
        btn.addEventListener('click', function () {
            carrito.splice(parseInt(this.dataset.index), 1);
            renderCarrito();
        });
    });

    document.querySelectorAll('.envioItemInput').forEach((input) => {
        input.addEventListener('input', function () {
            carrito[parseInt(this.dataset.index)].costo_envio = parseFloat(this.value || '0');
            actualizarTotal();
        });
    });

    actualizarTotal();
}

function actualizarTotal() {
    let total = 0;

    if (tipoEnvioSelect.value === 'GLOBAL') {
        total += parseFloat(costoEnvioGlobalInput.value || '0');
    }

    carrito.forEach((item) => {
        total += item.precio * item.cantidad;
        if (tipoEnvioSelect.value === 'POR_ITEM') {
            total += item.costo_envio || 0;
        }
    });

    document.getElementById('carritoTotal').textContent = '$' + total.toFixed(2);
}

document.getElementById('formPedido').addEventListener('submit', function (e) {
    if (!idClienteInput.value) {
        e.preventDefault();
        alert('Debe seleccionar un cliente.');
        return;
    }

    if (carrito.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un producto al pedido.');
        return;
    }

    const container = document.getElementById('itemsContainer');
    container.innerHTML = '';

    carrito.forEach((item, index) => {
        ['id_producto', 'tipo_item', 'cantidad', 'costo_envio'].forEach((campo) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `items[${index}][${campo}]`;
            input.value = item[campo];
            container.appendChild(input);
        });
    });
});
</script>
@stop

