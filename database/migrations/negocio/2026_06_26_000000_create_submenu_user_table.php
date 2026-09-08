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
    public function up(): void
    {
        if (! Schema::hasTable('submenu_user')) {
            Schema::create('submenu_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedBigInteger('submenu_id');
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
        Schema::dropIfExists('submenu_user');
    }
};
