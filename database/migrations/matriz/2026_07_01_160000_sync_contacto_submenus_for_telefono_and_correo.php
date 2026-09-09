<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'matriz';

    public function up(): void
    {
        $contactoMenuId = DB::connection($this->connection)
            ->table('menus')
            ->where('Ruta', 'contacto.dashboard')
            ->value('IdMenu');

        if (! $contactoMenuId) {
            $contactoMenuId = DB::connection($this->connection)
                ->table('menus')
                ->where('Titulo', 'Contactos')
                ->value('IdMenu');
        }

        if (! $contactoMenuId) {
            return;
        }

        DB::connection($this->connection)
            ->table('submenus')
            ->updateOrInsert(
                ['IdMenu' => $contactoMenuId, 'Ruta' => 'contacto.telefono_movil.index'],
                [
                    'Titulo' => 'Teléfonos',
                    'Icono' => 'fas fa-phone',
                    'Orden' => 8,
                    'Activo' => 1,
                ]
            );

        DB::connection($this->connection)
            ->table('submenus')
            ->updateOrInsert(
                ['IdMenu' => $contactoMenuId, 'Ruta' => 'contacto.correo.index'],
                [
                    'Titulo' => 'Correos',
                    'Icono' => 'fas fa-envelope',
                    'Orden' => 9,
                    'Activo' => 1,
                ]
            );
    }

    public function down(): void
    {
        DB::connection($this->connection)
            ->table('submenus')
            ->whereIn('Ruta', ['contacto.telefono_movil.index', 'contacto.correo.index'])
            ->delete();
    }
};
