<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\admin\Menu;
use App\Models\admin\Submenu;
use App\Models\matriz\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'persona'])->orderBy('email')->get();

        return view('configuracion.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::query()
            ->orderBy('Nombre')
            ->get();

        $personas = Persona::query()
            ->orderBy('Nombres')
            ->orderBy('Apellidos')
            ->get();

        return view('configuracion.users.create', compact('roles', 'personas'));
    }

    public function store(Request $request)
    {
        $missingColumns = $this->missingUsersColumns();
        if (! empty($missingColumns)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La tabla users no está alineada. Faltan columnas: '.implode(', ', $missingColumns));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:negocio.users,email',
            'password' => 'required|string|min:8|confirmed',
            'IdRol' => 'nullable|integer|exists:negocio.roles,IdRol',
            'IdPersona' => 'nullable|integer|exists:matriz.personas,IdPersona',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'IdRol' => $validated['IdRol'] ?? null,
            'IdPersona' => $validated['IdPersona'] ?? null,
        ]);

        return redirect()->route('configuracion.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function show(string $userId)
    {
        $user = User::with(['role', 'persona'])->findOrFail($userId);

        return view('configuracion.users.show', compact('user'));
    }

    public function userEdit(string $userId)
    {
        $user = User::findOrFail($userId);

        $roles = Role::query()
            ->orderBy('Nombre')
            ->get();

        $personas = Persona::query()
            ->orderBy('Nombres')
            ->orderBy('Apellidos')
            ->get();

        return view('configuracion.users.edit', compact('user', 'roles', 'personas'));
    }

    public function userUpdate(Request $request, string $userId)
    {
        $user = User::findOrFail($userId);

        $missingColumns = $this->missingUsersColumns();
        if (! empty($missingColumns)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La tabla users no está alineada. Faltan columnas: '.implode(', ', $missingColumns));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:negocio.users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'IdRol' => 'nullable|integer|exists:negocio.roles,IdRol',
            'IdPersona' => 'nullable|integer|exists:matriz.personas,IdPersona',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'IdRol' => $validated['IdRol'] ?? null,
            'IdPersona' => $validated['IdPersona'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()->route('configuracion.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(string $userId)
    {
        $user = User::findOrFail($userId);

        if ((int) Auth::id() === (int) $user->id) {
            return redirect()->route('configuracion.users.index')
                ->with('error', 'No puede eliminar el usuario autenticado.');
        }

        $user->menus()->detach();
        $user->submenus()->detach();
        $user->delete();

        return redirect()->route('configuracion.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    public function edit(string $userId)
    {
        $user = User::with('role.menus', 'role.submenus', 'menus', 'submenus')->findOrFail($userId);

        $menus = Menu::with(['children' => function ($query) {
            $query->where('Activo', 1)->orderBy('Orden');
        }])->where('Activo', 1)->orderByRaw('COALESCE(Orden, 999999)')->get();

        $permittedMenuIds = $user->menus()
            ->wherePivot('can_view', true)
            ->pluck('menus.IdMenu')
            ->all();

        $permittedSubmenuIds = $user->submenus()
            ->wherePivot('can_view', true)
            ->pluck('submenus.IdSubMenu')
            ->all();

        $submenuActionPermissions = $user->submenus()
            ->get(['submenus.IdSubMenu'])
            ->mapWithKeys(function ($submenu) {
                return [
                    $submenu->IdSubMenu => [
                        'can_view' => (bool) ($submenu->pivot->can_view ?? false),
                        'can_create' => (bool) ($submenu->pivot->can_create ?? false),
                        'can_edit' => (bool) ($submenu->pivot->can_edit ?? false),
                        'can_delete' => (bool) ($submenu->pivot->can_delete ?? false),
                    ],
                ];
            })
            ->all();

        return view('configuracion.menus.permissions', compact('user', 'menus', 'permittedMenuIds', 'permittedSubmenuIds', 'submenuActionPermissions'));
    }

    public function update(Request $request, string $userId)
    {
        $user = User::findOrFail($userId);

        $menuIds = array_map('intval', $request->input('menu_permissions', []));

        $rawSubmenuPermissions = $request->input('submenu_permissions', []);
        $syncSubmenuPayload = [];

        foreach ($rawSubmenuPermissions as $submenuId => $permissionFlags) {
            $submenuId = (int) $submenuId;

            if ($submenuId <= 0 || ! is_array($permissionFlags)) {
                continue;
            }

            $canCreate = isset($permissionFlags['can_create']);
            $canEdit = isset($permissionFlags['can_edit']);
            $canDelete = isset($permissionFlags['can_delete']);
            $canView = isset($permissionFlags['can_view']) || $canCreate || $canEdit || $canDelete;

            if (! $canView && ! $canCreate && ! $canEdit && ! $canDelete) {
                continue;
            }

            $syncSubmenuPayload[$submenuId] = [
                'can_view' => $canView,
                'can_create' => $canCreate,
                'can_edit' => $canEdit,
                'can_delete' => $canDelete,
            ];
        }

        $submenuIds = array_keys($syncSubmenuPayload);

        $submenuMenuIds = Submenu::query()
            ->whereIn('IdSubMenu', $submenuIds)
            ->pluck('IdMenu')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->all();

        $menuIds = array_values(array_unique(array_merge($menuIds, $submenuMenuIds)));

        $syncPayload = collect($menuIds)
            ->mapWithKeys(fn ($id) => [$id => [
                'can_view' => true,
                'can_create' => false,
                'can_edit' => false,
                'can_delete' => false,
            ]])
            ->all();

        $user->menus()->sync($syncPayload);
        $user->submenus()->sync($syncSubmenuPayload);

        return redirect()->route('configuracion.users.index')->with('success', 'Permisos de menu y submenu por usuario actualizados correctamente');
    }

    public function submenusByMenu(string $userId, string $menuId)
    {
        User::findOrFail($userId);

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

    protected function missingUsersColumns(): array
    {
        $requiredColumns = ['name', 'email', 'password', 'IdRol', 'IdPersona'];

        return collect($requiredColumns)
            ->filter(fn ($column) => ! Schema::connection('negocio')->hasColumn('users', $column))
            ->values()
            ->all();
    }
}
