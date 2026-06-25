<?php

namespace app\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\admin\Menu;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\Correo;
use App\Models\admin\Empresa;
use App\Models\admin\Campaign;
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

        $user = User::with(['role.submenus', 'persona'])->findOrFail($authId);

        $visibleSubmenuIds = $user->permittedSubmenus()
            ->pluck('IdSubMenu')
            ->all();

        $menus = Menu::with(['children' => function ($query) {
                $query->where('Activo', 1)->orderBy('Orden');
            }])
            ->where('Activo', 1)
            ->orderByRaw('COALESCE(Orden, 999999)')
            ->get()
            ->map(function ($menu) use ($visibleSubmenuIds) {
                $menu->link = $this->resolveMenuLink($menu->Ruta);
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
                return $menu->visible_children->isNotEmpty();
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
