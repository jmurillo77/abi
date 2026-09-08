<a href="{{ route('ventas.producto.show', $producto->IdProducto) }}" class="btn btn-sm btn-info" title="Ver">
    <i class="fas fa-eye"></i>
</a>
@submenuCan('edit', 'ventas.producto.index')
    <a href="{{ route('ventas.producto.edit', $producto->IdProducto) }}" class="btn btn-sm btn-primary" title="Editar">
        <i class="fas fa-edit"></i>
    </a>
@endsubmenuCan
@submenuCan('delete', 'ventas.producto.index')
    <form action="{{ route('ventas.producto.destroy', $producto->IdProducto) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Desea eliminar este producto?')" title="Eliminar">
            <i class="fas fa-trash"></i>
        </button>
    </form>
@endsubmenuCan
