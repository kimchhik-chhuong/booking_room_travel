<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomCors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        if ($request->getMethod() === "OPTIONS") {
            $response->setStatusCode(200);
            return $response;
        }

        return $response;
    }
}
