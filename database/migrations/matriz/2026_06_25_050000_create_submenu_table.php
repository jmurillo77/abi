<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'matriz';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('submenus')) {
            Schema::connection($this->connection)->create('submenus', function (Blueprint $table) {
                $table->id('IdSubMenu');
                $table->unsignedBigInteger('IdMenu');
                $table->string('Titulo', 255);
                $table->string('Ruta', 255)->nullable();
                $table->string('Icono', 120)->nullable();
                $table->integer('Orden')->default(0);
                $table->boolean('Activo')->default(1);
                $table->foreign('IdMenu')->references('IdMenu')->on('menus')->cascadeOnDelete();
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });
        }

        $menuId = DB::connection($this->connection)
            ->table('menus')
            ->where('Ruta', 'ventas.dashboard')
            ->value('IdMenu')
            ?? DB::connection($this->connection)
                ->table('menus')
                ->where('Titulo', 'Ventas')
                ->value('IdMenu');

        if ($menuId === null) {
            $menuId = DB::connection($this->connection)
                ->table('menus')
                ->insertGetId([
                    'Titulo' => 'Ventas',
                    'Ruta' => 'ventas.dashboard',
                    'Icono' => 'fas fa-cash-register',
                    'Orden' => 1,
                    'Activo' => 1,
                ]);
        }

        DB::connection($this->connection)
            ->table('submenus')
            ->where('Ruta', 'contacto.producto.index')
            ->update(['Ruta' => 'ventas.producto.index']);

        DB::connection($this->connection)
            ->table('submenus')
            ->updateOrInsert(
                ['Ruta' => 'ventas.producto.index'],
                [
                    'IdMenu' => (int) $menuId,
                    'Titulo' => 'Productos',
                    'Icono' => 'fas fa-utensils|#16a34a',
                    'Orden' => 10,
                    'Activo' => 1,
                ]
            );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::connection($this->connection)->hasTable('submenus')) {
            DB::connection($this->connection)
                ->table('submenus')
                ->where('Ruta', 'ventas.producto.index')
                ->update(['Ruta' => 'contacto.producto.index']);

            Schema::connection($this->connection)->dropIfExists('submenus');
        }
    }
};
