<?php

namespace App\Console\Commands;

use App\Models\EdcsBrgy;
use App\Models\DohFacility;
use Illuminate\Console\Command;

class CallDohFacilityBrgyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:dohfacility:brgyupdate';

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
        $list = DohFacility::where('address_muncity', 'CITY OF GENERAL TRIAS')->get();

        foreach($list as $l) {
            $brgy = EdcsBrgy::where('city_id', 388)
                ->where(function ($q) use ($l) {
                    $q->where('name', $l->address_barangay)
                      ->orWhere('alt_name', 'LIKE', '%'.strtoupper($l->address_barangay).'%');
                })
                ->first();

            if($brgy) {
                $l->brgy_id = $brgy->id;
                $l->save();
            }
        }
    }
}
