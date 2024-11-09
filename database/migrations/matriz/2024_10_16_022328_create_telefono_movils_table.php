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
        Schema::connection(name: 'matriz')->create('telefono_movils', function (Blueprint $table) {
            $MatrizDB = Config::get(key: 'database.connections.matriz.database');
            $table->id('IdTelefonoMovil');
            $table->string('Numero', length: 10)->unique();
            $table->foreignId('IdOperadora')->references('IdOperadora')->on("{$MatrizDB}.telefono_tipo_operadoras")->default('1');
            $table->enum('PhoneValido', ['0', '1'])->default('1');
            $table->enum('WhatsappValido', ['0', '1'])->default('1');
            $table->string('cUser')->nullable();
            $table->string('uUser')->nullable();
            $table->string('dUser')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('Tabla de Telefonos Movil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(name: 'matriz')->dropIfExists('telefono_movils');
    }
};
