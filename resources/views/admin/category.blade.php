@section('title','Manage Categories')
@extends('admin.partials.header')
@section('content')

<div class="container">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        <strong> {{ session('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
          <strong> {{ session('error') }}</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
        <strong> {{ $errors->first() }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="border border-dark border-3 p-3 m-3">
        <h2 class="text-center">New Category</h2>
        <form method="POST" action="/create_category">
        @csrf
            <label for="name" class="form-label mt-3"><b>Category Name:</b></label><br> 
            <input type="text" class="form-control" name="name"required>
            <div class="d-grid pt-3">
                <input class="btn btn-success btn-lg" type="submit" value="Create">
            </div>
        </form>
    </div>

    <div class="border border-dark border-3 p-3 m-3">
        <h2 class="text-center">All Categories</h2>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                    <div class="card bg-dark text-bg-danger">
                        @if(!$category->images->isEmpty())
                        <img class="card-img-top" src="{{ asset('storage/images/categories/' . $category->images->first()->path) }}" alt="Card image">
                        @else
                        <img class="card-img-top" src="{{asset('storage/images/categories/no_images.png')}}" alt="Card image">
                        @endif
                        <div class="card-body">
                            <h4 class="card-title"><b>Category ID : </b>{{$category->id}}</h4>
                            <p><b>Name : </b>{{$category->name}}</p>
                            <form class="d-grid gap-2" action="/delete_category/{{$category->id}}" method="POST">
                                @csrf
                                <a class="d-grid gap-2 btn btn-primary" href="/admin/category/{{$category->id}}">Update</a>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form> 
                            
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

@endsection
