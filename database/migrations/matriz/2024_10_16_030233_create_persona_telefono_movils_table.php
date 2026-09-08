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
        Schema::connection(name: 'matriz')->create('persona_telefono_movils', function (Blueprint $table) {
            $MatrizDB = DB::connection('matriz')->getDatabaseName();
            $table->id('IdPersonaTelefono');
            $table->foreignId('IdPersona')->references('IdPersona')->on('personas');
            $table->foreignId('IdTelefonoMovil')->references('IdTelefonoMovil')->on('telefono_movils');
            $table->string('cUser')->nullable();
            $table->string('uUser')->nullable();
            $table->string('dUser')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('Tabla de Persona Telefonos Movil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(name: 'matriz')->dropIfExists('persona_telefono_movils');
    }
};
