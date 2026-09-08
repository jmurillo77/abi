<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'matriz';

    public function up(): void
    {
        foreach (['Casa', 'Oficina', 'Otro'] as $nombre) {
            DB::connection($this->connection)
                ->table('direccion_tipo')
                ->updateOrInsert(
                    ['Nombre' => $nombre],
                    ['Nombre' => $nombre, 'Eliminado' => 'N']
                );
        }
    }

    public function down(): void
    {
        DB::connection($this->connection)
            ->table('direccion_tipo')
            ->whereIn('Nombre', ['Casa', 'Oficina', 'Otro'])
            ->delete();
    }
};
