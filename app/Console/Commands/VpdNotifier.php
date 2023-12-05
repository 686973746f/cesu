<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VpdNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vpdnotifier:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vaccine Preventable Diseases Notifier';

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
        DIPTH
        MEASLES
        AFP
        PERT
        */

        $diph_array = [];
        $measles_array = [];
        $afp_array = [];
        $pert_array = [];
        
        
    }
}
