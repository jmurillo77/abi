<?php

namespace app\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\Correo;
use App\Models\admin\Empresa;
use App\Models\admin\Campaign;

class AdminController extends Controller
{
    public function index()
    {
        $TotalEmpresa = Empresa::count();
        $TotalPersona = Persona::count();
        $TotalTelefono = TelefonoMovil::count();
        $TotalCorreo = Correo::count();
        $TotalCampaign = Campaign::count();
        return view('admin.admin', compact('TotalEmpresa','TotalPersona', 'TotalTelefono', 'TotalCorreo', 'TotalCampaign'));
    }
    public function menu()
    {
        return view('admin.menu');
    }
}
