<?php

use App\Http\Controllers\Admin\AdminController;
use App\Models\admin\Empresa;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use App\Models\admin\TelefonoTipoOperadora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba', function () {
    //$Operadoras = TelefonoTipoOperadora::all();
    //return view('prueba', compact('Operadoras'));

    //$Numeros = TelefonoMovil::all();
    //return view('prueba', compact('Numeros'));

    $persona = Persona::find(1);
    $numero = TelefonoMovil::find(2);
    return view('prueba', compact('persona', 'numero'));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    #Route::get('/admin', function () {
    #    return view('admin.admin');
    #})->name('admin');
    Route::get('/menu', [AdminController::class, 'menu'])->name('dashboard');
});

#Route::get('/', function(){
#    return view('admin.conf.dashboard');
#})->name('dashboard');

/*
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
*/
