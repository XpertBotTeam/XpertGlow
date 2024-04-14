<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $products = collect(); 
        $userFavorites = [];

        if ($query) {

            $products = Product::with('images')
                ->where('name', 'LIKE', '%' . $query . '%')
                ->get();
            if (Auth::check()) {
                $userId = Auth::id();
                $userFavorites = Favorite::where('user_id', $userId)
                    ->pluck('product_id')
                    ->toArray();
            }
        }
        return view('user.search', [
            'products' => $products,
            'userFavorites' => $userFavorites,
            'query' => $query
        ]);

    }

    
   
}
