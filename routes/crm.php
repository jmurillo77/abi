<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\PersonaController;
use App\Http\Controllers\Admin\TelefonomovilController;
use App\Http\Controllers\Admin\CorreoController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
});

#Route::get('/', function(){
#    return view('admin.crm.dashboard');
#})->name('crm_dashboard');

#Route::get('/', [AdminController::class, 'index'])->name('admin');

Route::middleware('auth')->group(function (){
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
});