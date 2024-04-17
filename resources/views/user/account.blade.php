@section('title', $user->name)
@extends('user.partials.header')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/user/account.css') }}">

<div class="account_wrapper">

    <div class="account_name">{{$user->name}}</div>
    <div class="account_email">Email : {{$user->email}}</div>

    <div class="account_password">
        <div>
            <button id="password">Password</button>
        </div>
        <div class="password_input">
            <form method="POST" action="/change_password">
                @csrf
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="submit" value="Submit">
            </form>
            <button class="close_password">Close</button>
        </div>
    </div>

    <div class="account_addresses">
        <div>
            <button id="address">Addresses</button>
        </div>

        <div class="address_input">
            <form method="POST" action="/add_address">
                @csrf
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="surname" placeholder="Surname" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="more_info" placeholder="More Info" required>
                <input type="text" name="district" placeholder="District" required>
                <input type="text" name="locality" placeholder="Locality" required>
                <input type="number" name="phone" placeholder="Phone" required>
                <input type="submit" value="Submit">
            </form>
            <button class="close_address">Close</button>
        </div>

        <div class="all_addresses">
            @foreach ($user->addresses as $address)
            <li>
                {{$address->name}} {{$address->surname}} - 
                {{$address->address}} - {{$address->more_info}} -
                {{$address->district}} / {{$address->locality}} - 
                {{$address->phone}}
            </li>
            @endforeach
        </div>
    </div>
    
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/user/account.js') }}"></script>
@endsection

