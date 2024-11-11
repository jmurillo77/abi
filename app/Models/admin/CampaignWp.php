<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CampaignWp extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'campaign_wp';
    protected $primaryKey = 'IdCampaignWP';

    public function telefono_movils(){
        $MatrizDB = DB::connection('mysql')->getDatabaseName();
        return $this->belongsToMany(TelefonoMovil::class, "$MatrizDB.campaign_wp", 'IdTelefonoMovil', 'IdTelefonoMovil');
    }
}
