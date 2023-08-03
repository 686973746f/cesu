<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forms;
use Carbon\CarbonPeriod;
use App\Models\SelfReports;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
use Illuminate\Support\Facades\Session;

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
        /*
        if(auth()->user()->isLevel1()) {
            if(auth()->user()->canaccess_covid == 1) {
                Session::put('default_menu', 'COVID');
                Session::put('default_home_url', route('main'));

                $paswabctr = PaSwabDetails::where('status', 'pending')->count();
                $selfreport_count = SelfReports::where('status', 'pending')->count();
                
                return view('home', [
                    'paswabctr' => $paswabctr,
                    'selfreport_count' => $selfreport_count,
                ]);
            }
            else if(auth()->user()->canaccess_abtc == 1) {
                return redirect()->route('abtc_home');
            }
        }
        else if(auth()->user()->isLevel2()) {

        }
        else if(auth()->user()->isLevel3()) {
            return redirect()->route('facility.home');
        }
        */

        return view('main_menu');
    }

    public function covid_home() {
        Session::put('default_menu', 'COVID');
        Session::put('default_home_url', route('main'));

        $paswabctr = PaSwabDetails::where('status', 'pending')->count();
        $selfreport_count = SelfReports::where('status', 'pending')->count();
        
        return view('home', [
            'paswabctr' => $paswabctr,
            'selfreport_count' => $selfreport_count,
        ]);
    }

    public function pendingSchedChecker() {
        $arr = [];

        $getLastDayOfMonth = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))
        ->endOfMonth()
        ->format('Y-m-d');

        $period = CarbonPeriod::create(date('Y-m-d'), $getLastDayOfMonth);
        $total = 0;

        $paswabctr = PaSwabDetails::where('status', 'pending')->count();

        

        foreach($period as $date) {
            $num = Forms::whereDate('testDateCollected1', $date->format('Y-m-d'))
            ->orWhereDate('testDateCollected2', $date->format('Y-m-d'))->count();

            array_push($arr, [
                'date' => $date->format('Y-m-d'),
                'count' => $num,
            ]);

            $total += $num;
        }

        return view('pendingschedchecker', [
            'arr' => $arr,
            'total' => $total,
            'paswabctr' => $paswabctr,
        ]);
    }
}
