<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('negocio')->create('pedido_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('IdPedido');
            $table->unsignedBigInteger('IdProducto');
            $table->string('Nombre', 150);
            $table->enum('TipoItem', ['MENU_DIA', 'CARTA', 'PORCION'])->default('CARTA');
            $table->decimal('Cantidad', 8, 2)->default(1);
            $table->decimal('PrecioUnitario', 10, 2)->default(0);
            $table->decimal('Subtotal', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('IdPedido')->references('id')->on('pedidos')->cascadeOnDelete();
            $table->foreign('IdProducto')->references('IdProducto')->on('producto');
        });
    }

    public function down(): void
    {
        Schema::connection('negocio')->dropIfExists('pedido_detalles');
    }
};
