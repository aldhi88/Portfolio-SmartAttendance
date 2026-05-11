<?php

namespace App\Http\Middleware;

use App\Helpers\RdpAccess;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->is_karyawan && !RdpAccess::isRdpEligibleEmployee($user)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('auth.formLogin')
                ->with('message', 'Akses login karyawan tidak diizinkan');
        }

        return $next($request);
    }
}
