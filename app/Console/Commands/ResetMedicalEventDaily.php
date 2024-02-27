<?php

namespace App\Console\Commands;

use App\Models\MedicalEvent;
use App\Models\User;
use Illuminate\Console\Command;

class ResetMedicalEventDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resetmedicalevent:daily';

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
        //Reset Users
        $get_users = User::whereNotNull('itr_medicalevent_id')->update([
            'itr_medicalevent_id' => NULL,
        ]);

        //Finish One Day and Deadline of Medical Events
        $get_me = MedicalEvent::where('oneDayEvent', 'Y')
        ->orWhereDate('date_end', date('Y-m-d', strtotime('-1 Day')))
        ->update([
            'status' => 'FINISHED',
        ]);
    }
}
