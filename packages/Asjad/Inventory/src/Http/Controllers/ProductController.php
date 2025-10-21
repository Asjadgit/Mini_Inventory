<?php

namespace Asjad\Inventory\Http\Controllers;

use Asjad\Inventory\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('inventory::products.index');
    }

    public function list()
    {
        return response()->json(Product::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product
        ], 201);
    }
}
