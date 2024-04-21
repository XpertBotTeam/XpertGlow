<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiUserMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        
        if ($user && !$user->isAdmin() && !$user->isBlocked()) { 
            return $next($request);
        }

        if ($user && $user->isBlocked()) {
            return response()->json(['error' => 'Your account is blocked. Please contact support for assistance.'], 403);
        }

        return response()->json(['error' => 'Unauthorized. You need to be a registered User.'], 403);
    }
}
