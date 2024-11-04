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
        Schema::create('persona_correos', function (Blueprint $table) {
            $table->id('IdPersonaCorreo');
            $table->foreignId('IdPersona')->references('IdPersona')->on('personas');
            $table->foreignId('IdCorreo')->references('IdCorreo')->on('correos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_correos');
    }
};
