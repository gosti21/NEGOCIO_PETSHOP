<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SortController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Admin\ProductController; // si necesitas AdminProduct también

// 🔹 Rutas públicas
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductApiController::class, 'show']);
Route::post('/sort/covers', [SortController::class, 'orderCover'])->name('api.sort.orderCover');
Route::apiResource('families', FamilyController::class);
Route::apiResource('categories', CategoryController::class);
Route::post('/login', [AuthController::class, 'login']);

// 🔹 Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/stock', [StockController::class, 'index']);
    Route::post('/stock/reset', [StockController::class, 'reset']);
    Route::get('/stock/alert', [StockController::class, 'alert']);
    Route::put('/stock/{id}', [StockController::class, 'update']);
});
