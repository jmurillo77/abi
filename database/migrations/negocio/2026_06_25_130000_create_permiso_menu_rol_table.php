<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Use the negocio connection for this migration.
     *
     * @var string
     */
    protected $connection = 'negocio';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('permiso_menu_rol', function (Blueprint $table) {
            $matrizDB = DB::connection('matriz')->getDatabaseName();

            $table->unsignedBigInteger('IdRol');
            $table->unsignedBigInteger('IdMenu');

            $table->primary(['IdRol', 'IdMenu']);
            $table->index('IdMenu');

            $table->foreign('IdRol')
                ->references('IdRol')
                ->on('roles')
                ->cascadeOnDelete();

            $table->foreign('IdMenu')
                ->references('IdMenu')
                ->on(DB::raw("{$matrizDB}.menus"))
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('permiso_menu_rol');
    }
};
