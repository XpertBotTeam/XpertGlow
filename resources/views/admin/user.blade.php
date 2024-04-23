@section('title','Manage Users')
@extends('admin.partials.header')
@section('content')

<div class="container">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <strong> {{ session('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        <strong> {{ $errors->first() }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        @foreach($users as $user)
            <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                <div class="card bg-dark text-bg-danger">
                    <div class="card-body">
                        <h4 class="card-title">{{$user->name}}</h4>
                        <p><b>User ID : </b>{{$user->id}}</p>
                        <p><b>Email : </b>{{$user->email}}</p>
                        <p><b>Created at : </b>{{$user->created_at}}</p>
                        <p><b>Total Orders : </b>{{count($user->orders)}}</p>
                        <p>Status : 
                            @if($user->isBlocked==0)
                                <b class="text-success">Active</b>
                            @else
                                <b class="text-danger">Blocked</b>
                            @endif
                        </p>
                        <form class="card-footer d-grid gap-2" action="/block_user/{{$user->id}}" method="POST">
                            @csrf
                            @if($user->isBlocked==0)
                                <button type="submit" class="btn btn-danger">Block this User</button>
                            @else
                                <button type="submit" class="btn btn-success">Unblock this User</button>
                            @endif
                        </form> 

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







