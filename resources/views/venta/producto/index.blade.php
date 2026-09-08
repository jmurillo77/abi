@extends('adminlte::page')

@section('title', 'Productos')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables-plugins/buttons/css/buttons.bootstrap4.min.css') }}">
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-boxes text-primary"></i> Gestión de Productos
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Productos</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            Catálogo de Productos
            <small class="text-muted">de menor a mayor complejidad: materia prima &rarr; receta &rarr; menú</small>
        </h3>
        @submenuCan('create', 'ventas.producto.index')
            <a href="{{ route('ventas.producto.crear') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Producto
            </a>
        @endsubmenuCan
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <ul class="nav nav-tabs" id="tabsProductos" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="tab-mp-link" data-toggle="tab" href="#tab-mp" role="tab">
                    <span class="badge badge-secondary">1</span> Materias Primas
                    <span class="badge badge-light">{{ $materiasPrimas->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-receta-link" data-toggle="tab" href="#tab-receta" role="tab">
                    <span class="badge badge-info">2</span> Recetas
                    <span class="badge badge-light">{{ $recetas->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab-menu-link" data-toggle="tab" href="#tab-menu" role="tab">
                    <span class="badge badge-success">3</span> Menú (Productos Finales)
                    <span class="badge badge-light">{{ $menus->count() }}</span>
                </a>
            </li>
        </ul>

        <div class="tab-content pt-3">
            {{-- 1. Materias Primas --}}
            <div class="tab-pane fade show active" id="tab-mp" role="tabpanel">
                <p class="text-muted">Insumos que se compran a proveedores, sin preparación previa. Se miden en unidades de inventario (kg, litros, unidades).</p>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="filtroCategoriaMp" class="mb-1">Categoría</label>
                        <select id="filtroCategoriaMp" class="form-control form-control-sm">
                            <option value="">Todas</option>
                            @foreach($categoriasMateriaPrima as $clave => $etiqueta)
                                <option value="{{ $etiqueta }}">{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filtroActivoMp" class="mb-1">Activo</label>
                        <select id="filtroActivoMp" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="Sí">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 limpiar-filtros" data-tabla="tablaMp">Limpiar filtros</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tablaMp" class="table table-bordered table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Unidad</th>
                                <th>Costo Unitario</th>
                                <th>Merma %</th>
                                <th>Stock</th>
                                <th>Activo</th>
                                <th width="170">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materiasPrimas as $producto)
                                <tr>
                                    <td>{{ $producto->IdProducto }}</td>
                                    <td>{{ $producto->Nombre }}</td>
                                    <td>{{ $producto->CategoriaLabel ?: '-' }}</td>
                                    <td>{{ $producto->UnidadMedida ?: '-' }}</td>
                                    <td>{{ is_null($producto->CostoUnitario) ? '-' : number_format((float) $producto->CostoUnitario, 2) }}</td>
                                    <td>{{ is_null($producto->PorcentajeMerma) ? '-' : number_format((float) $producto->PorcentajeMerma, 2) . '%' }}</td>
                                    <td>{{ is_null($producto->StockActual) ? '-' : number_format((float) $producto->StockActual, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ (int) $producto->Activo === 1 ? 'success' : 'secondary' }}">
                                            {{ (int) $producto->Activo === 1 ? 'Sí' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @include('venta.producto._acciones', ['producto' => $producto])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No hay materias primas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 2. Recetas y Sub-recetas --}}
            <div class="tab-pane fade" id="tab-receta" role="tabpanel">
                <p class="text-muted">Puente entre la materia prima y el menú final: porciones exactas que descuentan del inventario al vender un plato.</p>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="filtroCategoriaReceta" class="mb-1">Categoría</label>
                        <select id="filtroCategoriaReceta" class="form-control form-control-sm">
                            <option value="">Todas</option>
                            @foreach($categoriasReceta as $clave => $etiqueta)
                                <option value="{{ $etiqueta }}">{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filtroActivoReceta" class="mb-1">Activo</label>
                        <select id="filtroActivoReceta" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="Sí">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 limpiar-filtros" data-tabla="tablaReceta">Limpiar filtros</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tablaReceta" class="table table-bordered table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Rendimiento</th>
                                <th>Costo Unitario</th>
                                <th>Activo</th>
                                <th width="170">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recetas as $producto)
                                <tr>
                                    <td>{{ $producto->IdProducto }}</td>
                                    <td>{{ $producto->Nombre }}</td>
                                    <td>{{ $producto->CategoriaLabel ?: '-' }}</td>
                                    <td>
                                        @if($producto->RendimientoCantidad)
                                            {{ number_format((float) $producto->RendimientoCantidad, 2) }} {{ $producto->RendimientoUnidad }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ is_null($producto->CostoUnitario) ? '-' : number_format((float) $producto->CostoUnitario, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ (int) $producto->Activo === 1 ? 'success' : 'secondary' }}">
                                            {{ (int) $producto->Activo === 1 ? 'Sí' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @include('venta.producto._acciones', ['producto' => $producto])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay recetas registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. Menú (Productos Finales) --}}
            <div class="tab-pane fade" id="tab-menu" role="tabpanel">
                <p class="text-muted">Lo que el cliente ve en la carta o el punto de venta, vinculado a una receta para descontar el inventario.</p>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="filtroCategoriaMenu" class="mb-1">Categoría</label>
                        <select id="filtroCategoriaMenu" class="form-control form-control-sm">
                            <option value="">Todas</option>
                            @foreach($categoriasMenu as $clave => $etiqueta)
                                <option value="{{ $etiqueta }}">{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filtroTipoMenu" class="mb-1">Tipo de menú</label>
                        <select id="filtroTipoMenu" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="ALMUERZO">Almuerzo</option>
                            <option value="PIQUEO">Piqueo</option>
                            <option value="AMBOS">Ambos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filtroActivoMenu" class="mb-1">Activo</label>
                        <select id="filtroActivoMenu" class="form-control form-control-sm">
                            <option value="">Todos</option>
                            <option value="Sí">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 limpiar-filtros" data-tabla="tablaMenu">Limpiar filtros</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tablaMenu" class="table table-bordered table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Tipo Menú</th>
                                <th>Costo Unitario</th>
                                <th>Activo</th>
                                <th width="170">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $producto)
                                <tr>
                                    <td>{{ $producto->IdProducto }}</td>
                                    <td>{{ $producto->Nombre }}</td>
                                    <td>{{ $producto->CategoriaLabel ?: '-' }}</td>
                                    <td>{{ $producto->TipoMenu ?: '-' }}</td>
                                    <td>{{ is_null($producto->CostoUnitario) ? '-' : number_format((float) $producto->CostoUnitario, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ (int) $producto->Activo === 1 ? 'success' : 'secondary' }}">
                                            {{ (int) $producto->Activo === 1 ? 'Sí' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        @include('venta.producto._acciones', ['producto' => $producto])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay productos de menú registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('vendor/datatables-plugins/buttons/js/buttons.html5.min.js') }}"></script>

<script>
$(function () {
    const dtOptions = {
        responsive: true,
        autoWidth: false,
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: false,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        }
    };

    const tablaMp = $('#tablaMp').DataTable(dtOptions);
    const tablaReceta = $('#tablaReceta').DataTable(dtOptions);
    const tablaMenu = $('#tablaMenu').DataTable(dtOptions);

    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    // Búsqueda por subcadena (sin anclas ^$): las celdas traen badges con saltos de línea
    // y DataTables no recorta los espacios internos al extraer el texto para filtrar.
    const exactFilter = function (tabla, columna, valor) {
        tabla.column(columna).search(valor ? $.fn.dataTable.util.escapeRegex(valor) : '', true, false).draw();
    };

    $('#filtroCategoriaMp').on('change', function () { exactFilter(tablaMp, 2, $(this).val()); });
    $('#filtroActivoMp').on('change', function () { exactFilter(tablaMp, 7, $(this).val()); });

    $('#filtroCategoriaReceta').on('change', function () { exactFilter(tablaReceta, 2, $(this).val()); });
    $('#filtroActivoReceta').on('change', function () { exactFilter(tablaReceta, 5, $(this).val()); });

    $('#filtroCategoriaMenu').on('change', function () { exactFilter(tablaMenu, 2, $(this).val()); });
    $('#filtroTipoMenu').on('change', function () { exactFilter(tablaMenu, 3, $(this).val()); });
    $('#filtroActivoMenu').on('change', function () { exactFilter(tablaMenu, 5, $(this).val()); });

    $('.limpiar-filtros').on('click', function () {
        const tablas = { tablaMp: tablaMp, tablaReceta: tablaReceta, tablaMenu: tablaMenu };
        const tabla = tablas[$(this).data('tabla')];
        $(this).closest('.row').find('select').val('');
        tabla.search('').columns().search('').draw();
    });
});
</script>
@stop
