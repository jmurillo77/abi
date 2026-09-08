<?php

namespace App\Models\matriz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\matriz\Continente;

class Pais extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'pais';
    protected $primaryKey = 'IdPais';

    protected $fillable = [
        'Nombre',
        'IdContinente',
    ];

    public function continente()
    {
        return $this->belongsTo(Continente::class, 'IdContinente', 'IdContinente');
    }

    public function provincias()
    {
        return $this->hasMany(Provincia::class, 'IdPais', 'IdPais');
    }
}
