<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;



Route::get('/login', [PageController::class, 'LoginPage']);
Route::get('/register', [PageController::class, 'RegisterPage']);
Route::get('/', [PageController::class, 'UserHomePage'])->name('user.home');

Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', [PageController::class, 'AdminHomePage'])->name('admin.home');
});


Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register',[UserController::class,'register'])->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

