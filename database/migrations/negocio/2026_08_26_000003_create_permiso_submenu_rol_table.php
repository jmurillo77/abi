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
        if (! Schema::connection($this->connection)->hasTable('permiso_submenu_rol')) {
            Schema::connection($this->connection)->create('permiso_submenu_rol', function (Blueprint $table) {
                $matrizDB = DB::connection('matriz')->getDatabaseName();

                $table->unsignedBigInteger('IdRol');
                $table->unsignedBigInteger('IdSubMenu');

                $table->primary(['IdRol', 'IdSubMenu']);
                $table->index('IdSubMenu');

                if (Schema::connection('matriz')->hasTable('roles')) {
                    $table->foreign('IdRol')
                        ->references('IdRol')
                        ->on(DB::raw("{$matrizDB}.roles"))
                        ->cascadeOnDelete();
                }

                $table->foreign('IdSubMenu')
                    ->references('IdSubMenu')
                    ->on(DB::raw("{$matrizDB}.submenus"))
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('permiso_submenu_rol');
    }
};
