<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SubCategory;

class PageController extends Controller

{
    public function UserHomePage()
    {
        return view('user.home');
    }

    public function AdminHomePage()
    {
        return view('admin.home');
    }
    public function LoginPage()
    {
        return view('auth.login');
    }

    public function RegisterPage()
    {
        return view('auth.register');
    }

    public function SearchPage()
    {
        $results = [];
        $query = null; 
        return view('user.search', compact('results', 'query'));
    }

    public function SubCategoryPage($id){

        $subcategory = SubCategory::findOrFail($id);
        $products = $subcategory->products()->get();
        return view('user.subcategory', ['subcategory' => $subcategory, 'products' => $products]);
    }

    public function ProductPage($id){

        $product = Product::findOrFail($id);
        return view('user.product', ['product' => $product]);
    }
}
