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

    public function query(){
        return CampaignWp::query();
    }
}
