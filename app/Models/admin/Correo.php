<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    use HasFactory;
    protected $table = 'correos';
    protected $primaryKey = 'IdCorreo';

    protected $fillable = [
        'Correo',
        'Valido'
    ];

    public function personas(){
        return $this->belongsToMany(Persona::class, 'persona_correos', 'IdCorreo', 'IdCorreo');
    }
}
