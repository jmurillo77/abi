@extends('adminlte::page')

@section('title', 'Permisos de submenús')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Submenús del rol: {{ $role->Nombre }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('menu') }}">Menú</a></li>
                <li class="breadcrumb-item"><a href="{{ route('configuracion.roles.index') }}">Roles</a></li>
                <li class="breadcrumb-item active">Submenús</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">Árbol de permisos por menú y submenú</h3>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="document.querySelectorAll('input[name=\'submenus[]\']').forEach(el => el.checked = true)">
            Seleccionar todo
        </button>
    </div>

    <div class="card-body">
        <form action="{{ route('configuracion.roles.submenus.update', $role->IdRol) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="tree-menu-list">
                @foreach($menus as $menu)
                    @if($menu->children->count())
                        <div class="card card-outline card-secondary mb-3 shadow-sm">
                            <div class="card-header bg-light">
                                <div class="form-check">
                                    <input
                                        class="form-check-input menu-check"
                                        type="checkbox"
                                        data-menu-id="{{ $menu->IdMenu }}"
                                        id="menu_{{ $menu->IdMenu }}"
                                        {{ $menu->children->every(fn($submenu) => $role->submenus->contains('IdSubMenu', $submenu->IdSubMenu)) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label font-weight-bold" for="menu_{{ $menu->IdMenu }}">
                                        @if($menu->Icono)
                                            <i class="{{ $menu->Icono }} mr-2"></i>
                                        @endif
                                        {{ $menu->Titulo }}
                                    </label>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    @foreach($menu->children as $submenu)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="border rounded p-2 h-100 bg-white tree-node">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input submenu-check"
                                                        type="checkbox"
                                                        name="submenus[]"
                                                        value="{{ $submenu->IdSubMenu }}"
                                                        data-menu-id="{{ $menu->IdMenu }}"
                                                        id="submenu_{{ $submenu->IdSubMenu }}"
                                                        {{ $role->submenus->contains('IdSubMenu', $submenu->IdSubMenu) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="submenu_{{ $submenu->IdSubMenu }}">
                                                        <strong>{{ $submenu->Titulo }}</strong>
                                                    </label>
                                                </div>
                                                <small class="text-muted d-block mt-1">{{ $submenu->Ruta ?? 'Sin ruta' }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Guardar permisos
                </button>
                <a href="{{ route('configuracion.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuChecks = document.querySelectorAll('.menu-check');

        menuChecks.forEach(function (menuCheck) {
            const menuId = menuCheck.dataset.menuId;
            const submenuChecks = document.querySelectorAll('.submenu-check[data-menu-id="' + menuId + '"]');

            const updateMenuState = function () {
                const checkedCount = [...submenuChecks].filter(input => input.checked).length;
                menuCheck.checked = checkedCount > 0 && checkedCount === submenuChecks.length;
                menuCheck.indeterminate = checkedCount > 0 && checkedCount < submenuChecks.length;
            };

            submenuChecks.forEach(function (subCheck) {
                subCheck.addEventListener('change', function () {
                    updateMenuState();
                });
            });

            menuCheck.addEventListener('change', function () {
                submenuChecks.forEach(function (subCheck) {
                    subCheck.checked = menuCheck.checked;
                });
            });

            updateMenuState();
        });
    });
</script>
@stop
