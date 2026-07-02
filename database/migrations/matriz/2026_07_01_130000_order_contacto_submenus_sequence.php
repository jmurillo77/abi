<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'matriz';

    public function up(): void
    {
        $orderedRoutes = [
            'contacto.continente.index' => 1,
            'contacto.pais.index' => 2,
            'contacto.provincia.index' => 3,
            'contacto.canton.index' => 4,
            'contacto.parroquia.index' => 5,
            'contacto.persona.index' => 6,
            'contacto.empresa.index' => 7,
            'contacto.telefono_movil.index' => 8,
            'contacto.correo.index' => 9,
        ];

        foreach ($orderedRoutes as $route => $order) {
            DB::connection($this->connection)
                ->table('submenus')
                ->where('Ruta', $route)
                ->update(['Orden' => $order, 'Activo' => 1]);
        }
    }

    public function down(): void
    {
        $routes = [
            'contacto.continente.index',
            'contacto.pais.index',
            'contacto.provincia.index',
            'contacto.canton.index',
            'contacto.parroquia.index',
            'contacto.persona.index',
            'contacto.empresa.index',
            'contacto.telefono_movil.index',
            'contacto.correo.index',
        ];

        DB::connection($this->connection)
            ->table('submenus')
            ->whereIn('Ruta', $routes)
            ->update(['Orden' => 0]);
    }
};
