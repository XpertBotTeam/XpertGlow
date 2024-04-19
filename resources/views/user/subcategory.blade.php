@extends('user.partials.header')

@section('title', $subcategory->name)

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user/no_results.css') }}">

    @if($subcategory->products->isEmpty())
        <div class="no_results">
            <div class="no_results_i"><i class="fa-solid fa-ban"></i></div>
            <div class="no_results_text">No Products found for {{ $subcategory->name }}</div>
        </div>
    @else
        <div class="products_container">
            @foreach($subcategory->products as $product)
                @php $product->is_favorite = in_array($product->id, $userFavorites); @endphp
                <div class="product_item">
                    <div class="add_to_favorite" data-id="{{ $product->id }}">
                        <button class="favorite_button" data-in-favorites="{{ $product->is_favorite ? 'true' : 'false' }}">
                            <i class="{{ $product->is_favorite ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                        </button>
                    </div>
                    <div class="item_top">
                        <a href="{{ route('product', ['id' => $product->id]) }}">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/images/products/' . $product->images->first()->path) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('storage/images/products/no_images.png') }}" alt="{{ $product->name }}">
                            @endif
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
