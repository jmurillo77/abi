<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::connection('matriz')->table('submenus')
            ->where('Ruta', 'contacto.producto.index')
            ->update(['Ruta' => 'ventas.producto.index']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('matriz')->table('submenus')
            ->where('Ruta', 'ventas.producto.index')
            ->update(['Ruta' => 'contacto.producto.index']);
    }
};
