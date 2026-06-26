<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\admin\Menu;
use App\Models\admin\Submenu;
use Illuminate\Http\Request;

class SubmenuController extends Controller
{
    public function index()
    {
        $submenus = Submenu::with('menu')
            ->orderByRaw('COALESCE(IdMenu, 999999)')
            ->orderByRaw('COALESCE(Orden, 999999)')
            ->get();

        return view('admin.submenu.index', compact('submenus'));
    }

    public function create()
    {
        $menus = Menu::where('Activo', 1)->orderBy('Titulo')->get();

        return view('admin.submenu.agregar', compact('menus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'IdMenu' => 'required|integer|exists:matriz.menus,IdMenu',
            'Titulo' => 'required|string|max:100',
            'Ruta' => 'nullable|string|max:250',
            'Icono' => 'nullable|string|max:120',
            'Orden' => 'nullable|integer',
            'Activo' => 'nullable|boolean',
        ]);

        $data['Activo'] = (int) ($request->boolean('Activo', true));

        Submenu::create($data);

        return redirect()->route('configuracion.submenus.index')
            ->with('success', 'Submenú creado correctamente.');
    }

    public function show(string $id)
    {
        $submenu = Submenu::with('menu')->findOrFail($id);

        return view('admin.submenu.show', compact('submenu'));
    }

    public function edit(string $id)
    {
        $submenu = Submenu::findOrFail($id);
        $menus = Menu::where('Activo', 1)->orderBy('Titulo')->get();

        return view('admin.submenu.actualizar', compact('submenu', 'menus'));
    }

    public function update(Request $request, string $id)
    {
        $submenu = Submenu::findOrFail($id);

        $data = $request->validate([
            'IdMenu' => 'required|integer|exists:matriz.menus,IdMenu',
            'Titulo' => 'required|string|max:100',
            'Ruta' => 'nullable|string|max:250',
            'Icono' => 'nullable|string|max:120',
            'Orden' => 'nullable|integer',
            'Activo' => 'nullable|boolean',
        ]);

        $data['Activo'] = (int) ($request->boolean('Activo', true));

        $submenu->update($data);

        return redirect()->route('configuracion.submenus.index')
            ->with('success', 'Submenú actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $submenu = Submenu::findOrFail($id);
        $submenu->delete();

        return redirect()->route('configuracion.submenus.index')
            ->with('success', 'Submenú eliminado correctamente.');
    }
}
