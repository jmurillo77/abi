<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $connection = 'matriz';
    protected $table = 'menus';
    protected $primaryKey = 'IdMenu';

    protected $fillable = [
        'Titulo',
        'Ruta',
        'Icono',
        'parent_id',
        'order',
        'cUser',
        'uUser',
        'dUser',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'IdMenu');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'IdMenu');
    }

    public function users()
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'menu_user',
            'menu_id',
            'user_id'
        )->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete'])->withTimestamps();
    }
}
