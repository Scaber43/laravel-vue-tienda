<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;

Route::prefix('v1')->name('v1.')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/pedidos', [PedidoController::class, 'store']);
        Route::post('/broadcasting/auth', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Support\Facades\Broadcast::auth($request);
        });
    });

    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show']);
    Route::get('/categorias', [CategoriaController::class, 'index']);
    Route::apiResource('categorias', CategoriaController::class);
    Route::get('categorias/{categoria}/productos', [CategoriaController::class, 'productos']);
    Route::apiResource('productos', ProductoController::class);
});

Route::prefix('v2')->name('v2.')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('productos', \App\Http\Controllers\Api\V2\ProductoController::class);
    });
});
