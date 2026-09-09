<?php

namespace App\Models\matriz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Continente extends Model
{
    use HasFactory;
    protected $connection = 'matriz';  
    protected $table = 'continentes';
    protected $primaryKey = 'IdContinente';

    protected $fillable = [
        'Nombre',
    ];

    public function paises()
    {
        return $this->hasMany(Pais::class, 'IdContinente', 'IdContinente');
    }

    public function countries()
    {
        return $this->paises();
    }

    public function provincias()
    {
        return $this->hasManyThrough(Provincia::class, Pais::class, 'IdContinente', 'IdPais', 'IdContinente', 'IdPais');
    }
}
