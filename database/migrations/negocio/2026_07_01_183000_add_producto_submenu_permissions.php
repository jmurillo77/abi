<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::connection('matriz')->table('submenus')->updateOrInsert(
            ['Ruta' => 'ventas.producto.index'],
            [
                'IdMenu' => 2,
                'Titulo' => 'Productos',
                'Icono' => 'fas fa-utensils|#16a34a',
                'Orden' => 10,
                'Activo' => 1,
            ]
        );

        $submenuId = (int) DB::connection('matriz')->table('submenus')
            ->where('Ruta', 'ventas.producto.index')
            ->value('IdSubMenu');

        $roleIds = DB::connection('negocio')->table('roles')
            ->where('Activo', 1)
            ->pluck('IdRol');

        foreach ($roleIds as $roleId) {
            DB::connection('negocio')->table('permiso_menu_rol')->updateOrInsert(
                ['IdRol' => $roleId, 'IdMenu' => 2],
                []
            );

            DB::connection('negocio')->table('permiso_submenu_rol')->updateOrInsert(
                ['IdRol' => $roleId, 'IdSubMenu' => $submenuId],
                []
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $submenuId = DB::connection('matriz')->table('submenus')
            ->where('Ruta', 'ventas.producto.index')
            ->value('IdSubMenu');

        if ($submenuId) {
            DB::connection('negocio')->table('permiso_submenu_rol')
                ->where('IdSubMenu', $submenuId)
                ->delete();
        }

        DB::connection('matriz')->table('submenus')
            ->where('Ruta', 'ventas.producto.index')
            ->delete();
    }
};
