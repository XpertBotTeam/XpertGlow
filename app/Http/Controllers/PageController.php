<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Carousel;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller

{
    public function UserHomePage()
    {
        $userFavorites = [];
        $products = Product::with('images')->latest()->take(8)->get();
        if (Auth::check()) {
            $userId = Auth::id();
            $userFavorites = Favorite::where('user_id', $userId)
                ->whereIn('product_id', $products->pluck('id'))
                ->pluck('product_id')
                ->toArray();
        }
        
        return view('user.home', compact('products','userFavorites'));
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

        $addresses = Address::where('user_id', $userId)->get();

        return view('user.cart', compact('cart','addresses'));
    }

    public function OrderPage()
    {
        $userId = Auth::id();
        $orders = Order::where('user_id',$userId)->with('orderItems.product.images')->get();
        return view('user.order',compact('orders'));
    }

    public function ViewOrderPage($id)
    {  
         $order = Order::with(['orderItems.product.images','address'])->findOrFail($id);

        return view('user.view_order', compact('order'));
    }

    public function AccountPage()
    {
        $userId = Auth::id();
        $user = User::with(['addresses'])->findOrFail($userId);
        return view('user.account', compact('user'));
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

    public function AdminUser()
    {
        $users = User::with('orders')
            ->where('isAdmin', false)  
            ->get();

        return view('admin.user', compact('users'));
    }

    public function AdminCategories()
    {
        $categories = Category::with(['images'])->get();
        return view('admin.category',compact('categories'));
    }

    public function AdminCategory($id)
    {
        $category = Category::find($id);
        return view('admin.category_edit',compact('category'));
    }

    public function AdminSubCategories()
    {
        $subcategories = SubCategory::with(['images'])->get();
        $categories = Category::all();
        return view('admin.subcategory',compact('subcategories','categories'));
    }

    public function AdminSubCategory($id)
    {
        $subcategory = SubCategory::with(['category'])->find($id);
        $categories = Category::all();
        return view('admin.subcategory_edit',compact('subcategory','categories'));
    }

    public function AdminProducts()
    {
        $products = Product::with(['images'])->get();
        $subcategories = SubCategory::all();
        return view('admin.product',compact('products','subcategories'));
    }

    public function AdminProduct($id)
    {
        $product = Product::with(['subcategory'])->find($id);
        $subcategories = SubCategory::all();
        return view('admin.product_edit',compact('product','subcategories'));
    }

    public function AdminOrders()
    {
        $orders = Order::with(['user','address','orderItems.product'])->get();
        return view('admin.order',compact('orders'));
    }

    public function AdminOrder($id)
    {
        $order = Order::with(['user','address','orderItems.product'])->find($id);
        return view('admin.order_edit',compact('order'));
    }

    public function AdminCarousels()
    {
        $products = Product::all();
        $subcategories = SubCategory::all();
        $carousels = Carousel::with(['image'])->get();
        return view('admin.carousel',compact('carousels','products','subcategories'));
    }


}
