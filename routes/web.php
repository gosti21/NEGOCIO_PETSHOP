<?php

use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\FamilyController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\ShippingController;
use App\Http\Controllers\Shop\WelcomeController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\IAController;


Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');
Route::get('families/{family}', [FamilyController::class, 'show'])->name('families.show');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('cart', [CartController::class, 'index'])->name('cart.index');

Route::get('shipping', [ShippingController::class, 'index'])
    ->middleware('auth')
    ->name('shipping.index');

Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout/paid', [CheckoutController::class, 'paid'])->name('checkout.paid')->withoutMiddleware([ValidateCsrfToken::class]);
Route::get('thanks', function () {
    return view('shop.thanks');
})->name('thanks');

Route::get('/legal/terms-and-conditions', function () {
    return view('shop.terms-and-conditions');
})->name('legal.terms-and-conditions');
Route::get('/legal/privacy-policy', function () {
    return view('shop.privacy-policy');
})->name('legal.privacy-policy');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);


Route::post('/preguntar-ia', [IAController::class, 'responderConIA']);
