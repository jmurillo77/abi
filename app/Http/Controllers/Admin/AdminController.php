<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\Correo;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $TotalPersona = Persona::count();
        $TotalTelefono = TelefonoMovil::count();
        $TotalCorreo = Correo::count();
        return view('admin.admin', compact('TotalPersona', 'TotalTelefono', 'TotalCorreo'));
    }
    public function menu()
    {
        $TotalPersona = Persona::count();
        return view('admin.menu');
    }
}
