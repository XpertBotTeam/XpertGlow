    @section('title',$subcategory->name)

    @extends('user.partials.header')

    @section('content')
    
    <link rel="stylesheet" href="{{ asset('assets/css/user/cards.css') }}">

        @if($products->isEmpty())
            <div class="no_results">
                <div class="no_results_i"><i class="fa-solid fa-ban"></i></div>
                <div class="no_results_text">No Products found for {{$subcategory->name}}</div>
            </div>
            
        @else

        <div class="products_container">

        @foreach($products as $product)
        <div class="product_item">
            <div class="item_top">
            <a href="{{ route('product', ['id' => $product->id]) }}">
              <img src="https://feel22.com/cdn/shop/files/Untitleddesign_c3897b73-ca5b-4baf-8e92-2f88d2674f3a.png?v=1691411984&width=700" alt="Product 1">
            </a>
            </div>
            <div class="item_bottom">
               <div class="item_name"><a href="{{ route('product', ['id' => $product->id]) }}">{{ $product->name }}</a></div>
               <div class="item_price">$ {{ $product->price }}</div>
            </div>
        </div>
        @endforeach
        @endif

    </div>

    @endsection
    
