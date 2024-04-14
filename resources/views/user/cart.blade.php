@section('title', 'Your Cart')
@extends('user.partials.header')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/user/cart.css') }}">

<div class="cart_wrapper">

    <div class="all_items">
        <div class="item">
            <div class="item_image"></div>
            <div class="item_name"></div>
            <div class="item_quantity"></div>
            <div class="item_subtotal"></div>
            <div class="item_delete"><button>Remove All</button></div>
        </div>

        <div class="item">
            <div class="item_image">
                <div class="image_container"><img src="https://feel22.com/cdn/shop/files/Untitleddesign_c3897b73-ca5b-4baf-8e92-2f88d2674f3a.png?v=1691411984&width=700" alt="Product 1"></div>
            </div>
            <div class="item_name">item_name</div>
            <div class="item_quantity">
                <div class="input_container">
                    <button>-</button>
                    <input type="number" value="1" min="1">
                    <button>+</button>
                </div>
            </div>
            <div class="item_subtotal">$15.99</div>
            <div class="item_delete"><button>Remove</button></div>
        </div>
        
    </div>

    <div class="check">
        <div class="check_summary">Summary</div>
        <div class="check_items">Item(s) : <span>8</span></div>
        <div class="check_price">Total Price : <span>$50</span></div>
        <div class="check_address">
            <select id="address-select" name="address-select">
                <option value="" disabled selected>Select an Address</option>
                <option value="21">bidyas</option>
            </select>
        </div>
        <div class="check_place"><button>Place Order</button></div>
    </div>
</div>

@endsection







