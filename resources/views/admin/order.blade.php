@section('title','Manage Orders')
@extends('admin.partials.header')
@section('content')
<div class="container">

  <div class="border border-dark border-3 p-3 m-3"> 
        <h3 class="text-center">All Orders</h3>
    <div class="row">
        @foreach($orders as $order)
            <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                <div class="card bg-dark text-bg-danger">
                    <div class="card-body">
                        <h4 class="card-title"><b>Order ID : </b>{{$order->id}}</h4>
                        <p><b>Owner : </b>{{$order->user->name}}</p>
                        @php $itemCount = 0; @endphp
                      @foreach($order->orderItems as $orderItem)
                          @php $itemCount += $orderItem->quantity;@endphp
                      @endforeach
                        <p><b>Total Item(s) : </b>{{$itemCount}}</p>
                        <p><b>Total Price : </b>$ {{$order->total_price}}</p>
                        <p class="text-capitalize"><b>Status : </b>{{$order->status}}</p>
                        <a class="d-grid gap-2 btn btn-primary" href="/admin/order/{{$order->id}}">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    </div>
</div>

@endsection







