@section('title', 'Your Order(s)')
@extends('user.partials.header')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/css/user/order.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/user/no_results.css') }}">


@if($orders->isEmpty())

<div class="no_results">
    <div class="no_results_i"><i class="fa-solid fa-ban"></i></div>
    <div class="no_results_text">No Order(s) Placed</div>
</div>

@else

<div class="orders_container">
    @foreach($orders as $order)
    @php $itemCount = 0; @endphp
    @foreach($order->orderItems as $orderItem)
        @php $itemCount += $orderItem->quantity;@endphp
    @endforeach
    <div class="order_item">
        <div class="order_number"><span>Order Number : </span>{{$order->id}}</div>
        <div class="order_date"><span>Placed on : </span>{{$order->created_at}}</div>
        <div class="order_price"><span>Total Price : </span>$ {{$order->total_price}}</div>
        <div class="order_status"><span>Status : </span>{{$order->status}}</div>
        <div class="order_items"><span>{{$itemCount}} Item(s)</span></div>
        <div class="order_images">
            @foreach($order->orderItems as $orderItem)
                <div class="img_container">
                    <img src="{{ asset('storage/images/products/' . $orderItem->product->images->first()->path) }}" alt="Product Image">
                </div>
            @endforeach
        </div>
        <div class="order_view"><a href="/order/view/{{$order->id}}">View Order</a></div>
    </div>
    @endforeach
</div>

@endif

@endsection







