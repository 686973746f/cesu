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
        $forms = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->get();

        $dateToday = Carbon::parse(date('Y-m-d'));

        foreach($forms as $item) {
            if($item->dispoType != 6) {
                if(!is_null($item->testDateCollected2)) {
                    $swabDateCollected = $item->testDateCollected2;
                }
                else {
                    $swabDateCollected = $item->testDateCollected1;
                }

                if($item->dispoType == 1 || $item->healthStatus == 'Severe' || $item->healthStatus == 'Critical') {
                    $daysToRecover = 21;
                }
                else {
                    $daysToRecover = 10;
                }
    
                /*

                OLD FORMAT (IBANG DATE PAG SA CLOSE CONTACT)

                if($item->pType == 'PROBABLE' || $item->pType == 'TESTING') {
                    $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected)));
                    $recoverDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected.' + '.($daysToRecover-1).' Day')));
                }
                else if($item->pType == 'CLOSE CONTACT') {
                    if(!is_null($item->expoitem1)) {
                        $startDate = Carbon::parse(date('Y-m-d', strtotime($item->expoitem1)));
                        $recoverDate = Carbon::parse(date('Y-m-d', strtotime($item->expoitem1.' + '.($daysToRecover-1).' Day')));
                    }
                    else {
                        $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected)));
                        $recoverDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected.' + '.($daysToRecover-1).' Day')));
                    }
                }
                */

                $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected)));
                //$recoverDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected.' + '.($daysToRecover - 1).' Day'))); //MINUS ONE BECAUSE START DATE IS CONSIRED AS DAY 1
                
                $diff = $startDate->diffInDays($dateToday);
                if($diff >= ($daysToRecover - 1)) { //MINUS ONE BECAUSE START DATE IS CONSIRED AS DAY 1
                    $update = Forms::find($item->id);
    
                    $update->outcomeCondition = 'Recovered';
                    $update->outcomeRecovDate = date('Y-m-d');
    
                    $update->save();
                }
            }
        }
    }
}
