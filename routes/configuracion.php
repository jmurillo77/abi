<?php


use App\Http\Controllers\Configuracion\InicioController;
use App\Http\Controllers\Configuracion\MenuController;
use App\Http\Controllers\Configuracion\SubmenuController;
use App\Http\Controllers\Configuracion\UserPermissionController;

use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [InicioController::class, 'index'])->name('dashboard');
    Route::prefix('menus')->name('menus.')->controller(MenuController::class)->group(function(){
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{menu}', 'show')->name('show');
            Route::get('/{menu}/edit', 'edit')->name('edit');
            Route::put('/{menu}', 'update')->name('update');
            Route::delete('/{menu}', 'destroy')->name('destroy');
        });

    Route::prefix('submenus')->name('submenus.')->controller(SubmenuController::class)->group(function(){
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{submenu}', 'show')->name('show');
            Route::get('/{submenu}/edit', 'edit')->name('edit');
            Route::put('/{submenu}', 'update')->name('update');
            Route::delete('/{submenu}', 'destroy')->name('destroy');
        });

    Route::prefix('users')->name('users.')->controller(UserPermissionController::class)->group(function(){
            Route::get('/', 'index')->name('index')->middleware('can:assign-menu-permissions');
            Route::get('/{user}/permissions', 'edit')->name('permissions.edit')->middleware('can:assign-menu-permissions');
            Route::get('/{user}/permissions/menu/{menu}/submenus', 'submenusByMenu')->name('permissions.submenus')->middleware('can:assign-menu-permissions');
            Route::put('/{user}/permissions', 'update')->name('permissions.update')->middleware('can:assign-menu-permissions');
        });
});
