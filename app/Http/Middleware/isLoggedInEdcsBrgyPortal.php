<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Brgy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class isLoggedInEdcsBrgyPortal
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
        if(Session::has('brgyName') && Session::has('edcs_pw')) {
            $brgy = session('brgyName');
            $pw = session('edcs_pw');

            if(Brgy::where('brgyName', $brgy)->where('edcs_pw', $pw)->exists()) {
                return $next($request);
            }
            else {
                return abort(401);
            }
            
        }
        else {
            return abort(401);
        }
    }
}
