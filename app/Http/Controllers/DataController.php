<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Suplier;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function productAll(Request $request)
    {
        $products = Product::where('name', 'like', '%'.$request->input('term')['term'].'%')->get();
        return response()->json($products, 200);
    }

    public function productById($id)
    {
        $product = Product::find($id);
        return response()->json($product, 200);
    }
}
