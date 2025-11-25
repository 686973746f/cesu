<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Mail\SendEncoderStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class AutoEncoderStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autosendencoderstats:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Send Encoder Stats';

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
        $today = Carbon::now();

        if($today->dayOfWeek != Carbon::SATURDAY && $today->dayOfWeek != Carbon::SUNDAY) {
            Mail::to(['cjh687332@gmail.com', 'cesu.gentrias@gmail.com'])->send(new SendEncoderStatus());
        }
    }
}
