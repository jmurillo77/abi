<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('negocio')->create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('IdCliente');
            $table->dateTime('Fecha')->useCurrent();
            $table->enum('Estado', ['PENDIENTE', 'EN_PREPARACION', 'ENTREGADO', 'CANCELADO'])->default('PENDIENTE');
            $table->decimal('Total', 10, 2)->default(0);
            $table->string('Observaciones', 500)->nullable();
            $table->string('cUser')->nullable();
            $table->string('uUser')->nullable();
            $table->timestamps();

            $table->foreign('IdCliente')->references('id')->on('clientes');
        });
    }

    public function down(): void
    {
        Schema::connection('negocio')->dropIfExists('pedidos');
    }
};
