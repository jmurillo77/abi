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
        Schema::connection(name: 'matriz')->create('empresas', function (Blueprint $table) {
            $MatrizDB = DB::connection('matriz')->getDatabaseName();
            $table->id('IdEmpresa');
            $table->string('RUC', length: 13)->unique()->nullable();
            $table->string('RazonSocial', length: 200)->nullable();
            $table->foreignId('RepresentanteLegal')->nullable()->references('IdPersona')->on(new Expression($MatrizDB.'.personas'));
            $table->enum('Eliminado', ['S','N'])->default('N');
            $table->string('cUser')->nullable();
            $table->string('uUser')->nullable();
            $table->string('dUser')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->comment('Tabla de Empresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(name: 'matriz')->dropIfExists('empresas');
    }
};
