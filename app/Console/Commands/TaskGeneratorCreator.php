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
            $fetch_daily = TaskGenerator::get();

            foreach($fetch_daily as $d) {
                if($d->generate_every == 'DAILY') {
                    $performCheckNow = true;
                }
                else if($d->generate_every == 'WEEKLY') {
                    if($d->weekly_whatday == 1 && $now->dayOfWeek == Carbon::MONDAY) {
                        $performCheckNow = true;
                    }
                    else if($d->weekly_whatday == 2 && $now->dayOfWeek == Carbon::TUESDAY) {
                        $performCheckNow = true;
                    }
                    else if($d->weekly_whatday == 3 && $now->dayOfWeek == Carbon::WEDNESDAY) {
                        $performCheckNow = true;
                    }
                    else if($d->weekly_whatday == 4 && $now->dayOfWeek == Carbon::THURSDAY) {
                        $performCheckNow = true;
                    }
                    else if($d->weekly_whatday == 5 && $now->dayOfWeek == Carbon::FRIDAY) {
                        $performCheckNow = true;
                    }
                    else if($d->weekly_whatday == 6 && $now->dayOfWeek == Carbon::SATURDAY) {
                        $performCheckNow = true;
                    }
                    else if($d->weekly_whatday == 7 && $now->dayOfWeek == Carbon::SUNDAY) {
                        $performCheckNow = true;
                    }
                    else {
                        $performCheckNow = false;
                    }
                }
                else if($d->generate_every == 'MONTHLY') {
                    
                }
                else if($d->generate_every == 'YEARLY') {

                }

                if($performCheckNow) {
                    $check_data = WorkTask::where('name', $d->name)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->first();
                }

                if(!$check_data) {
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
