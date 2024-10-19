<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PersonaController;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\TelefonoTipoOperadora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba', function () {
    $Operadoras = TelefonoTipoOperadora::all();
    return view('prueba', compact('Operadoras'));

    //$Numeros = TelefonoMovil::all();
    //return view('prueba', compact('Numeros'));
    
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Auth::routes(); 

Route::middleware('auth')->prefix('admin')->group(function (){
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::prefix('persona')->name('persona.')->controller(PersonaController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{persona}', 'show')->name('show');
        Route::get('/{persona}/edit', 'edit')->name('edit');
        Route::put('/{persona}', 'update')->name('update');
        Route::delete('/{persona}', 'destroy')->name('destroy');
    });
});

/*
Route::middleware('auth')->prefix('admin')->group(function (){
    
    Route::prefix('persona')->name('persona.')->controller(PersonaController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{persona}', 'show')->name('show');
        Route::get('/{persona}/edit', 'edit')->name('edit');
        Route::put('/{persona}', 'update')->name('update');
        Route::delete('/{persona}', 'destroy')->name('destroy');
    });
});

Route::middleware('auth')->group(function (){
    Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
});


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
*/
