@section('title','Manage Users')
@extends('admin.partials.header')
@section('content')

<div class="container">
    <div class="row">
        @foreach($users as $user)
            <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                <div class="card bg-dark text-bg-danger">
                    <div class="card-body">
                        <h4 class="card-title">{{$user->name}}</h4>
                        <p>User ID : {{$user->id}}</p>
                        <p>Email : {{$user->email}}</p>
                        <p>Created at : {{$user->created_at}}</p>
                        <p>Total Orders : {{count($user->orders)}}</p>
                        <form class="card-footer d-grid gap-2" action="/delete_user/{{$user->id}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form> 
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection







