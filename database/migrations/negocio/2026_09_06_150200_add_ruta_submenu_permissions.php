<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection('matriz')->hasTable('roles')) {
            return;
        }

        $menuId = DB::connection('matriz')
            ->table('menus')
            ->where('Ruta', 'ventas.dashboard')
            ->value('IdMenu');

        if ($menuId === null) {
            $menuId = DB::connection('matriz')
                ->table('menus')
                ->where('Titulo', 'Ventas')
                ->value('IdMenu');
        }

        if ($menuId === null) {
            return;
        }

        DB::connection('matriz')->table('submenus')->updateOrInsert(
            ['Ruta' => 'ventas.ruta.index'],
            [
                'IdMenu' => (int) $menuId,
                'Titulo' => 'Rutas',
                'Icono' => 'fas fa-route|#0ea5e9',
                'Orden' => 15,
                'Activo' => 1,
            ]
        );

        $submenuId = (int) DB::connection('matriz')->table('submenus')
            ->where('Ruta', 'ventas.ruta.index')
            ->value('IdSubMenu');

        if (! Schema::connection('negocio')->hasTable('permiso_menu_rol')) {
            return;
        }

        $roleIds = DB::connection('matriz')->table('roles')
            ->where('Activo', 1)
            ->pluck('IdRol');

        foreach ($roleIds as $roleId) {
            DB::connection('negocio')->table('permiso_menu_rol')->updateOrInsert(
                ['IdRol' => $roleId, 'IdMenu' => (int) $menuId],
                []
            );

            if (Schema::connection('negocio')->hasTable('permiso_submenu_rol')) {
                DB::connection('negocio')->table('permiso_submenu_rol')->updateOrInsert(
                    ['IdRol' => $roleId, 'IdSubMenu' => $submenuId],
                    []
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $submenuId = DB::connection('matriz')->table('submenus')
            ->where('Ruta', 'ventas.ruta.index')
            ->value('IdSubMenu');

        if ($submenuId) {
            DB::connection('negocio')->table('permiso_submenu_rol')
                ->where('IdSubMenu', $submenuId)
                ->delete();
        }

        DB::connection('matriz')->table('submenus')
            ->where('Ruta', 'ventas.ruta.index')
            ->delete();
    }
};
