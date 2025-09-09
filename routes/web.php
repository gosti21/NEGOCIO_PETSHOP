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


Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');
Route::get('families/{family}', [FamilyController::class, 'show'])->name('shop.families.show');
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('cart', [CartController::class, 'index'])->name('cart.index');

Route::get('shipping', [ShippingController::class, 'index'])
    ->middleware('auth')
    ->name('shipping.index');

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

// RUTA PRINCIPAL PARA BOTMAN (GET Y POST)
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);

// OPCIONAL: Ruta para probar iniciar IAConversation manualmente (útil en desarrollo)
Route::get('/botman/test', function () {
    $botman = app('botman');
    $botman->startConversation(new IAConversation());
});

Route::post('/preguntar-ia', [BotManController::class, 'responderConIATexto']);


Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/session', [CheckoutController::class, 'createStripeSession'])->name('checkout.session');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('stripe.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('stripe.cancel');

//chatgptbot
Route::post('/chatbot/ask', [App\Http\Controllers\ChatbotController::class, 'ask'])->name('chatbot.ask');



Route::get('/test-openai', function () {
    $result = OpenAI::chat()->create([
        'model' => 'gpt-4o-mini', // o 'gpt-4.1-mini'
        'messages' => [
            ['role' => 'user', 'content' => 'Hola, ¿me puedes responder desde Laravel?'],
        ],
    ]);

    return $result->choices[0]->message->content;
});
