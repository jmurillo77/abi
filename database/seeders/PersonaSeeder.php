<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::connection('matriz')->hasTable('personas')) {
            return;
        }

        $personas = [
            [
                'DNI' => '0920256047',
                'Nombres' => 'Jose Luis',
                'Apellidos' => 'Murillo Gonzalez',
                'FechaNacimiento' => '1981-03-26',
            ],
            [
                'DNI' => '0920256045',
                'Nombres' => 'Sara Carolina',
                'Apellidos' => 'Caceres Barba',
                'FechaNacimiento' => '1999-03-24',
            ],
        ];

        foreach ($personas as $persona) {
            DB::connection('matriz')->table('personas')->updateOrInsert(
                ['DNI' => $persona['DNI']],
                [
                    'Nombres' => $persona['Nombres'],
                    'Apellidos' => $persona['Apellidos'],
                    'FechaNacimiento' => $persona['FechaNacimiento'],
                    'Eliminado' => 'N',
                    'cUser' => 'seed',
                    'uUser' => 'seed',
                    'dUser' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }
    }
}
