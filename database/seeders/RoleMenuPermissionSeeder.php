<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleMenuPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::connection('negocio')->hasTable('permiso_menu_rol')) {
            return;
        }

        if (! Schema::connection('matriz')->hasTable('roles') || ! Schema::connection('matriz')->hasTable('menus')) {
            return;
        }

        $roleId = DB::connection('matriz')
            ->table('roles')
            ->where('IdRol', 1)
            ->value('IdRol');

        if ($roleId === null) {
            return;
        }

        $menuIds = DB::connection('matriz')
            ->table('menus')
            ->where('Activo', 1)
            ->pluck('IdMenu');

        foreach ($menuIds as $menuId) {
            DB::connection('negocio')->table('permiso_menu_rol')->updateOrInsert(
                [
                    'IdRol' => (int) $roleId,
                    'IdMenu' => (int) $menuId,
                ],
                []
            );
        }
    }
}
