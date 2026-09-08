<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DireccionTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            'Casa',
            'Residencial',
            'Comercial',
            'Oficina',
            'Bodega',
            'Sucursal',
            'Fabrica',
            'Otro',
        ];

        foreach ($tipos as $nombre) {
            DB::connection('matriz')
                ->table('direccion_tipo')
                ->updateOrInsert(
                    ['Nombre' => $nombre],
                    [
                        'Nombre' => $nombre,
                        'Eliminado' => 'N',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
        }
    }
}
