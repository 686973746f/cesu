<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Forms;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test_command';

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
        $list = Forms::whereYear('morbidityMonth', 2024)->get();

        foreach($list as $d) {
            $cFromDate = Carbon::parse($d->morbidityMonth);

            $d->morb_week = $cFromDate->format('W');
            $d->morb_month = $cFromDate->format('n');
            $d->year = $cFromDate->format('Y');

            if($d->isDirty()) {
                $d->save();
            }
        }
    }
}
