<?php

namespace App\Models\matriz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\matriz\TelefonoMovil;
use App\Models\matriz\Correo;
use App\Models\matriz\Direccion;

class Empresa extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
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
    public function direcciones(){
        return $this->belongsToMany(Direccion::class, 'empresa_direccion', 'IdEmpresa', 'IdDireccion');
    }
}
