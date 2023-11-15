<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\AbtcVaccineLogs;
use Illuminate\Console\Command;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccineStocks;
use App\Models\AbtcVaccinationSite;

class AbtcReconsLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abtcreconslogs';

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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $branches = AbtcVaccinationSite::where('id', 2)->get();

        $today = Carbon::parse('2023-11-14');

        $firstDayOfMonth = Carbon::parse('2023-10-23');
        
        foreach($branches as $b) {
            $vaccines = AbtcVaccineStocks::where('branch_id', $b->id)
            ->where('initial_stock', '!=', 0)
            ->get();

            foreach($vaccines as $v) {
                for ($sdate = $firstDayOfMonth; $sdate->lte($today); $sdate->addDay()) {
                    if (!in_array($sdate->dayOfWeek, [Carbon::WEDNESDAY, Carbon::SATURDAY, Carbon::SUNDAY]) && $sdate->format('Y-m-d') !== '2023-10-31' && $sdate->format('Y-m-d') !== '2023-11-01' && $sdate->format('Y-m-d') !== '2023-11-02') {
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
                        ->where('vaccination_site_id', $b->id)
                        ->count();
    
                        if($plist != 0) {
                            $plist = ceil($plist / $v->vaccine->est_maxdose_perbottle);
    
                            $wastage_search = AbtcVaccineLogs::whereDate('created_at', $sdate->format('Y-m-d'))
                            ->where('vaccine_id', $v->vaccine->id)
                            ->where('branch_id', $b->id)
                            ->first();
    
                            if($wastage_search) {
                                $prev_log = AbtcVaccineLogs::where('id', '<', $wastage_search->id)
                                ->where('vaccine_id', $v->vaccine->id)
                                ->where('branch_id', $b->id)
                                ->latest()
                                ->first();
    
                                if($prev_log) {
                                    $set_stockremaining = $prev_log->stocks_remaining;
                                }
                                else {
                                    $vstock = AbtcVaccineStocks::where('vaccine_id', $v->vaccine->id)
                                    ->where('branch_id', $b->id)
                                    ->first();
    
                                    $set_stockremaining = $vstock->initial_stock;
                                }
    
                                //$set_stockremaining = ceil(($set_stockremaining - $plist) / $v->est_maxdose_perbottle);
    
                                $wastage_search->stocks_remaining = $set_stockremaining;
    
                                if($wastage_search->isDirty()) {
                                    $wastage_search->save();
                                }
                            }
                            else {
                                $prev_log = AbtcVaccineLogs::where('vaccine_id', $v->vaccine->id)
                                ->where('branch_id', $b->id)
                                ->latest()
                                ->first();
    
                                if($prev_log) {
                                    $set_stockremaining = $prev_log->stocks_remaining;
                                }
                                else {
                                    $vstock = AbtcVaccineStocks::where('vaccine_id', $v->vaccine->id)
                                    ->where('branch_id', $b->id)
                                    ->first();
    
                                    $set_stockremaining = $vstock->initial_stock;
                                }
    
                                //$set_stockremaining = ceil(($set_stockremaining - $plist) / $v->est_maxdose_perbottle);
    
                                $wastage_search = AbtcVaccineLogs::create([
                                    'vaccine_id' => $v->vaccine->id,
                                    'branch_id' => $b->id,
                                    'wastage_dose_count' => 0,
                                    'stocks_remaining' => ($set_stockremaining - $plist),
                                    'created_at' => $sdate->format('Y-m-d').' 16:00:00',
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
