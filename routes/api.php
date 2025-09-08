<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Api\SortController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StockController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index']);

Route::post('/sort/covers', [SortController::class, 'orderCover'])->name('api.sort.orderCover');

Route::get('/products/{id}', [ProductApiController::class, 'show']);

Route::apiResource('families', FamilyController::class);
Route::apiResource('categories', CategoryController::class);

Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/stock', [StockController::class, 'index']);
    Route::post('/stock/reset', [StockController::class, 'reset']);
    Route::get('/stock/alert', [StockController::class, 'alert']);
    Route::put('/stock/{id}', [StockController::class, 'update']); // nueva ruta
});
