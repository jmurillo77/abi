<?php

namespace app\Models\matriz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign_type extends Model
{
    use HasFactory;
    protected $table = 'campaign_type';
    protected $primaryKey = 'IdCampaignType';
}
