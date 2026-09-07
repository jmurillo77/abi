<?php

namespace App\Models\negocio;

use App\Models\Cliente;
use App\Models\matriz\Direccion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $connection = 'negocio';
    protected $table = 'pedidos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'IdCliente',
        'IdDireccion',
        'IdRuta',
        'Fecha',
        'Estado',
        'Total',
        'TipoEnvio',
        'CostoEnvio',
        'Observaciones',
        'cUser',
        'uUser',
    ];

    protected $casts = [
        'Fecha' => 'datetime',
        'Total' => 'decimal:2',
        'CostoEnvio' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'IdCliente', 'id');
    }

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'IdDireccion', 'IdDireccion');
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'IdRuta');
    }

    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class, 'IdPedido');
    }

    public function getTipoEnvioLabelAttribute(): string
    {
        return match ($this->TipoEnvio) {
            'GLOBAL' => 'Envío global',
            'POR_ITEM' => 'Envío por producto',
            default => 'Sin envío',
        };
    }
}
