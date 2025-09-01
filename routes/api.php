<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Api\SortController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\FamilyController;
use App\Http\Controllers\Api\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', [ProductController::class, 'index']);

Route::post('/sort/covers', [SortController::class, 'orderCover'])->name('api.sort.orderCover');

Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{id}', [ProductApiController::class, 'show']);

Route::apiResource('families', FamilyController::class);
Route::apiResource('categories', CategoryController::class);
