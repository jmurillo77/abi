{{-- Tabla de productos reutilizable para las pestañas de menú del día / a la carta / porciones --}}
@if($productos->isEmpty())
    <p class="text-muted text-center py-3 mb-0"><i class="fas fa-info-circle"></i> {{ $vacioMensaje }}</p>
@else
    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th width="120">Cantidad</th>
                    <th width="110"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    @php $precio = round((float) $producto->CostoUnitario * $factor, 2); @endphp
                    <tr>
                        <td>
                            {{ $producto->Nombre }}
                            @if($producto->TipoMenu)
                                <span class="badge badge-light">{{ $producto->TipoMenu }}</span>
                            @endif
                        </td>
                        <td>${{ number_format($precio, 2) }}</td>
                        <td>
                            <input type="number" id="cantidad-{{ $tipoItem }}-{{ $producto->IdProducto }}" class="form-control form-control-sm" value="1" min="0.1" step="any">
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm btn-block btnAgregar"
                                data-id="{{ $producto->IdProducto }}"
                                data-nombre="{{ $producto->Nombre }}"
                                data-precio="{{ $precio }}"
                                data-tipo="{{ $tipoItem }}"
                                data-tipo-label="{{ $tipoItem === 'MENU_DIA' ? 'Menú del día' : ($tipoItem === 'PORCION' ? 'Porción' : 'A la carta') }}">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
