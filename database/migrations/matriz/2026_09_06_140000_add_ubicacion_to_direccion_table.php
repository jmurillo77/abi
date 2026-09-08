<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('matriz')->table('direccion', function (Blueprint $table) {
            // URL de Google Maps o coordenadas "lat,lng" para ubicar la dirección en el mapa.
            $table->string('Ubicacion', 500)->nullable()->after('IdParroquia');
        });
    }

    public function down(): void
    {
        Schema::connection('matriz')->table('direccion', function (Blueprint $table) {
            $table->dropColumn('Ubicacion');
        });
    }
};
