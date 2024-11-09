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
        Schema::create('campaignemail', function (Blueprint $table) {
            $MatrizDB = Config::get(key: 'database.connections.matriz.database');
            $table->id('CampaignEmail');
            $table->foreignId('IdCampaign')->references('IdCampaign')->on("campaign");
            $table->foreignId('IdCorreo')->references('IdCorreo')->on("{$MatrizDB}.correos");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaignemail');
    }
};
