<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class isAccountEnabledMiddleware
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
        if($request->user()->enabled != 1) {
            Auth::logout();
            
            return redirect()
            ->route('main')
            ->with('msg', 'Error: Your account was disabled by admin. Please contact CESU Staff if you think this was a mistake.')
            ->with('msgtype', 'warning');
        }

        return $next($request);
    }
}
