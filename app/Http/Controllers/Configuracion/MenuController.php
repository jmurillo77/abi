<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\admin\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('parent')->orderBy('order')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::orderBy('Nombre')->get();
        return view('admin.menu.agregar', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Nombre' => 'required|string|max:255',
            'Url' => 'nullable|string|max:255',
            'Icono' => 'nullable|string|max:100',
            'parent_id' => 'nullable|integer|exists:matriz.menus,IdMenu',
            'order' => 'nullable|integer',
        ]);

        $data['parent_id'] = $request->parent_id ?: null;

        Menu::create($data);

        return redirect()->route('menus.index')
            ->with('success', 'Menú creado correctamente');
    }

    public function show(string $id)
    {
        $menu = Menu::with('parent', 'children')->findOrFail($id);
        return view('admin.menu.show', compact('menu'));
    }

    public function edit(string $id)
    {
        $menu = Menu::findOrFail($id);
        $parents = Menu::where('IdMenu', '!=', $id)->orderBy('Nombre')->get();
        return view('admin.menu.actualizar', compact('menu', 'parents'));
    }

    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        $data = $request->validate([
            'Nombre' => 'required|string|max:255',
            'Url' => 'nullable|string|max:255',
            'Icono' => 'nullable|string|max:100',
            'parent_id' => 'nullable|integer|exists:matriz.menus,IdMenu',
            'order' => 'nullable|integer',
        ]);

        $data['parent_id'] = $request->parent_id ?: null;

        $menu->update($data);

        return redirect()->route('menus.index')
            ->with('success', 'Menú actualizado correctamente');
    }

    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('menus.index')
            ->with('success', 'Menú eliminado correctamente');
    }

    public function editPermissions(string $userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $menus = Menu::with('children')->orderBy('order')->get();

        // load existing pivot data
        // pivot data will be accessed in the view through $user->menus

        return view('configuracion.menus.permissions', compact('user', 'menus'));
    }

    public function updatePermissions(\Illuminate\Http\Request $request, string $userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        $data = $request->input('permissions', []);

        $sync = [];
        foreach ($data as $menuId => $perms) {
            $sync[$menuId] = [
                'can_view' => isset($perms['can_view']) ? 1 : 0,
                'can_create' => isset($perms['can_create']) ? 1 : 0,
                'can_edit' => isset($perms['can_edit']) ? 1 : 0,
                'can_delete' => isset($perms['can_delete']) ? 1 : 0,
            ];
        }

        $user->menus()->sync($sync);

        return redirect()->route('menus.index')->with('success', 'Permisos actualizados');
    }
}
