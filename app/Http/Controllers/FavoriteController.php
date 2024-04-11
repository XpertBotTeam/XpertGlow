<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle_favorite(Request $request)
    {

            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $userId = Auth::id();
            $productId = $request->input('product_id');
            $isFavorite = filter_var($request->input('is_favorite'), FILTER_VALIDATE_BOOLEAN);

            $favorite = Favorite::where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->first();

            $response['is_favorite'] = null;
            if ($favorite) {

                if ($isFavorite === true) {
                    $response = ['is_favorite' => $favorite];
                    $favorite->delete();
                    $response['is_favorite'] = false;
                }
                
            }
            else {
                if ($isFavorite === false) {
                    $newFavorite = new Favorite();
                    $newFavorite->user_id = $userId;
                    $newFavorite->product_id = $productId;
                    $newFavorite->save();
                    $response['is_favorite'] = true;
                }
                else{
                    $response['is_favorite'] = false;
                }
            }

            return response()->json($response);

    }

 
}
