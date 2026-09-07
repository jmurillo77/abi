<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection('negocio');

        if (! $schema->hasColumn('clientes', 'TipoCliente')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->string('TipoCliente', 20)->default('persona')->after('id');
            });
        }

        if (! $schema->hasColumn('clientes', 'IdPersona')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->unsignedBigInteger('IdPersona')->nullable()->after('TipoCliente');
            });
        }

        if (! $schema->hasColumn('clientes', 'IdEmpresa')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->unsignedBigInteger('IdEmpresa')->nullable()->after('IdPersona');
            });
        }

        if (! $schema->hasColumn('clientes', 'Nombre')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->string('Nombre', 255)->nullable()->after('IdEmpresa');
            });
        }

        if (! $schema->hasColumn('clientes', 'Documento')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->string('Documento', 50)->nullable()->after('Nombre');
            });
        }

        if (! $schema->hasColumn('clientes', 'Email')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->string('Email', 150)->nullable()->after('Documento');
            });
        }

        if (! $schema->hasColumn('clientes', 'Activo')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->boolean('Activo')->default(true)->after('Email');
            });
        }

        $matrizDb = DB::connection('matriz')->getDatabaseName();

        if (! $this->foreignKeyExists('clientes_idpersona_foreign')) {
            DB::connection('negocio')->statement(
                "ALTER TABLE clientes ADD CONSTRAINT clientes_idpersona_foreign FOREIGN KEY (IdPersona) REFERENCES {$matrizDb}.personas(IdPersona)"
            );
        }

        if (! $this->foreignKeyExists('clientes_idempresa_foreign')) {
            DB::connection('negocio')->statement(
                "ALTER TABLE clientes ADD CONSTRAINT clientes_idempresa_foreign FOREIGN KEY (IdEmpresa) REFERENCES {$matrizDb}.empresas(IdEmpresa)"
            );
        }

        DB::connection('negocio')->table('clientes')->whereNull('TipoCliente')->update([
            'TipoCliente' => 'persona',
            'Activo' => true,
        ]);

        if ($schema->hasColumn('clientes', 'IdPersona')) {
            DB::connection('negocio')->table('clientes')->whereNotNull('IdPersona')->update([
                'TipoCliente' => 'persona',
                'Nombre' => DB::raw("(SELECT CONCAT(Nombres, ' ', Apellidos) FROM {$matrizDb}.personas WHERE {$matrizDb}.personas.IdPersona = clientes.IdPersona)"),
                'Documento' => DB::raw("(SELECT DNI FROM {$matrizDb}.personas WHERE {$matrizDb}.personas.IdPersona = clientes.IdPersona)"),
            ]);
        }

        if ($schema->hasColumn('clientes', 'IdEmpresa')) {
            DB::connection('negocio')->table('clientes')->whereNull('IdPersona')->whereNotNull('IdEmpresa')->update([
                'TipoCliente' => 'empresa',
                'Nombre' => DB::raw("(SELECT RazonSocial FROM {$matrizDb}.empresas WHERE {$matrizDb}.empresas.IdEmpresa = clientes.IdEmpresa)"),
                'Documento' => DB::raw("(SELECT RUC FROM {$matrizDb}.empresas WHERE {$matrizDb}.empresas.IdEmpresa = clientes.IdEmpresa)"),
            ]);
        }
    }

    protected function foreignKeyExists(string $constraintName): bool
    {
        $database = DB::connection('negocio')->getDatabaseName();

        $rows = DB::connection('negocio')->select(
            'SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?',
            [$database, 'clientes', $constraintName]
        );

        return ! empty($rows);
    }

    public function down(): void
    {
        $schema = Schema::connection('negocio');

        if ($this->foreignKeyExists('clientes_idempresa_foreign')) {
            DB::connection('negocio')->statement('ALTER TABLE clientes DROP FOREIGN KEY clientes_idempresa_foreign');
        }

        if ($this->foreignKeyExists('clientes_idpersona_foreign')) {
            DB::connection('negocio')->statement('ALTER TABLE clientes DROP FOREIGN KEY clientes_idpersona_foreign');
        }

        if ($schema->hasColumn('clientes', 'Activo')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->dropColumn('Activo');
            });
        }

        if ($schema->hasColumn('clientes', 'Email')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->dropColumn('Email');
            });
        }

        if ($schema->hasColumn('clientes', 'Documento')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->dropColumn('Documento');
            });
        }

        if ($schema->hasColumn('clientes', 'Nombre')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->dropColumn('Nombre');
            });
        }

        if ($schema->hasColumn('clientes', 'IdEmpresa')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->dropColumn('IdEmpresa');
            });
        }

        if ($schema->hasColumn('clientes', 'IdPersona')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->dropColumn('IdPersona');
            });
        }

        if ($schema->hasColumn('clientes', 'TipoCliente')) {
            $schema->table('clientes', function (Blueprint $table) {
                $table->dropColumn('TipoCliente');
            });
        }
    }
};
