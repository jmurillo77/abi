<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignWp extends Model
{
    use HasFactory;
    protected $table = 'campaign_wp';
    protected $primaryKey = 'IdCampaignWP';
}
