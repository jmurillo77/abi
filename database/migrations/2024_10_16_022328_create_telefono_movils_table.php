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
        Schema::create('telefono_movils', function (Blueprint $table) {
            $table->id('IdTelefonoMovil');
            $table->string('Numero')->unique();
            $table->foreignId('IdOperadora')->references('IdOperadora')->on('telefono_tipo_operadoras');
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
        Schema::dropIfExists('telefono_movils');
    }
};
