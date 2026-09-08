<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('negocio')->create('producto_ingredientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('IdProducto');
            $table->unsignedBigInteger('IdInsumo');
            $table->decimal('Cantidad', 10, 3);
            $table->string('UnidadMedida', 30)->nullable();
            $table->timestamps();

            $table->foreign('IdProducto')->references('IdProducto')->on('producto')->cascadeOnDelete();
            $table->foreign('IdInsumo')->references('IdProducto')->on('producto');
        });
    }

    public function down(): void
    {
        Schema::connection('negocio')->dropIfExists('producto_ingredientes');
    }
};
