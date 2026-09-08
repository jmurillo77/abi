<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection(name: 'matriz')->create('empresa_telefono_movils', function (Blueprint $table) {
            $table->id('IdEmpresaTelefono');
            $table->foreignId('IdEmpresa')->references('IdEmpresa')->on('empresas');
            $table->foreignId('IdTelefonoMovil')->references('IdTelefonoMovil')->on('telefono_movils');
            $table->string('cUser')->nullable();
            $table->string('uUser')->nullable();
            $table->string('dUser')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('Tabla Empresa Correo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(name: 'matriz')->dropIfExists('empresa_telefono_movils');
    }
};
