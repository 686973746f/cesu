<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SbdsCaseEmailNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sbds:caseemailnotifier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'School Based Disease Surveillance System Case Email Notifier';

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
