<?php


use App\Http\Controllers\Contacto\EmpresaController;
use App\Http\Controllers\Contacto\PersonaController;
use App\Http\Controllers\Contacto\MainController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [MainController::class, 'index'])->name('dashboard');
    Route::prefix('persona')->name('persona.')->controller(PersonaController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('crear');
        Route::post('/', 'store')->name('store');
        Route::get('/{persona}', 'show')->name('show');
        //Route::get('/{persona}/edit', 'edit')->name('edit');
        //Route::put('/{persona}', 'update')->name('update');
        //Route::delete('/{persona}', 'destroy')->name('destroy');
    });
    Route::prefix('empresa')->name('empresa.')->controller(EmpresaController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('crear');
        Route::post('/', 'store')->name('store');
        Route::get('/{empresa}', 'show')->name('show');
        //Route::get('/{empresa}/edit', 'edit')->name('edit');
        //Route::put('/{empresa}', 'update')->name('update');
        //Route::delete('/{empresa}', 'destroy')->name('destroy');
    });
});


// Route::middleware('auth')->group(function (){
//     Route::prefix('empresa')->name('empresa.')->controller(EmpresaController::class)->group(function(){
//         Route::get('/', 'index')->name('index');
//         Route::get('/create', 'create')->name('crear');
//         Route::post('/', 'store')->name('store');
//         Route::get('/{empresa}', 'show')->name('show');
//         //Route::get('/{empresa}/edit', 'edit')->name('edit');
//         //Route::put('/{empresa}', 'update')->name('update');
//         //Route::delete('/{empresa}', 'destroy')->name('destroy');
//     });
// });