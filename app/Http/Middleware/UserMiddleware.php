<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->isAdmin) {
            return $next($request);
        }

        return redirect()->route('user.home')->with('error', 'You do not have permission to access this page.');
    }
}
