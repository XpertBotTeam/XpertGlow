<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function ajax_search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', '%' . $query . '%')->get();
        return response()->json($products);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $results = Product::where('name', 'LIKE', '%'.$query.'%')->get();
        } else {
            $results = []; 
        }
    return view('user.search', ['results' => $results, 'query' => $query]);

    }
   
}
