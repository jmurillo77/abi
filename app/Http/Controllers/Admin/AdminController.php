<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $TotalPersona = Persona::count();
        $TotalTelefono = TelefonoMovil::count();
        return view('admin.admin', compact('TotalPersona', 'TotalTelefono'));
    }
    public function menu()
    {
        $TotalPersona = Persona::count();
        return view('admin.menu');
    }
}
