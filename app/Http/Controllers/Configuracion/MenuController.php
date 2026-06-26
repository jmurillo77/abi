<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\admin\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('parent')->orderBy('Orden')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $parents = Menu::orderBy('Titulo')->get();
        return view('admin.menu.agregar', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'Titulo' => 'required|string|max:255',
            'Ruta' => 'nullable|string|max:255',
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
            'Titulo' => 'required|string|max:255',
            'Ruta' => 'nullable|string|max:255',
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
        $user = \App\Models\User::with('role.submenus', 'role.menus')->findOrFail($userId);
        abort_if(! $user->role, 422, 'El usuario no tiene un rol asignado.');

        $menus = Menu::with(['children' => function ($query) {
            $query->where('Activo', 1)->orderBy('Orden');
        }])->where('Activo', 1)->orderByRaw('COALESCE(Orden, 999999)')->get();
        $permittedMenuIds = $user->role->menus()->pluck('menus.IdMenu')->all();
        $permittedSubmenuIds = $user->permittedSubmenus()->pluck('IdSubMenu')->all();

        return view('configuracion.menus.permissions', compact('user', 'menus', 'permittedMenuIds', 'permittedSubmenuIds'));
    }

    public function updatePermissions(\Illuminate\Http\Request $request, string $userId)
    {
        $user = \App\Models\User::with('role')->findOrFail($userId);
        abort_if(! $user->role, 422, 'El usuario no tiene un rol asignado.');

        $menuIds = array_map('intval', $request->input('menu_permissions', []));
        $submenuIds = array_map('intval', $request->input('permissions', []));

        $user->role->menus()->sync($menuIds);
        $user->role->submenus()->sync($submenuIds);

        return redirect()->route('menus.index')->with('success', 'Permisos del rol actualizados');
    }
}
