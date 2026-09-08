<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'matriz';

    public function up(): void
    {
        Schema::connection('matriz')->table('personas', function (Blueprint $table) {
            $table->foreignId('IdEmpresa')->nullable()->after('FechaNacimiento')
                ->constrained('empresas', 'IdEmpresa')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('matriz')->table('personas', function (Blueprint $table) {
            $table->dropForeign(['IdEmpresa']);
            $table->dropColumn('IdEmpresa');
        });
    }
};
