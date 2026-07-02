<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'negocio';

    public function up(): void
    {
        $pivotTable = Schema::connection($this->connection)->hasTable('permiso_submenu_rol')
            ? 'permiso_submenu_rol'
            : 'permisos_submenu_rol';

        if (! Schema::connection($this->connection)->hasTable($pivotTable)) {
            return;
        }

        $contactoMenuId = DB::connection('matriz')
            ->table('menus')
            ->where('Ruta', 'contacto.dashboard')
            ->value('IdMenu');

        if (! $contactoMenuId) {
            return;
        }

        $submenuIds = DB::connection('matriz')
            ->table('submenus')
            ->where('IdMenu', $contactoMenuId)
            ->whereIn('Ruta', ['contacto.telefono_movil.index', 'contacto.correo.index'])
            ->pluck('IdSubMenu');

        if ($submenuIds->isEmpty()) {
            return;
        }

        $roleIds = DB::connection($this->connection)
            ->table('permiso_menu_rol')
            ->where('IdMenu', $contactoMenuId)
            ->pluck('IdRol')
            ->unique();

        if ($roleIds->isEmpty()) {
            return;
        }

        foreach ($roleIds as $roleId) {
            foreach ($submenuIds as $submenuId) {
                DB::connection($this->connection)
                    ->table($pivotTable)
                    ->updateOrInsert(
                        ['IdRol' => $roleId, 'IdSubMenu' => $submenuId],
                        ['IdRol' => $roleId, 'IdSubMenu' => $submenuId]
                    );
            }
        }
    }

    public function down(): void
    {
        $pivotTable = Schema::connection($this->connection)->hasTable('permiso_submenu_rol')
            ? 'permiso_submenu_rol'
            : 'permisos_submenu_rol';

        if (! Schema::connection($this->connection)->hasTable($pivotTable)) {
            return;
        }

        $submenuIds = DB::connection('matriz')
            ->table('submenus')
            ->whereIn('Ruta', ['contacto.telefono_movil.index', 'contacto.correo.index'])
            ->pluck('IdSubMenu');

        if ($submenuIds->isEmpty()) {
            return;
        }

        DB::connection($this->connection)
            ->table($pivotTable)
            ->whereIn('IdSubMenu', $submenuIds)
            ->delete();
    }
};
