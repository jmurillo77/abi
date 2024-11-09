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
        Schema::create('campaigncallmovil', function (Blueprint $table) {
            $MatrizDB = Config::get(key: 'database.connections.matriz.database');
            $table->id('IdCampaignCallMovil');
            $table->foreignId('IdCampaign')->references('IdCampaign')->on("campaign");
            $table->foreignId('IdTelefonoMovil')->references('IdTelefonoMovil')->on("{$MatrizDB}.telefono_movils");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigncallmovil');
    }
};
