<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index()
    {
        // Listar familias con sus categorías
        return Family::with('categories')->get();
    }

    public function store(Request $request)
    {
        $family = Family::create($request->validate([
            'name' => 'required|string|max:255',
        ]));

        return response()->json($family, 201);
    }

    public function show(Family $family)
    {
        return $family->load('categories');
    }

    public function update(Request $request, Family $family)
    {
        $family->update($request->validate([
            'name' => 'required|string|max:255',
        ]));

        return response()->json($family);
    }

    public function destroy(Family $family)
    {
        $family->delete();
        return response()->json(null, 204);
    }
}
