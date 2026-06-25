<?php

namespace App\Models;

use App\Models\admin\Submenu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $pivotTable = config('database.connections.negocio.database')
            ? config('database.connections.negocio.database').'.permisos_submenu_rol'
            : 'permisos_submenu_rol';

        return $this->belongsToMany(
            Submenu::class,
            $pivotTable,
            'IdRol',
            'IdSubMenu',
            'IdRol',
            'IdSubMenu'
        );
    }
}