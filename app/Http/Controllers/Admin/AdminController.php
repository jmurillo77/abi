<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\admin\Campaign;
use App\Models\admin\Menu;
use App\Models\matriz\Correo;
use App\Models\matriz\Empresa;
use App\Models\matriz\Persona;
use App\Models\matriz\TelefonoMovil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class AdminController extends Controller
{
    public function index()
    {
        $TotalEmpresa = Empresa::count();
        $TotalPersona = Persona::count();
        $TotalTelefono = TelefonoMovil::count();
        $TotalCorreo = Correo::count();
        $TotalCampaign = Campaign::count();
        return view('admin.admin', compact('TotalEmpresa','TotalPersona', 'TotalTelefono', 'TotalCorreo', 'TotalCampaign'));
    }
    public function menu()
    {
        $authId = Auth::id();

        if (! $authId) {
            abort(403);
        }

        $user = User::with(['role.submenus', 'role.menus', 'menus', 'submenus', 'persona'])->findOrFail($authId);

        $visibleMenuIds = $user->permittedMenus()->pluck('IdMenu')->all();

        $permittedSubmenus = $user->permittedSubmenus();
        $visibleSubmenuIds = $permittedSubmenus->pluck('IdSubMenu')->all();
        $legacyVisibleMenuIds = $permittedSubmenus->pluck('IdMenu')->unique()->all();
        $hasExplicitMenuPermissions = ! empty($visibleMenuIds);

        $menus = Menu::with(['children' => function ($query) {
                $query->where('Activo', 1)->orderBy('Orden');
            }])
            ->where('Activo', 1)
            ->orderByRaw('COALESCE(Orden, 999999)')
            ->get()
            ->map(function ($menu) use ($visibleMenuIds, $visibleSubmenuIds, $legacyVisibleMenuIds, $hasExplicitMenuPermissions) {
                $menu->link = $this->resolveMenuLink($menu->Ruta);
                $menu->has_menu_access = $hasExplicitMenuPermissions
                    ? in_array($menu->IdMenu, $visibleMenuIds)
                    : in_array($menu->IdMenu, $legacyVisibleMenuIds);
                $menu->visible_children = $menu->children
                    ->filter(function ($child) use ($visibleSubmenuIds) {
                        return in_array($child->IdSubMenu, $visibleSubmenuIds);
                    })
                    ->map(function ($child) {
                        $child->link = $this->resolveMenuLink($child->Ruta);

                        return $child;
                    })
                    ->values();

                return $menu;
            })
            ->filter(function ($menu) {
                return $menu->has_menu_access;
            })
            ->values();

        return view('admin.menu', compact('menus', 'user'));
    }

    protected function resolveMenuLink(?string $routeOrPath): string
    {
        if (! $routeOrPath) {
            return '#';
        }

        if (Route::has($routeOrPath)) {
            return route($routeOrPath);
        }

        return URL::to($routeOrPath);
    }
}
