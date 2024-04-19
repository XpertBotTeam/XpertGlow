
@section('title','XpertGlow Admin Dashboard')
@extends('admin.partials.header')
@section('content')

<div class="container">

    <h2 class="pt-3">Welcome {{auth()->user()->name}}</h2>
    
    <div class="row">

        <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
          <div class="card">
          <div class="card-header text-center"><b>Users</b></div>
          <div class="card-body text-center text-secondary"><b>Total : {{count($users);}}</b></div>
          <div class="card-footer d-grid gap-2">
            <a href="admin/user" class="btn btn-dark">Manage</a>
          </div>
          </div>
        </div>

        <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
            <div class="card">
            <div class="card-header text-center"><b>Categories</b></div>
            <div class="card-body text-center text-secondary"><b>Total : {{count($categories);}}</b></div>
            <div class="card-footer d-grid gap-2">
              <a href="admin/category" class="btn btn-dark">Manage</a>
            </div>
            </div>
        </div>

          <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
            <div class="card">
            <div class="card-header text-center"><b>Subcategories</b></div>
            <div class="card-body text-center text-secondary"><b>Total : {{count($subcategories);}}</b></div>
            <div class="card-footer d-grid gap-2">
              <a href="admin/subcategory" class="btn btn-dark">Manage</a>
            </div>
            </div>
          </div>

          <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
            <div class="card">
            <div class="card-header text-center"><b>Products</b></div>
            <div class="card-body text-center text-secondary"><b>Total : {{count($products);}}</b></div>
            <div class="card-footer d-grid gap-2">
              <a href="admin/product" class="btn btn-dark">Manage</a>
            </div>
            </div>
          </div>

          <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
            <div class="card">
            <div class="card-header text-center"><b>Orders</b></div>
            <div class="card-body text-center text-secondary"><b>Total : {{count($orders);}}</b></div>
            <div class="card-footer d-grid gap-2">
              <a href="admin/order" class="btn btn-dark">Manage</a>
            </div>
            </div>
          </div>

          <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 col-xxl-3 p-3">
            <div class="card">
            <div class="card-header text-center"><b>Carousels</b></div>
            <div class="card-body text-center text-secondary"><b>Total : {{count($carousels);}}</b></div>
            <div class="card-footer d-grid gap-2">
              <a href="admin/carousel" class="btn btn-dark">Manage</a>
            </div>
            </div>
          </div>

    </div>
</div>

@endsection
