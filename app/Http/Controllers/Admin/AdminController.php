<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\Correo;
use App\Models\admin\Empresa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $TotalEmpresa = Empresa::count();
        $TotalPersona = Persona::count();
        $TotalTelefono = TelefonoMovil::count();
        $TotalCorreo = Correo::count();
        return view('admin.admin', compact('TotalEmpresa','TotalPersona', 'TotalTelefono', 'TotalCorreo'));
    }
    public function menu()
    {
        $TotalPersona = Persona::count();
        return view('admin.menu');
    }
}
