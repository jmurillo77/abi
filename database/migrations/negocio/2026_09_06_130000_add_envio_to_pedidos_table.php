<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('negocio')->table('pedidos', function (Blueprint $table) {
            // IdDireccion referencia matriz.direccion (otra base de datos, sin FK física)
            $table->unsignedBigInteger('IdDireccion')->nullable()->after('IdCliente');
            $table->enum('TipoEnvio', ['NINGUNO', 'GLOBAL', 'POR_ITEM'])->default('NINGUNO')->after('Total');
            $table->decimal('CostoEnvio', 10, 2)->default(0)->after('TipoEnvio');
        });

        Schema::connection('negocio')->table('pedido_detalles', function (Blueprint $table) {
            $table->decimal('CostoEnvio', 10, 2)->default(0)->after('Subtotal');
        });
    }

    public function down(): void
    {
        Schema::connection('negocio')->table('pedido_detalles', function (Blueprint $table) {
            $table->dropColumn('CostoEnvio');
        });

        Schema::connection('negocio')->table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['IdDireccion', 'TipoEnvio', 'CostoEnvio']);
        });
    }
};
