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
        Schema::create('persona_telefono_movils', function (Blueprint $table) {
            $table->id('IdPersonaTelefono');
            $table->foreignId('IdPersona')->references('IdPersona')->on('personas');
            $table->foreignId('IdTelefonoMovil')->references('IdTelefonoMovil')->on('telefono_movils');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_telefono_movils');
    }
};
