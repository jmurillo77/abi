<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Configuracion\MenuController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngresarPedidosController;
use App\Http\Controllers\PedidoController;

Route::view('/', 'welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/menu', [AdminController::class, 'menu'])->name('menu');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::prefix('/ingresa-un-nuevo-pedido')->name('listaPedido.')->controller(IngresarPedidosController::class)->group(function () {
        Route::get('/formulario', [IngresarPedidosController::class, 'formulario'])->name('formulario');
        Route::get('/lista-pedidos', [IngresarPedidosController::class, 'listaPedidos'])->name('listaPedidos');
        Route::post('/pedido-guardado', [IngresarPedidosController::class, 'guardarPedido'])->name('guardarPedido');
    });

    Route::prefix('/pedido')->name('pedido.')->controller(PedidoController::class)->group(function () {
        Route::get('/{id}', [PedidoController::class, 'Pedido'])->name('pedido');
        Route::get('/pedidos-imagenes/{idImagen}', [PedidoController::class, 'PedidoImagenes'])->name('pedidoImagenes');
        Route::put('/editar', [PedidoController::class, 'PedidoEdit'])->name('pedidoEditar');
        Route::post('/eliminar-imagen/{id}', [PedidoController::class, 'eliminarImagen'])->name('eliminarImagen');
        Route::post('/subir-imagen/{id}', [PedidoController::class, 'subirImagen'])->name('subirImagen');
    });
});

Auth::routes();

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');

    Route::prefix('contacto')->name('contacto.')->group(function () {
        Route::resource('campaign', CampaignController::class)->only(['index', 'show']);
        Route::get('campaign/exporta/{campaign}', [CampaignController::class, 'exporta'])->name('campaign.exporta');
        Route::resource('menus', MenuController::class);
    });

    Route::view('/profile', 'profile.show')->name('profile');

    Route::view('/events', 'events')->name('events');
});