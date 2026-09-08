<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'negocio';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasColumn('users', 'name')) {
            Schema::connection($this->connection)->table('users', function (Blueprint $table) {
                $table->string('name')->nullable()->after('IdRol');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection($this->connection)->hasColumn('users', 'name')) {
            Schema::connection($this->connection)->table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
};
