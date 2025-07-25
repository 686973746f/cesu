<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class canAccessPharmacyMasterAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!($request->user()->isPharmacyMasterAdmin())) {
            return abort(401);
        }

        return $next($request);
    }
}
