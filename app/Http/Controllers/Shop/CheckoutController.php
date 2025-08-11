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
use Culqi\Culqi;

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
        // En Culqi, no necesitamos generar un preferenceId
        $publicKey = config('services.culqi.public_key');
        $total = (Cart::instance('shopping')->subtotal() + 15) * 100; // en céntimos
        return view('shop.checkout.index', compact('publicKey', 'total'));
    }

    public function paid(Request $request)
    {
        // Validamos que haya token de Culqi
        if (!$request->has('token')) {
            return back()->withErrors('Error: No se recibió el token de pago.');
        }

        $culqi = new Culqi(['api_key' => config('services.culqi.secret_key')]);

        try {
            $charge = $culqi->Charges->create([
                "amount" => (Cart::instance('shopping')->subtotal() + 15) * 100,
                "currency_code" => "PEN",
                "email" => Auth::user()->email,
                "source_id" => $request->token
            ]);

            if ($charge->outcome->type === 'venta_exitosa') {
                $address = Address::where('user_id', Auth::user()->id)
                    ->where('default', true)
                    ->first();

                $order = Order::create([
                    'user_id' => Auth::user()->id,
                    'content' => Cart::instance('shopping')->content(),
                    'address' => $address,
                    'payment_id' => $charge->id,
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
            } else {
                return back()->withErrors('El pago no se procesó correctamente.');
            }
        } catch (\Exception $e) {
            return back()->withErrors('Error en el pago: ' . $e->getMessage());
        }
    }
}
