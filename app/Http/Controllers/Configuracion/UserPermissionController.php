<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\admin\Menu;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('configuracion.users.index', compact('users'));
    }

    public function edit(string $userId)
    {
        $user = User::findOrFail($userId);
        $menus = Menu::with('children')->orderBy('order')->get();

        return view('configuracion.menus.permissions', compact('user', 'menus'));
    }

    public function update(Request $request, string $userId)
    {
        $user = User::findOrFail($userId);
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

        return redirect()->route('users.index')->with('success', 'Permisos actualizados correctamente');
    }
}
