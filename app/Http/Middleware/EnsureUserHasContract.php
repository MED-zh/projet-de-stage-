<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasContract
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in
        // 2. Check if they have a contract number
        if (Auth::check() && Auth::user()->contrat_num) {
            return $next($request);
        }

        // If they fail the check, kick them back to login with a message
        return redirect()->route('login')->with('error', 'Accès refusé. Contrat requis.');
    }
}