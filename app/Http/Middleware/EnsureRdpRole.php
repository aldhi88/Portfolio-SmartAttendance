<?php

namespace App\Http\Middleware;

use App\Helpers\RdpAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRdpRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_if(!RdpAccess::matchesAnyRole($roles, $request->user()), 403);

        return $next($request);
    }
}
