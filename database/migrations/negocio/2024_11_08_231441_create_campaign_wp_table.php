<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaign_wp', function (Blueprint $table) {
            $MatrizDB = DB::connection('matriz')->getDatabaseName();
            $table->id('IdCampaignWP');
            $table->foreignId('IdCampaign')->references('IdCampaign')->on("campaign");
            $table->foreignId('IdTelefonoMovil')->references('IdTelefonoMovil')->on(new Expression($MatrizDB.'.telefono_movils'));
            $table->enum('MensajeEnviado', ['S','N'])->default('N');
            $table->enum('Status', ['Por Contactar','Interesado','No Interesado','Venta'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_wp');
    }
};
