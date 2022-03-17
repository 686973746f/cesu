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
        /*
        $forms = Forms::with('records')
        ->whereHas('records', function ($q) {
            $q->where('records.address_province', 'CAVITE')
            ->where('records.address_city', 'GENERAL TRIAS');
        })
        ->where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->whereNotIn('dispoType', [6,7])
        ->get();
        */

        $forms = Forms::where('status', 'approved')
        ->where('outcomeCondition', 'Active')
        ->where('caseClassification', 'Confirmed')
        ->whereNotIn('dispoType', [6,7])
        ->get();

        $dateToday = Carbon::parse(date('Y-m-d'));

        foreach($forms as $item) {
            if($item->dispoType != 6 && $item->dispoType != 7) {
                if(!is_null($item->testDateCollected2)) {
                    $swabDateCollected = $item->testDateCollected2;
                }
                else {
                    $swabDateCollected = $item->testDateCollected1;
                }

                //Note: If may babaguhin dito, dapat palitan din yung sa FormsController Store and Update
                if($item->dispoType == 1 || $item->healthStatus == 'Severe' || $item->healthStatus == 'Critical') {
                    $daysToRecover = 21;
                }
                else {
                    if(!is_null($item->records->vaccinationDate2)) {
                        $date1 = Carbon::parse($item->records->vaccinationDate2);
                        $days_diff = $date1->diffInDays($dateToday);

                        if($days_diff >= 14) {
                            $daysToRecover = 7;
                        }
                        else {
                            $daysToRecover = 10;
                        }
                    }
                    else {
                        if($item->records->vaccinationName1 == 'JANSSEN') {
                            $date1 = Carbon::parse($item->records->vaccinationDate1);
                            $days_diff = $date1->diffInDays($dateToday);

                            if($days_diff >= 14) {
                                $daysToRecover = 7;
                            }
                            else {
                                $daysToRecover = 10;
                            }
                        }
                        else {
                            $daysToRecover = 10;
                        }
                    }
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
                if($diff >= $daysToRecover) { //MINUS ONE BECAUSE START DATE IS CONSIRED AS DAY 1
                    $update = Forms::find($item->id);
    
                    $update->outcomeCondition = 'Recovered';
                    //$update->outcomeRecovDate = date('Y-m-d');
                    $update->outcomeRecovDate = Carbon::parse($swabDateCollected)->addDays($daysToRecover)->format('Y-m-d');
    
                    if($update->isDirty()) {
                        $update->save();
                    }
                }
            }
        }
    }
}
