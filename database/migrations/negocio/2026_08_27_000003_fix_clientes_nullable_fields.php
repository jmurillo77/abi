<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::connection('negocio')->statement('ALTER TABLE clientes MODIFY IdPersona BIGINT UNSIGNED NULL');
        DB::connection('negocio')->statement('ALTER TABLE clientes MODIFY IdEmpresa BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('negocio')->statement('ALTER TABLE clientes MODIFY IdPersona BIGINT UNSIGNED NOT NULL');
        DB::connection('negocio')->statement('ALTER TABLE clientes MODIFY IdEmpresa BIGINT UNSIGNED NULL');
    }
};
