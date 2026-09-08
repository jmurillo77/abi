<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection = 'matriz';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('menus')) {
            Schema::connection($this->connection)->create('menus', function (Blueprint $table) {
                $table->id('IdMenu');
                $table->string('Titulo', 255);
                $table->string('Ruta', 255)->nullable();
                $table->string('Icono', 100)->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->foreign('parent_id')->references('IdMenu')->on('menus')->nullOnDelete();
                $table->integer('Orden')->nullable();
                $table->boolean('Activo')->default(true);
                $table->string('cUser')->nullable();
                $table->string('uUser')->nullable();
                $table->string('dUser')->nullable();
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable();
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->comment('Tabla Menus del Sistema');
            });
        }

        $columns = Schema::connection($this->connection)->getColumnListing('menus');

        foreach (['Titulo', 'Ruta', 'Icono', 'parent_id', 'Orden', 'Activo', 'cUser', 'uUser', 'dUser', 'deleted_at'] as $column) {
            if (! in_array($column, $columns, true)) {
                if ($column === 'Orden' && in_array('order', $columns, true)) {
                    Schema::connection($this->connection)->table('menus', function (Blueprint $table) {
                        $table->renameColumn('order', 'Orden');
                    });

                    $columns = Schema::connection($this->connection)->getColumnListing('menus');
                    continue;
                }

                Schema::connection($this->connection)->table('menus', function (Blueprint $table) use ($column) {
                    if ($column === 'Titulo') {
                        $table->string('Titulo', 255)->nullable()->after('IdMenu');
                    } elseif ($column === 'Ruta') {
                        $table->string('Ruta', 255)->nullable()->after('Titulo');
                    } elseif ($column === 'Icono') {
                        $table->string('Icono', 100)->nullable()->after('Ruta');
                    } elseif ($column === 'parent_id') {
                        $table->unsignedBigInteger('parent_id')->nullable()->after('Icono');
                    } elseif ($column === 'Orden') {
                        $table->integer('Orden')->nullable()->after('parent_id');
                    } elseif ($column === 'Activo') {
                        $table->boolean('Activo')->default(true)->after('Orden');
                    } elseif ($column === 'cUser') {
                        $table->string('cUser')->nullable()->after('Activo');
                    } elseif ($column === 'uUser') {
                        $table->string('uUser')->nullable()->after('cUser');
                    } elseif ($column === 'dUser') {
                        $table->string('dUser')->nullable()->after('uUser');
                    } elseif ($column === 'deleted_at') {
                        $table->timestamp('deleted_at')->nullable()->after('updated_at');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('menus');
    }
};
