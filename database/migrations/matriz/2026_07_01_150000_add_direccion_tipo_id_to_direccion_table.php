<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'matriz';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasColumn('direccion', 'IdDireccionTipo')) {
            $matrizDb = DB::connection($this->connection)->getDatabaseName();

            Schema::connection($this->connection)->table('direccion', function (Blueprint $table) use ($matrizDb) {
                $table->foreignId('IdDireccionTipo')
                    ->nullable()
                    ->after('Nombre')
                    ->references('IdDireccionTipo')
                    ->on(new Expression($matrizDb.'.direccion_tipo'));
            });
        }
    }

    public function down(): void
    {
        if (Schema::connection($this->connection)->hasColumn('direccion', 'IdDireccionTipo')) {
            Schema::connection($this->connection)->table('direccion', function (Blueprint $table) {
                $table->dropForeign(['IdDireccionTipo']);
                $table->dropColumn('IdDireccionTipo');
            });
        }
    }
};
