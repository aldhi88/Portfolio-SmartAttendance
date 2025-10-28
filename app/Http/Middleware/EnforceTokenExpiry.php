<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceTokenExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()?->currentAccessToken();

        if ($token && $token->expires_at && $token->expires_at->isPast()) {
            return response()->json(['code' => 'ACCESS_EXPIRED', 'message' => 'Token expired'], 401);
        }

        // opsional: update last_used_at
        if ($token) {
            $token->forceFill(['last_used_at' => now()])->save();
        }

        return $next($request);
    }
}
