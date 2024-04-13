@section('title',$product->name)

@extends('user.partials.header')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/css/user/product.css') }}">

<div class="product_wrapper">
    
        <div class="add_to_favorite" data-id="{{ $product->id }}">
            <button class="favorite_button" data-in-favorites="{{ $isFavorited===true ? 'true' : 'false' }}">
                <i class="{{ $isFavorited===true ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
            </button>
        </div>
    <div class="images_container">
        <div class="image_display_container">
            <div class="image_display">
            <img id="main_image" src="https://feel22.com/cdn/shop/files/Untitleddesign_c3897b73-ca5b-4baf-8e92-2f88d2674f3a.png?v=1691411984&width=700" alt="Product Image">
            </div>
        </div>
        <div class="image_select">
            <button data-src="https://feel22.com/cdn/shop/files/Untitleddesign_c3897b73-ca5b-4baf-8e92-2f88d2674f3a.png?v=1691411984&width=700">
                <img src="https://feel22.com/cdn/shop/files/Untitleddesign_c3897b73-ca5b-4baf-8e92-2f88d2674f3a.png?v=1691411984&width=700" alt="Thumbnail 1">
            </button>

            <button data-src="https://cdn.ebaumsworld.com/mediaFiles/picture/1035099/85932860.jpg">
                <img src="https://cdn.ebaumsworld.com/mediaFiles/picture/1035099/85932860.jpg" alt="Thumbnail 1">
            </button>

            <button data-src="https://img1.od-cdn.com/ImageType-400/2390-1/45E/80C/ED/%7B45E80CED-B5BB-4285-822E-5A166E33B5A1%7DImg400.jpg">
                <img src="https://img1.od-cdn.com/ImageType-400/2390-1/45E/80C/ED/%7B45E80CED-B5BB-4285-822E-5A166E33B5A1%7DImg400.jpg" alt="Thumbnail 2">
            </button>

           
        </div>
    </div>

    <div class="content_container">
        <div class="content_top">
            <div class="top_name">{{$product->name}}</div>
            <div class="top_price">${{$product->price}}</div>
            <div class="middle_description">{{$product->name}}</div>
        </div>
        <div class="content_input">
            <div class="input_container">
                <button id="decrease">-</button>
                <input type="number" id="quantity" value="1" min="1" readonly>
                <button id="increase">+</button>
            </div>
            <button id="addToCart">Add to Cart</button>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/user/toggle_favorite.js') }}"></script>
<script src="{{ asset('assets/js/user/product.js') }}"></script>
@endsection

