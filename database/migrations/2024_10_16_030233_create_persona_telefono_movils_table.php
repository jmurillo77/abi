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
            $table->unsignedBigInteger('IdPersona');
            $table->foreign('IdPersona')->references('IdPersona')->on('personas');
            $table->unsignedBigInteger('IdTelefonoMovil');
            $table->foreign('IdTelefonoMovil')->references('IdTelefonoMovil')->on('telefono_movils');
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
