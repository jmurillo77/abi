<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'negocio';

    public function up(): void
    {
        Schema::connection(name: 'negocio')->create('submenu_user', function (Blueprint $table) {
            $MatrizDB = DB::connection('matriz')->getDatabaseName();

            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('submenu_id');
            $table->foreign('submenu_id')->references('IdSubMenu')->on(DB::raw("{$MatrizDB}.submenus"))->cascadeOnDelete();
            $table->boolean('can_view')->default(true);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'submenu_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(name: 'negocio')->dropIfExists('submenu_user');
    }
};
