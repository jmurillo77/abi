<?php

namespace App\Models;

use App\Models\admin\Menu;
use App\Models\admin\Submenu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Role extends Model
{
    use HasFactory;

    protected $connection = 'negocio';
    protected $table = 'roles';
    protected $primaryKey = 'IdRol';
    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Descripcion',
        'Activo',
    ];

    public function submenus()
    {
        $pivotBaseTable = Schema::connection('negocio')->hasTable('permiso_submenu_rol')
            ? 'permiso_submenu_rol'
            : 'permisos_submenu_rol';

        $pivotTable = config('database.connections.negocio.database')
            ? config('database.connections.negocio.database').'.'.$pivotBaseTable
            : $pivotBaseTable;

        return $this->belongsToMany(
            Submenu::class,
            $pivotTable,
            'IdRol',
            'IdSubMenu',
            'IdRol',
            'IdSubMenu'
        );
    }

    public function menus()
    {
        $pivotTable = config('database.connections.negocio.database')
            ? config('database.connections.negocio.database').'.permiso_menu_rol'
            : 'permiso_menu_rol';

        return $this->belongsToMany(
            Menu::class,
            $pivotTable,
            'IdRol',
            'IdMenu',
            'IdRol',
            'IdMenu'
        );
    }
}