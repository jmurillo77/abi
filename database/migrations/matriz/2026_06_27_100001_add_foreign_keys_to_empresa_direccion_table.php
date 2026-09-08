<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection(name: 'matriz')->table('empresa_direccion', function (Blueprint $table) {
            if (!Schema::connection('matriz')->hasColumn('empresa_direccion', 'IdEmpresa')) {
                $table->foreignId('IdEmpresa')->constrained('empresas', 'IdEmpresa');
            }
            if (!Schema::connection('matriz')->hasColumn('empresa_direccion', 'IdDireccion')) {
                $table->foreignId('IdDireccion')->constrained('direccion', 'IdDireccion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(name: 'matriz')->table('empresa_direccion', function (Blueprint $table) {
            if (Schema::connection('matriz')->hasColumn('empresa_direccion', 'IdEmpresa')) {
                $table->dropForeign(['IdEmpresa']);
                $table->dropColumn('IdEmpresa');
            }
            if (Schema::connection('matriz')->hasColumn('empresa_direccion', 'IdDireccion')) {
                $table->dropForeign(['IdDireccion']);
                $table->dropColumn('IdDireccion');
            }
        });
    }
};
