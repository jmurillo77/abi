<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\matriz\Menu;
use App\Models\matriz\Submenu;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::query()->orderBy('Nombre')->get();

        return view('configuracion.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('configuracion.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Nombre' => ['required', 'string', 'max:100', 'unique:matriz.roles,Nombre'],
            'Descripcion' => ['nullable', 'string'],
            'Activo' => ['nullable', 'boolean'],
        ]);

        Role::create([
            'Nombre' => $validated['Nombre'],
            'Descripcion' => $validated['Descripcion'] ?? null,
            'Activo' => $validated['Activo'] ?? true,
        ]);

        return redirect()->route('configuracion.roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    public function show(string $id)
    {
        $role = Role::findOrFail($id);

        return view('configuracion.roles.show', compact('role'));
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);

        return view('configuracion.roles.edit', compact('role'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'Nombre' => ['required', 'string', 'max:100', 'unique:matriz.roles,Nombre,'.$role->IdRol.',IdRol'],
            'Descripcion' => ['nullable', 'string'],
            'Activo' => ['nullable', 'boolean'],
        ]);

        $role->update([
            'Nombre' => $validated['Nombre'],
            'Descripcion' => $validated['Descripcion'] ?? null,
            'Activo' => $validated['Activo'] ?? true,
        ]);

        return redirect()->route('configuracion.roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('configuracion.roles.index')
            ->with('success', 'Rol eliminado correctamente.');
    }

    public function submenus(string $id)
    {
        $role = Role::with('submenus')->findOrFail($id);

        $menus = Menu::query()
            ->with(['children' => function ($query) {
                $query->where('Activo', 1)
                    ->orderBy('Orden')
                    ->orderBy('Titulo');
            }])
            ->where('Activo', 1)
            ->orderByRaw('COALESCE(Orden, 999999)')
            ->get();

        return view('configuracion.roles.submenus', compact('role', 'menus'));
    }

    public function updateSubmenus(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'submenus' => ['nullable', 'array'],
            'submenus.*' => ['integer', 'exists:matriz.submenus,IdSubMenu'],
        ]);

        $submenuIds = collect($validated['submenus'] ?? [])
            ->map(fn ($submenuId) => (int) $submenuId)
            ->unique()
            ->values()
            ->all();

        $role->submenus()->sync($submenuIds);

        return redirect()->route('configuracion.roles.index')
            ->with('success', 'Submenús del rol actualizados correctamente.');
    }
}