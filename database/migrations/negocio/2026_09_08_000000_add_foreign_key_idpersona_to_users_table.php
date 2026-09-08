<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 'personas' vive en la conexión 'matriz' (otra base de datos); Schema::table()
     * no puede referenciar una tabla fuera de la conexión actual, así que la FK
     * cruzada se agrega con SQL crudo y el nombre de base totalmente calificado
     * (mismo patrón ya usado por direccion.IdRuta hacia negocio.rutas).
     */
    public function up(): void
    {
        if (! Schema::connection('negocio')->hasTable('users')) {
            return;
        }

        if (! Schema::connection('matriz')->hasTable('personas')) {
            return;
        }

        if (! $this->foreignKeyExists('users_idpersona_foreign')) {
            $matrizDb = DB::connection('matriz')->getDatabaseName();

            DB::connection('negocio')->statement(
                "ALTER TABLE users ADD CONSTRAINT users_idpersona_foreign FOREIGN KEY (IdPersona) REFERENCES {$matrizDb}.personas(IdPersona)"
            );
        }
    }

    public function down(): void
    {
        if ($this->foreignKeyExists('users_idpersona_foreign')) {
            DB::connection('negocio')->statement('ALTER TABLE users DROP FOREIGN KEY users_idpersona_foreign');
        }
    }

    protected function foreignKeyExists(string $constraintName): bool
    {
        $database = DB::connection('negocio')->getDatabaseName();

        $rows = DB::connection('negocio')->select(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?',
            [$database, 'users', $constraintName]
        );

        return ! empty($rows);
    }
};
