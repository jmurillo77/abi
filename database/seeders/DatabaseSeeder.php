<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $currentDatabase = DB::connection()->getDatabaseName();
        $matrizDatabase = config('database.connections.matriz.database');
        $negocioDatabase = config('database.connections.negocio.database');

        if ($currentDatabase === $negocioDatabase) {
            $this->call([
                UserSeeder::class,
                RoleMenuPermissionSeeder::class,
                RoleSubmenuPermissionSeeder::class,
                /*ProductoSeeder::class,*/
            ]);

            return;
        }

        $this->call([
            PersonaSeeder::class,
            RoleSeeder::class,
            TelefonoTipoOperadoraSeeder::class,
            DireccionTipoSeeder::class,
            ContinenteSeeder::class,
            PaisSeeder::class,
            ProvinciaSeeder::class,
            CiudadSeeder::class,
            ParroquiaSeeder::class,
            MenuSeeder::class,
            SubmenuSeeder::class,
        ]);
    }
}
