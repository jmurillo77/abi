<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    use HasFactory;
    protected $connection = 'matriz';
    protected $table = 'correos';
    protected $primaryKey = 'IdCorreo';

    protected $fillable = [
        'Correo',
        'Valido'
    ];

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'persona_correos', 'IdCorreo', 'IdPersona');
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_correos', 'IdCorreo', 'IdEmpresa');
    }
}
