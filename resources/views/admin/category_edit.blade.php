@section('title','Update Category')
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
        <h2 class="text-center">Update Category</h2>
        <form method="POST" action="/update_category/{{$category->id}}">
        @csrf
         <label for="name" class="form-label mt-3"><b>Category Name:</b></label><br> 
         <input type="text" class="form-control" value="{{$category->name}}" name="name">
            <div class="d-grid pt-3">
                <input class="btn btn-success btn-lg" type="submit" value="Save">
            </div>
        </form>
    </div>

    <div class="border border-dark border-3 p-3 m-3">

        <h3 class="text-center">Add Image for Category</h3>

        <form action="/upload_image" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-grid">
            <input type="hidden" name="imageable_id" value="{{$category->id}}">
            <input type="hidden" name="imageable_type" value="App\Models\Category">
            <label for="image" class="form-label"><b>Choose an image:</b></label>
            <input type="file" name="image" class="form-control" required accept="image/*">
            <input class="btn btn-success btn-lg  mt-3" type="submit" value="Upload">
            </div>
        </form>

        <h3 class="text-center pt-3">All Images</h3>

        @if($category->images->isEmpty())
            <h3 class="text-center pt-3">No Images for this Category</h3>
        @else
        <div class="row">
            @foreach ($category->images as $image)
            <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                <div class="card bg-dark text-bg-danger">
                    <img class="card-img-top" src="{{ asset('storage/images/categories/' . $image->path) }}" alt="Card image">
                    <div class="card-body">
                        <p><b>Image ID : </b>{{$image->id}}</p>
                        <form class="d-grid gap-2" action="/delete_image/{{$image->id}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form> 
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        
    </div>


</div>

@endsection
