<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('verified');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currentWeek = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->format('W');

        if(auth()->user()->isAdmin == 4) {
            //Facility Account
            $list = Forms::where('dispoType', 6)
            ->where('status', 'approved')
            ->where('caseClassification', 'Confirmed')
            ->where('outcomeCondition', 'Active')
            ->get();
            
            $list = $list->sortBy('records.lname');

            return view('home_facility', ['currentWeek' => $currentWeek, 'list' => $list]);
        }
        else {
            $paswabctr = PaSwabDetails::where('status', 'pending')->count();
            return view('home', ['currentWeek' => $currentWeek, 'paswabctr' => $paswabctr]);
        }
    }
}
