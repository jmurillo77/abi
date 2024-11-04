<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';
    protected $primaryKey = 'IdEmpresa';

    protected $fillable = [
        'RUC',
        'RazonSocial'
    ];

    public function telefono_movils(){
        return $this->belongsToMany(TelefonoMovil::class, 'empresa_telefono_movils', 'IdEmpresa', 'IdTelefonoMovil');
    }
    public function correos(){
        return $this->belongsToMany(Correo::class, 'empresa_correos', 'IdEmpresa', 'IdCorreo');
    }
}
