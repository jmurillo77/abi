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
        if (! Schema::connection($this->connection)->hasTable('roles')) {
            Schema::connection($this->connection)->create('roles', function (Blueprint $table) {
                $table->id('IdRol');
                $table->string('Nombre', 100)->unique();
                $table->text('Descripcion')->nullable();
                $table->boolean('Activo')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::connection('negocio')->hasTable('roles')) {
            $legacyRows = DB::connection('negocio')->table('roles')->get();

            foreach ($legacyRows as $row) {
                DB::connection('matriz')->table('roles')->updateOrInsert(
                    ['IdRol' => $row->IdRol],
                    (array) $row
                );
            }

            DB::connection('negocio')->table('roles')->delete();
        }
    }

    public function down(): void
    {
        Schema::connection('matriz')->dropIfExists('roles');
    }
};
