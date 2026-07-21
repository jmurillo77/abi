<?php


use App\Http\Controllers\Venta\InicioController;
use App\Http\Controllers\Venta\ProductoController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [InicioController::class, 'index'])->name('dashboard');
    Route::prefix('producto')->name('producto.')->controller(ProductoController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,ventas.producto.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,ventas.producto.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,ventas.producto.index');
        Route::get('/{producto}', 'show')->name('show')->middleware('submenu.permission:view,ventas.producto.index');
        Route::get('/{producto}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,ventas.producto.index');
        Route::put('/{producto}', 'update')->name('update')->middleware('submenu.permission:edit,ventas.producto.index');
        Route::delete('/{producto}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,ventas.producto.index');
    });
});
