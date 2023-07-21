<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Provinces;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;

class altnameinit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'altnameinit';

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
        $batchSize = 1000; //for batching
        $data = [];

        $collection = (new FastExcel)->import(storage_path('altprov.xlsx'), function ($row) use (&$data, $batchSize) {
            return Provinces::where('json_code', substr($row['code'],0,4))->update([
                'alt_name' => $row['desc'],
            ]);
        });

        $collection2 = (new FastExcel)->import(storage_path('altcity.xlsx'), function ($row) use (&$data, $batchSize) {
            return City::where('json_code', substr($row['code'],0,6))->update([
                'alt_name' => $row['desc'],
            ]);
        });
    }
}
