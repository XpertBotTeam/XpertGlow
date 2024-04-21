<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminMiddleware
{
    
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->isAdmin()) { 
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized. You need to be a registered Admin.'], 403);
    }

    
}
