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
        ['Africa'],
        ['America'],
        ['Asia'],
        ['Europa'],     
        ['Oceania'],
    ];
    public function run(): void
    {
        foreach (self::$data as $value) {
            DB::connection('matriz')->table('continentes')->updateOrInsert(
                ['Nombre' => $value[0]],
                []
            );
        }
    }
}
