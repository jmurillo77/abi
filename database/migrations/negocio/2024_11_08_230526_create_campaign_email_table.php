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
        Schema::create('campaign_email', function (Blueprint $table) {
            $MatrizDB = DB::connection('matriz')->getDatabaseName();
            $table->id('CampaignEmail');
            $table->foreignId('IdCampaign')->references('IdCampaign')->on("campaign");
            $table->foreignId('IdCorreo')->references('IdCorreo')->on(new Expression($MatrizDB.'.correos'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_email');
    }
};
