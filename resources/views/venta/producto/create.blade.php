@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-md-6">
            <h1>
                <i class="fas fa-plus-circle text-primary"></i> Nuevo Producto
            </h1>
        </div>
        <div class="col-md-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('contacto.dashboard') }}">Contactos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.producto.index') }}">Productos</a></li>
                <li class="breadcrumb-item active">Crear</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card card-outline card-primary shadow">
    <div class="card-header">
        <h3 class="card-title">Registrar Producto</h3>
    </div>

    <form action="{{ route('ventas.producto.store') }}" method="POST">
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

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                    <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" maxlength="120" required>
                    @error('nombre')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-3 form-group">
                    <label for="tipo_producto">Tipo <span class="text-danger">*</span></label>
                    <select id="tipo_producto" name="tipo_producto" class="form-control @error('tipo_producto') is-invalid @enderror" required>
                        <option value="">Seleccione</option>
                        <option value="MATERIA_PRIMA" @selected(old('tipo_producto') === 'MATERIA_PRIMA')>Materia prima</option>
                        <option value="ELABORADO" @selected(old('tipo_producto') === 'ELABORADO')>Producto elaborado</option>
                    </select>
                    @error('tipo_producto')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-3 form-group">
                    <label for="unidad_medida">Unidad</label>
                    <input type="text" id="unidad_medida" name="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" value="{{ old('unidad_medida') }}" maxlength="30" placeholder="kg, g, l, unidad">
                    @error('unidad_medida')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-3 form-group">
                    <label for="costo_unitario">Costo unitario</label>
                    <input type="number" step="0.01" min="0" id="costo_unitario" name="costo_unitario" class="form-control @error('costo_unitario') is-invalid @enderror" value="{{ old('costo_unitario') }}">
                    @error('costo_unitario')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-3 form-group">
                    <label for="stock_actual">Stock actual</label>
                    <input type="number" step="0.01" min="0" id="stock_actual" name="stock_actual" class="form-control @error('stock_actual') is-invalid @enderror" value="{{ old('stock_actual') }}">
                    @error('stock_actual')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-3 form-group d-flex align-items-end">
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="usa_receta" name="usa_receta" value="1" @checked(old('usa_receta', 1))>
                        <label class="custom-control-label" for="usa_receta">Usa en recetas</label>
                    </div>
                </div>

                <div class="col-md-3 form-group d-flex align-items-end">
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="usa_menu" name="usa_menu" value="1" @checked(old('usa_menu'))>
                        <label class="custom-control-label" for="usa_menu">Usa en menú</label>
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label for="tipo_menu">Tipo de menú</label>
                    <select id="tipo_menu" name="tipo_menu" class="form-control @error('tipo_menu') is-invalid @enderror">
                        <option value="">Seleccione</option>
                        <option value="ALMUERZO" @selected(old('tipo_menu') === 'ALMUERZO')>Almuerzo</option>
                        <option value="PIQUEO" @selected(old('tipo_menu') === 'PIQUEO')>Piqueo</option>
                        <option value="AMBOS" @selected(old('tipo_menu') === 'AMBOS')>Ambos</option>
                    </select>
                    @error('tipo_menu')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-12 form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror" maxlength="500">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-3 form-group d-flex align-items-end">
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="activo" name="activo" value="1" @checked(old('activo', 1))>
                        <label class="custom-control-label" for="activo">Activo</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('ventas.producto.index') }}" class="btn btn-secondary">
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
$(function () {
    const usaMenu = $('#usa_menu');
    const tipoMenu = $('#tipo_menu');

    const syncTipoMenu = function () {
        const enabled = usaMenu.is(':checked');
        tipoMenu.prop('disabled', !enabled);
        if (!enabled) {
            tipoMenu.val('');
        }
    };

    usaMenu.on('change', syncTipoMenu);
    syncTipoMenu();
});
</script>
@stop
