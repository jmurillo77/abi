<?php


use App\Http\Controllers\Contacto\InicioController;
use App\Http\Controllers\Contacto\EmpresaController;
use App\Http\Controllers\Contacto\PersonaController;
use App\Http\Controllers\Admin\CorreoController;
use App\Http\Controllers\Admin\TelefonomovilController;
use App\Http\Controllers\Contacto\ContinenteController;
use App\Http\Controllers\Contacto\PaisController;
use App\Http\Controllers\Contacto\ProvinciaController;
use App\Http\Controllers\Contacto\CantonController;
use App\Http\Controllers\Contacto\ParroquiaController;
use App\Http\Controllers\Contacto\ProductoController;
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
    Route::prefix('telefono_movil')->name('telefono_movil.')->controller(TelefonomovilController::class)->group(function () {
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,contacto.telefono_movil.index');
        Route::get('/create', 'create')->name('create')->middleware('submenu.permission:create,contacto.telefono_movil.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,contacto.telefono_movil.index');
        Route::get('/{telefono_movil}', 'show')->name('show')->middleware('submenu.permission:view,contacto.telefono_movil.index');
        Route::get('/{telefono_movil}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,contacto.telefono_movil.index');
        Route::put('/{telefono_movil}', 'update')->name('update')->middleware('submenu.permission:edit,contacto.telefono_movil.index');
        Route::delete('/{telefono_movil}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,contacto.telefono_movil.index');
    });

    Route::prefix('correo')->name('correo.')->controller(CorreoController::class)->group(function () {
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,contacto.correo.index');
        Route::get('/create', 'create')->name('create')->middleware('submenu.permission:create,contacto.correo.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,contacto.correo.index');
        Route::get('/{correo}', 'show')->name('show')->middleware('submenu.permission:view,contacto.correo.index');
        Route::get('/{correo}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,contacto.correo.index');
        Route::put('/{correo}', 'update')->name('update')->middleware('submenu.permission:edit,contacto.correo.index');
        Route::delete('/{correo}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,contacto.correo.index');
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
    Route::prefix('provincia')->name('provincia.')->controller(ProvinciaController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,contacto.provincia.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,contacto.provincia.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,contacto.provincia.index');
        Route::get('/{provincia}', 'show')->name('show')->middleware('submenu.permission:view,contacto.provincia.index');
        Route::get('/{provincia}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,contacto.provincia.index');
        Route::put('/{provincia}', 'update')->name('update')->middleware('submenu.permission:edit,contacto.provincia.index');
        Route::delete('/{provincia}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,contacto.provincia.index');
    });
    Route::prefix('canton')->name('canton.')->controller(CantonController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,contacto.canton.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,contacto.canton.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,contacto.canton.index');
        Route::get('/{canton}', 'show')->name('show')->middleware('submenu.permission:view,contacto.canton.index');
        Route::get('/{canton}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,contacto.canton.index');
        Route::put('/{canton}', 'update')->name('update')->middleware('submenu.permission:edit,contacto.canton.index');
        Route::delete('/{canton}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,contacto.canton.index');
    });
    Route::prefix('parroquia')->name('parroquia.')->controller(ParroquiaController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,contacto.parroquia.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,contacto.parroquia.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,contacto.parroquia.index');
        Route::get('/{parroquia}', 'show')->name('show')->middleware('submenu.permission:view,contacto.parroquia.index');
        Route::get('/{parroquia}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,contacto.parroquia.index');
        Route::put('/{parroquia}', 'update')->name('update')->middleware('submenu.permission:edit,contacto.parroquia.index');
        Route::delete('/{parroquia}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,contacto.parroquia.index');
    });
});
