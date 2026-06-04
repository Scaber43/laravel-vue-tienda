<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get(
    '/me',
    [AuthController::class, 'me']
);

Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);

Route::get('/categorias', [CategoriaController::class, 'index']);

Route::apiResource('categorias', CategoriaController::class);

Route::get(
    'categorias/{categoria}/productos',
    [CategoriaController::class, 'productos']
);

Route::apiResource('productos', ProductoController::class);