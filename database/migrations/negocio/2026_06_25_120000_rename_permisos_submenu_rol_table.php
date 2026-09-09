<?php

use Illuminate\Database\Migrations\Migration;
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
        if (Schema::connection($this->connection)->hasTable('permisos_submenu_rol')
            && ! Schema::connection($this->connection)->hasTable('permiso_submenu_rol')) {
            Schema::connection($this->connection)->rename('permisos_submenu_rol', 'permiso_submenu_rol');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection($this->connection)->hasTable('permiso_submenu_rol')
            && ! Schema::connection($this->connection)->hasTable('permisos_submenu_rol')) {
            Schema::connection($this->connection)->rename('permiso_submenu_rol', 'permisos_submenu_rol');
        }
    }
};
