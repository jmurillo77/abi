<?php

namespace App\Models\negocio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $connection = 'negocio';
    protected $table = 'rutas';

    protected $fillable = [
        'Nombre',
        'Fecha',
        'Estado',
        'Observaciones',
        'cUser',
        'uUser',
    ];

    protected $casts = [
        'Fecha' => 'date',
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'IdRuta');
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->Estado) {
            'EN_CURSO' => 'En curso',
            'FINALIZADA' => 'Finalizada',
            'CANCELADA' => 'Cancelada',
            default => 'Planificada',
        };
    }
}
