<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\ContinenteController;
use App\Http\Controllers\Admin\CorreoController;
use App\Http\Controllers\Admin\TelefonomovilController;
use App\Http\Controllers\Configuracion\MenuController;
use App\Models\admin\Persona;
use App\Models\admin\TelefonoMovil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
});

Auth::routes();

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    Route::prefix('contacto')->name('contacto.')->group(function () {
        Route::resource('continente', ContinenteController::class);
        Route::resource('telefono_movil', TelefonomovilController::class)->only(['index', 'show']);
        Route::resource('correo', CorreoController::class)->only(['index', 'show']);
        Route::resource('campaign', CampaignController::class)->only(['index', 'show']);
        Route::get('campaign/exporta/{campaign}', [CampaignController::class, 'exporta'])->name('campaign.exporta');
        Route::resource('menus', MenuController::class);
    });

    Route::view('/profile', 'profile.show')->name('profile');

    Route::view('/events', 'events')->name('events');
});