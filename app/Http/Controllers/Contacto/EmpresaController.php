<?php

namespace app\Http\Controllers\contacto;

use App\Http\Controllers\Controller;
use App\Models\admin\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::all();
        return view('contacto.empresa.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacto.empresa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Empresa $empresa)
    {
        $empresa = Empresa::find($empresa);
        return view('contacto.empresa.show', compact('empresa'));
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
