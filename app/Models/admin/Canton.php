<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canton extends Model
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
