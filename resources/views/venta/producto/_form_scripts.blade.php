@php
    $producto = $producto ?? null;
    $categoriaActual = old('categoria', $producto->Categoria ?? '');
    $ingredientesActuales = old('ingredientes', $producto?->ingredientes
        ->map(fn ($item) => [
            'id_insumo' => $item->IdInsumo,
            'cantidad' => (float) $item->Cantidad,
            'unidad_medida' => $item->UnidadMedida,
        ])
        ->values()
        ->all() ?? []);
@endphp

<script>
$(function () {
    const categoriasPorTipo = {
        MATERIA_PRIMA: @json($categoriasMateriaPrima),
        RECETA: @json($categoriasReceta),
        MENU: @json($categoriasMenu),
    };
    const insumos = @json($insumos->map(fn ($insumo) => [
        'id' => $insumo->IdProducto,
        'nombre' => $insumo->Nombre,
        'tipo' => $insumo->TipoProducto === 'MATERIA_PRIMA' ? 'Materia Prima' : 'Receta',
        'unidad' => $insumo->UnidadMedida,
    ]));
    const ingredientesActuales = @json($ingredientesActuales);
    const categoriaActual = @json($categoriaActual);

    const tipoProducto = $('#tipo_producto');
    const categoria = $('#categoria');

    const refrescarCategorias = function () {
        const tipo = tipoProducto.val();
        const opciones = categoriasPorTipo[tipo] || {};
        const valorPrevio = categoria.data('preseleccion') !== undefined ? categoria.data('preseleccion') : categoria.val();

        categoria.empty();
        categoria.append($('<option>', { value: '', text: Object.keys(opciones).length ? 'Seleccione' : 'Seleccione un nivel primero' }));

        $.each(opciones, function (clave, etiqueta) {
            categoria.append($('<option>', { value: clave, text: etiqueta }));
        });

        if (valorPrevio && opciones[valorPrevio]) {
            categoria.val(valorPrevio);
        }

        categoria.data('preseleccion', null);
    };

    const refrescarVisibilidad = function () {
        const tipo = tipoProducto.val();
        $('.campo-materia-prima').toggle(tipo === 'MATERIA_PRIMA');
        $('.campo-receta').toggle(tipo === 'RECETA');
        $('.campo-menu').toggle(tipo === 'MENU');
        $('.campo-ingredientes').toggle(tipo === 'RECETA' || tipo === 'MENU');
    };

    let contadorFilas = 0;

    const agregarFilaIngrediente = function (datos) {
        datos = datos || {};
        const plantilla = document.getElementById('plantillaFilaIngrediente');
        const fila = $(plantilla.content.cloneNode(true));
        const indice = contadorFilas++;

        const select = fila.find('.insumo-select').attr('name', 'ingredientes[' + indice + '][id_insumo]');
        select.append($('<option>', { value: '', text: 'Seleccione un insumo' }));
        $.each(insumos, function (i, insumo) {
            select.append($('<option>', {
                value: insumo.id,
                text: insumo.nombre + ' (' + insumo.tipo + (insumo.unidad ? ', ' + insumo.unidad : '') + ')',
                'data-unidad': insumo.unidad || '',
            }));
        });
        if (datos.id_insumo) {
            select.val(datos.id_insumo);
        }

        fila.find('.cantidad-input')
            .attr('name', 'ingredientes[' + indice + '][cantidad]')
            .val(datos.cantidad || '');

        fila.find('.unidad-input')
            .attr('name', 'ingredientes[' + indice + '][unidad_medida]')
            .val(datos.unidad_medida || (datos.id_insumo ? select.find(':selected').data('unidad') : ''));

        $('#filasIngredientes').append(fila);
    };

    $('#tablaIngredientes').on('change', '.insumo-select', function () {
        const fila = $(this).closest('tr');
        const unidadInput = fila.find('.unidad-input');
        if (!unidadInput.val()) {
            unidadInput.val($(this).find(':selected').data('unidad') || '');
        }
    });

    $('#tablaIngredientes').on('click', '.quitar-ingrediente', function () {
        $(this).closest('tr').remove();
    });

    $('#agregarIngrediente').on('click', function () {
        agregarFilaIngrediente();
    });

    tipoProducto.on('change', function () {
        refrescarCategorias();
        refrescarVisibilidad();
    });

    categoria.data('preseleccion', categoriaActual);
    refrescarCategorias();
    refrescarVisibilidad();

    if (ingredientesActuales && ingredientesActuales.length) {
        $.each(ingredientesActuales, function (i, item) {
            agregarFilaIngrediente(item);
        });
    }
});
</script>
