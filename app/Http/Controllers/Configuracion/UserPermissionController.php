<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\admin\Menu;
use App\Models\admin\Submenu;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('email')->get();

        return view('configuracion.users.index', compact('users'));
    }

    public function edit(string $userId)
    {
        $user = User::with('role.submenus', 'role.menus')->findOrFail($userId);
        abort_if(! $user->role, 422, 'El usuario no tiene un rol asignado.');

        $menus = Menu::with(['children' => function ($query) {
            $query->where('Activo', 1)->orderBy('Orden');
        }])->where('Activo', 1)->orderByRaw('COALESCE(Orden, 999999)')->get();
        $permittedMenuIds = $user->role->menus()->pluck('menus.IdMenu')->all();
        $permittedSubmenuIds = $user->permittedSubmenus()->pluck('IdSubMenu')->all();

        return view('configuracion.menus.permissions', compact('user', 'menus', 'permittedMenuIds', 'permittedSubmenuIds'));
    }

    public function update(Request $request, string $userId)
    {
        $user = User::with('role')->findOrFail($userId);
        abort_if(! $user->role, 422, 'El usuario no tiene un rol asignado.');

        $menuIds = array_map('intval', $request->input('menu_permissions', []));
        $submenuIds = array_map('intval', $request->input('permissions', []));

        $user->role->menus()->sync($menuIds);
        $user->role->submenus()->sync($submenuIds);

        return redirect()->route('users.index')->with('success', 'Permisos del rol actualizados correctamente');
    }

    public function submenusByMenu(string $userId, string $menuId)
    {
        $user = User::with('role')->findOrFail($userId);
        abort_if(! $user->role, 422, 'El usuario no tiene un rol asignado.');

        $submenus = Submenu::query()
            ->where('Activo', 1)
            ->where('IdMenu', (int) $menuId)
            ->orderBy('Orden')
            ->get(['IdSubMenu', 'IdMenu', 'Titulo', 'Ruta', 'Icono']);

        return response()->json([
            'menu_id' => (int) $menuId,
            'submenus' => $submenus,
        ]);
    }
}
