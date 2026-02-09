<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InhouseChildCare;
use App\Models\InhouseChildNutrition;
use App\Models\InhouseMaternalCare;
use App\Models\InhouseFamilyPlanning;

class UpdateBdateFixed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:bdate-fixed';

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
        $list = InhouseMaternalCare::whereNull('bdate_fixed')->get();

        foreach($list as $item) {
            $item->bdate_fixed = $item->patient->bdate;
            $item->save();
        }

        $list = InhouseChildCare::whereNull('bdate_fixed')->get();

        foreach($list as $item) {
            $item->bdate_fixed = $item->patient->bdate;
            $item->save();
        }

        $list = InhouseFamilyPlanning::whereNull('bdate_fixed')->get();

        foreach($list as $item) {
            $item->bdate_fixed = $item->patient->bdate;
            $item->save();
        }

        $list = InhouseChildNutrition::whereNull('bdate_fixed')->get();
        
        foreach($list as $item) {
            $item->bdate_fixed = $item->patient->bdate;
            $item->save();
        }

        return 0;
    }
}
