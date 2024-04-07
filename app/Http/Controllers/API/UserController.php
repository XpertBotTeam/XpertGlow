<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->isAdmin) {
                return redirect()->route('admin.home');
            } else {
                return redirect()->route('user.home');
            }
        } else {
            return back()->withErrors(['message' => 'Invalid credentials']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.home');
    }

    public function register(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
    
        Auth::login($user);
    
        return redirect()->route('user.home');
    }
}

