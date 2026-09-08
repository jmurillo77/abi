<?php

namespace App\Models\matriz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionTipo extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'direccion_tipo';
    protected $primaryKey = 'IdDireccionTipo';

    protected $fillable = [
        'Nombre',
    ];

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'IdDireccionTipo', 'IdDireccionTipo');
    }
}
