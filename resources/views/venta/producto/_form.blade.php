@php
    $producto = $producto ?? null;
    $tipoActual = old('tipo_producto', $producto->TipoProducto ?? '');
@endphp

<div class="row">
    <div class="col-md-5 form-group">
        <label for="nombre">Nombre <span class="text-danger">*</span></label>
        <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $producto->Nombre ?? '') }}" maxlength="120" required>
        @error('nombre')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-4 form-group">
        <label for="tipo_producto">Nivel <span class="text-danger">*</span></label>
        <select id="tipo_producto" name="tipo_producto" class="form-control @error('tipo_producto') is-invalid @enderror" required>
            <option value="">Seleccione</option>
            <option value="MATERIA_PRIMA" @selected($tipoActual === 'MATERIA_PRIMA')>1. Materia Prima (insumo)</option>
            <option value="RECETA" @selected($tipoActual === 'RECETA')>2. Receta / Sub-receta</option>
            <option value="MENU" @selected($tipoActual === 'MENU')>3. Menú (producto final)</option>
        </select>
        @error('tipo_producto')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-3 form-group">
        <label for="categoria">Categoría</label>
        <select id="categoria" name="categoria" class="form-control @error('categoria') is-invalid @enderror">
            <option value="">Seleccione un nivel primero</option>
        </select>
        @error('categoria')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-3 form-group">
        <label for="unidad_medida">Unidad de medida</label>
        <input type="text" id="unidad_medida" name="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" value="{{ old('unidad_medida', $producto->UnidadMedida ?? '') }}" maxlength="30" placeholder="kg, g, l, ml, unidad">
        @error('unidad_medida')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-3 form-group">
        <label for="costo_unitario">Costo unitario</label>
        <input type="number" step="0.01" min="0" id="costo_unitario" name="costo_unitario" class="form-control @error('costo_unitario') is-invalid @enderror" value="{{ old('costo_unitario', $producto->CostoUnitario ?? '') }}">
        @error('costo_unitario')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-3 form-group campo-materia-prima">
        <label for="stock_actual">Stock actual</label>
        <input type="number" step="0.01" min="0" id="stock_actual" name="stock_actual" class="form-control @error('stock_actual') is-invalid @enderror" value="{{ old('stock_actual', $producto->StockActual ?? '') }}">
        @error('stock_actual')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-3 form-group campo-materia-prima">
        <label for="porcentaje_merma">Merma (%)</label>
        <input type="number" step="0.01" min="0" max="100" id="porcentaje_merma" name="porcentaje_merma" class="form-control @error('porcentaje_merma') is-invalid @enderror" value="{{ old('porcentaje_merma', $producto->PorcentajeMerma ?? '') }}" placeholder="Ej: 12 (peso perdido al pelar/limpiar)">
        @error('porcentaje_merma')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-3 form-group campo-receta">
        <label for="rendimiento_cantidad">Rinde (cantidad)</label>
        <input type="number" step="0.01" min="0" id="rendimiento_cantidad" name="rendimiento_cantidad" class="form-control @error('rendimiento_cantidad') is-invalid @enderror" value="{{ old('rendimiento_cantidad', $producto->RendimientoCantidad ?? '') }}">
        @error('rendimiento_cantidad')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-3 form-group campo-receta">
        <label for="rendimiento_unidad">Rinde (unidad)</label>
        <input type="text" id="rendimiento_unidad" name="rendimiento_unidad" class="form-control @error('rendimiento_unidad') is-invalid @enderror" value="{{ old('rendimiento_unidad', $producto->RendimientoUnidad ?? '') }}" maxlength="30" placeholder="porciones, litros">
        @error('rendimiento_unidad')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-2 form-group d-flex align-items-end">
        <div class="custom-control custom-switch mb-2">
            <input type="checkbox" class="custom-control-input" id="usa_receta" name="usa_receta" value="1" @checked(old('usa_receta', ($producto->UsaReceta ?? 'S') === 'S'))>
            <label class="custom-control-label" for="usa_receta">Usa en recetas</label>
        </div>
    </div>

    <div class="col-md-2 form-group d-flex align-items-end">
        <div class="custom-control custom-switch mb-2">
            <input type="checkbox" class="custom-control-input" id="usa_menu" name="usa_menu" value="1" @checked(old('usa_menu', ($producto->UsaMenu ?? '') === 'S'))>
            <label class="custom-control-label" for="usa_menu">Usa en menú</label>
        </div>
    </div>

    <div class="col-md-3 form-group campo-menu">
        <label for="tipo_menu">Tipo de menú</label>
        <select id="tipo_menu" name="tipo_menu" class="form-control @error('tipo_menu') is-invalid @enderror">
            <option value="">Seleccione</option>
            <option value="ALMUERZO" @selected(old('tipo_menu', $producto->TipoMenu ?? '') === 'ALMUERZO')>Almuerzo</option>
            <option value="PIQUEO" @selected(old('tipo_menu', $producto->TipoMenu ?? '') === 'PIQUEO')>Piqueo</option>
            <option value="AMBOS" @selected(old('tipo_menu', $producto->TipoMenu ?? '') === 'AMBOS')>Ambos</option>
        </select>
        @error('tipo_menu')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="col-md-2 form-group d-flex align-items-end">
        <div class="custom-control custom-switch mb-2">
            <input type="checkbox" class="custom-control-input" id="activo" name="activo" value="1" @checked(old('activo', (int) ($producto->Activo ?? 1) === 1))>
            <label class="custom-control-label" for="activo">Activo</label>
        </div>
    </div>

    <div class="col-md-12 form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror" maxlength="500">{{ old('descripcion', $producto->Descripcion ?? '') }}</textarea>
        @error('descripcion')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

<div class="campo-ingredientes">
    <hr>
    <h5><i class="fas fa-list-ul"></i> Composición (ingredientes / sub-recetas)</h5>
    <p class="text-muted">Insumos y/o sub-recetas que se descuentan del inventario cada vez que se prepara o vende este producto.</p>

    <div class="table-responsive">
        <table class="table table-sm table-bordered" id="tablaIngredientes">
            <thead class="thead-light">
                <tr>
                    <th>Insumo / Sub-receta</th>
                    <th width="150">Cantidad</th>
                    <th width="150">Unidad</th>
                    <th width="60"></th>
                </tr>
            </thead>
            <tbody id="filasIngredientes"></tbody>
        </table>
    </div>

    <button type="button" id="agregarIngrediente" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-plus"></i> Agregar ingrediente
    </button>
</div>

<template id="plantillaFilaIngrediente">
    <tr class="fila-ingrediente">
        <td>
            <select name="" class="form-control form-control-sm insumo-select"></select>
        </td>
        <td>
            <input type="number" step="0.001" min="0.001" name="" class="form-control form-control-sm cantidad-input">
        </td>
        <td>
            <input type="text" maxlength="30" name="" class="form-control form-control-sm unidad-input">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger quitar-ingrediente"><i class="fas fa-trash"></i></button>
        </td>
    </tr>
</template>
