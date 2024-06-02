<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\WorkTask;
use App\Models\TaskGenerator;
use Illuminate\Console\Command;

class TaskGeneratorCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taskgenerator_creator';

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
        $now = Carbon::now();
        
        //Generate Tickets
        
        //Get Daily Task, check if not Saturday or Sunday first
        if($now->dayOfWeek != Carbon::SATURDAY && $now->dayOfWeek != Carbon::SUNDAY) {
            $fetch_daily = TaskGenerator::where('generate_every', 'DAILY')->get();

            foreach($fetch_daily as $d) {
                $check_daily = WorkTask::whereDate('created_at', date('Y-m-d'))
                ->first();

                if(!$check_daily) {

                    if($d->has_duration == 'Y') {
                        if($d->duration_type == 'DAILY') {
                            if(!is_null($d->duration_daily_whattime)) {
                                $until = $now->format('Y-m-d '.$d->duration_daily_whattime);
                            }
                            else {
                                $until = $now->addDay(1)->format('Y-m-d 00:00:00');
                            }
                        }
                        else if($d->duration_type == 'WEEKLY') {
                            $until = $now->addWeek($d->duration_weekly_howmanydays)->format('Y-m-d 00:00:00');
                        }
                        else if($d->duration_type == 'MONTHLY') {
                            $until = $now->addMonth($d->duration_monthly_howmanymonth)->format('Y-m-d 00:00:00');
                        }
                        else if($d->duration_type == 'YEARLY') {
                            $until = $now->addYear($d->duration_yearly_howmanyyear)->format('Y-m-d 00:00:00');
                        }
                    }
                    
                    WorkTask::create([
                        'name' => $d->name,
                        'description' => $d->description,
                        'has_duration' => $d->has_duration,
                        'until' => ($d->has_duration == 'Y') ? $until : NULL,
                        'encodedcount_enable' => $d->encodedcount_enable,
                        'has_tosendimageproof' => $d->has_tosendimageproof,
                    ]);
                }
            }
        }
    }
}
