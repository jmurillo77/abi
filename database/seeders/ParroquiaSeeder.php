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
        [1, 'Cuenca', 1],
        [2, 'Guaranda', 2],
        [3, 'Azogues', 3],
        [4, 'Tulcan', 4],
        [5, 'Riobamba', 5],
        [6, 'Latacunga', 6],
        [7, 'Machala', 7],
        [8, 'Esmeraldas', 8],
        [9, 'Puerto Baquerizo Moreno', 9],
        [10, 'Guayaquil', 10],
        [11, 'Ibarra', 11],
        [12, 'Loja', 12],
        [13, 'Babahoyo', 13],
        [14, 'Portoviejo', 14],
        [15, 'Macas', 15],
        [16, 'Tena', 16],
        [17, 'Nueva Loja', 17],
        [18, 'Puyo', 18],
        [19, 'Quito', 19],
        [20, 'Santa Elena', 20],
        [21, 'Santo Domingo', 21],
        [22, 'Puerto Francisco de Orellana', 22],
        [23, 'Ambato', 23],
        [24, 'Zamora', 24],
        [25, 'Milagro', 25],
        [26, 'Playas', 26],
    ];

    public function run(): void
    {
        foreach (self::$data as $value) {
            DB::connection('matriz')->table('parroquia')->updateOrInsert(
                ['IdParroquia' => $value[0]],
                [
                    'Nombre' => $value[1],
                    'IdCiudad' => $value[2],
                ]
            );
        }
    }
}
