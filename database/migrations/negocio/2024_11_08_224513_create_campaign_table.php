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
        Schema::create('campaign', function (Blueprint $table) {
            $table->id('IdCampaign');
            $table->string('Nombre', length: 100);
            $table->foreignId('IdCampaignType')->references('IdCampaignType')->on("campaign_type");
            $table->enum('Estado', ['A','I','T'])->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign');
    }
};
