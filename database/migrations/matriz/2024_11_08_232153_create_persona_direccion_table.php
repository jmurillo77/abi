<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'matriz';

    public function up(): void
    {
        Schema::connection($this->connection)->create('persona_direccion', function (Blueprint $table) {
            $table->id('IdPersonaDireccion');
            $table->unsignedBigInteger('IdPersona');
            $table->unsignedBigInteger('IdDireccion');
            $table->foreign('IdPersona')->references('IdPersona')->on('personas')->cascadeOnDelete();
            $table->foreign('IdDireccion')->references('IdDireccion')->on('direccion')->cascadeOnDelete();
            $table->enum('Eliminado', ['S','N'])->default('N');
            $table->string('cUser')->nullable();
            $table->string('uUser')->nullable();
            $table->string('dUser')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('Tabla Direccion de Persona');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('persona_direccion');
    }
};
