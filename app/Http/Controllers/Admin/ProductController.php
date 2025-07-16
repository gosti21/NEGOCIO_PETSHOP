<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Admin\ProductRepository;

class ProductController extends BaseAdminController
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct($productRepository);
        $this->productRepository = $productRepository;
    }

    public function destroy(int $id)
    {
        $this->productRepository->destroy($id);

        return redirect()->route("admin.products.index");
    }
}
