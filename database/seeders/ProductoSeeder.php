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
        $productos = [
            // Materia prima
            ['Arroz', 'Arroz blanco para preparaciones base', 'MATERIA_PRIMA', 'kg', 1.20, 80, 'S', 'N', null, 1],
            ['Pechuga de pollo', 'Corte de pollo sin piel', 'MATERIA_PRIMA', 'kg', 3.80, 45, 'S', 'S', 'ALMUERZO', 1],
            ['Carne de res', 'Pulpa de res para guisos y plancha', 'MATERIA_PRIMA', 'kg', 5.10, 35, 'S', 'S', 'ALMUERZO', 1],
            ['Papas', 'Papa chola', 'MATERIA_PRIMA', 'kg', 0.90, 60, 'S', 'S', 'AMBOS', 1],
            ['Aceite vegetal', 'Aceite para frituras y salteados', 'MATERIA_PRIMA', 'l', 2.40, 30, 'S', 'S', 'AMBOS', 1],
            ['Cebolla paiteña', 'Base de sofritos', 'MATERIA_PRIMA', 'kg', 1.00, 40, 'S', 'S', 'AMBOS', 1],
            ['Tomate', 'Tomate riñón', 'MATERIA_PRIMA', 'kg', 1.10, 35, 'S', 'S', 'AMBOS', 1],
            ['Pimiento', 'Pimiento para salteados', 'MATERIA_PRIMA', 'kg', 1.60, 20, 'S', 'S', 'AMBOS', 1],
            ['Queso mozzarella', 'Queso para gratinados y piqueos', 'MATERIA_PRIMA', 'kg', 6.80, 12, 'S', 'S', 'PIQUEO', 1],
            ['Harina de trigo', 'Harina para empanizados y masas', 'MATERIA_PRIMA', 'kg', 1.30, 25, 'S', 'S', 'PIQUEO', 1],
            ['Leche', 'Leche entera', 'MATERIA_PRIMA', 'l', 1.20, 22, 'S', 'N', null, 1],
            ['Huevos', 'Huevo de gallina', 'MATERIA_PRIMA', 'unidad', 0.18, 240, 'S', 'S', 'AMBOS', 1],

            // Productos elaborados
            ['Salsa de tomate casera', 'Salsa base para platos y piqueos', 'ELABORADO', 'l', 2.80, 10, 'S', 'S', 'AMBOS', 1],
            ['Arroz cocido', 'Arroz blanco cocido', 'ELABORADO', 'kg', 2.00, 15, 'S', 'S', 'ALMUERZO', 1],
            ['Pollo apanado', 'Pechuga empanizada lista para fritura', 'ELABORADO', 'kg', 6.20, 8, 'S', 'S', 'AMBOS', 1],
            ['Papas fritas', 'Papas prefritas listas para servir', 'ELABORADO', 'kg', 3.50, 10, 'S', 'S', 'AMBOS', 1],
            ['Ensalada base', 'Mix de lechuga, tomate y cebolla', 'ELABORADO', 'kg', 2.40, 9, 'S', 'S', 'ALMUERZO', 1],
            ['Empanadas de queso', 'Empanadas listas para calentado', 'ELABORADO', 'unidad', 1.20, 30, 'S', 'S', 'PIQUEO', 1],
            ['Mini hamburguesa', 'Preparacion lista para piqueo', 'ELABORADO', 'unidad', 1.80, 25, 'S', 'S', 'PIQUEO', 1],
            ['Wrap de pollo', 'Producto listo para menu ejecutivo', 'ELABORADO', 'unidad', 2.90, 18, 'S', 'S', 'ALMUERZO', 1],
        ];

        foreach ($productos as $producto) {
            DB::connection('negocio')->table('producto')->updateOrInsert(
                ['Nombre' => $producto[0]],
                [
                    'Descripcion' => $producto[1],
                    'TipoProducto' => $producto[2],
                    'UnidadMedida' => $producto[3],
                    'CostoUnitario' => $producto[4],
                    'StockActual' => $producto[5],
                    'UsaReceta' => $producto[6],
                    'UsaMenu' => $producto[7],
                    'TipoMenu' => $producto[8],
                    'Activo' => $producto[9],
                    'Eliminado' => 'N',
                    'uUser' => 'seeder',
                    'updated_at' => now(),
                ]
            );
        }
    }
}
