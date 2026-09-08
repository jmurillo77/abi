<?php

namespace App\Models\matriz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\matriz\Ciudad;
use App\Models\matriz\Direccion;
use App\Models\matriz\Provincia;

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

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'IdCiudad', 'IdCiudad');
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'IdParroquia', 'IdParroquia');
    }

    public function provincia()
    {
        return $this->hasOneThrough(
            Provincia::class,
            Ciudad::class,
            'IdCiudad',
            'IdProvincia',
            'IdCiudad',
            'IdProvincia'
        );
    }
}
