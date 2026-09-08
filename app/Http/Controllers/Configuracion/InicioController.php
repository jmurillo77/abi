<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Menu;
use App\Models\User;

class InicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $TotalMenu = Menu::count();

        $TotalUsuarios = User::count();
        $UsuariosVerificados = User::whereNotNull('email_verified_at')->count();
        $UsuariosConRol = User::whereNotNull('IdRol')->count();
        $UsuariosConPersona = User::whereNotNull('IdPersona')->count();

        return view('configuracion.dashboard', compact(
            'TotalMenu',
            'TotalUsuarios',
            'UsuariosVerificados',
            'UsuariosConRol',
            'UsuariosConPersona'
        ));
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
