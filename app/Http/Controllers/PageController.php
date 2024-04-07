<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller

{
    public function UserHomePage()
    {
        return view('user.home');
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
}
