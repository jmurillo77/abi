<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    static $data = [
        ['jmurillo77@gmail.com', '123456789', 1, 1],
        ['scaceres@gmail.com', '123456789', 2, 2],
    ];
    public function run(): void
    {
        if (! Schema::connection('negocio')->hasTable('users')) {
            return;
        }

        foreach (self::$data as $value) {
            DB::connection('negocio')->table('users')->updateOrInsert(
                ['email' => $value[0]],
                [
                    'IdPersona' => $value[3],
                    'IdRol' => $value[2],
                    'password' => Hash::make($value[1]),
                ]
            );
        }
    }
}
