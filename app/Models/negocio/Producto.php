<?php

namespace App\Models\negocio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $connection = 'negocio';
    protected $table = 'producto';
    protected $primaryKey = 'IdProducto';

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'TipoProducto',
        'UnidadMedida',
        'CostoUnitario',
        'StockActual',
        'UsaReceta',
        'UsaMenu',
        'TipoMenu',
        'Activo',
    ];
}
