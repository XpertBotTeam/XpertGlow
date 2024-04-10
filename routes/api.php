<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SubCategoryController;

Route::group(['middleware'=>['auth:sanctum']],function(){
    
    Route::resource('categories',CategoryController::class);
    Route::resource('subcategories',SubCategoryController::class);
    Route::resource('products',ProductController::class);
  
});




