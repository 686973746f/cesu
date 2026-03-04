<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Forms;
use App\Models\InhouseMaternalCare;
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
        $list = InhouseMaternalCare::whereNotNull('outcome')->get();

        foreach ($list as $item) {
            $delivery_date = Carbon::parse($item->delivery_date);

            $item->pnc1_est = $delivery_date->copy()->format('Y-m-d');
            $item->pnc2_est = $delivery_date->copy()->addDays(3)->format('Y-m-d');
            $item->pnc3_est = $delivery_date->copy()->addDays(7)->format('Y-m-d');
            $item->pnc4_est = $delivery_date->copy()->addWeeks(6)->format('Y-m-d');

            $item->save();
        }
    }
}
