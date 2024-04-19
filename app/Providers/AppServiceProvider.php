<?php

namespace App\Providers;

use App\Models\Carousel;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        view()->composer('user.partials.header', function ($view) {
            $categories = Category::with('subcategories')->get();
            $view->with('categories', $categories);
        });

        view()->composer('user.partials.carousel', function ($view) {
            $carousels = Carousel::with('image')->get();
            $view->with('carousels', $carousels);
        });

        view()->composer('admin.home', function ($view) {

            $users = User::where('isAdmin', false)->get();
            $categories = Category::all();
            $subcategories = SubCategory::all();
            $products = Product::all();
            $orders = Order::all();
            $carousels = Carousel::all();
            
            $view->with('users', $users);
            $view->with('categories', $categories);
            $view->with('subcategories', $subcategories);
            $view->with('products', $products);
            $view->with('orders', $orders);
            $view->with('carousels', $carousels);
            
        });
    }

    public function register()
    {
        // Register any application services
    }
}
