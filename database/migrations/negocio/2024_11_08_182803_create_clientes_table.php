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
        Schema::connection(name: 'mysql')->create('clientes', function (Blueprint $table) {
            $MatrizDB = Config::get(key: 'database.connections.matriz.database');
            $table->id();
            $table->foreignId('IdPersona')->references('IdPersona')->on("{$MatrizDB}.personas");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(name: 'mysql')->dropIfExists('clientes');
    }
};
