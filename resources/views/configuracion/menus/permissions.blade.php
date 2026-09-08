@extends('adminlte::page')

@section('title', 'Permisos de Menús')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Permisos para {{ $user->name ?? $user->email }}</h1>
            <p class="text-muted mb-0">Rol actual: {{ $user->role->Nombre ?? 'Sin rol' }}</p>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.menus.index') }}">Menús</a></li>
                <li class="breadcrumb-item active">Permisos</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            Los permisos se asignan directamente al usuario en las tablas menu_user y submenu_user.
        </div>

        <form action="{{ route('configuracion.users.permissions.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Menú / Submenú</th>
                        <th class="text-center">Ver</th>
                        <th class="text-center">Crear</th>
                        <th class="text-center">Editar</th>
                        <th class="text-center">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                        @if($menu->children->count())
                            <tr class="table-secondary">
                                <td>
                                    <strong>{{ $menu->Titulo }}</strong>
                                    <span class="text-muted">({{ $menu->children->count() }} submenus)</span>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="menu_permissions[]" value="{{ $menu->IdMenu }}" data-menu-id="{{ $menu->IdMenu }}" {{ in_array($menu->IdMenu, $permittedMenuIds ?? []) ? 'checked' : '' }}>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @foreach($menu->children as $child)
                                <tr class="submenu-row" data-parent-menu-row-id="{{ $menu->IdMenu }}" style="display: none;">
                                    <td class="ps-4">- {{ $child->Titulo }}</td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            name="submenu_permissions[{{ $child->IdSubMenu }}][can_view]"
                                            value="1"
                                            class="submenu-permission"
                                            data-action="can_view"
                                            data-submenu-id="{{ $child->IdSubMenu }}"
                                            data-parent-menu-id="{{ $menu->IdMenu }}"
                                            {{ (bool) ($submenuActionPermissions[$child->IdSubMenu]['can_view'] ?? in_array($child->IdSubMenu, $permittedSubmenuIds ?? [])) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            name="submenu_permissions[{{ $child->IdSubMenu }}][can_create]"
                                            value="1"
                                            class="submenu-permission"
                                            data-action="can_create"
                                            data-submenu-id="{{ $child->IdSubMenu }}"
                                            data-parent-menu-id="{{ $menu->IdMenu }}"
                                            {{ (bool) ($submenuActionPermissions[$child->IdSubMenu]['can_create'] ?? false) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            name="submenu_permissions[{{ $child->IdSubMenu }}][can_edit]"
                                            value="1"
                                            class="submenu-permission"
                                            data-action="can_edit"
                                            data-submenu-id="{{ $child->IdSubMenu }}"
                                            data-parent-menu-id="{{ $menu->IdMenu }}"
                                            {{ (bool) ($submenuActionPermissions[$child->IdSubMenu]['can_edit'] ?? false) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <input
                                            type="checkbox"
                                            name="submenu_permissions[{{ $child->IdSubMenu }}][can_delete]"
                                            value="1"
                                            class="submenu-permission"
                                            data-action="can_delete"
                                            data-submenu-id="{{ $child->IdSubMenu }}"
                                            data-parent-menu-id="{{ $menu->IdMenu }}"
                                            {{ (bool) ($submenuActionPermissions[$child->IdSubMenu]['can_delete'] ?? false) ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td><strong>{{ $menu->Titulo }}</strong></td>
                                <td class="text-center">
                                    <input type="checkbox" name="menu_permissions[]" value="{{ $menu->IdMenu }}" data-menu-id="{{ $menu->IdMenu }}" {{ in_array($menu->IdMenu, $permittedMenuIds ?? []) ? 'checked' : '' }}>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <button class="btn btn-primary">Guardar permisos</button>
            <a href="{{ route('configuracion.users.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuCheckboxes = document.querySelectorAll('input[name="menu_permissions[]"][data-menu-id]');

        const syncChildrenVisibility = (menuId) => {
            const parentMenu = document.querySelector('input[name="menu_permissions[]"][data-menu-id="' + menuId + '"]');
            const childrenRows = document.querySelectorAll('tr.submenu-row[data-parent-menu-row-id="' + menuId + '"]');

            if (!parentMenu || childrenRows.length === 0) {
                return;
            }

            childrenRows.forEach((row) => {
                row.style.display = parentMenu.checked ? '' : 'none';
            });
        };

        const syncParentMenu = (menuId) => {
            const parentMenu = document.querySelector('input[name="menu_permissions[]"][data-menu-id="' + menuId + '"]');
            const children = document.querySelectorAll('input.submenu-permission[data-action="can_view"][data-parent-menu-id="' + menuId + '"]');

            if (!parentMenu || children.length === 0) {
                return;
            }

            const checkedChildren = Array.from(children).filter((child) => child.checked).length;
            parentMenu.checked = checkedChildren > 0;
            parentMenu.indeterminate = checkedChildren > 0 && checkedChildren < children.length;
        };

        menuCheckboxes.forEach((menuCheckbox) => {
            const menuId = menuCheckbox.getAttribute('data-menu-id');
            const children = document.querySelectorAll('input.submenu-permission[data-parent-menu-id="' + menuId + '"]');

            menuCheckbox.addEventListener('change', function () {
                menuCheckbox.indeterminate = false;
                children.forEach((child) => {
                    if (child.getAttribute('data-action') === 'can_view') {
                        child.checked = menuCheckbox.checked;
                    }
                });

                syncChildrenVisibility(menuId);
            });

            children.forEach((child) => {
                child.addEventListener('change', function () {
                    if (['can_create', 'can_edit', 'can_delete'].includes(child.getAttribute('data-action')) && child.checked) {
                        const submenuId = child.getAttribute('data-submenu-id');
                        const viewCheckbox = document.querySelector('input.submenu-permission[data-action="can_view"][data-submenu-id="' + submenuId + '"]');

                        if (viewCheckbox) {
                            viewCheckbox.checked = true;
                        }
                    }

                    syncParentMenu(menuId);
                    syncChildrenVisibility(menuId);
                });
            });

            syncParentMenu(menuId);
            syncChildrenVisibility(menuId);
        });
    });
</script>
@endsection

