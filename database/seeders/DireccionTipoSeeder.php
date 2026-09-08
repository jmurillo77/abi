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
        DB::connection('matriz')->table('direccion_tipo')->insert([
            [
                'Nombre' => 'Residencial',
                'Eliminado' => 'N',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Comercial',
                'Eliminado' => 'N',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Oficina',
                'Eliminado' => 'N',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Bodega',
                'Eliminado' => 'N',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Sucursal',
                'Eliminado' => 'N',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'Nombre' => 'Fabrica',
                'Eliminado' => 'N',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
