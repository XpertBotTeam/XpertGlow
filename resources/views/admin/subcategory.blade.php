@section('title','Manage Subcategories')
@extends('admin.partials.header')
@section('content')

<div class="container">

    <div class="border border-dark border-3 p-3 m-3">
        <h2 class="text-center">New Subcategory</h2>
        <form method="POST" action="/create_subcategory">
        @csrf
            <label for="name" class="form-label mt-3"><b>Subcategory Name:</b></label><br> 
            <input type="text" class="form-control" name="name" required>

            <label for="category" class="form-label mt-3"><b>Subcategory Category:</b></label><br>
            <select name="category" class="form-select" required>
                    <option value="" disabled selected>Select an Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
            </select>

            <div class="d-grid mt-3">
                <input class="btn btn-success btn-lg" type="submit" value="Create">
            </div>
        </form>
    </div>

    <div class="border border-dark border-3 p-3 m-3">
        <h3 class="text-center">All Subcategories</h3>
        <div class="row">
            @foreach($subcategories as $subcategory)
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
                    <div class="card bg-dark text-bg-danger">
                        @if(!$subcategory->images->isEmpty())
                        <img class="card-img-top" src="{{ asset('storage/images/subcategories/' . $subcategory->images->first()->path) }}" alt="Card image">
                        @else
                        <img class="card-img-top" src="{{asset('storage/images/subcategories/no_images.png')}}" alt="Card image">
                        @endif
                        <div class="card-body">
                            <h4 class="card-title"><b>Subcategory ID : </b>{{$subcategory->id}}</h4>
                            <p><b>Name : </b>{{$subcategory->name}}</p>
                            <form class="d-grid gap-2" action="/delete_subcategory/{{$subcategory->id}}" method="POST">
                                @csrf
                                <a class="d-grid gap-2 btn btn-primary" href="/admin/subcategory/{{$subcategory->id}}">Update</a>
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
