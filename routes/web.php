<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;

Route::get('/login', [PageController::class, 'LoginPage'])->name('login_page');
Route::get('/register', [PageController::class, 'RegisterPage'])->name('register_page');
Route::get('/', [PageController::class, 'UserHomePage'])->name('user.home');
Route::get('/subcategory/{id}', [PageController::class, 'SubCategoryPage'])->name('subcategory');
Route::get('/product/{id}', [PageController::class, 'ProductPage'])->name('product');
Route::get('/search', [PageController::class, 'SearchPage'])->name('search_page');

Route::get('/ajax_search', [SearchController::class, 'ajax_search'])->name('ajax_search');
Route::get('/search', [SearchController::class, 'search'])->name('searchh');

Route::post('/toggle_favorite', [ActionController::class, 'toggle_favorite']);
Route::post('/toggle_add_to_cart', [ActionController::class, 'toggle_add_to_cart']);
Route::post('/update_cart_item', [ActionController::class, 'update_cart_item']);
Route::post('/delete_cart_item', [ActionController::class, 'delete_cart_item']);
Route::post('/delete_cart', [ActionController::class, 'delete_cart']);

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register',[UserController::class,'register'])->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'user'], function () {
    Route::get('/favorite', [PageController::class, 'FavoritePage'])->name('favorite_page');
    Route::get('/cart', [PageController::class, 'CartPage'])->name('cart_page');
    Route::get('/order', [PageController::class, 'OrderPage'])->name('order_page');
    Route::get('/account', [PageController::class, 'AccountPage'])->name('account_page');
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', [PageController::class, 'AdminHomePage'])->name('admin.home');
});

