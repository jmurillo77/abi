<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContinenteController;
use App\Http\Controllers\Admin\TelefonomovilController;
use App\Http\Controllers\Admin\CorreoController;
use App\Http\Controllers\Admin\CampaignController;  
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

//Manejar expresiones regulares en las rutas
//Route::get('/cursos/{curso}', function ($curso) {
//    return "Bienvenido al curso de: " . $curso;
//})->where('curso', '[A-Za-z]+');

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
    Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
});

#Route::get('/', function(){
#    return view('admin.conf.dashboard');
#})->name('dashboard');

/*
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
*/


Auth::routes(); 

Route::middleware('auth')->prefix('admin')->group(function (){
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::prefix('continente')->name('continente.')->controller(ContinenteController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{continente}', 'show')->name('show');
        Route::get('/{continente}/edit', 'edit')->name('edit');
        Route::put('/{continente}', 'update')->name('update');
        Route::delete('/{continente}', 'destroy')->name('destroy');
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
    Route::prefix('campaign')->name('campaign.')->controller(CampaignController::class)->group(function(){
        Route::get('/', 'index')->name('index');
        //Route::get('/create', 'create')->name('create');
        //Route::post('/', 'store')->name('store');
        Route::get('/{campaign}', 'show')->name('show');
        //Route::get('/{campaign}/edit', 'edit')->name('edit');
        //Route::put('/{campaign}', 'update')->name('update');
        //Route::delete('/{campaign}', 'destroy')->name('destroy');
        Route::get('/exporta/{campaign}', 'exporta')->name('exporta');
    });
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('/events', function (){
        return view(view: 'events');
    })->name('events');
});