<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $table = 'campaign';
    protected $primaryKey = 'IdCampaign';

    protected $fillable = [
        'Nombre',
        'Estado'
    ];

    public function TipoCampaign(){
        return $this->hasOne(Campaign_Type::class, 'IdCampaignType', 'IdCampaignType');
    }
    public function campaign_numeros()    {
        return $this->hasMany(TelefonoMovil::class, 'IdOperadora', 'IdOperadora');
    }
}
