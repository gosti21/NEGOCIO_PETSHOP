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
use App\Http\Controllers\Conversations\IAConversation;
use OpenAI\Laravel\Facades\OpenAI;

// Página principal
Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');

// Familias, categorías y productos
Route::get('families/{family}', [FamilyController::class, 'show'])->name('families.show');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

// Carrito y envío
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::get('shipping', [ShippingController::class, 'index'])
    ->middleware('auth')
    ->name('shipping.index');

// Checkout y pagos
Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout/paid', [CheckoutController::class, 'paid'])
    ->name('checkout.paid')
    ->withoutMiddleware([ValidateCsrfToken::class]);

Route::post('/checkout/session', [CheckoutController::class, 'createStripeSession'])->name('checkout.session');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('stripe.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('stripe.cancel');

// Páginas legales
Route::get('thanks', fn () => view('shop.thanks'))->name('thanks');
Route::get('/legal/terms-and-conditions', fn () => view('shop.terms-and-conditions'))->name('legal.terms-and-conditions');
Route::get('/legal/privacy-policy', fn () => view('shop.privacy-policy'))->name('legal.privacy-policy');

// Dashboard protegido
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
        Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    });

// Botman
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
Route::get('/botman/test', function () {
    $botman = app('botman');
    $botman->startConversation(new IAConversation());
});
Route::post('/preguntar-ia', [BotManController::class, 'responderConIATexto']);

// Chatbot con OpenAI
Route::post('/chatbot/ask', [App\Http\Controllers\ChatbotController::class, 'ask'])->name('chatbot.ask');

Route::get('/test-openai', function () {
    $result = OpenAI::chat()->create([
        'model' => 'gpt-4o-mini',
        'messages' => [
            ['role' => 'user', 'content' => 'Hola, ¿me puedes responder desde Laravel?'],
        ],
    ]);
    return $result->choices[0]->message->content;
});
