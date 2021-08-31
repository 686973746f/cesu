<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Console\Command;

class AutoRecoveredActiveCases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autorecoveredactivecases:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduler to make active confirmed cases recovered after 14 days of swab';

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
        $forms = Forms::where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')->get();

        $dateToday = Carbon::parse(date('Y-m-d'));

        foreach($forms as $item) {
            if(!is_null($item->testDateCollected2)) {
                $swabDateCollected = $item->testDateCollected2;
            }
            else {
                $swabDateCollected = $item->testDateCollected1;
            }

            if($item->pType == 'PROBABLE' || $item->pType == 'TESTING') {
                $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected.' + 1 Day')));
            }
            else if($item->pType == 'CLOSE CONTACT') {
                if(!is_null($item->expoitem1)) {
                    $startDate = Carbon::parse(date('Y-m-d', strtotime($item->expoitem1)));
                }
                else {
                    $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected.' + 1 Day')));
                }
            }
            
            if($item->dispoType == 1 || $item->healthStatus == 'Severe' || $item->healthStatus == 'Critical') {
                $daysToRecover = 21;
            }
            else {
                $daysToRecover = 14;
            }

            $diff = $startDate->diffInDays($dateToday);
            if($diff >= $daysToRecover) {
                $update = Forms::find($item->id);

                $update->outcomeCondition = 'Recovered';
                $update->outcomeRecovDate = date('Y-m-d');

                $update->save();
            }
        }
    }
}
