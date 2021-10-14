<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isLevel3
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
        //For Facility Middleware
        if($request->user()->isAdmin == 4) {
            return $next($request);
        }
        else {
            return abort(401);
        }
    }
}
