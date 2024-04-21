@section('title','Update Subcategory')
@extends('admin.partials.header')
@section('content')

<div class="container">

    <div class="border border-dark border-3 p-3 m-3">
        <h2 class="text-center">Update Subcategory</h2>
        <form method="POST" action="/update_subcategory/{{$subcategory->id}}">
        @csrf
            <label for="name" class="form-label mt-3"><b>Subcategory Name:</b></label><br> 
            <input type="text" class="form-control" name="name" value="{{$subcategory->name}}">

            <label for="category" class="form-label mt-3"><b>Subcategory Category:</b></label><br>
            <select name="category" class="form-select">
                    <option value="" disabled selected>Select an Category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $subcategory->category->id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
            </select>
            <div class="d-grid pt-3">
                <input class="btn btn-success btn-lg" type="submit" value="Save">
            </div>
        </form>
    </div>

    <div class="border border-dark border-3 p-3 m-3">

        <h3 class="text-center">Add Image for Subcategory</h3>

        <form action="/upload_image" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-grid">
            <input type="hidden" name="id" value="{{$subcategory->id}}">
            <input type="hidden" name="imageable_type" value="App\Models\SubCategory">
            <label for="image" class="form-label"><b>Choose an image:</b></label>
            <input type="file" name="image" class="form-control" required accept="image/*">
            <input class="btn btn-success btn-lg  mt-3" type="submit" value="Upload">
            </div>
        </form>

        <h3 class="text-center pt-3">All Images</h3>

        @if($subcategory->images->isEmpty())
            <h3 class="text-center pt-3">No Images for this Product</h3>
        @else
        <div class="row">
            @foreach ($subcategory->images as $image)
            <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                <div class="card bg-dark text-bg-danger">
                    <img class="card-img-top" src="{{ asset('storage/images/subcategories/' . $image->path) }}" alt="Card image">
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
