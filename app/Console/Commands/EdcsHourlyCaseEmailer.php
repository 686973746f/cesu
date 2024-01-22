<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class EdcsHourlyCaseEmailer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edcscaseemailer:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mostly used for Category 1 or Immediate Notifiable';

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

        $startDateTime = Carbon::now()->setTime(13, 0, 0);
        $endDateTime = Carbon::now()->setTime(23, 59, 59);

        if($now->dayOfWeek == Carbon::TUESDAY) {
            if ($now->between($startDateTime, $endDateTime)) {
                $proceed = true;
            }
            else {
                $proceed = false;
            }
        }
        else {
            $proceed = true;
        }
        
        if($proceed) {

        }
    }
}
