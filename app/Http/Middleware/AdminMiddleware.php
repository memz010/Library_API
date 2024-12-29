<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->id !== 101 && $request->user()->id!= 201) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
