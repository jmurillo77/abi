<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Materias Primas (Categoria, UnidadMedida, Costo, Stock, PorcentajeMerma).
        $materiasPrimas = [
            ['Arroz', 'Arroz blanco para preparaciones base', 'ABARROTES', 'kg', 1.20, 80, null],
            ['Pechuga de pollo', 'Corte de pollo sin piel', 'PROTEINAS', 'kg', 3.80, 45, 8],
            ['Carne de res', 'Pulpa de res para guisos y plancha', 'PROTEINAS', 'kg', 5.10, 35, 5],
            ['Papas', 'Papa chola', 'FRUTAS_VERDURAS', 'kg', 0.90, 60, 15],
            ['Aceite vegetal', 'Aceite para frituras y salteados', 'ABARROTES', 'l', 2.40, 30, null],
            ['Cebolla paiteña', 'Base de sofritos', 'FRUTAS_VERDURAS', 'kg', 1.00, 40, 10],
            ['Tomate', 'Tomate riñón', 'FRUTAS_VERDURAS', 'kg', 1.10, 35, 8],
            ['Pimiento', 'Pimiento para salteados', 'FRUTAS_VERDURAS', 'kg', 1.60, 20, 12],
            ['Queso mozzarella', 'Queso para gratinados y piqueos', 'LACTEOS_HUEVOS', 'kg', 6.80, 12, null],
            ['Harina de trigo', 'Harina para empanizados y masas', 'ABARROTES', 'kg', 1.30, 25, null],
            ['Leche', 'Leche entera', 'LACTEOS_HUEVOS', 'l', 1.20, 22, null],
            ['Huevos', 'Huevo de gallina', 'LACTEOS_HUEVOS', 'unidad', 0.18, 240, null],
            ['Pan de hamburguesa', 'Pan brioche para hamburguesa', 'ABARROTES', 'unidad', 0.35, 100, null],
            ['Sal', 'Sal refinada', 'ABARROTES', 'kg', 0.60, 15, null],
        ];

        // 2. Recetas y Sub-recetas (Categoria, Rendimiento, ingredientes desde materia prima).
        $recetas = [
            [
                'nombre' => 'Salsa de tomate casera',
                'descripcion' => 'Salsa base para platos y piqueos',
                'categoria' => 'SUB_RECETA',
                'unidad' => 'l',
                'costo' => 2.80,
                'rendimiento_cantidad' => 10,
                'rendimiento_unidad' => 'porciones',
                'ingredientes' => [
                    ['Tomate', 5, 'kg'],
                    ['Aceite vegetal', 0.2, 'l'],
                    ['Cebolla paiteña', 0.5, 'kg'],
                    ['Sal', 0.05, 'kg'],
                ],
            ],
            [
                'nombre' => 'Carne molida maridada',
                'descripcion' => 'Carne de res molida y sazonada, lista para armar hamburguesas',
                'categoria' => 'SUB_RECETA',
                'unidad' => 'kg',
                'costo' => 5.60,
                'rendimiento_cantidad' => 5,
                'rendimiento_unidad' => 'kg',
                'ingredientes' => [
                    ['Carne de res', 5, 'kg'],
                    ['Sal', 0.03, 'kg'],
                ],
            ],
            [
                'nombre' => 'Arroz cocido',
                'descripcion' => 'Arroz blanco cocido, listo para emplatar',
                'categoria' => 'EMPLATADO',
                'unidad' => 'porcion',
                'costo' => 0.45,
                'rendimiento_cantidad' => 15,
                'rendimiento_unidad' => 'porciones',
                'ingredientes' => [
                    ['Arroz', 3, 'kg'],
                    ['Aceite vegetal', 0.1, 'l'],
                    ['Sal', 0.02, 'kg'],
                ],
            ],
            [
                'nombre' => 'Hamburguesa (emplatado)',
                'descripcion' => 'Armado de la hamburguesa: pan, carne maridada, queso y tomate',
                'categoria' => 'EMPLATADO',
                'unidad' => 'porcion',
                'costo' => 2.10,
                'rendimiento_cantidad' => 1,
                'rendimiento_unidad' => 'porcion',
                'ingredientes' => [
                    ['Pan de hamburguesa', 1, 'unidad'],
                    ['Queso mozzarella', 0.02, 'kg'],
                    ['Tomate', 0.03, 'kg'],
                ],
            ],
        ];

        // 3. Menú (Categoria, TipoMenu, ingredientes desde recetas o materia prima directa).
        $menus = [
            [
                'nombre' => 'Hamburguesa clásica',
                'descripcion' => 'Hamburguesa de carne maridada con queso',
                'categoria' => 'PLATOS_FUERTES',
                'tipo_menu' => 'ALMUERZO',
                'costo' => 3.20,
                'ingredientes' => [
                    ['Hamburguesa (emplatado)', 1, 'porcion'],
                    ['Carne molida maridada', 0.15, 'kg'],
                ],
            ],
            [
                'nombre' => 'Papas fritas',
                'descripcion' => 'Porción de papas fritas crocantes',
                'categoria' => 'ENTRADAS',
                'tipo_menu' => 'PIQUEO',
                'costo' => 1.10,
                'ingredientes' => [
                    ['Papas', 0.3, 'kg'],
                    ['Aceite vegetal', 0.05, 'l'],
                ],
            ],
            [
                'nombre' => 'Almuerzo ejecutivo',
                'descripcion' => 'Arroz, proteína y ensalada del día',
                'categoria' => 'PLATOS_FUERTES',
                'tipo_menu' => 'ALMUERZO',
                'costo' => 2.50,
                'ingredientes' => [
                    ['Arroz cocido', 1, 'porcion'],
                    ['Pechuga de pollo', 0.15, 'kg'],
                ],
            ],
            [
                'nombre' => 'Jugo natural',
                'descripcion' => 'Jugo de fruta natural del día',
                'categoria' => 'BEBIDAS',
                'tipo_menu' => 'AMBOS',
                'costo' => 1.00,
                'ingredientes' => [],
            ],
        ];

        $idsPorNombre = [];

        foreach ($materiasPrimas as $mp) {
            [$nombre, $descripcion, $categoria, $unidad, $costo, $stock, $merma] = $mp;

            $id = DB::connection('negocio')->table('producto')->updateOrInsert(
                ['Nombre' => $nombre],
                [
                    'Descripcion' => $descripcion,
                    'TipoProducto' => 'MATERIA_PRIMA',
                    'Categoria' => $categoria,
                    'UnidadMedida' => $unidad,
                    'CostoUnitario' => $costo,
                    'StockActual' => $stock,
                    'PorcentajeMerma' => $merma,
                    'UsaReceta' => 'S',
                    'UsaMenu' => 'N',
                    'Activo' => 1,
                    'Eliminado' => 'N',
                    'uUser' => 'seeder',
                    'updated_at' => now(),
                ]
            );

            $idsPorNombre[$nombre] = DB::connection('negocio')->table('producto')->where('Nombre', $nombre)->value('IdProducto');
        }

        foreach ($recetas as $receta) {
            DB::connection('negocio')->table('producto')->updateOrInsert(
                ['Nombre' => $receta['nombre']],
                [
                    'Descripcion' => $receta['descripcion'],
                    'TipoProducto' => 'RECETA',
                    'Categoria' => $receta['categoria'],
                    'UnidadMedida' => $receta['unidad'],
                    'CostoUnitario' => $receta['costo'],
                    'RendimientoCantidad' => $receta['rendimiento_cantidad'],
                    'RendimientoUnidad' => $receta['rendimiento_unidad'],
                    'UsaReceta' => 'S',
                    'UsaMenu' => 'N',
                    'Activo' => 1,
                    'Eliminado' => 'N',
                    'uUser' => 'seeder',
                    'updated_at' => now(),
                ]
            );

            $idsPorNombre[$receta['nombre']] = DB::connection('negocio')->table('producto')->where('Nombre', $receta['nombre'])->value('IdProducto');
        }

        foreach ($menus as $menu) {
            DB::connection('negocio')->table('producto')->updateOrInsert(
                ['Nombre' => $menu['nombre']],
                [
                    'Descripcion' => $menu['descripcion'],
                    'TipoProducto' => 'MENU',
                    'Categoria' => $menu['categoria'],
                    'TipoMenu' => $menu['tipo_menu'],
                    'CostoUnitario' => $menu['costo'],
                    'UsaReceta' => 'N',
                    'UsaMenu' => 'S',
                    'Activo' => 1,
                    'Eliminado' => 'N',
                    'uUser' => 'seeder',
                    'updated_at' => now(),
                ]
            );

            $idsPorNombre[$menu['nombre']] = DB::connection('negocio')->table('producto')->where('Nombre', $menu['nombre'])->value('IdProducto');
        }

        // Composición (BOM): se reconstruye desde cero para las recetas y menús sembrados aquí.
        $composiciones = array_merge($recetas, $menus);

        foreach ($composiciones as $item) {
            if (empty($item['ingredientes'])) {
                continue;
            }

            $idProducto = $idsPorNombre[$item['nombre']];

            DB::connection('negocio')->table('producto_ingredientes')->where('IdProducto', $idProducto)->delete();

            foreach ($item['ingredientes'] as [$nombreInsumo, $cantidad, $unidad]) {
                if (! isset($idsPorNombre[$nombreInsumo])) {
                    continue;
                }

                DB::connection('negocio')->table('producto_ingredientes')->insert([
                    'IdProducto' => $idProducto,
                    'IdInsumo' => $idsPorNombre[$nombreInsumo],
                    'Cantidad' => $cantidad,
                    'UnidadMedida' => $unidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
