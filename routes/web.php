<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\PageController;

Route::get('/login', [PageController::class, 'LoginPage'])->name('login_page');
Route::get('/register', [PageController::class, 'RegisterPage'])->name('register_page');

Route::post('/login', [ActionController::class, 'login']);
Route::post('/register',[ActionController::class,'register']);
Route::post('/logout', [ActionController::class, 'logout']);

Route::group(['middleware' => 'user'], function () {
    Route::get('/', [PageController::class, 'UserHomePage'])->name('user.home');
    Route::get('/account', [PageController::class, 'AccountPage'])->name('account_page');
    Route::get('/subcategory/{id}', [PageController::class, 'SubCategoryPage'])->name('subcategory');
    Route::get('/product/{id}', [PageController::class, 'ProductPage'])->name('product');
    Route::get('/favorite', [PageController::class, 'FavoritePage'])->name('favorite_page');
    Route::get('/search', [PageController::class, 'SearchPage']);
    Route::get('/ajax_search', [ActionController::class, 'ajax_search']);
    Route::get('/search/page', [ActionController::class, 'search']);
    Route::get('/cart', [PageController::class, 'CartPage'])->name('cart_page');
    Route::get('/order', [PageController::class, 'OrderPage'])->name('order_page');
    Route::get('/order/view/{id}', [PageController::class, 'ViewOrderPage']);
    Route::post('/toggle_favorite', [ActionController::class, 'toggle_favorite']);
    Route::post('/toggle_add_to_cart', [ActionController::class, 'toggle_add_to_cart']);
    Route::post('/update_cart_item', [ActionController::class, 'update_cart_item']);
    Route::post('/delete_cart_item', [ActionController::class, 'delete_cart_item']);
    Route::post('/delete_cart', [ActionController::class, 'delete_cart']);
    Route::post('/place_order', [ActionController::class, 'place_order']);
    Route::post('/cancel_order', [ActionController::class, 'cancel_order']);
    Route::post('/remove_order_item/{id}', [ActionController::class, 'remove_order_item']);
    Route::post('/add_address', [ActionController::class, 'add_address']);
    Route::post('/delete_address/{id}', [ActionController::class, 'delete_address']);
    Route::post('/change_password', [ActionController::class, 'change_password']);
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', [PageController::class, 'AdminHomePage'])->name('admin.home');
    Route::get('/admin/category', [PageController::class, 'AdminCategories']);
    Route::get('/admin/category/{id}', [PageController::class, 'AdminCategory']);
    Route::get('/admin/subcategory', [PageController::class, 'AdminSubCategories']);
    Route::get('/admin/subcategory/{id}', [PageController::class, 'AdminSubcategory']);
    Route::get('/admin/product', [PageController::class, 'AdminProducts']);
    Route::get('/admin/product/{id}', [PageController::class, 'AdminProduct']);
    Route::get('/admin/user', [PageController::class, 'AdminUser']);
    Route::get('/admin/order', [PageController::class, 'AdminOrders']);
    Route::get('/admin/order/{id}', [PageController::class, 'AdminOrder']);
    Route::get('/admin/carousel', [PageController::class, 'AdminCarousels']);

    Route::post('/delete_user/{id}', [ActionController::class, 'delete_user']);
    Route::post('/block_user/{id}', [ActionController::class, 'block_user']);

    Route::post('/create_category', [ActionController::class, 'create_category']);
    Route::post('/update_category/{id}', [ActionController::class, 'update_category']);
    Route::post('/delete_category/{id}', [ActionController::class, 'delete_category']);

    Route::post('/create_subcategory', [ActionController::class, 'create_subcategory']);
    Route::post('/update_subcategory/{id}', [ActionController::class, 'update_subcategory']);
    Route::post('/delete_subcategory/{id}', [ActionController::class, 'delete_subcategory']);

    Route::post('/create_product', [ActionController::class, 'create_product']);
    Route::post('/update_product/{id}', [ActionController::class, 'update_product']);
    Route::post('/delete_product/{id}', [ActionController::class, 'delete_product']);

    Route::post('/create_carousel', [ActionController::class, 'create_carousel']);
    Route::post('/delete_carousel/{id}', [ActionController::class, 'delete_carousel']);

    Route::post('/upload_image', [ActionController::class, 'upload_image']);
    Route::post('/delete_image/{id}', [ActionController::class, 'delete_image']);

    Route::post('/update_order_status/{id}', [ActionController::class, 'update_order_status']);
});





