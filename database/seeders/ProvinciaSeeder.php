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
    static array $data = [
        // Catalogo completo de provincias del Ecuador (IdPais = 52).
        [1, 'Azuay', 52],
        [2, 'Bolivar', 52],
        [3, 'Canar', 52],
        [4, 'Carchi', 52],
        [5, 'Chimborazo', 52],
        [6, 'Cotopaxi', 52],
        [7, 'El Oro', 52],
        [8, 'Esmeraldas', 52],
        [9, 'Galapagos', 52],
        [10, 'Guayas', 52],
        [11, 'Imbabura', 52],
        [12, 'Loja', 52],
        [13, 'Los Rios', 52],
        [14, 'Manabi', 52],
        [15, 'Morona Santiago', 52],
        [16, 'Napo', 52],
        [17, 'Sucumbios', 52],
        [18, 'Pastaza', 52],
        [19, 'Pichincha', 52],
        [20, 'Santa Elena', 52],
        [21, 'Santo Domingo de los Tsachilas', 52],
        [22, 'Orellana', 52],
        [23, 'Tungurahua', 52],
        [24, 'Zamora Chinchipe', 52],
    ];
    public function run(): void
    {
        foreach (self::$data as $key => $value) {
            DB::connection(name: 'matriz')->table('provincia')->insert([
                'IdProvincia' => $value[0],
                'Nombre' => $value[1],
                'IdPais' => $value[2],
            ]);
        }
    }
}
