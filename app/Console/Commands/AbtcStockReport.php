<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\AbtcVaccineLogs;
use Illuminate\Console\Command;
use App\Models\AbtcVaccineBrand;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccineStocks;
use App\Mail\AbtcStockReportEmail;
use Illuminate\Support\Facades\DB;
use App\Models\AbtcVaccinationSite;
use Illuminate\Support\Facades\Mail;

class AbtcStockReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abtcstockreport:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        DB::setDefaultConnection('cesureport1');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $arr = [];
        
        $branches = AbtcVaccinationSite::where('enabled', 1)->get();
        //$vaccines = AbtcVaccineBrand::where('enabled', 1)->get();

        $today = Carbon::today();
        //$firstDayOfMonth = Carbon::parse(date('Y-m-01'));

        $proceed_sending = false;
        
        foreach($branches as $b) {
            $second_array = [];
            $vaccines = AbtcVaccineStocks::where('branch_id', $b->id)
            ->where('initial_stock', '!=', 0)
            ->get();

            foreach($vaccines as $v) {
                $third_array = [];
                $stock_remain = $v->initial_stock;

                if(Carbon::parse($v->initial_date)->month != Carbon::today()->month) {
                    $firstDayOfMonth = Carbon::parse(date('Y-m-01'));
                }
                else {
                    $firstDayOfMonth = Carbon::parse($v->initial_date);
                }
                
                for ($sdate = $firstDayOfMonth; $sdate->lte($today); $sdate->addDay()) {
                    $plist = AbtcBakunaRecords::where(function ($r) use ($sdate, $v) {
                        $r->where(function ($q) use ($sdate, $v) {
                            $q->whereDate('d0_date', $sdate->format('Y-m-d'))
                            ->where('d0_done', 1)
                            ->where('d0_brand', $v->vaccine->brand_name)
                            ->where('d0_vaccinated_inbranch', 1);
                        })->orwhere(function ($q) use ($sdate, $v) {
                            $q->whereDate('d3_date', $sdate->format('Y-m-d'))
                            ->where('d3_done', 1)
                            ->where('d3_brand', $v->vaccine->brand_name)
                            ->where('d3_vaccinated_inbranch', 1);
                        })->orWhere(function ($q) use ($sdate, $v) {
                            $q->whereDate('d7_date', $sdate->format('Y-m-d'))
                            ->where('d7_done', 1)
                            ->where('d7_brand', $v->vaccine->brand_name)
                            ->where('d7_vaccinated_inbranch', 1);
                        })->orWhere(function ($q) use ($sdate, $v) {
                            $q->whereDate('d14_date', $sdate->format('Y-m-d'))
                            ->where('d14_done', 1)
                            ->where('pep_route', 'IM')
                            ->where('d14_brand', $v->vaccine->brand_name)
                            ->where('d14_vaccinated_inbranch', 1);
                        })->orWhere(function ($q) use ($sdate, $v) {
                            $q->whereDate('d28_date', $sdate->format('Y-m-d'))
                            ->where('d28_done', 1)
                            ->where('d28_brand', $v->vaccine->brand_name)
                            ->where('d28_vaccinated_inbranch', 1);
                        });
                    })
                    ->where('vaccination_site_id', $v->branch_id)
                    ->count();

                    //$stock_remain -= $plist;

                    $wastage_search = AbtcVaccineLogs::whereDate('created_at', $sdate->format('Y-m-d'))
                    ->where('vaccine_id', $v->id)
                    ->where('branch_id', $b->id)
                    ->first();
                    
                    if($wastage_search) {
                        $prev_log = AbtcVaccineLogs::where('id', '<', $wastage_search->id)
                        ->latest()
                        ->first();

                        $wastage_search->stocks_remaining = ($prev_log->stocks_remaining - $plist);

                        if($wastage_search->isDirty()) {
                            $wastage_search->save();
                        }
                    }
                    else {
                        $prev_log = AbtcVaccineLogs::where('vaccine_id', $v->id)
                        ->where('branch_id', $b->id)
                        ->latest()
                        ->first();

                        $wastage_search = AbtcVaccineLogs::create([
                            'vaccine_id' => $v->id,
                            'branch_id' => $b->id,
                            'wastage_dose_count' => 0,
                            'stocks_remaining' => ($prev_log->stocks_remaining - $plist),
                        ]);
                    }

                    if($plist != 0) {
                        if(!$proceed_sending) {
                            $proceed_sending = true;
                        }

                        $third_array[] = [
                            'date' => $sdate->format('m/d/Y'),
                            'count' => $plist,
                            'used_vials' => ($plist / $v->vaccine->est_maxdose_perbottle),
                            'wastage_count' => $wastage_search->wastage_dose_count,
                            'stock_remaining' => $wastage_search->stocks_remaining,
                        ];
                    }
                }

                $second_array[] = [
                    'brand' => $v->vaccine->brand_name,
                    'third' => $third_array,
                ];
            }

            $arr[] = [
                'branch' => $b->site_name,
                'second' => $second_array,
            ];
        }

        //return view('email.abtcstockreportview', ['arr' => $arr]);
        
        if($proceed_sending) {
            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new AbtcStockReportEmail($arr));
        }
    }
}
