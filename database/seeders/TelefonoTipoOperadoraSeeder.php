<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TelefonoTipoOperadoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static $data = [
        ['NA'],
        ['Claro'],
        ['Movistar'],
        ['CNT'],
        ['Tuenti'],
    ];
    public function run(): void
    {
        foreach (self::$data as $key => $value) {
            DB::table('telefono_tipo_operadoras')->insert([
                'Nombre' => $value[0],
            ]);
        }
    }
}
