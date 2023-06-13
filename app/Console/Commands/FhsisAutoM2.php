<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FhsisAutoM2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fhsism2autosender:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'FHSIS Auto Sender Email';

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
        return 0;
    }
}
