<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
