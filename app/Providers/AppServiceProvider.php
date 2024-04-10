<?php

namespace App\Providers;

use App\Models\Category;
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
    }

    public function register()
    {
        // Register any application services
    }
}
