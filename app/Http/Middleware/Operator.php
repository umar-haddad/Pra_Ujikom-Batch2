<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Operator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //catch operator => 2
        if (Auth::check() && Auth::user()->id_level == 2) {
            return $next($request);
        }
        return redirect()->route('dashboard.index')->with('error', 'you do not access operator');
    }
}
