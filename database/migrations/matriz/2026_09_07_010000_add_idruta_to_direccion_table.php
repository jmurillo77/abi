<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('matriz');

        if (! $schema->hasColumn('direccion', 'IdRuta')) {
            $schema->table('direccion', function (Blueprint $table) {
                // Recorrido de entrega por defecto para esta dirección específica.
                $table->unsignedBigInteger('IdRuta')->nullable()->after('Ubicacion');
            });
        }

        // 'rutas' vive en la conexión 'negocio' (otra base de datos); Schema::table()
        // no puede referenciar una tabla fuera de la conexión actual, así que la FK
        // cruzada se agrega con SQL crudo y el nombre de base totalmente calificado
        // (mismo patrón ya usado por clientes.IdPersona/IdEmpresa hacia matriz).
        if (! $this->foreignKeyExists('direccion_idruta_foreign')) {
            $negocioDb = DB::connection('negocio')->getDatabaseName();

            DB::connection('matriz')->statement(
                "ALTER TABLE direccion ADD CONSTRAINT direccion_idruta_foreign FOREIGN KEY (IdRuta) REFERENCES {$negocioDb}.rutas(id) ON DELETE SET NULL"
            );
        }
    }

    public function down(): void
    {
        if ($this->foreignKeyExists('direccion_idruta_foreign')) {
            DB::connection('matriz')->statement('ALTER TABLE direccion DROP FOREIGN KEY direccion_idruta_foreign');
        }

        $schema = Schema::connection('matriz');

        if ($schema->hasColumn('direccion', 'IdRuta')) {
            $schema->table('direccion', function (Blueprint $table) {
                $table->dropColumn('IdRuta');
            });
        }
    }

    protected function foreignKeyExists(string $constraintName): bool
    {
        $database = DB::connection('matriz')->getDatabaseName();

        $rows = DB::connection('matriz')->select(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?',
            [$database, 'direccion', $constraintName]
        );

        return ! empty($rows);
    }
};
