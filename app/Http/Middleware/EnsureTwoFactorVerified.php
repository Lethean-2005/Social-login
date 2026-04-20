<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->session()->get('two_factor.verified')) {
            return redirect()->route('two-factor.show');
        }

        return $next($request);
    }
}
