<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelefonoMovil extends Model
{
    use HasFactory;
    protected $connection = 'matriz';
    protected $table = 'telefono_movils';
    protected $primaryKey = 'IdTelefonoMovil';

    protected $fillable = [
        'Numero',
        'IdOperadora',
        'PhoneValido',
        'WhatsappValido'
    ];

    public function operadora()
    {
        return $this->hasOne(TelefonoTipoOperadora::class, 'IdOperadora', 'IdOperadora');
    }
    public function personas(){
        return $this->belongsToMany(Persona::class, 'persona_telefono_movils', 'IdTelefonoMovil', 'IdTelefonoMovil');
    }
    public function campaignwp(){
        return $this->belongsToMany(CampaignWp::class, 'campaign_wp', 'IdCampaign', 'IdCampaign');
    }
}
