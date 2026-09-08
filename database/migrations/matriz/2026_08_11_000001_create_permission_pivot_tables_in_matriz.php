<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'matriz';

    public function up(): void
    {
        if (! Schema::connection('negocio')->hasTable('users')) {
            return;
        }

        $negocioDb = DB::connection('negocio')->getDatabaseName();
        $matrizDb = DB::connection('matriz')->getDatabaseName();

        if (! Schema::connection('negocio')->hasTable('menu_user')) {
            Schema::connection('negocio')->create('menu_user', function (Blueprint $table) use ($negocioDb, $matrizDb) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('menu_id');
                $table->boolean('can_view')->default(true);
                $table->boolean('can_create')->default(false);
                $table->boolean('can_edit')->default(false);
                $table->boolean('can_delete')->default(false);
                $table->timestamps();

                $table->unique(['user_id', 'menu_id']);
                $table->foreign('user_id')->references('id')->on(DB::raw("{$negocioDb}.users"))->cascadeOnDelete();
                $table->foreign('menu_id')->references('IdMenu')->on(DB::raw("{$matrizDb}.menus"))->cascadeOnDelete();
            });
        }

        if (! Schema::connection('negocio')->hasTable('submenu_user')) {
            Schema::connection('negocio')->create('submenu_user', function (Blueprint $table) use ($negocioDb, $matrizDb) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('submenu_id');
                $table->boolean('can_view')->default(true);
                $table->boolean('can_create')->default(false);
                $table->boolean('can_edit')->default(false);
                $table->boolean('can_delete')->default(false);
                $table->timestamps();

                $table->unique(['user_id', 'submenu_id']);
                $table->foreign('user_id')->references('id')->on(DB::raw("{$negocioDb}.users"))->cascadeOnDelete();
                $table->foreign('submenu_id')->references('IdSubMenu')->on(DB::raw("{$matrizDb}.submenus"))->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::connection('negocio')->dropIfExists('submenu_user');
        Schema::connection('negocio')->dropIfExists('menu_user');
    }
};
