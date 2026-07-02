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
            ->where('IdMenu', $contactoMenuId)
            ->where('Titulo', 'Provincia')
            ->update([
                'Ruta' => 'contacto.provincia.index',
                'Icono' => 'fas fa-map-marked-alt',
                'Activo' => 1,
            ]);

        DB::connection($this->connection)
            ->table('submenus')
            ->updateOrInsert(
                ['IdMenu' => $contactoMenuId, 'Titulo' => 'Canton'],
                [
                    'Ruta' => 'contacto.canton.index',
                    'Icono' => 'fas fa-map',
                    'Orden' => 9,
                    'Activo' => 1,
                ]
            );

        DB::connection($this->connection)
            ->table('submenus')
            ->updateOrInsert(
                ['IdMenu' => $contactoMenuId, 'Titulo' => 'Parroquia'],
                [
                    'Ruta' => 'contacto.parroquia.index',
                    'Icono' => 'fas fa-map-pin',
                    'Orden' => 10,
                    'Activo' => 1,
                ]
            );
    }

    public function down(): void
    {
        $contactoMenuId = DB::connection($this->connection)
            ->table('menus')
            ->where('Ruta', 'contacto.dashboard')
            ->value('IdMenu');

        if (! $contactoMenuId) {
            return;
        }

        DB::connection($this->connection)
            ->table('submenus')
            ->where('IdMenu', $contactoMenuId)
            ->whereIn('Titulo', ['Canton', 'Parroquia'])
            ->delete();
    }
};
