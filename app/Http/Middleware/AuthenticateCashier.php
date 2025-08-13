<?php

namespace App\Http\Middleware;

use Closure;

class AuthenticateCashier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->type == 11 || auth()->user()->type == 4 || auth()->user()->type == 15 || auth()->user()->type == 17)
        {
            return $next($request);
        }

        return redirect('/login');
    }
}
