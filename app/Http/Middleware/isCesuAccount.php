<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isCesuAccount
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
        if(!is_null($request->user()->brgy_id) || !is_null($request->user()->company_id)) {
            return abort(401);
        }
        else {
            return $next($request);
        }
    }
}
