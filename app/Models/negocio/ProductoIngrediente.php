<?php

namespace App\Models\negocio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoIngrediente extends Model
{
    use HasFactory;

    protected $connection = 'negocio';
    protected $table = 'producto_ingredientes';

    protected $fillable = [
        'IdProducto',
        'IdInsumo',
        'Cantidad',
        'UnidadMedida',
    ];

    protected $casts = [
        'Cantidad' => 'decimal:3',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'IdProducto', 'IdProducto');
    }

    public function insumo()
    {
        return $this->belongsTo(Producto::class, 'IdInsumo', 'IdProducto');
    }

    /**
     * Costo estimado de esta línea, convirtiendo unidades si es necesario.
     * Devuelve null si las unidades no son compatibles (para no mostrar un costo incorrecto).
     */
    public function getCostoEstimadoAttribute(): ?float
    {
        if (! $this->insumo || $this->insumo->CostoUnitario === null) {
            return null;
        }

        $cantidadConvertida = Producto::convertirCantidad(
            (float) $this->Cantidad,
            $this->UnidadMedida,
            $this->insumo->UnidadMedida
        );

        if ($cantidadConvertida === null) {
            return null;
        }

        return round($cantidadConvertida * (float) $this->insumo->CostoUnitario, 4);
    }
}
