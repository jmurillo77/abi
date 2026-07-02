<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'direccion';
    protected $primaryKey = 'IdDireccion';

    protected $fillable = [
        'Nombre',
        'IdDireccionTipo',
        'IdParroquia',
    ];

    public function tipo()
    {
        return $this->belongsTo(DireccionTipo::class, 'IdDireccionTipo', 'IdDireccionTipo');
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'IdParroquia', 'IdParroquia');
    }

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'persona_direccion', 'IdDireccion', 'IdPersona');
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_direccion', 'IdDireccion', 'IdEmpresa');
    }
}
