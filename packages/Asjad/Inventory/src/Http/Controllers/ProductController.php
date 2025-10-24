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

    public function list(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
            ->orWhere('stock', 'like', "%{$search}%")
            ->orWhere('price', 'like', "%{$search}%");
        }

        $products = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json($products);
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

    public function edit($id)
    {
        $product = Product::find($id);
        try {
            return response()->json([
                'product' => $product,
            ], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'stock' => 'required|integer|min:0',
                'price' => 'required|numeric|min:0',
            ]);

            $product->update($validated);

            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        try {
            $product->delete();
            return response()->json([
                'message' => 'Product deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
