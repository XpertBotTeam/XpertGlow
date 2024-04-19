@section('title','Manage Carousels')
@extends('admin.partials.header')
@section('content')

<div class="container">

    <div class="border border-dark border-3 p-3 m-3">
        <form action="/create_carousel" method="POST" enctype="multipart/form-data" class="border border-dark border-2 p-3 m-3">
            <h3 class="text-center">Product Carousel</h3>
            @csrf
            <div class="d-grid">
                <input type="hidden" name="carouselable_type" value="App\Models\Product">
                <label for="subcategory" class="form-label mt-3"><b>Product Carousel:</b></label>
                <select name="carouselable_id" class="form-select" required>
                        <option value="" disabled selected>Select an Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                </select>
                <input type="hidden" name="imageable_type" value="App\Models\Product">
                <label for="image" class="form-label"><b>Choose an image:</b></label>
                <input type="file" name="image" class="form-control" required accept="image/*">
            <input class="btn btn-success btn-lg  mt-3" type="submit" value="Create">
            </div>
        </form>

        <form action="/create_carousel" method="POST" enctype="multipart/form-data" class="border border-dark border-2 p-3 m-3">
            <h3 class="text-center">Subcategory Carousel</h3>
            @csrf
            <div class="d-grid">
                <input type="hidden" name="carouselable_type" value="App\Models\SubCategory">
                <label for="subcategory" class="form-label mt-3"><b>Subcategory Carousel:</b></label>
                <select name="carouselable_id" class="form-select" required>
                        <option value="" disabled selected>Select an Subcategory</option>
                        @foreach ($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                        @endforeach
                </select>
                <input type="hidden" name="imageable_type" value="App\Models\SubCategory">
                <label for="image" class="form-label"><b>Choose an image:</b></label>
                <input type="file" name="image" class="form-control" required accept="image/*">
            <input class="btn btn-success btn-lg  mt-3" type="submit" value="Create">
            </div>
        </form>
    </div>

    <div class="border border-dark border-3 p-3 m-3">
        <h2 class="text-center">All Carousels</h2>
        <div class="row">
            @foreach($carousels as $carousel)
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                    <div class="card bg-dark text-bg-danger"> 
                        <img class="card-img-top" src="{{ asset('storage/images/carousels/' . $carousel->image->path) }}" alt="Card image">
                        <div class="card-body">
                            <h4 class="card-title"><b>Carousel ID : </b>{{$carousel->id}}</h4>
                            <form class="d-grid gap-2" action="/delete_carousel/{{$carousel->id}}" method="POST">
                                @csrf
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
