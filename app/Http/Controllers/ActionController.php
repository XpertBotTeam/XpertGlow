<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ActionController extends Controller
{

    public function update_cart_item(Request $request)
    {
        $itemId = $request->input('item_id');
        $newQuantity = $request->input('quantity');

        $cartItem = CartItem::find($itemId);

        if ($cartItem) {
            $cartItem->quantity = $newQuantity;
            $saveSuccess = $cartItem->save();

            if ($saveSuccess) {
                $userId = Auth::id();
                $cart = Cart::where('user_id', $userId)
                ->with('cartItems.product.images') 
                ->first();

                return response()->json([
                'success' => true,
                'cart' => $cart,
            ]);
            }
        }
    }

    public function delete_cart_item(Request $request)
    {
        $itemId = $request->input('item_id');
       
        $cartItem = CartItem::find($itemId);
        if ($cartItem) {
            $deleteSuccess = $cartItem->delete();
            if ($deleteSuccess) {
                $userId = Auth::id();
                $cart = Cart::where('user_id', $userId)
                ->with('cartItems.product.images') 
                ->first();
                return response()->json([
                'success' => true,
                'cart' => $cart,
            ]);
            } 
        }
    }

    public function delete_cart(Request $request)
    {
        $cartId = $request->input('cart_id');
        $cart = Cart::find($cartId);
        if ($cart) {
            $deleteSuccess = $cart->delete();
            if ($deleteSuccess) {
                return response()->json([
                'success' => true
            ]);
            } 
        }
    }

    public function place_order(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer',
            'address_id' => 'required|integer',
        ]);

        $cartId = $request->input('cart_id');
        $addressId = $request->input('address_id');

        $cart = Cart::with('cartItems.product')->find($cartId);
        $address = Address::find($addressId);

        $total_price = 0;
        foreach ($cart->cartItems as $cartItem) {
            $total_price +=$cartItem->product->price*$cartItem->quantity;
        }

        $order = new Order();
        $order->user_id = Auth::id();
        $order->address_id = $address->id;
        $order->total_price = $total_price;
        $order->save();

        foreach ($cart->cartItems as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $cartItem->product->id;
            $orderItem->price = $cartItem->product->price;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->save();
        }

        $cart->delete();

        return response()->json([
            'success' => true
        ]);

    }

    public function cancel_order(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer'
        ]);

        $order = Order::find($request->input('order_id'));
        $order->status = 'cancelled';
        $order->save();

        return response()->json([
            'success' => 'Order has been cancelled successfully',
        ], 200);
    }

    public function toggle_add_to_cart(Request $request)
    {
        
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

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


    public function add_address(Request $request)
    {
        $userId = Auth::id();
        $address = Address::create([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'address' => $request->input('address'),
            'more_info' => $request->input('more_info'),
            'district' => $request->input('district'),
            'locality' => $request->input('locality'),
            'phone' => $request->input('phone'),
            'user_id' => $userId
        ]);
        return redirect()->to('/account');
    }

    public function change_password(Request $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        if (Hash::check($request->input('current_password'), $user->password)) {
           $user->password = Hash::make($request->input('new_password'));
            $success = $user->save(); 
            if($success){
                Auth::logout();
            }
            return redirect()->to('/login');
        }
        else{
            return redirect()->back();
        }

        
    }
}
