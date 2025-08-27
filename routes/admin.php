<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\PersonaController;
use App\Http\Controllers\Admin\TelefonomovilController;
use App\Http\Controllers\Admin\CorreoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

#Route::middleware([
#    'auth:sanctum',
#    config('jetstream.auth_session'),
#    'verified',
#])->group(function () {
#    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
#});

#Auth::routes(); 


Route::middleware('auth')->group(function (){
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::prefix('empresa')->name('empresa.')->controller(EmpresaController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('crear');
        Route::post('/', 'store')->name('store');
        Route::get('/{empresa}', 'show')->name('show');
        //Route::get('/{empresa}/edit', 'edit')->name('edit');
        //Route::put('/{empresa}', 'update')->name('update');
        //Route::delete('/{empresa}', 'destroy')->name('destroy');
    });
    Route::prefix('persona')->name('persona.')->controller(PersonaController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('crear');
        Route::post('/', 'store')->name('store');
        Route::get('/{persona}', 'show')->name('show');
        //Route::get('/{persona}/edit', 'edit')->name('edit');
        //Route::put('/{persona}', 'update')->name('update');
        //Route::delete('/{persona}', 'destroy')->name('destroy');
    });
    Route::prefix('telefono_movil')->name('telefono_movil.')->controller(TelefonomovilController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        //Route::get('/create', 'create')->name('create');
        //Route::post('/', 'store')->name('store');
        Route::get('/{telefono_movil}', 'show')->name('show');
        //Route::get('/{telefono_movil}/edit', 'edit')->name('edit');
        //Route::put('/{telefono_movil}', 'update')->name('update');
        //Route::delete('/{telefono_movil}', 'destroy')->name('destroy');
    });
    Route::prefix('correo')->name('correo.')->controller(CorreoController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        //Route::get('/create', 'create')->name('create');
        //Route::post('/', 'store')->name('store');
        Route::get('/{correo}', 'show')->name('show');
        //Route::get('/{correo}/edit', 'edit')->name('edit');
        //Route::put('/{correo}', 'update')->name('update');
        //Route::delete('/{correo}', 'destroy')->name('destroy');
    });
});