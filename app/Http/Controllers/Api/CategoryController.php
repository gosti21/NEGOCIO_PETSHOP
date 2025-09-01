<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Listar categorías con su familia
        return Category::with('family')->get();
    }

    public function store(Request $request)
    {
        $category = Category::create($request->validate([
            'name' => 'required|string|max:255',
            'family_id' => 'required|exists:families,id',
        ]));

        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        return $category->load('family');
    }

    public function update(Request $request, Category $category)
    {
        $category->update($request->validate([
            'name' => 'required|string|max:255',
            'family_id' => 'required|exists:families,id',
        ]));

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
