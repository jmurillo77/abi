<?php

namespace App\Models\negocio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    use HasFactory;

    protected $connection = 'negocio';
    protected $table = 'pedido_detalles';

    protected $fillable = [
        'IdPedido',
        'IdProducto',
        'Nombre',
        'TipoItem',
        'Cantidad',
        'PrecioUnitario',
        'Subtotal',
        'CostoEnvio',
    ];

    protected $casts = [
        'Cantidad' => 'decimal:2',
        'PrecioUnitario' => 'decimal:2',
        'Subtotal' => 'decimal:2',
        'CostoEnvio' => 'decimal:2',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'IdPedido');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'IdProducto', 'IdProducto');
    }

    public function getTipoItemLabelAttribute(): string
    {
        return match ($this->TipoItem) {
            'MENU_DIA' => 'Menú del día',
            'PORCION' => 'Porción',
            default => 'A la carta',
        };
    }
}
