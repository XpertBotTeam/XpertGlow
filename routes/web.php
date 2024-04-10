<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;

Route::get('/login', [PageController::class, 'LoginPage']);
Route::get('/register', [PageController::class, 'RegisterPage']);
Route::get('/', [PageController::class, 'UserHomePage'])->name('user.home');
Route::get('/subcategory/{id}', [PageController::class, 'SubCategoryPage'])->name('subcategory');
Route::get('/product/{id}', [PageController::class, 'ProductPage'])->name('product');
Route::get('/search', [PageController::class, 'SearchPage'])->name('search_page');

Route::get('/ajax_search', [SearchController::class, 'ajax_search'])->name('ajax_search');
Route::get('/search', [SearchController::class, 'search'])->name('searchh');

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register',[UserController::class,'register'])->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', [PageController::class, 'AdminHomePage'])->name('admin.home');
});

