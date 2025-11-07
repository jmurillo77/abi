<?php

namespace App\Http\Controllers\Contacto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Empresa;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\Correo;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $TotalEmpresa = Empresa::count();
        $TotalPersona = Persona::count();
        $TotalTelefono = TelefonoMovil::count();
        $TotalCorreo = Correo::count();
        return view('contacto.dashboard', compact('TotalEmpresa','TotalPersona', 'TotalTelefono', 'TotalCorreo'));
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
    public function show(string $id)
    {
        //
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
}
