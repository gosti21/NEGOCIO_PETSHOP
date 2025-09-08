<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class StockController extends Controller
{
    // Ver stock de todos los productos
    public function index()
    {
        return Product::select('id', 'name', 'stock')->get();
    }

    // Reiniciar stock (ejemplo: todos a 100)
    public function reset()
    {
        Product::query()->update(['stock' => 100]);

        return response()->json([
            'message' => 'Stock reiniciado a 100 para todos los productos.'
        ]);
    }

    // Alerta de stock bajo (ej: menor a 5)
    public function alert()
    {
        $lowStock = Product::where('stock', '<', 5)->get();

        return response()->json([
            'alerta' => $lowStock
        ]);
    }
}
