<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('negocio')->table('pedidos', function (Blueprint $table) {
            $table->unsignedBigInteger('IdRuta')->nullable()->after('IdDireccion');
            $table->foreign('IdRuta')->references('id')->on('rutas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::connection('negocio')->table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['IdRuta']);
            $table->dropColumn('IdRuta');
        });
    }
};
