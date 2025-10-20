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
}
