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
        if(Session::has('brgyName') && Session::has('session_code')) {
           /*
                Remove then add anti-flood login code later...
                $brgy = session('brgyName');
                $scode = session('session_code');

                $session_check = Brgy::where('brgyName', $brgy)->where('edcs_session_code', $scode)->first();

                if($session_check) {
                    $session_check->edcs_lastlogin_date = date('Y-m-d H:i:s');
                    
                    if($session_check->isDirty()) {
                        $session_check->save();
                    }
                    
                    
                }
                else {
                    return redirect()->route('edcs_barangay_welcome')
                    ->with('msg', 'Please login to continue.')
                    ->with('msgtype', 'warning');
                }
           */

            return $next($request);
        }
        else {
            return abort(401);
        }
    }
}
