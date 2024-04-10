@if (empty($query))
    @section('title','XpertGlow | Search')
@else
    @section('title', 'Search Results: ' . $query)
@endif

@extends('user.partials.header')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/user/cards.css') }}">



    @if (!empty($query))
    @if (count($results) === 0)
    <div class="no_results">
        <div class="no_results_i"><i class="fa-solid fa-ban"></i></div>
        <div class="no_results_text">Your search "{{$query}}" didn't match any Results</div>
    </div>
    @else
    <div class="products_container">
    @foreach($results as $result)
    <div class="product_item">
        <div class="item_top">
        <a href="{{ route('product', ['id' => $result->id]) }}">
          <img src="https://feel22.com/cdn/shop/files/Untitleddesign_c3897b73-ca5b-4baf-8e92-2f88d2674f3a.png?v=1691411984&width=700" alt="Product 1">
        </a>
        </div>
        <div class="item_bottom">
           <div class="item_name"><a href="{{ route('product', ['id' => $result->id]) }}">{{ $result->name }}</a></div>
           <div class="item_price">$ {{ $result->price }}</div>
        </div>
    </div>
    @endforeach
    </div>
    @endif
    @endif

@endsection


