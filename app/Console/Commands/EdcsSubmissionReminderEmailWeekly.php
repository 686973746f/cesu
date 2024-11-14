<?php

namespace App\Console\Commands;

use App\Models\DohFacility;
use Illuminate\Console\Command;

class EdcsSubmissionReminderEmailWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edcs_submission_weekly_reminder';

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
        $email_arr = [];

        $list = DohFacility::where('is_weeklyreport_submitter', 'Y')->get();

        foreach($list as $l) {
            
        }
    }
}
