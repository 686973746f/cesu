<?php

namespace App\Console\Commands;

use App\Http\Controllers\ElectronicTclController;
use App\Models\InhouseFpVisit;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FamilyPlanningScheduleChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $currentDate = Carbon::now();
        
        $list = InhouseFpVisit::where('enabled', 'Y')
        ->where('status', 'PENDING')
        ->where('is_permanent', 'N')
        ->where('is_visible', 'Y')
        ->whereMonth('visit_date_estimated', now()->month)
        ->whereYear('visit_date_estimated', now()->year)
        ->get();

        foreach($list as $item) {
            $item->dropout_date = Carbon::now()->format('Y-m-d');
            $item->status = 'DROP-OUT';
            $item->dropout_reason = 'I';

            $item->familyplanning->is_locked = 'Y';
            $item->familyplanning->is_dropout = 'Y';
            $item->familyplanning->dropout_date = Carbon::now()->format('Y-m-d');
            $item->familyplanning->dropout_reason = 'I';
            
            $item->save();
            $item->familyplanning->save();
        }

        $list = InhouseFpVisit::where('enabled', 'Y')
        ->where('is_permanent', 'Y')
        ->where('is_visible', 'N')
        ->whereMonth('visit_date_estimated', now()->month)
        ->whereYear('visit_date_estimated', now()->year)
        ->get();

        foreach($list as $item) {
            ElectronicTclController::makeNextVisit($item->id);
        }

        return 0;
    }
}
