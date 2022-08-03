<?php

namespace App\Console\Commands;

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
        Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new SendEncoderStatus());
    }
}
