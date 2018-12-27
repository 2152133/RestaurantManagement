<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
        //dd(auth()->guard('api')->user()->type);
        if (auth()->guard('api')->user()->type == 'manager') {
            return $next($request);
        }
        return redirect('/items');
    }
}
