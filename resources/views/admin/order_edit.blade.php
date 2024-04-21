@section('title','Update Order')
@extends('admin.partials.header')
@section('content')

<div class="container">

    <table class="table table-hover table-bordered border-dark mt-3">
        <thead>
          <tr>
            <th class="table-dark"><b>Order Reference : </b>{{$order->id}}</th>
          </tr>
        </thead>
        <tbody>
          <tr><th><b>Placed On : </b>{{$order->created_at}}</th></tr>
          <tr><th><b>Placed by : </b>{{$order->user->name}}</th></tr>
          <tr><th><b>Address : </b>{{$order->address->name}} {{$order->address->surname}} - 
            {{$order->address->address}} - {{$order->address->more_info}} -
            {{$order->address->district}} / {{$order->address->locality}} - 
            {{$order->address->phone}}</th></tr>
          <tr><th><b>Total Price : </b>${{$order->total_price}}</th></tr>
          <tr>
            <th>
                <b>Status : </b>
                <form method="POST" action="/update_order_status/{{$order->id}}">
                  @csrf
                <select class="form-select" name="status">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                      Pending
                    </option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                      Processing
                    </option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                      Completed
                    </option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                      Cancelled
                    </option>
                </select>
            </th>
          </tr>

          <tr>
            <th>
              <div class="d-flex flex-column gap-2">
                @foreach($order->orderItems as $orderItem)
                <div class="d-flex border overflow-hidden" style="height: 150px;">
                    <div class="h-100">
                        @if ($orderItem->product->images->count() > 0)
                            <img class="object-fit-contain h-100 ratio ratio-1x1" src="{{ asset('storage/images/products/' . $orderItem->product->images->first()->path) }}" alt="Product Image">
                        @else
                             <img src="{{ asset('storage/images/products/no_images.png') }}" alt="No image available">
                        @endif
                    </div>
                    <div class="d-flex flex-column justify-content-center ps-3 gap-2">
                        <div>{{$orderItem->product->name}}</div>
                        <div>$ {{$orderItem->price}}</div>
                        <div>{{$orderItem->quantity}} Item(s)</div>
                    </div>
                </div>
                @endforeach
              </div>
            </th>
          </tr>

          <tr>
            <th class="d-grid gap-2">
            <input type="submit" name="save" value="Save" class="btn btn-primary mt-1">
            </form>
          </th>

          </tr>
        </tbody>
      </table>
</div>

@endsection







