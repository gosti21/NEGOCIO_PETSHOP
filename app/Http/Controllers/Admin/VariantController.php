<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Variant\UpdateVariantRequest;
use App\Models\Product;
use App\Models\Variant;
use App\Repositories\Admin\VariantRepository;

class VariantController extends Controller
{
    protected $variantRepository;

    public function __construct(VariantRepository $variantRepository)
    {
        $this->variantRepository = $variantRepository;
    }

    public function edit(Product $product, Variant $variant)
    {
        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    public function create(Product $product)
    {
        return view('admin.products.variants.create', compact('product'));
    }

    public function update(UpdateVariantRequest $request, Product $product, Variant $variant)
    {
        $this->variantRepository->update($request, $variant);

        return redirect()->route('admin.variants.edit', ['product' => $product, 'variant' => $variant]);
    }
}
