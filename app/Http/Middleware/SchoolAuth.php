<?php

namespace App\Http\Middleware;

use App\Models\SchoolGradeLevel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolAuth
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
        if (!Auth::guard('school')->check()) {
            return abort(401);
        }

        $s = auth('school')->user();

        //Check School Level and Section, must not be empty
        $check = SchoolGradeLevel::where('school_id', $s->id)->first();

        if(!$check) {
            return redirect()->route('sbs_viewlevel')
            ->with('msg', 'WARNING: To proceed on using the system, you must input every Grade Levels and Sections in your respective facility.')
            ->with('msgtype', 'warning');
        }

        return $next($request);
    }
}
