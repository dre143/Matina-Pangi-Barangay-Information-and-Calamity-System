<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CalamityAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !(auth()->user()->isSuperAdmin() || auth()->user()->isCalamityHead())) {
            abort(403, 'Calamity Management access required.');
        }
        return $next($request);
    }
}