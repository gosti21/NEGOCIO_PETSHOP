<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Variant;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference;
use MercadoPago\Resources\Item;
use MercadoPago\Client\Preference\PreferenceClient;

class CheckoutController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index()
    {
        // Generar el preferenceId de Mercado Pago
        $preferenceId = $this->generateMercadoPagoPreference();

        return view('shop.checkout.index', compact('preferenceId'));
    }

    public function generateMercadoPagoPreference()
    {
        // Establecer el token desde .env (debe estar correctamente configurado)
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $items = [];

        foreach (Cart::instance('shopping')->content() as $cartItem) {
            $items[] = [
                'title' => $cartItem->name,
                'quantity' => $cartItem->qty,
                'unit_price' => (float) $cartItem->price,
                'currency_id' => 'PEN',
            ];
        }

        $client = new PreferenceClient();
        $preference = $client->create([
            'items' => $items,
        ]);

        return $preference->id;
    }
    public function paid(Request $request)
    {
        $address = Address::where('user_id', Auth::user()->id)
            ->where('default', true)
            ->first();

        $order = Order::create([
            'user_id' => Auth::user()->id,
            'content' => Cart::instance('shopping')->content(),
            'address' => $address,
            'payment_id' => $request->payment_id ?? 'MercadoPago-' . now()->timestamp,
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
}
