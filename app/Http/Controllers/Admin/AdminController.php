<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\Persona;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $TotalPersona = Persona::count();
        return view('admin.admin', compact('TotalPersona'));
    }
    public function menu()
    {
        $TotalPersona = Persona::count();
        return view('admin.menu');
    }
}
