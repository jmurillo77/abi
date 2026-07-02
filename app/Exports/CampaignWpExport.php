<?php

namespace App\Exports;

use App\Models\admin\CampaignWp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class CampaignWpExport implements FromCollection
{
    public function __construct(private readonly ?int $campaignId = null)
    {
    }

    public function collection(): Collection
    {
        $query = CampaignWp::query();

        if (!empty($this->campaignId)) {
            $query->where('IdCampaign', $this->campaignId);
        }

        return $query->get();
    }
}
