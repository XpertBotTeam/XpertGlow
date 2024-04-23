<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where('name', 'like', "%$query%")->get();

        return response()->json(['products' => $products], 200);
    }
}
