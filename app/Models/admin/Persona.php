<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'personas';
    protected $primaryKey = 'IdPersona';

    protected $fillable = [
        'DNI',
        'Nombres',
        'Apellidos',
        'FechaNacimiento'
    ];

    protected function casts(): array{
        return [
            'deleted_at' => 'datetime',
            'Eliminado' => 'boolean'
        ];
    }

    public function telefono_movils(){
        return $this->belongsToMany(TelefonoMovil::class, 'persona_telefono_movils', 'IdPersona', 'IdTelefonoMovil');
    }
    public function correos(){
        return $this->belongsToMany(Correo::class, 'persona_correos', 'IdPersona', 'IdCorreo');
    }

}
