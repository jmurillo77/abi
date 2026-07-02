<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'parroquia';
    protected $primaryKey = 'IdParroquia';

    protected $fillable = [
        'Nombre',
        'IdCiudad',
    ];

    public function canton()
    {
        return $this->belongsTo(Canton::class, 'IdCiudad', 'IdCiudad');
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'IdParroquia', 'IdParroquia');
    }

    public function provincia()
    {
        return $this->hasOneThrough(
            Provincia::class,
            Canton::class,
            'IdCiudad',
            'IdProvincia',
            'IdCiudad',
            'IdProvincia'
        );
    }
}
