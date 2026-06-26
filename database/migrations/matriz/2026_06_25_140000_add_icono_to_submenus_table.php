<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Use matriz connection for this migration.
     *
     * @var string
     */
    protected $connection = 'matriz';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasColumn('submenus', 'Icono')) {
            Schema::connection($this->connection)->table('submenus', function (Blueprint $table) {
                $table->string('Icono', 120)->nullable()->after('Titulo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection($this->connection)->hasColumn('submenus', 'Icono')) {
            Schema::connection($this->connection)->table('submenus', function (Blueprint $table) {
                $table->dropColumn('Icono');
            });
        }
    }
};
