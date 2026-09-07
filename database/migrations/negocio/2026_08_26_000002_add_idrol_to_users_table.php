<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('negocio')->hasTable('users')) {
            return;
        }

        if (! Schema::connection('negocio')->hasColumn('users', 'IdRol')) {
            Schema::connection('negocio')->table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('IdRol')->nullable()->after('IdPersona');
            });
        }

        $databaseName = DB::connection('matriz')->getDatabaseName();
        $fkName = 'users_idrol_foreign';

        $hasForeignKey = DB::connection('negocio')->selectOne("SELECT COUNT(*) AS total FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'IdRol' AND CONSTRAINT_NAME = '{$fkName}'");

        if (! $hasForeignKey || (int) $hasForeignKey->total === 0) {
            DB::connection('negocio')->statement(
                "ALTER TABLE users ADD CONSTRAINT {$fkName} FOREIGN KEY (IdRol) REFERENCES {$databaseName}.roles(IdRol) ON DELETE SET NULL"
            );
        }
    }

    public function down(): void
    {
        if (Schema::connection('negocio')->hasColumn('users', 'IdRol')) {
            try {
                DB::connection('negocio')->statement('ALTER TABLE users DROP FOREIGN KEY users_idrol_foreign');
            } catch (Throwable $e) {
                // Ignore if the constraint is already missing.
            }

            Schema::connection('negocio')->table('users', function (Blueprint $table) {
                $table->dropColumn('IdRol');
            });
        }
    }
};
