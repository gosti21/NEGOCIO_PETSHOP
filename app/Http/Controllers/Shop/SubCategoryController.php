<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function show(SubCategory $subcategory)
    {
        return view('shop.subcategories.show', compact('subcategory'));
    }
}
