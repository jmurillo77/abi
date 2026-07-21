<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection(name: 'negocio')->create('producto', function (Blueprint $table) {
            $table->id('IdProducto');
            $table->string('Nombre', 120);
            $table->string('Descripcion', 500)->nullable();
            $table->enum('TipoProducto', ['MATERIA_PRIMA', 'ELABORADO']);
            $table->string('UnidadMedida', 30)->nullable();
            $table->decimal('CostoUnitario', 10, 2)->nullable();
            $table->decimal('StockActual', 10, 2)->nullable();
            $table->enum('UsaReceta', ['S', 'N'])->default('N');
            $table->enum('UsaMenu', ['S', 'N'])->default('N');
            $table->enum('TipoMenu', ['ALMUERZO', 'PIQUEO', 'AMBOS'])->nullable();
            $table->tinyInteger('Activo')->default(1);
            $table->enum('Eliminado', ['S', 'N'])->default('N');
            $table->string('cUser')->nullable();
            $table->string('uUser')->nullable();
            $table->string('dUser')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('Tabla de productos de cocina para materia prima y elaborados');

            $table->unique('Nombre');
            $table->index('TipoProducto');
            $table->index('TipoMenu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(name: 'negocio')->dropIfExists('producto');
    }
};
