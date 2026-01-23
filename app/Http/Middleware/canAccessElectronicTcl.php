<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class canAccessElectronicTcl
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
        if(!auth()->user()->etcl_bhs_id){
            abort(403, 'You don\'t have access to Electronic TCL module. You are not linked to a BHS facility.');
        }

        if(!(auth()->user()->canAccessElectronicTcl())){
            abort(403, 'You don\'t have access to Electronic TCL module. Please contact your administrator.');
        }
        
        return $next($request);
    }
}
