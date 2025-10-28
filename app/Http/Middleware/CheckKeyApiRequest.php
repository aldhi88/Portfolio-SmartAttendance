<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckKeyApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     $apiKey = $request->header('X-API-KEY');

    //     if ($apiKey !== config('app.api_secret_key')) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next): Response
    {
        // Kalau sudah autentik via Sanctum, lewati cek API key
        if ($request->user()) {           // atau: if (auth('sanctum')->check())
            return $next($request);
        }

        // Selain itu, wajib bawa X-API-KEY yang valid (server-to-server)
        $apiKey = (string) $request->header('X-API-KEY');
        if (!hash_equals((string) config('app.api_secret_key'), $apiKey)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
