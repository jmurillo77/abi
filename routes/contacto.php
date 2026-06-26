<?php


use App\Http\Controllers\Contacto\InicioController;
use App\Http\Controllers\Contacto\EmpresaController;
use App\Http\Controllers\Contacto\PersonaController;
use App\Http\Controllers\Contacto\ContinenteController;
use App\Http\Controllers\Contacto\PaisController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [InicioController::class, 'index'])->name('dashboard');
    Route::prefix('persona')->name('persona.')->controller(PersonaController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,contacto.persona.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,contacto.persona.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,contacto.persona.index');
        Route::get('/{persona}', 'show')->name('show')->middleware('submenu.permission:view,contacto.persona.index');
        Route::get('/{persona}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,contacto.persona.index');
        Route::put('/{persona}', 'update')->name('update')->middleware('submenu.permission:edit,contacto.persona.index');
        Route::delete('/{persona}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,contacto.persona.index');
    });
    Route::prefix('empresa')->name('empresa.')->controller(EmpresaController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,contacto.empresa.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,contacto.empresa.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,contacto.empresa.index');
        Route::get('/{empresa}', 'show')->name('show')->middleware('submenu.permission:view,contacto.empresa.index');
        Route::get('/{empresa}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,contacto.empresa.index');
        Route::put('/{empresa}', 'update')->name('update')->middleware('submenu.permission:edit,contacto.empresa.index');
        Route::delete('/{empresa}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,contacto.empresa.index');
    });
    Route::prefix('continente')->name('continente.')->controller(ContinenteController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,contacto.continente.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,contacto.continente.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,contacto.continente.index');
        Route::get('/{continente}', 'show')->name('show')->middleware('submenu.permission:view,contacto.continente.index');
        Route::get('/{continente}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,contacto.continente.index');
        Route::put('/{continente}', 'update')->name('update')->middleware('submenu.permission:edit,contacto.continente.index');
        Route::delete('/{continente}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,contacto.continente.index');
    });
    Route::prefix('pais')->name('pais.')->controller(PaisController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,contacto.pais.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,contacto.pais.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,contacto.pais.index');
        Route::get('/{pais}', 'show')->name('show')->middleware('submenu.permission:view,contacto.pais.index');
        Route::get('/{pais}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,contacto.pais.index');
        Route::put('/{pais}', 'update')->name('update')->middleware('submenu.permission:edit,contacto.pais.index');
        Route::delete('/{pais}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,contacto.pais.index');
    });
});
