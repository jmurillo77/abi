<?php

namespace App\Exports;

use App\Models\admin\CampaignWp;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class CampaignWpExport implements FromQuery
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */

    public function campaign(string $IdCampaign)
    {
        $this->IdCampaign = $IdCampaign;
        return $this;
    }

    public function query()
    {
        return CampaignWp::query()->where('IdCampaign',$this->IdCampaign);
    }
}
