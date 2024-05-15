<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
                return response()->json(['message' => $validator->errors()->first()], 422);
            }
        
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if ($user->isBlocked()) {
                    return response()->json(['message' => 'User is blocked'], 401);
                }

                $token = $user->createToken('API Token')->plainTextToken;
                return response()->json([
                    'token' => $token,
                    'user' => $user
                ], 200);
            }

            return response()->json(['message' => 'The email address or password is incorrect'], 401);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function change_user_status(Request $request, $id)
    {
        $user = $request->user(); 
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $change_user = User::find($id);
        if (!$change_user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        $change_user->isBlocked = !$change_user->isBlocked;
        $change_user->save();
        $message = $change_user->isBlocked ? 'User blocked successfully' : 'User unblocked successfully';

        return response()->json(['message' => $message], 200);
    }

    public function change_password(Request $request)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function delete_user(Request $request, $id)
    {
        $user = $request->user(); 

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if ($user->id === $id) {
            return response()->json(['error' => 'You cannot delete your own account'], 400);
        }

        $deleted_user = User::find($id);
        if (!$deleted_user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $deleted_user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function index()
    {
        $users = User::with('orders')->get();
        return response()->json(['users' => $users], 200);
    }

}

