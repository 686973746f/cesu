<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SendCompositeMeasure;
use Illuminate\Support\Facades\Mail;

class AutoEmailCompositeMeasure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoemailcompositemeasure:on15and30';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Composite Measure on Every 15 and 30 of the Month';

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
        Mail::to(['cjh687332@gmail.com', 'cesu.gentrias@gmail.com', 'cesugentri2022@yahoo.com'])->send(new SendCompositeMeasure());
    }
}
