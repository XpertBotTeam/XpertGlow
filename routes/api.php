<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;





Route::group(['middleware'=>['auth:sanctum']],function(){
    
Route::resource('categories',CategoryController::class);
Route::resource('products',ProductController::class);
  
});


