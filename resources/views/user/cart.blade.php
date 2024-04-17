@section('title', 'Your Cart')
@extends('user.partials.header')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/css/user/cart.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/user/no_results.css') }}">


@if($cart && count($cart->cartItems) > 0)
        <div class="cart_wrapper" data-cart-id="{{ $cart->id }}">
        <div class="all_items">
            @php
                $total_quantity = 0;
                $total_price = 0;
            @endphp
        <div class="item empty-item">
            <div class="item_image"></div>
            <div class="item_name"></div>
            <div class="item_quantity"></div>
            <div class="item_subtotal"></div>
            <div class="item_delete"><button id="remove_all">Remove All</button></div>
        </div>
        @foreach($cart->cartItems as $cartItem)
        <div class="item" data-item-id="{{ $cartItem->id }}">
            <div class="item_image">
                <div class="image_container">
                    <img src="{{ asset('storage/images/products/' . $cartItem->product->images->first()->path) }}" alt="Product 1">
                </div>
            </div>
            <div class="item_name">
                <a href="{{ route('product', ['id' => $cartItem->product->id]) }}">{{ $cartItem->product->name }}</a>
            </div>
            <div class="item_quantity">
                <div class="input_container">
                    <button id="decrease">-</button>
                    <input type="number" id="quantity" value="{{$cartItem->quantity}}" min="1" readonly>
                    <button id="increase" >+</button>
                </div>
            </div>

            <div class="item_subtotal" data-price-per-item="{{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}">
                ${{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}
            </div>

            <div class="item_delete"><button id="remove">Remove</button></div>
        </div>
        @php
            $total_quantity += $cartItem->quantity;
            $total_price += $cartItem->product->price * $cartItem->quantity;
        @endphp
        @endforeach
        </div>
        <div class="check">
            <div class="check_summary">Summary</div>
            <div class="check_items">Item(s) : <span>{{$total_quantity}}</span></div>
            <div class="check_price">Total Price : <span>${{$total_price}}</span></div>
            <div class="check_address">
                <select id="address-select" name="address-select" required>
                    <option value="" disabled selected>Select an Address</option>
                    @foreach ($addresses as $address)
                        <option value="{{ $address->id }}">
                            {{ $address->name }} 
                            {{ $address->surname }} / 
                            {{ $address->district }} -
                            {{ $address->locality }} -
                            {{ $address->phone }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="check_place" data-cart-id="{{ $cart->id }}">
                <button id="place_order" >Place Order</button>
            </div>
        </div>
        </div>
@else

<div class="no_results">
    <div class="no_results_i"><i class="fa-solid fa-ban"></i></div>
    <div class="no_results_text">Your Cart is Empty</div>
</div>

@endif
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/user/manage_cart.js') }}"></script>
@endsection







