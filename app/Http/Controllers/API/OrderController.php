<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function place_order(Request $request)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $cart = $user->cart;

        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        foreach ($cart->cartItems as $cart_item) {
            if ($cart_item->quantity > $cart_item->product->quantity) {
                return response()->json(['error' => 'Not enough quantity available for product: ' . $cart_item->product->name], 400);
            }
        }

        $totalPrice = 0;
        foreach ($cart->cartItems as $cart_item) {
            $totalPrice += $cart_item->product->price * $cart_item->quantity;
        }

        $order = new Order([
            'address_id' => $request->address_id,
            'user_id' => $user->id,
            'total_price' => $totalPrice,
        ]);

        $order->save();

        foreach ($cart->cartItems as $cart_item) {

            $order_item = new OrderItem([
                'product_id' => $cart_item->product_id,
                'quantity' => $cart_item->quantity,
                'price' => $cart_item->product->price * $cart_item->quantity,
            ]);

            $order->orderItems()->save($order_item);

            $cart_item->product->quantity -= $cart_item->quantity;
            $cart_item->product->save();
        }

        $cart->cartItems()->delete();
        
        return response()->json(['order' => $order], 201);
    }

    public function cancel_order(Request $request, $id)
    {
        $user = $request->user(); 
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($order->status === 'cancelled') {
            return response()->json(['message' => 'Order is already cancelled'], 400);
        }

        if ($order->status === 'processing') {
            return response()->json(['message' => 'Order is already in processing status. You cannot cancel it now.'], 400);
        }

        $order->status = 'cancelled';
        $order->save();

        foreach ($order->orderItems as $order_item) {
            $order_item->product->quantity += $order_item->quantity;
            $order_item->product->save();
        }
        return response()->json(['message' => 'Order cancelled successfully'], 200);
    }

    public function remove_order_item(Request $request, $order_id, $item_id)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $order = Order::find($order_id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order is not pending. You cannot remove items from it now.'], 400);
        }

        $order_item = $order->orderItems()->find($item_id);

        if (!$order_item) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        $order_item->product->quantity += $order_item->quantity;
        $order_item->product->save();

        $order_item->delete();
        return response()->json(['message' => 'Order item removed successfully from order'], 200);
    }

    public function change_order_status(Request $request, $id)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $status = $request->input('status');

        if (!in_array($status, ['pending', 'processing', 'cancelled'])) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $order->status = $status;
        $order->save();
        return response()->json(['message' => 'Order status updated successfully'], 200);
    }

    public function index()
    {
        $orders = Order::all();
        return response()->json(['orders' => $orders], 200);
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json(['order' => $order], 200);
    }


}
