<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionController extends Controller
{
    public function toggle_add_to_cart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $product = Product::findOrFail($productId);
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $cartItem = $cart->cartItems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();

        } else {
            $cart->cartItems()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
        ]);
    }


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
