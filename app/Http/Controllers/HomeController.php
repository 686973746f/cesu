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
        if(auth()->user()->isLevel1()) {
            $currentWeek = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->format('W');

            $paswabctr = PaSwabDetails::where('status', 'pending')->count();
            return view('home', ['currentWeek' => $currentWeek, 'paswabctr' => $paswabctr]);
        }
        else if(auth()->user()->isLevel2()) {

        }
        else if(auth()->user()->isLevel3()) {
            return redirect()->route('facility.home');
        }
    }
}
