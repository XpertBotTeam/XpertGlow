<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\CarouselController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SubCategoryController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\SearchController;

        Route::post('/login', [UserController::class, 'login']);
        Route::post('/register', [UserController::class, 'register']);
        Route::get('/search', [SearchController::class, 'search']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}', [CategoryController::class, 'show']);
        Route::get('/subcategories', [SubCategoryController::class, 'index']);
        Route::get('/subcategories/{id}', [SubCategoryController::class, 'show']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);

    Route::middleware(['auth:sanctum', 'api_user'])->group(function () {
        Route::get('/all_favorites', [FavoriteController::class, 'all_favorites']);
        Route::post('/toggle_favorite/{id}', [FavoriteController::class, 'toggle_favorite']);
        Route::get('/cart', [CartController::class, 'list_cart_items']);
        Route::post('/add_to_cart/{id}', [CartController::class, 'add_to_cart']);
        Route::post('/update_cart_item_quantity/{id}', [CartController::class, 'update_cart_item_quantity']);
        Route::delete('/remove_from_cart/{id}', [CartController::class, 'remove_from_cart']);
        Route::delete('/cart/clear', [CartController::class, 'clear_cart']);
        Route::get('/user/addresses', [AddressController::class, 'get_user_addresses']);
        Route::post('/addresses', [AddressController::class, 'store']);
        Route::put('/addresses/{id}', [AddressController::class, 'update']);
        Route::put('/addresses/deactivate/{id}', [AddressController::class, 'deactivate_address']);
        Route::post('/place_order', [OrderController::class, 'place_order']);
        Route::get('/orders/user', [OrderController::class, 'user_orders']);
        Route::delete('/orders/{order_id}/item/{item_id}', [OrderController::class, 'remove_order_item']);
        Route::put('/orders/cancel/{id}', [OrderController::class, 'cancel_order']);
    });

    Route::middleware(['auth:sanctum', 'api_admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/users/status/{id}', [UserController::class, 'change_user_status']);
        Route::delete('/users/{id}', [UserController::class, 'delete_user']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
        Route::post('/subcategories', [SubCategoryController::class, 'store']);
        Route::put('/subcategories/{id}', [SubCategoryController::class, 'update']);
        Route::delete('/subcategories/{id}', [SubCategoryController::class, 'destroy']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::get('/images', [ImageController::class, 'index']);
        Route::post('/images', [ImageController::class, 'store']);
        Route::delete('/images/{id}', [ImageController::class, 'destroy']);
        Route::get('/carousels', [CarouselController::class, 'index']);
        Route::post('/carousels', [CarouselController::class, 'store']);
        Route::delete('/carousels/{id}', [CarouselController::class, 'destroy']);
        Route::get('/addresses', [AddressController::class, 'index']);
        Route::get('/addresses/{id}', [AddressController::class, 'show']);
        Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::put('/orders/status/{id}', [OrderController::class, 'change_order_status']);
    });

    Route::middleware(['auth:sanctum', 'api_user_admin'])->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
        Route::put('/users/change_password', [UserController::class, 'change_password']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
    });

    
    
    
   

   

    
   



  


