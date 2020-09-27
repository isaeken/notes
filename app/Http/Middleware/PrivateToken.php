<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check())
        {
            if (count(Auth::user()->tokens) < 1)
            {
                Auth::user()->createToken('private-token', [ 'create', 'read', 'update', 'delete' ]);
            }
        }
        return $next($request);
    }
}
