<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CoverController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\ShippingCompanyController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VariantController;
use Illuminate\Support\Facades\Route;

// 🔹 Redirigir /admin a /admin/dashboard
Route::get('/', fn() => redirect()->route('admin.dashboard'));

// 🔹 Dashboard real
Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

// 🔹 Usuarios y opciones
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/options', [OptionController::class, 'index'])->name('options.index');

// 🔹 Recursos principales
Route::resource('families', FamilyController::class);
Route::resource('categories', CategoryController::class);
// Route::resource('subcategories', SubCategoryController::class);
Route::resource('products', ProductController::class);

// 🔹 Variantes de productos
Route::get('/products/{product}/variants', [VariantController::class, 'create'])
    ->name('variants.create')
    ->scopeBindings();
Route::get('/products/{product}/variants/{variant}', [VariantController::class, 'edit'])
    ->name('variants.edit')
    ->scopeBindings();
Route::match(['put','patch'], '/products/{product}/variants/{variant}', [VariantController::class, 'update'])
    ->name('variants.update')
    ->scopeBindings();

// 🔹 Otros recursos
Route::resource('covers', CoverController::class);
Route::resource('shipping-companies', ShippingCompanyController::class);

// 🔹 Órdenes y envíos
Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('shipments', [ShipmentController::class, 'index'])->name('shipments.index');
