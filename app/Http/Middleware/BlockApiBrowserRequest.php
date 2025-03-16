<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockApiBrowserRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika request tidak mengharapkan JSON, tolak akses
        if (!$request->expectsJson()) {
            return response()->json(['message' => 'Only JSON requests are allowed'], 406);
        }

        return $next($request);
    }
}
