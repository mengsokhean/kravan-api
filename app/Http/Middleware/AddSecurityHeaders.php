<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddSecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set(
            'Content-Security-Policy',
            "default-src *; connect-src *; img-src * data:; script-src * 'unsafe-inline' 'unsafe-eval'; style-src * 'unsafe-inline';"
        );
        return $response;
    }
}