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
                @if ($images->isNotEmpty())
                    <img id="main_image" src="{{ asset('storage/images/products/' . $images->first()->path) }}" alt="Product Image">
                @else
                    <img id="main_image" src="{{ asset('storage/images/products/no_images.png') }}" alt="No Images">
                @endif
            </div>
        </div>
        <div class="image_select">
            @foreach ($images as $image)
                <button data-src="{{ asset('storage/images/products/' . $image->path) }}">
                    <img src="{{ asset('storage/images/products/' . $image->path) }}" alt="Thumbnail Image">
                </button>
            @endforeach
        </div>
    </div>
    <div class="content_container">
        <div class="content_top">
            <div class="top_name">{{$product->name}}</div>
            <div class="top_price">${{$product->price}}</div>
            <div class="middle_description">{{$product->description}}</div>
        </div>
        <div class="content_input">
            <div class="input_container">
                <button id="decrease">-</button>
                <input type="number" id="quantity" value="1" min="1" readonly>
                <button id="increase">+</button>
            </div>
            <button id="addToCart" data-id="{{ $product->id }}">Add to Cart</button>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/user/toggle_add_to_cart.js') }}"></script>
<script src="{{ asset('assets/js/user/toggle_favorite.js') }}"></script>
<script src="{{ asset('assets/js/user/product.js') }}"></script>
@endsection

