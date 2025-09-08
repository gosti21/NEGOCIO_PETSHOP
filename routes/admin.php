<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CoverController;
use App\Http\Controllers\Admin\FamilyController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\ShippingCompanyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VariantController;

Route::view('/', 'admin.dashboard')->name('admin.dashboard');
Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
Route::get('/options', [OptionController::class, 'index'])->name('admin.options.index');

// Prefijo de nombres "admin."
Route::resource('families', FamilyController::class)->names('admin.families');
Route::resource('categories', CategoryController::class)->names('admin.categories');
Route::resource('products', ProductController::class)->names('admin.products');

Route::get('/products/{product}/variants', [VariantController::class, 'create'])
    ->name('admin.variants.create')
    ->scopeBindings();
Route::get('/products/{product}/variants/{variant}', [VariantController::class, 'edit'])
    ->name('admin.variants.edit')
    ->scopeBindings();
Route::match(['put', 'patch'], '/products/{product}/variants/{variant}', [VariantController::class, 'update'])
    ->name('admin.variants.update')
    ->scopeBindings();

Route::resource('covers', CoverController::class)->names('admin.covers');
Route::resource('shipping-companies', ShippingCompanyController::class)->names('admin.shipping-companies');

Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
Route::get('shipments', [ShipmentController::class, 'index'])->name('admin.shipments.index');
