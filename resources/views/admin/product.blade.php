@section('title','Manage Products')
@extends('admin.partials.header')
@section('content')

<div class="container">

    <div class="border border-dark border-3 p-3 m-3">
        <h2 class="text-center">New Product</h2>
        <form method="POST" action="/create_product">
        @csrf
            <label for="name" class="form-label mt-3"><b>Product Name:</b></label><br> 
            <input type="text" class="form-control" name="name" required>

            <label for="description" class="form-label mt-3"><b>Product Description:</b></label><br> 
            <input type="text" class="form-control" name="description" required>

            <label for="code" class="form-label mt-3"><b>Product Code:</b></label><br> 
            <input type="text" class="form-control" name="code" required>

            <label for="quantity" class="form-label mt-3"><b>Product quantity:</b></label><br> 
            <input type="number" step="1" class="form-control" name="quantity" required>

            <label for="price" class="form-label mt-3"><b>Product Price:</b></label><br> 
            <input type="number" step="0.01" class="form-control" name="price" required>

            <label for="subcategory" class="form-label mt-3"><b>Product Subcategory:</b></label><br>
            <select name="subcategory" class="form-select" required>
                    <option value="" disabled selected>Select an Subcategory</option>
                    @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                    @endforeach
            </select>

            <div class="d-grid mt-3">
                <input class="btn btn-success btn-lg" type="submit" value="Create">
            </div>
        </form>
    </div>

    <div class="border border-dark border-3 p-3 m-3"> 
        <h3 class="text-center">All Products</h3>
    <div class="row">
        @foreach($products as $product)
            <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                <div class="card bg-dark text-bg-danger">
                    @if(!$product->images->isEmpty())
                    <img class="card-img-top" src="{{ asset('storage/images/products/' . $product->images->first()->path) }}" alt="Card image">
                    @else
                    <img class="card-img-top" src="{{asset('storage/images/products/no_images.png')}}" alt="Card image">
                    @endif
                    <div class="card-body">
                        <h4 class="card-title"><b>Product ID : </b>{{$product->id}}</h4>
                        <p><b>Name : </b>{{$product->name}}</p>
                        <p><b>Code : </b>{{$product->code}}</p>
                        <p><b>Quantity : </b>{{$product->quantity}}</p>
                        <form class="d-grid gap-2" action="/delete_product/{{$product->id}}" method="POST">
                            @csrf
                            <a class="d-grid gap-2 btn btn-primary" href="/admin/product/{{$product->id}}">Update</a>
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
