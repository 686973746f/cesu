<?php

namespace App\Console\Commands;

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

        foreach($branches as $b) {
            $sdate = date('Y-m-d');

            $plist = AbtcBakunaRecords::where(function ($r) use ($sdate) {
                $r->where(function ($q) use ($sdate) {
                    $q->whereDate('d0_date', $sdate)
                    ->where('d0_done', 1);
                })->orwhere(function ($q) use ($sdate) {
                    $q->whereDate('d3_date', $sdate)
                    ->where('d3_done', 1);
                })->orWhere(function ($q) use ($sdate) {
                    $q->whereDate('d7_date', $sdate)
                    ->where('d7_done', 1);
                })->orWhere(function ($q) use ($sdate) {
                    $q->whereDate('d14_date', $sdate)
                    ->where('d14_done', 1)
                    ->where('pep_route', 'IM');
                })->orWhere(function ($q) use ($sdate) {
                    $q->whereDate('d28_date', $sdate)
                    ->where('d28_done', 1);
                });
            })->where('vaccination_site_id', $b->id)
            ->get();

            if($plist->count() != 0) {
                $second_array = [];
                $temp_array = [];
                
                foreach($plist as $d) {
                    if($d->getTodayDose() == 1) {
                        if($d->d0_vaccinated_inbranch == 1) {
                            $brand = $d->d0_brand;
                            $proceed = true;
                        }
                        else {
                            $proceed = false;
                        }
                    }
                    else if($d->getTodayDose() == 2) {
                        if($d->d3_vaccinated_inbranch == 1) {
                            $brand = $d->d3_brand;
                            $proceed = true;
                        }
                        else {
                            $proceed = false;
                        }
                    }
                    else if($d->getTodayDose() == 3) {
                        if($d->d7_vaccinated_inbranch == 1) {
                            $brand = $d->d7_brand;
                            $proceed = true;
                        }
                        else {
                            $proceed = false;
                        }
                    }
                    else if($d->getTodayDose() == 4) {
                        if($d->d14_vaccinated_inbranch == 1) {
                            $brand = $d->d14_brand;
                            $proceed = true;
                        }
                        else {
                            $proceed = false;
                        }
                    }
                    else if($d->getTodayDose() == 5) {
                        if($d->d28_vaccinated_inbranch == 1) {
                            $brand = $d->d28_brand;
                            $proceed = true;
                        }
                        else {
                            $proceed = false;
                        }
                    }

                    if($proceed) {
                        
                        // Check if the brand already exists in the temp_array
                        if (isset($temp_array[$brand])) {
                            // If it exists, increment the count
                            $temp_array[$brand]['count']++;
                        } else {
                            // If it doesn't exist, add it to the temp_array
                            $temp_array[$brand] = [
                                'count' => 1,
                            ];
                        }
                    }
                }

                foreach($temp_array as $ind => $t) {
                    $get_vbrand = AbtcVaccineBrand::where('brand_name', $ind)->first();
                    $get_vstock = AbtcVaccineStocks::where('vaccine_id', $get_vbrand->id)
                    ->where('branch_id', $b->id)
                    ->first();

                    $temp_array[$ind]['brand_id'] = $get_vbrand->id;
                    $temp_array[$ind]['bottle_used'] = ceil($t['count'] / $get_vbrand->est_maxdose_perbottle);
                    $temp_array[$ind]['remaining'] = $get_vstock->current_stock;

                    /*
                    $temp_array[$ind] = [
                        'brand_id' => $get_vbrand->id,
                        'bottle_used' => floor($t['count'] / $get_vbrand->est_maxdose_perbottle),
                        'remaining' => $get_vstock->current_stock,
                    ];
                    */
                }

                $arr[] = [
                    'branch' => $b->site_name,
                    'master_count' => $temp_array,
                ];
            }
        }

        if(!empty($arr)) {
            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new AbtcStockReportEmail($arr));
        }
    }
}
