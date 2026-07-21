<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParroquiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static array $data = [
        // Catalogo base de parroquias, alineado con CiudadSeeder.
        [1, 'Centro Cuenca', 1],
        [2, 'Centro Guaranda', 2],
        [3, 'Centro Azogues', 3],
        [4, 'Centro Tulcan', 4],
        [5, 'Centro Riobamba', 5],
        [6, 'Centro Latacunga', 6],
        [7, 'Centro Machala', 7],
        [8, 'Centro Esmeraldas', 8],
        [9, 'Centro Puerto Baquerizo Moreno', 9],
        [10, 'Centro Guayaquil', 10],
        [11, 'Centro Ibarra', 11],
        [12, 'Centro Loja', 12],
        [13, 'Centro Babahoyo', 13],
        [14, 'Centro Portoviejo', 14],
        [15, 'Centro Macas', 15],
        [16, 'Centro Tena', 16],
        [17, 'Centro Nueva Loja', 17],
        [18, 'Centro Puyo', 18],
        [19, 'Centro Quito', 19],
        [20, 'Centro Santa Elena', 20],
        [21, 'Centro Santo Domingo', 21],
        [22, 'Centro Puerto Francisco de Orellana', 22],
        [23, 'Centro Ambato', 23],
        [24, 'Centro Zamora', 24],
        [25, 'Centro Milagro', 25],
        [26, 'Centro Playas', 26],
    ];

    public function run(): void
    {
        foreach (self::$data as $key => $value) {
            DB::connection(name: 'matriz')->table('parroquia')->insert([
                'IdParroquia' => $value[0],
                'Nombre' => $value[1],
                'IdCiudad' => $value[2],
            ]);
        }
    }
}
