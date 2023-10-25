<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SyndromicRecords;
use App\Mail\SyndromicEmailSender;
use Illuminate\Support\Facades\Mail;

class SyndromicHourlyCaseNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syndromicchecker:daily';

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
        $list = SyndromicRecords::whereDate('created_at', date('Y-m-d'))
        ->where('email_notified', 0);

        if($list->get()->count() != 0) {
            $get_list = $list->get();

            Mail::to(['hihihisto@gmail.com', 'cesu.gentrias@gmail.com'])->send(new SyndromicEmailSender($get_list));
        }

        $update = $list->update([
            'email_notified' => 1,
        ]);
    }
}
