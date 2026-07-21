<?php

namespace App\Models\matriz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'ciudad';
    protected $primaryKey = 'IdCiudad';

    protected $fillable = [
        'Nombre',
        'IdProvincia',
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'IdProvincia', 'IdProvincia');
    }

    public function parroquias()
    {
        return $this->hasMany(Parroquia::class, 'IdCiudad', 'IdCiudad');
    }
}
