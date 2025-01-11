<?php

namespace App\Exports;

use App\Models\admin\CampaignWp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class CampaignWpExport implements FromView
{
    public function view(): View{
        return view('export', [
            'Users' -> User::all()
        ]);
    }
}
