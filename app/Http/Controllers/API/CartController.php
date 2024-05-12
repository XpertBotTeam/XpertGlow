<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function list_cart_items(Request $request)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        $cart_items = $cart->cartItems()->with('product.images')->get();

        return response()->json(['cart_items' => $cart_items], 200);
    }

    public function add_to_cart(Request $request, $product_id)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user_id = $user->id;

        $product = Product::find($product_id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $quantity = $request->input('quantity', 1);

        $total_cart_quantity = CartItem::where('product_id', $product_id)
                                   ->whereHas('cart', function ($query) use ($user_id) {
                                       $query->where('user_id', $user_id);
                                   })->sum('quantity');

        $available_quantity = $product->quantity - $total_cart_quantity;

        if ($available_quantity < $quantity) {
            return response()->json(['error' => "Not enough quantity available, only $available_quantity available for you"], 400);
        }

        if ($quantity < 1) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }

        if ($product->quantity < $quantity) {
            return response()->json(['error' => 'Not enough quantity available'], 400);
        }

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->save();
        }

        $cart_item = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product_id)
                            ->first();
        
        if($cart_item){
            $cart_item->quantity += $quantity;
            $cart_item->save();
        }

        else{
            $cart_item = new CartItem();
            $cart_item->cart_id = $cart->id;
            $cart_item->product_id = $product_id;
            $cart_item->quantity = $quantity;
            $cart_item->save();
        }

        return response()->json(['message' => 'Product added to cart'], 200);
    }

    public function update_cart_item_quantity(Request $request, $id)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    
        $cart_item = CartItem::find($id);
    
        if (!$cart_item) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }
    
        $cart = $cart_item->cart;
    
        if (!$cart || $cart->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $quantity = $request->input('quantity');
    
        if (!is_numeric($quantity) || $quantity < 1) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }

        $product = $cart_item->product;

        if($cart_item->quantity>$quantity){
            $cart_item->quantity = $quantity;
            $cart_item->save();
            return response()->json(['message' => 'Cart item quantity updated'], 200);
        }

        if ($product->quantity < $quantity) {
            return response()->json(['error' => 'Not enough quantity available'], 400);
        }
    
        $cart_item->quantity = $quantity;
        $cart_item->save();
    
        return response()->json(['message' => 'Cart item quantity updated'], 200);
    }


    public function remove_from_cart(Request $request, $id)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $cart_item = CartItem::find($id);

        if (!$cart_item) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        if ($cart_item->cart->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cart_item->delete();

        return response()->json(['message' => 'Cart item removed'], 200);
    }

    public function clear_cart(Request $request)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        if ($cart->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($cart->cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is already empty'], 200);
        }

        $cart->cartItems()->delete();

        return response()->json(['message' => 'Cart Cleared'], 200);
    }

}
