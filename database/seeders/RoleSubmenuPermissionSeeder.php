<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSubmenuPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::connection('negocio')->hasTable('permiso_submenu_rol')) {
            return;
        }

        if (! Schema::connection('matriz')->hasTable('roles') || ! Schema::connection('matriz')->hasTable('submenus')) {
            return;
        }

        $roleId = DB::connection('matriz')
            ->table('roles')
            ->where('IdRol', 1)
            ->value('IdRol');

        if ($roleId === null) {
            return;
        }

        $submenuIds = DB::connection('matriz')
            ->table('submenus')
            ->where('Activo', 1)
            ->pluck('IdSubMenu');

        foreach ($submenuIds as $submenuId) {
            DB::connection('negocio')->table('permiso_submenu_rol')->updateOrInsert(
                [
                    'IdRol' => (int) $roleId,
                    'IdSubMenu' => (int) $submenuId,
                ],
                []
            );
        }
    }
}
