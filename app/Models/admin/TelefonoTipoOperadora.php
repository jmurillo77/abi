<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelefonoTipoOperadora extends Model
{
    use HasFactory;
    protected $connection = 'matriz';
    protected $table = 'telefono_tipo_operadoras';
    protected $primaryKey = 'IdOperadora';

    protected $fillable = [
        'Nombre'
    ];
    public function numeros() 
    {
        return $this->hasMany(TelefonoMovil::class, 'IdOperadora', 'IdOperadora');
    }

}
