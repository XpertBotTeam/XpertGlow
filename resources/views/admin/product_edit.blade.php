@section('title','Update Product')
@extends('admin.partials.header')
@section('content')

<div class="container">

    <div class="border border-dark border-3 p-3 m-3">
        <h2 class="text-center">Update Product</h2>
        <form method="POST" action="/update_product/{{$product->id}}">
        @csrf
            <label for="name" class="form-label mt-3"><b>Product Name:</b></label><br> 
            <input type="text" class="form-control" name="name" value="{{$product->name}}">

            <label for="description" class="form-label mt-3"><b>Product Description:</b></label><br> 
            <textarea type="text" class="form-control" name="description" >{{$product->description}}</textarea>

            <label for="code" class="form-label mt-3"><b>Product Code:</b></label><br> 
            <input type="text" class="form-control" name="code" value="{{$product->code}}">

            <label for="quantity" class="form-label mt-3"><b>Product quantity:</b></label><br> 
            <input type="number" step="1" class="form-control" name="quantity" value="{{$product->quantity}}">

            <label for="price" class="form-label mt-3"><b>Product Price:</b></label><br> 
            <input type="number" step="0.01" class="form-control" name="price" value="{{$product->price}}">

            <label for="subcategory" class="form-label mt-3"><b>Product Subcategory:</b></label><br>
            <select name="subcategory" class="form-select">
                    <option value="" disabled selected>Select an Subcategory</option>
                    @foreach ($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" {{ $product->sub_category_id == $subcategory->id ? 'selected' : '' }}>
                        {{ $subcategory->name }}
                    </option>
                    @endforeach
            </select>
            
            <div class="d-grid mt-3">
                <input class="btn btn-success btn-lg" type="submit" value="Save">
            </div>
        </form>
    </div>

    <div class="border border-dark border-3 p-3 m-3">

        <h3 class="text-center">Add Image for Product</h3>

        <form action="/upload_image" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-grid">
            <input type="hidden" name="id" value="{{$product->id}}">
            <input type="hidden" name="imageable_type" value="App\Models\Product">
            <label for="image" class="form-label"><b>Choose an image:</b></label>
            <input type="file" name="image" class="form-control" required accept="image/*">
            <input class="btn btn-success btn-lg  mt-3" type="submit" value="Upload">
            </div>
        </form>

        <h3 class="text-center pt-3">All Images</h3>

        @if($product->images->isEmpty())
            <h3 class="text-center pt-3">No Images for this Product</h3>
        @else
        <div class="row">
            @foreach ($product->images as $image)
            <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                <div class="card bg-dark text-bg-danger">
                    <img class="card-img-top" src="{{ asset('storage/images/products/' . $image->path) }}" alt="Card image">
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
