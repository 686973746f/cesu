<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\AbtcVaccineLogs;
use Illuminate\Console\Command;
use App\Models\AbtcBakunaRecords;
use App\Models\AbtcVaccineStocks;
use App\Mail\AbtcStockReportEmail;
use Illuminate\Support\Facades\DB;
use App\Models\AbtcVaccinationSite;
use App\Models\AbtcVaccineBrand;
use Illuminate\Support\Facades\Mail;

class AbtcStockReportOld extends Command
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
        $today = Carbon::today();
        $get_branches = AbtcVaccinationSite::where('enabled', 1)->get();
        
        if(!in_array($today->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) { //ALSO ADD HOLIDAY LATER
            //CREATE ROW FIRST
            foreach($get_branches as $branch) {
                $vaccines = AbtcVaccineStocks::where('branch_id', $branch->id)
                ->where('initial_stock', '!=', 0)
                ->get();

                foreach($vaccines as $v) {
                    $plist = AbtcBakunaRecords::where(function ($r) use ($today, $v) {
                        $r->where(function ($q) use ($today, $v) {
                            $q->whereDate('d0_date', $today->format('Y-m-d'))
                            ->where('d0_done', 1)
                            ->where('d0_brand', $v->vaccine->brand_name)
                            ->where('d0_vaccinated_inbranch', 1);
                        })->orwhere(function ($q) use ($today, $v) {
                            $q->whereDate('d3_date', $today->format('Y-m-d'))
                            ->where('d3_done', 1)
                            ->where('d3_brand', $v->vaccine->brand_name)
                            ->where('d3_vaccinated_inbranch', 1);
                        })->orWhere(function ($q) use ($today, $v) {
                            $q->whereDate('d7_date', $today->format('Y-m-d'))
                            ->where('d7_done', 1)
                            ->where('d7_brand', $v->vaccine->brand_name)
                            ->where('d7_vaccinated_inbranch', 1);
                        })->orWhere(function ($q) use ($today, $v) {
                            $q->whereDate('d14_date', $today->format('Y-m-d'))
                            ->where('d14_done', 1)
                            ->where('pep_route', 'IM')
                            ->where('d14_brand', $v->vaccine->brand_name)
                            ->where('d14_vaccinated_inbranch', 1);
                        })->orWhere(function ($q) use ($today, $v) {
                            $q->whereDate('d28_date', $today->format('Y-m-d'))
                            ->where('d28_done', 1)
                            ->where('d28_brand', $v->vaccine->brand_name)
                            ->where('d28_vaccinated_inbranch', 1);
                        });
                    })
                    ->where('vaccination_site_id', $branch->id)
                    ->count();

                    if($plist != 0) {
                        $patient_count_final = ($plist / $v->vaccine->est_maxdose_perbottle);
                    }
                    else {
                        $patient_count_final = 0;
                    }

                    //SEARCH IF WASTAGE HAS BEEN INITIATED
                    $cwastage = AbtcVaccineLogs::whereDate('created_at', $today->format('Y-m-d'))
                    ->where('vaccine_id', $v->vaccine->id)
                    ->where('branch_id', $branch->id)
                    ->first();

                    if($cwastage) {
                        //GET WASTAGE VALUE AND CREATE A NEW ONE
                        $get_wastage_count = $cwastage->wastage_dose_count;

                        $cwastage->delete();
                    }
                    else {
                        $get_wastage_count = 0;
                    }

                    //GET PREVIOUS LOG
                    $previous_log = AbtcVaccineLogs::where('vaccine_id', $v->vaccine->id)
                    ->where('branch_id', $branch->id)
                    ->latest()
                    ->first();

                    //CREATE NEW LOGS
                    $create_log = AbtcVaccineLogs::create([
                        'vaccine_id' => $v->vaccine->id,
                        'branch_id' => $branch->id,
                        'patients_count' => $plist,
                        'vials_used' => $patient_count_final,
                        'wastage_dose_count' => $get_wastage_count,
                        'stocks_remaining' => ($previous_log->stocks_remaining - $patient_count_final),
                    ]);
                }
            }

            $arr = [];

            //FETCH VALUES BASED ON CURRENT MONTH
            foreach($get_branches as $branch) {
                $second_array = [];

                $fetch_vaccines = AbtcVaccineBrand::where('enabled', 1)->get();

                foreach($fetch_vaccines as $v) {
                    $third_array = [];

                    $fetch_logs = AbtcVaccineLogs::whereBetween('created_at', [date('Y-m-01'), date('Y-m-d')])
                    ->where('branch_id', $branch->id)
                    ->where('vaccine_id', $v->id)
                    ->get();

                    if($fetch_logs->count() != 0) {
                        foreach($fetch_logs as $log) {
                            $third_array[] = [
                                'date' => date('m/d/Y - D', strotime($log->created_at)),
                                'count' => $log->patients_count,
                                'used_vials' => $log->vials_used,
                                'wastage_count' => $log->wastage_dose_count,
                                'stock_remaining' => $log->stocks_remaining,
                            ];
                        }
                    }

                    $second_array[] = [
                        'brand' => $v->vaccine->brand_name,
                        'third' => $third_array,
                    ];
                }

                $arr[] = [
                    'branch' => $branch->site_name,
                    'second' => $second_array,
                ];
            }
            
            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new AbtcStockReportEmail($arr));
        }
    }
}
