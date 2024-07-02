<?php

use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\PersonaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->prefix('admin')->group(function (){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('admin');
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::prefix('persona')->name('persona.')->controller(PersonaController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{persona}', 'show')->name('show');
        Route::get('/{persona}/edit', 'edit')->name('edit');
        Route::put('/{persona}', 'update')->name('update');
        Route::delete('/{persona}', 'destroy')->name('destroy');
    });

    Route::prefix('empresa')->name('empresa.')->controller(EmpresaController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{empresa}', 'show')->name('show');
        Route::get('/{empresa}/edit', 'edit')->name('edit');
        Route::put('/{empresa}', 'update')->name('update');
        Route::delete('/{empresa}', 'destroy')->name('destroy');
    });

    Route::get('/events', function (){
        return view(view: 'events');
    })->name('events');
});
