<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelefonoMovil extends Model
{
    use HasFactory;
    protected $table = 'telefono_movils';
    protected $primaryKey = 'IdTelefonoMovil';

    protected $fillable = [
        'Numero',
        'IdOperadora',
        'PhoneValido',
        'WhatsappValido'
    ];

    public function operadora(){
        return $this->belongsTo(TelefonoTipoOperadora::class);
    }
}
