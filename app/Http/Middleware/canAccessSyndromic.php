<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class canAccessSyndromic
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
        if(is_null($request->user()->itr_facility_id)) {
            return redirect()->route('home')
            ->with('msg', 'Error: Your account is not yet linked to a Facility ID. Please contact CESU Staff.')
            ->with('msgtype', 'warning');
        }

        if(!($request->user()->canAccessSyndromic())) {
            if(!($request->user()->isTbdotsEncoder())) {
                return abort(401);
            }
        }

        return $next($request);
    }
}
