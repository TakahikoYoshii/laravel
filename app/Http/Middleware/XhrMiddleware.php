<?php

namespace App\Http\Middleware;

use Closure;

class XhrMiddleware
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
        // ajax() is  \Request::isXmlHttpRequest() wrapper method
        if(!\Request::ajax()){
            abort(400);
        }
        return $next($request);
    }
}
