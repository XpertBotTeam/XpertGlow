<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Auth;

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

    public function FavoritePage()
    {
        $products = [];
        $userFavorites = [];
        
        if (Auth::check()) {
            $products = Product::all();
            $userId = Auth::id();
            $userFavorites = Favorite::where('user_id', $userId)
                ->pluck('product_id')
                ->toArray();
        }  

        return view('user.favorite', [
            'products' => $products,
            'userFavorites'=>$userFavorites
        ]);
    }

    public function CartPage()
    {
        return view('user.cart');
    }

    public function OrderPage()
    {
        return view('user.order');
    }

    public function AccountPage()
    {
        return view('user.account');
    }

    public function SubCategoryPage($id)
    {
        $subcategory = SubCategory::with('products')->findOrFail($id);
        $userFavorites = [];
        if (Auth::check()) {
            $userId = Auth::id();
            $userFavorites = Favorite::where('user_id', $userId)
                ->whereIn('product_id', $subcategory->products->pluck('id'))
                ->pluck('product_id')
                ->toArray();
        }
        return view('user.subcategory', [
            'subcategory' => $subcategory,
            'products' => $subcategory->products,
            'userFavorites' => $userFavorites,
        ]);
    }

    public function ProductPage($id){
        $product = Product::findOrFail($id);
        return view('user.product', ['product' => $product]);
    }
}
