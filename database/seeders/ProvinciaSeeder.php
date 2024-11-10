<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static $data = [
        ['Azuay','52'],
        ['Bolivar','52'],
        ['Cañar','52'],
        ['Carchi','52'],
        ['Chimborazo','52'],
        ['Cotopaxi','52'],
        ['El Oro','52'],
        ['Esmeraldas','52'],
        ['Galápagos','52'],
        ['Guayas','52'],
        ['Imbabura','52'],
        ['Loja','52'],
        ['Los Ríos','52'],
        ['Manabí','52'],
        ['Morona Santiago','52'],
        ['Napo','52'],
        ['Sucumbíos','52'],
        ['Pastaza','52'],
        ['Pinchincha','52'],
        ['Santa Elena','52'],
        ['Santo Domingo','52'],
        ['Francisco De Orellana','52'],
        ['Tungurahua','52'],
        ['Zamora Chinchipe','52'],
    ];
    public function run(): void
    {
        foreach (self::$data as $key => $value) {
            DB::connection(name: 'matriz')->table('provincia')->insert([
                'Nombre' => $value[0],
                'IdPais' => $value[1],
            ]);
        }
    }
}
