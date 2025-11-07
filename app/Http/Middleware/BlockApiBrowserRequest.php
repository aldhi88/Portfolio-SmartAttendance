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
        $contentType = $request->header('Content-Type');

        // Izinkan JSON dan multipart
        $isJson = $contentType && str_contains($contentType, 'application/json');
        $isMultipart = $contentType && str_contains($contentType, 'multipart/form-data');

        if (!($isJson || $isMultipart)) {
            return response()->json([
                'message' => 'Unsupported Content-Type. Only application/json or multipart/form-data allowed.',
            ], 406); // 415 = Unsupported Media Type
        }

        return $next($request);
    }
}
