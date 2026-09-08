<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    static $data = [
        ['Sara Caceres', 'scaceres@gmail.com', '123456789'],
        ['Jose Murillo', 'jmurillo77@gmail.com', '123456789'],
    ];
    public function run(): void
    {
        foreach (self::$data as $key => $value) {
            $exists = DB::table('users')->where('email', $value[1])->exists();

            if (! $exists) {
                DB::table('users')->insert([
                    'name' => $value[0],
                    'email' => $value[1],
                    'password' => Hash::make($value[2]),
                ]);
            }
        }
    }
}
