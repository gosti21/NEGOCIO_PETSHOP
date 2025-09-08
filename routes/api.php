<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\SortController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StockController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{id}', [ProductApiController::class, 'show']);

Route::post('/sort/covers', [SortController::class, 'orderCover'])->name('api.sort.orderCover');

// Prefijo de nombres para evitar conflicto con web y admin
Route::apiResource('families', FamilyController::class)->names('api.families');
Route::apiResource('categories', CategoryController::class)->names('api.categories');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/stock', [StockController::class, 'index']);
    Route::post('/stock/reset', [StockController::class, 'reset']);
    Route::get('/stock/alert', [StockController::class, 'alert']);
    Route::put('/stock/{id}', [StockController::class, 'update']);
});
