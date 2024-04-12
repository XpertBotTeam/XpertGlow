@section('title','Your Favorites')
@extends('user.partials.header')
@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/css/user/cards.css') }}">


    @if(empty($userFavorites))
    <div class="no_results">
        <div class="no_results_i"><i class="fa-solid fa-heart-crack"></i></div>
        <div class="no_results_text">Your Favorites is Empty</div>
    </div> 
    @else
    <div class="products_container">
        @foreach($products as $product)
          @php $product->is_favorite = in_array($product->id, $userFavorites); @endphp
            <div class="product_item" data-id="{{ $product->id }}">
                <div class="add_to_favorite">
                    <button class="favorite_button" data-in-favorites="{{ $product->is_favorite ? 'true' : 'false' }}">
                        <i class="{{ $product->is_favorite ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                    </button>
                </div>
                <div class="item_top">
                    <a href="{{ route('product', ['id' => $product->id]) }}">
                        <img src="https://feel22.com/cdn/shop/files/Untitleddesign_c3897b73-ca5b-4baf-8e92-2f88d2674f3a.png?v=1691411984&width=700" alt="Product 1">
                    </a>
                </div>
                <div class="item_bottom">
                    <div class="item_name">
                        <a href="{{ route('product', ['id' => $product->id]) }}">{{ $product->name }}</a>
                    </div>
                    <div class="item_price">$ {{ number_format($product->price, 2) }}</div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/user/toggle_favorite.js') }}"></script>
@endsection

