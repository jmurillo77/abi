<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['Admin', 'Administrador del sistema', true],
            ['Usuario', 'Usuario estándar con acceso base', true],
            ['Supervisor', 'Supervisor con permisos ampliados', true],
        ];

        foreach ($roles as [$nombre, $descripcion, $activo]) {
            DB::connection('matriz')->table('roles')->updateOrInsert(
                ['Nombre' => $nombre],
                [
                    'Descripcion' => $descripcion,
                    'Activo' => $activo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
