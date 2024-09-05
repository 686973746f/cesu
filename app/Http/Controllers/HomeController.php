<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Forms;
use Carbon\CarbonPeriod;
use App\Models\ExportJobs;
use App\Models\SelfReports;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
use App\Models\VaxcertConcern;
use Illuminate\Support\Facades\Auth;
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
        //Excel::import(new EdcsImport(), storage_path('app/edcs/TEST.xlsx'));
        
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

        if(auth()->user()->isMayor()) {
            return redirect()->route('mayor_main_menu');
        }

        if(date('Y-m-d') == date('Y-m-d', strtotime(auth()->user()->last_login_date))) {
            $showmodal = false;
        }
        else {
            $showmodal = true;

            //update last_login_date
            $u = User::find(auth()->user()->id);

            $u->last_login_date = Carbon::now()->format('Y-m-d H:i:s');
            $u->save();
        }

        $vaxcert_pending_count = VaxcertConcern::where('status', 'PENDING')->count();

        return view('main_menu', [
            'showmodal' => $showmodal,
            'vaxcert_pending_count' => $vaxcert_pending_count,
        ]);
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

    public function exportJobsIndex() {
        if(auth()->user()->isGlobalAdmin()) {
            $l = ExportJobs::orderBy('created_at', 'DESC')
            ->paginate(10);
        }
        else {
            $l = ExportJobs::where('created_by', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        }

        return view('exports.index', [
            'list' => $l,
        ]);
    }

    public function exportJobsDownloadFile($id) {
        $d = ExportJobs::findOrFail($id);

        return response()->download(storage_path('export_jobs/'.$d->filename));
    }
}
