<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Se amplía el enum para poder reclasificar sin perder filas existentes.
        DB::connection('negocio')->statement(
            "ALTER TABLE producto MODIFY TipoProducto ENUM('MATERIA_PRIMA','ELABORADO','RECETA','MENU') NOT NULL"
        );

        // 2) Los productos elaborados que ya se usaban para tomar pedidos (UsaMenu = S) pasan a
        // ser productos de MENU; el resto (preparaciones intermedias) pasan a ser RECETA.
        DB::connection('negocio')->table('producto')
            ->where('TipoProducto', 'ELABORADO')
            ->where('UsaMenu', 'S')
            ->update(['TipoProducto' => 'MENU']);

        DB::connection('negocio')->table('producto')
            ->where('TipoProducto', 'ELABORADO')
            ->update(['TipoProducto' => 'RECETA']);

        // 3) Ahora sí se restringe el enum a los 3 niveles definitivos.
        DB::connection('negocio')->statement(
            "ALTER TABLE producto MODIFY TipoProducto ENUM('MATERIA_PRIMA','RECETA','MENU') NOT NULL"
        );

        Schema::connection('negocio')->table('producto', function (Blueprint $table) {
            // Subcategoría según el nivel (proteínas/abarrotes.. para materia prima,
            // sub_receta/emplatado para recetas, entradas/platos_fuertes.. para menú).
            $table->string('Categoria', 60)->nullable()->after('TipoProducto');
            // % de merma esperado (ej. lo que se pierde al pelar una cebolla) — solo materia prima.
            $table->decimal('PorcentajeMerma', 5, 2)->nullable()->after('StockActual');
            // Rendimiento de una receta/sub-receta (ej. "rinde 10 porciones").
            $table->decimal('RendimientoCantidad', 10, 2)->nullable()->after('PorcentajeMerma');
            $table->string('RendimientoUnidad', 30)->nullable()->after('RendimientoCantidad');
        });
    }

    public function down(): void
    {
        Schema::connection('negocio')->table('producto', function (Blueprint $table) {
            $table->dropColumn(['Categoria', 'PorcentajeMerma', 'RendimientoCantidad', 'RendimientoUnidad']);
        });

        DB::connection('negocio')->statement(
            "ALTER TABLE producto MODIFY TipoProducto ENUM('MATERIA_PRIMA','ELABORADO','RECETA','MENU') NOT NULL"
        );

        DB::connection('negocio')->table('producto')
            ->whereIn('TipoProducto', ['RECETA', 'MENU'])
            ->update(['TipoProducto' => 'ELABORADO']);

        DB::connection('negocio')->statement(
            "ALTER TABLE producto MODIFY TipoProducto ENUM('MATERIA_PRIMA','ELABORADO') NOT NULL"
        );
    }
};
