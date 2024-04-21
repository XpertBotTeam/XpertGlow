<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function toggle_favorite(Request $request, $product_id)
    {
        $user = $request->user(); 
    
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    
        $product = Product::find($product_id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        $favorite = Favorite::where('user_id', $user->id)
                            ->where('product_id', $product_id)
                            ->first();
    
        if ($favorite) {
            if($favorite->delete()){
                return response()->json(['message' => 'Product removed from favorites'], 200);
            }
            else{
                return response()->json(['error' => 'Failed to remove product from favorites'], 500);
            }
        } 
        else {
            $favorite = new Favorite();
            $favorite->user_id = $user->id;
            $favorite->product_id = $product_id;
            if ($favorite->save()) {
                return response()->json(['message' => 'Product added to favorites'], 200);
            } 
            else {
                return response()->json(['error' => 'Failed to add product to favorites'], 500);
            }
        }
    }

    public function all_favorites(Request $request)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $favorites = Favorite::where('user_id', $user->id)->with('product')->get();

        return response()->json(['favorites' => $favorites], 200);
    }

}
