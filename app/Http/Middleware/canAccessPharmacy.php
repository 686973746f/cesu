<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class canAccessPharmacy
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
        if(!($request->user()->canAccessPharmacy())) {
            return abort(401);
        }

        if(is_null($request->user()->pharmacy_branch_id)) {
            return abort('User was not linked to a Pharmacy Branch ID yet. Please contact the system administrator.');
        }
        
        return $next($request);
    }
}
