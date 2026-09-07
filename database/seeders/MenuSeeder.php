<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $menus = [
            [
                'Titulo' => 'Contactos',
                'Ruta' => 'contacto.dashboard',
                'Icono' => 'fas fa-address-book|#0284c7',
                'Orden' => 1,
                'Activo' => true,
            ],
            [
                'Titulo' => 'Compras',
                'Ruta' => 'compras.dashboard',
                'Icono' => 'fas fa-shopping-cart|#ea580c',
                'Orden' => 2,
                'Activo' => true,
            ],
            [
                'Titulo' => 'Inventario',
                'Ruta' => 'inventario.dashboard',
                'Icono' => 'fas fa-boxes|#16a34a',
                'Orden' => 3,
                'Activo' => true,
            ],
            [
                'Titulo' => 'Ventas',
                'Ruta' => 'ventas.dashboard',
                'Icono' => 'fas fa-store|#0d9488',
                'Orden' => 4,
                'Activo' => true,
            ],
            [
                'Titulo' => 'Facturacion',
                'Ruta' => 'facturacion.dashboard',
                'Icono' => 'fas fa-file-invoice-dollar|#2563eb',
                'Orden' => 5,
                'Activo' => true,
            ],
            [
                'Titulo' => 'Configuracion',
                'Ruta' => 'configuracion.dashboard',
                'Icono' => 'fas fa-cogs|#475569',
                'Orden' => 6,
                'Activo' => true,
            ],
        ];

        foreach ($menus as $menu) {
            DB::connection('matriz')->table('menus')->updateOrInsert(
                ['Titulo' => $menu['Titulo']],
                [
                    'Ruta' => $menu['Ruta'],
                    'Icono' => $menu['Icono'],
                    'parent_id' => null,
                    'Orden' => $menu['Orden'],
                    'Activo' => $menu['Activo'],
                    'cUser' => 'seed',
                    'uUser' => 'seed',
                    'dUser' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]
            );
        }
    }
}
