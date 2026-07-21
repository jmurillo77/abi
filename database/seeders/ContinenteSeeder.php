<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContinenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static $data = [
        ['America'],
        ['Europa'],
        ['Africa'],
        ['Asia'],
        ['Oceania'],
    ];
    public function run(): void
    {
        foreach (self::$data as $key => $value) {
            DB::connection(name: 'matriz')->table('continentes')->insert([
                'Nombre' => $value[0],
            ]);
        }
    }
}
