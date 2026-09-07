<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'matriz';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('menus')) {
            return;
        }

        if (Schema::connection($this->connection)->hasColumn('menus', 'order')
            && ! Schema::connection($this->connection)->hasColumn('menus', 'Orden')) {
            Schema::connection($this->connection)->table('menus', function (Blueprint $table) {
                $table->renameColumn('order', 'Orden');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::connection($this->connection)->hasTable('menus')) {
            return;
        }

        if (Schema::connection($this->connection)->hasColumn('menus', 'Orden')
            && ! Schema::connection($this->connection)->hasColumn('menus', 'order')) {
            Schema::connection($this->connection)->table('menus', function (Blueprint $table) {
                $table->renameColumn('Orden', 'order');
            });
        }
    }
};
