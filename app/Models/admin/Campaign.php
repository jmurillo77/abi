<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'campaign';
    protected $primaryKey = 'IdCampaign';

    protected $fillable = [
        'Nombre',
        'Estado'
    ];

    public function TipoCampaign(){
        return $this->hasOne(Campaign_Type::class, 'IdCampaignType', 'IdCampaignType');
    }
}
