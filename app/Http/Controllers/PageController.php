<?php

namespace App\Http\Controllers;

use App\Models\Cart;
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
        $products = collect();
        $userFavorites = [];
        
        if (Auth::check()) {
            $userId = Auth::id();
            $favoriteProductIds = Favorite::where('user_id', $userId)
            ->pluck('product_id')
            ->toArray();
            $products = Product::with('images')
            ->whereIn('id', $favoriteProductIds)
            ->get();
            $userFavorites = $favoriteProductIds;
        }  
        return view('user.favorite', compact('products', 'userFavorites'));
    }

    public function CartPage()
    {
        $userId = Auth::id();

        $cart = Cart::where('user_id', $userId)
                ->with('cartItems.product.images') 
                ->first();

        return view('user.cart', compact('cart'));
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
        $subcategory = SubCategory::with(['products.images'])->findOrFail($id);
        $userFavorites = [];
        if (Auth::check()) {
            $userId = Auth::id();
            $userFavorites = Favorite::where('user_id', $userId)
                ->whereIn('product_id', $subcategory->products->pluck('id'))
                ->pluck('product_id')
                ->toArray();
        }
        return view('user.subcategory', compact('subcategory', 'userFavorites'));
    }

    public function ProductPage($id)
    {
        $product = Product::findOrFail($id);
        $isFavorited = false;
        if (Auth::check()) {
            $userId = Auth::id();
            $isFavorited = Favorite::where('user_id', $userId)
                                ->where('product_id', $id)
                                ->exists();
        }
        $images = $product->images;
        return view('user.product', compact('product', 'images', 'isFavorited'));
    }
}
