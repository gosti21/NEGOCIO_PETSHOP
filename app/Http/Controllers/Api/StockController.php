<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Variant;

class StockController extends Controller
{
    // Ver stock total por producto
    public function index()
    {
        $products = Product::with('variants')->get();

        $data = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'total_stock' => $product->variants->sum('stock'), // suma stock de todas las variantes
                'variants' => $product->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'sku' => $variant->sku,
                        'stock' => $variant->stock,
                        'price' => $variant->price,
                    ];
                }),
            ];
        });

        return response()->json($data);
    }

    // Reiniciar stock de todas las variantes
    public function reset()
    {
        foreach (Product::with('variants')->get() as $product) {
            foreach ($product->variants as $variant) {
                $variant->update(['stock' => 100]); // ejemplo: reinicia a 100
            }
        }

        return response()->json(['message' => 'Stock reiniciado a 100 para todas las variantes']);
    }

    // Alertas de stock bajo (≤5)
    public function alert()
    {
        $lowStock = [];

        foreach (Product::with('variants')->get() as $product) {
            foreach ($product->variants as $variant) {
                if ($variant->stock <= 5) {
                    $lowStock[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'variant_id' => $variant->id,
                        'sku' => $variant->sku,
                        'stock' => $variant->stock,
                    ];
                }
            }
        }

        return response()->json($lowStock);
    }

    // Actualizar stock de una variante específica
    public function update(Request $request, $variant_id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $variant = Variant::findOrFail($variant_id);
        $variant->update(['stock' => $request->stock]);

        return response()->json([
            'message' => 'Stock actualizado',
            'variant' => $variant
        ]);
    }
}
