<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CiudadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static $data = [
        ['Guayaquil','1'],
        ['Milagro','1'],
        ['Playas','1'],
        ];
    public function run(): void
    {
        foreach (self::$data as $key => $value) {
            DB::connection(name: 'matriz')->table('ciudad')->insert([
                'Nombre' => $value[0],
                'IdProvincia' => $value[1],
            ]);
        }
    }
}
