<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignWp extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'campaign_wp';
    protected $primaryKey = 'IdCampaignWP';

    public function telefono_movils(){
        return $this->belongsToMany(TelefonoMovil::class, 'jmurillo_bitscopy.campaign_wp', 'IdTelefonoMovil', 'IdTelefonoMovil');
    }
}
