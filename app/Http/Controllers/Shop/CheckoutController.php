<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Variant;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('shop.checkout.index');
    }

    public function createStripeSession()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        foreach (Cart::instance('shopping')->content() as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'pen',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => intval($item->price * 100), // en centavos
                ],
                'quantity' => $item->qty,
            ];
        }

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.cancel'),
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function success()
    {
        $address = Address::where('user_id', Auth::user()->id)
            ->where('default', true)
            ->first();

        $order = Order::create([
            'user_id' => Auth::user()->id,
            'content' => Cart::instance('shopping')->content(),
            'address' => $address,
            'payment_id' => 'Stripe-' . now()->timestamp,
            'total' => Cart::instance('shopping')->subtotal() + 15,
        ]);

        foreach (Cart::instance('shopping')->content() as $item) {
            Variant::where('sku', $item->options['sku'])
                ->decrement('stock', $item->qty);
        }

        Cart::destroy();
        if (Auth::check()) {
            Cart::store(Auth::user()->id);
        }

        return redirect()->route('thanks')->with('order', $order);
    }

    public function cancel()
    {
        return redirect()->route('checkout.index')->with('error', 'Pago cancelado');
    }
}
