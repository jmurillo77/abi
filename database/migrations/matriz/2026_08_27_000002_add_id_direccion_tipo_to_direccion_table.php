<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('matriz')->table('direccion', function (Blueprint $table) {
            if (! Schema::connection('matriz')->hasColumn('direccion', 'IdDireccionTipo')) {
                $table->unsignedBigInteger('IdDireccionTipo')->nullable()->after('Nombre');
            }
        });

        if (Schema::connection('matriz')->hasColumn('direccion', 'IdDireccionTipo')) {
            $database = config('database.connections.matriz.database');
            $exists = DB::connection('matriz')->selectOne(
                "SELECT 1 FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'direccion' AND COLUMN_NAME = 'IdDireccionTipo' AND CONSTRAINT_NAME <> '' LIMIT 1",
                [$database]
            );

            if (! $exists) {
                DB::connection('matriz')->statement(
                    'ALTER TABLE direccion ADD CONSTRAINT direccion_iddirecciontipo_foreign FOREIGN KEY (IdDireccionTipo) REFERENCES direccion_tipo(IdDireccionTipo)'
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('matriz')->table('direccion', function (Blueprint $table) {
            if (Schema::connection('matriz')->hasColumn('direccion', 'IdDireccionTipo')) {
                $table->dropColumn('IdDireccionTipo');
            }
        });
    }
};
