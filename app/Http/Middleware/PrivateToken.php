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
                Auth::user()->createToken('private-token', [
                    'note:create', 'note:read', 'note:update', 'note:delete',
                    'comment:create', 'comment:read', 'comment:update', 'comment:delete',
                    'user:create', 'user:read', 'user:update', 'user:delete',
                ]);
                return redirect()->route('web.home.index');
            }
        }
        return $next($request);
    }
}
