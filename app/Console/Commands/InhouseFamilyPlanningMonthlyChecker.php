<?php

namespace App\Console\Commands;

use App\Http\Controllers\ElectronicTclController;
use App\Models\InhouseFpVisit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class InhouseFamilyPlanningMonthlyChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inhousefp:monthly-checker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For automation of permanent method (BTL, NSV) and their age.';

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
        $last_month = Carbon::now()->subMonth();

        $list = InhouseFpVisit::whereIn('method_used', ['BTL', 'NSV'])
        ->where('is_permanent', 'Y')
        ->whereYear('visit_date_actual', $last_month->year)
        ->whereMonth('visit_date_actual', $last_month->month)
        ->get();

        foreach($list as $item) {
            ElectronicTclController::makeNextVisit($item->id);
        }

        return 0;
    }
}
