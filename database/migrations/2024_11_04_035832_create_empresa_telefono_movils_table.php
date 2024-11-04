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
        Schema::create('empresa_telefono_movils', function (Blueprint $table) {
            $table->id('IdEmpresaTelefono');
            $table->foreignId('IdEmpresa')->references('IdEmpresa')->on('empresas');
            $table->foreignId('IdTelefonoMovil')->references('IdTelefonoMovil')->on('telefono_movils');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa_telefono_movils');
    }
};
