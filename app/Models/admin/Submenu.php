<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

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
        'Icono',
        'Ruta',
        'Orden',
        'Activo',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $submenu) {
            $ruta = trim((string) ($submenu->Ruta ?? ''));

            if ($ruta === '') {
                $submenu->Ruta = null;

                return;
            }

            $isAbsoluteUrl = str_starts_with($ruta, 'http://') || str_starts_with($ruta, 'https://');
            $isAbsolutePath = str_starts_with($ruta, '/');
            $isRelativePath = str_contains($ruta, '/');
            $isNamedRoute = Route::has($ruta);

            if (! $isAbsoluteUrl && ! $isAbsolutePath && ! $isRelativePath && ! $isNamedRoute) {
                throw ValidationException::withMessages([
                    'Ruta' => "La ruta [{$ruta}] no existe. Usa un nombre de ruta válido, una URL absoluta o un path.",
                ]);
            }

            $submenu->Ruta = $ruta;
        });
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'IdMenu', 'IdMenu');
    }
}