<?php

namespace App\Console\Commands;

use App\Models\Brgy;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class EdcsMakeBrgyPw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edcs:brgypasswordgenerator';

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
        $brgy_list = Brgy::where('city_id', 1)
        ->where('displayInList', 1)
        ->get();

        foreach($brgy_list as $b) {
            $b->edcs_quicklogin_code = Str::random(10);
            $b->edcs_pw = mb_strtoupper(Str::random(5));
            if($b->isDirty()) {
                $b->save();
            }
        }
    }
}
