<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('negocio')->create('rutas', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre', 150);
            $table->date('Fecha');
            $table->enum('Estado', ['PLANIFICADA', 'EN_CURSO', 'FINALIZADA', 'CANCELADA'])->default('PLANIFICADA');
            $table->string('Observaciones', 500)->nullable();
            $table->string('cUser')->nullable();
            $table->string('uUser')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('negocio')->dropIfExists('rutas');
    }
};
