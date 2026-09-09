<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'negocio';

    public function up(): void
    {
        Schema::connection(name: 'negocio')->create('users', function (Blueprint $table) {
            // La FK hacia matriz.personas se agrega en una migración posterior
            // (2026_09_08_000000_add_foreign_key_idpersona_to_users_table), porque
            // 'personas' vive en otra base de datos y su tabla todavía no existe
            // en este punto de una instalación nueva (orden global por timestamp).
            $table->id();
            $table->foreignId('IdPersona')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('Avatar')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });

        Schema::connection('negocio')->create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::connection('negocio')->create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(name: 'negocio')->dropIfExists('users');
        Schema::connection(name: 'negocio')->dropIfExists('password_reset_tokens');
        Schema::connection(name: 'negocio')->dropIfExists('sessions');
    }
};
