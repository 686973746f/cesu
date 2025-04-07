<?php

namespace App\Console\Commands;

use App\Http\Controllers\PIDSRController;
use App\Models\PidsrThreshold;
use Illuminate\Console\Command;

class PidsrYearlyThresholdGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pidsrgeneratethreshold:yearly';

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
        PIDSRController::callGenerateThreshold('ALL', date('Y', strtotime('-1 Year')));
    }
}
