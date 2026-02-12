<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HasLatestPassword
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
        if(auth()->user()->is_firsttimelogin == 1) {
            return redirect()->route('first_changepw_view')
            ->with('msg', 'This is your first time logging in. Please change your password to continue.')
            ->with('msgtype', 'info');
        }

        //Check if lastpasswordchange_date is 3 months ago
        if(Carbon::parse(auth()->user()->lastpasswordchange_date)->addMonths(3)->isPast()) {

        }

        return $next($request);
    }
}
