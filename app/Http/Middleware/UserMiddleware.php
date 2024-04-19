<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if(Auth::user()->isAdmin){
                return redirect()->route('admin.home');
            }
            else{
                return $next($request);
            }
        }

        else{
            return $next($request);
        }
    }
}
