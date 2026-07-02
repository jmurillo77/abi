<?php

namespace app\Http\Controllers\admin;

use App\Exports\CampaignWpExport;
use App\Http\Controllers\Controller;
use App\Models\admin\Campaign;
use App\Models\admin\CampaignWp;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campaigns = Campaign::with('TipoCampaign')->orderByDesc('IdCampaign')->get();
        return view('admin.campaign.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $IdCampaign)
    {
        $campaign = Campaign::find($IdCampaign);
        $campaign_wp = CampaignWp::where('IdCampaign',$IdCampaign)->get();
        return view('admin.campaign.show', compact('campaign','campaign_wp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Exporta Numero de Campaña
     */
    public function exporta(string $id)
    {
        return Excel::download(new CampaignWpExport((int) $id), 'numeros.xlsx');
    }
}
