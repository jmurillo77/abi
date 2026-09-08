<?php


use App\Http\Controllers\Contacto\ClienteController;
use App\Http\Controllers\Venta\InicioController;
use App\Http\Controllers\Venta\PedidoController;
use App\Http\Controllers\Venta\ProductoController;
use App\Http\Controllers\Venta\RutaController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [InicioController::class, 'index'])->name('dashboard');
    Route::prefix('cliente')->name('cliente.')->controller(ClienteController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,ventas.cliente.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,ventas.cliente.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,ventas.cliente.index');
        Route::get('/{cliente}', 'show')->name('show')->middleware('submenu.permission:view,ventas.cliente.index');
        Route::get('/{cliente}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,ventas.cliente.index');
        Route::put('/{cliente}', 'update')->name('update')->middleware('submenu.permission:edit,ventas.cliente.index');
        Route::delete('/{cliente}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,ventas.cliente.index');
    });

    Route::prefix('producto')->name('producto.')->controller(ProductoController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,ventas.producto.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,ventas.producto.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,ventas.producto.index');
        Route::get('/{producto}', 'show')->name('show')->middleware('submenu.permission:view,ventas.producto.index');
        Route::get('/{producto}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,ventas.producto.index');
        Route::put('/{producto}', 'update')->name('update')->middleware('submenu.permission:edit,ventas.producto.index');
        Route::delete('/{producto}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,ventas.producto.index');
    });

    Route::prefix('pedido')->name('pedido.')->controller(PedidoController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,ventas.pedido.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,ventas.pedido.index');
        Route::get('/clientes/{cliente}/direcciones', 'direccionesCliente')->name('direcciones')->middleware('submenu.permission:create,ventas.pedido.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,ventas.pedido.index');
        Route::get('/{pedido}', 'show')->name('show')->middleware('submenu.permission:view,ventas.pedido.index');
        Route::get('/{pedido}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,ventas.pedido.index');
        Route::put('/{pedido}', 'update')->name('update')->middleware('submenu.permission:edit,ventas.pedido.index');
        Route::delete('/{pedido}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,ventas.pedido.index');
    });

    Route::prefix('ruta')->name('ruta.')->controller(RutaController::class)->group(function(){
        Route::get('/', 'index')->name('index')->middleware('submenu.permission:view,ventas.ruta.index');
        Route::get('/create', 'create')->name('crear')->middleware('submenu.permission:create,ventas.ruta.index');
        Route::post('/', 'store')->name('store')->middleware('submenu.permission:create,ventas.ruta.index');
        Route::get('/{ruta}', 'show')->name('show')->middleware('submenu.permission:view,ventas.ruta.index');
        Route::get('/{ruta}/edit', 'edit')->name('edit')->middleware('submenu.permission:edit,ventas.ruta.index');
        Route::put('/{ruta}', 'update')->name('update')->middleware('submenu.permission:edit,ventas.ruta.index');
        Route::delete('/{ruta}', 'destroy')->name('destroy')->middleware('submenu.permission:delete,ventas.ruta.index');
        Route::post('/{ruta}/pedidos', 'asignarPedidos')->name('asignar-pedidos')->middleware('submenu.permission:edit,ventas.ruta.index');
        Route::delete('/{ruta}/pedidos/{pedidoId}', 'quitarPedido')->name('quitar-pedido')->middleware('submenu.permission:edit,ventas.ruta.index');
    });
});
