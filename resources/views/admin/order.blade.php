@section('title','Manage Orders')
@extends('admin.partials.header')
@section('content')

<div class="container">

    @foreach($orders as $order)
    
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
                <select class="form-select" name="status">
                    <option value="" disabled selected></option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                </select>
            </th>
          </tr>

          <tr>
            <th class="d-grid gap-2">
            <input type="submit" name="save" value="Save" class="btn btn-primary mt-1">
          </th>

          </tr>
        </tbody>
      </table>
      @endforeach

</div>

@endsection







