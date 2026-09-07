<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubmenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuIds = DB::connection('matriz')
            ->table('menus')
            ->pluck('IdMenu', 'Titulo')
            ->all();

        $baseSubmenus = [
            'Contactos' => [
                [
                    'Titulo' => 'Personas',
                    'Ruta' => 'contacto.persona.index',
                    'Icono' => 'fas fa-user|#0284c7',
                    'Orden' => 1,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Empresas',
                    'Ruta' => 'contacto.empresa.index',
                    'Icono' => 'fas fa-building|#0284c7',
                    'Orden' => 2,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Teléfonos',
                    'Ruta' => 'contacto.telefono_movil.index',
                    'Icono' => 'fas fa-phone|#0284c7',
                    'Orden' => 3,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Correos',
                    'Ruta' => 'contacto.correo.index',
                    'Icono' => 'fas fa-envelope|#0284c7',
                    'Orden' => 4,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Continentes',
                    'Ruta' => 'contacto.continente.index',
                    'Icono' => 'fas fa-globe|#0284c7',
                    'Orden' => 5,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Países',
                    'Ruta' => 'contacto.pais.index',
                    'Icono' => 'fas fa-flag|#0284c7',
                    'Orden' => 6,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Provincias',
                    'Ruta' => 'contacto.provincia.index',
                    'Icono' => 'fas fa-map-marked-alt|#0284c7',
                    'Orden' => 7,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Cantones',
                    'Ruta' => 'contacto.canton.index',
                    'Icono' => 'fas fa-map-pin|#0284c7',
                    'Orden' => 8,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Parroquias',
                    'Ruta' => 'contacto.parroquia.index',
                    'Icono' => 'fas fa-location-dot|#0284c7',
                    'Orden' => 9,
                    'Activo' => true,
                ],
            ],
            'Ventas' => [
                [
                    'Titulo' => 'Clientes',
                    'Ruta' => 'ventas.cliente.index',
                    'Icono' => 'fas fa-user-tie|#0284c7',
                    'Orden' => 1,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Pedidos',
                    'Ruta' => 'ventas.pedido.index',
                    'Icono' => 'fas fa-boxes|#0d9488',
                    'Orden' => 2,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Productos',
                    'Ruta' => 'ventas.producto.index',
                    'Icono' => 'fas fa-boxes|#0d9488',
                    'Orden' => 3,
                    'Activo' => true,
                ],
            ],
            'Configuracion' => [
                [
                    'Titulo' => 'Usuarios',
                    'Ruta' => 'configuracion.users.index',
                    'Icono' => 'fas fa-user-shield|#475569',
                    'Orden' => 1,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Roles',
                    'Ruta' => 'configuracion.roles.index',
                    'Icono' => 'fas fa-user-tag|#475569',
                    'Orden' => 2,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Menús',
                    'Ruta' => 'configuracion.menus.index',
                    'Icono' => 'fas fa-list|#475569',
                    'Orden' => 3,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Submenús',
                    'Ruta' => 'configuracion.submenus.index',
                    'Icono' => 'fas fa-sitemap|#475569',
                    'Orden' => 4,
                    'Activo' => true,
                ],
            ],
            'Compras' => [
                [
                    'Titulo' => 'Proveedores',
                    'Ruta' => null,
                    'Icono' => 'fas fa-truck|#ea580c',
                    'Orden' => 1,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Órdenes de compra',
                    'Ruta' => null,
                    'Icono' => 'fas fa-receipt|#ea580c',
                    'Orden' => 2,
                    'Activo' => true,
                ],
            ],
            'Inventario' => [
                [
                    'Titulo' => 'Productos',
                    'Ruta' => null,
                    'Icono' => 'fas fa-box|#16a34a',
                    'Orden' => 1,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Kardex',
                    'Ruta' => null,
                    'Icono' => 'fas fa-clipboard-list|#16a34a',
                    'Orden' => 2,
                    'Activo' => true,
                ],
            ],
            'Facturacion' => [
                [
                    'Titulo' => 'Facturas',
                    'Ruta' => null,
                    'Icono' => 'fas fa-file-invoice|#2563eb',
                    'Orden' => 1,
                    'Activo' => true,
                ],
                [
                    'Titulo' => 'Cobros',
                    'Ruta' => null,
                    'Icono' => 'fas fa-wallet|#2563eb',
                    'Orden' => 2,
                    'Activo' => true,
                ],
            ],
        ];

        foreach ($baseSubmenus as $menuTitle => $submenus) {
            $menuId = $menuIds[$menuTitle] ?? null;

            if ($menuId === null) {
                continue;
            }

            foreach ($submenus as $submenu) {
                DB::connection('matriz')->table('submenus')->updateOrInsert(
                    ['IdMenu' => $menuId, 'Titulo' => $submenu['Titulo']],
                    [
                        'Ruta' => $submenu['Ruta'],
                        'Icono' => $submenu['Icono'],
                        'Orden' => $submenu['Orden'],
                        'Activo' => $submenu['Activo'] ? 1 : 0,
                    ]
                );
            }
        }
    }
}
