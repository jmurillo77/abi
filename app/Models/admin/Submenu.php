<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'submenus';
    protected $primaryKey = 'IdSubMenu';
    public $timestamps = false;

    protected $fillable = [
        'IdMenu',
        'Titulo',
        'Ruta',
        'Orden',
        'Activo',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'IdMenu', 'IdMenu');
    }
}