<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'negocio';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = $this->connection;
        $matrizDb = DB::connection('matriz')->getDatabaseName();

        if (! Schema::connection($connection)->hasTable('menu_user')) {
            Schema::connection($connection)->create('menu_user', function (Blueprint $table) use ($matrizDb) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedBigInteger('menu_id');
                $table->foreign('menu_id')->references('IdMenu')->on(DB::raw("{$matrizDb}.menus"))->cascadeOnDelete();
                $table->boolean('can_view')->default(true);
                $table->boolean('can_create')->default(false);
                $table->boolean('can_edit')->default(false);
                $table->boolean('can_delete')->default(false);
                $table->timestamps();
                $table->unique(['user_id', 'menu_id']);
            });
        }

        if (! Schema::connection($connection)->hasTable('submenu_user')) {
            Schema::connection($connection)->create('submenu_user', function (Blueprint $table) use ($matrizDb) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedBigInteger('submenu_id');
                $table->foreign('submenu_id')->references('IdSubMenu')->on(DB::raw("{$matrizDb}.submenus"))->cascadeOnDelete();
                $table->boolean('can_view')->default(true);
                $table->boolean('can_create')->default(false);
                $table->boolean('can_edit')->default(false);
                $table->boolean('can_delete')->default(false);
                $table->timestamps();
                $table->unique(['user_id', 'submenu_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: safety migration to ensure pivots exist across environments.
    }
};
