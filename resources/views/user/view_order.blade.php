@extends('user.partials.header')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/css/user/view_order.css') }}">

<div class="order_wrapper" data-order-status="{{$order->status}}">

        <div class="order_status">
            <div class="line_1"></div>
            <div class="line_2"></div>
            <div class="pending">Pending</div>
            <div class="processing">Processing</div>
            <div class="completed">Completed</div>
            <div class="pending_circle"></div>
            <div class="processing_circle"></div>
            <div class="completed_circle"></div>
        </div>

        <div class="order_information">
            @php $itemCount = 0; @endphp
            @foreach($order->orderItems as $orderItem)
            @php $itemCount += $orderItem->quantity;@endphp
            @endforeach
            @if($order->status==="pending")
            <div class="order_cancel" data-order-id="{{$order->id}}">
                <button id="cancel">Cancel Order</button>
            </div>
            @endif
            <div class="order_nb"><span>Order Number : </span>{{$order->id}}</div>
            <div class="order_date"><span>Placed on : </span>{{$order->created_at}}</div>
            <div class="order_price"><span>Total Price : </span>$ {{$order->total_price}}</div>
            <div class="order_status_text"><span>Status : </span>{{$order->status}}</div>
            <div class="order_address"><span>Address : </span>
            {{$order->address->name}} {{$order->address->surname}} - 
            {{$order->address->address}} - {{$order->address->more_info}} -
            {{$order->address->district}} / {{$order->address->locality}} - 
            {{$order->address->phone}}
            </div>
            <div class="order_total_items"><span>{{$itemCount}} Item(s)</span></div>
        </div>

        <div class="order_items">
                @foreach($order->orderItems as $orderItem)
                <div class="item" data-item_id="{{$orderItem->id}}">

                    <div class="img_container">
                        @if ($orderItem->product->images->count() > 0)
                            <img src="{{ asset('storage/images/products/' . $orderItem->product->images->first()->path) }}" alt="Product Image">
                        @else
                             <img src="{{ asset('storage/images/products/no_images.png') }}" alt="No image available">
                        @endif
                    </div>

                    <div class="item_information">
                        <div class="item_name">{{$orderItem->product->name}}</div>
                        <div class="item_price">$ {{$orderItem->price}}</div>
                        <div class="item_quantity">{{$orderItem->quantity}} Item(s)</div>
                        @if($order->status === "pending")
                        <div class="item_remove">
                            <form action="/remove_order_item/{{$orderItem->id}}" method="POST">
                                @csrf
                            <button type="submit">Remove</button>
                            </form> 
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/user/manage_order.js') }}"></script>
@endsection