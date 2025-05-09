<?php

namespace App\Console\Commands;

use App\Models\DengueClusteringSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FoggingScheduleMover extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'foggingschedulemover:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check yesterday fogging schedule and move to the next cycle automatically';

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
        $yesterday = Carbon::now()->subDay(1)->format('Y-m-d');

        $list = DengueClusteringSchedule::where('enabled', 1)
        ->where('is_completed', 0)
        ->whereNotNull('assigned_team')
        ->where(function ($q) use ($yesterday) {
            $q->whereDate('cycle1_date', $yesterday)
            ->orWhereDate('cycle2_date', $yesterday)
            ->orWhereDate('cycle3_date', $yesterday)
            ->orWhereDate('cycle4_date', $yesterday);
        })
        ->get();

        if($list->count() != 0) {
            foreach($list as $d) {
                $currentStatus = $d->status;

                if($currentStatus == 'PENDING') {
                    $d->status = 'CYCLE1';
                }
                else if($currentStatus == 'CYCLE1') {
                    $d->status = 'CYCLE2';
                }
                else if($currentStatus == 'CYCLE2') {
                    $d->status = 'CYCLE3';
                }
                else if($currentStatus == 'CYCLE3') {
                    $d->status = 'CYCLE4';
                }
                else if($currentStatus == 'CYCLE4') {
                    $d->is_completed = 1;
                }

                if($d->isDirty()) {
                    $d->save();
                }
            }
        }
    }
}
