<?php

namespace App\Console\Commands;

use App\Models\SyndromicRecords;
use Illuminate\Console\Command;

class SyndromicRebuildCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'syndromic_caserebuilder';

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
        $fetch = SyndromicRecords::whereNull('generated_susdiseaselist')->get();

        foreach($fetch as $d) {
            $d->generated_susdiseaselist = ($d->getListOfSuspDiseases() != 'N/A') ? $d->getListOfSuspDiseases() : NULL;

            if($d->isDirty()) {
                $d->save();
            }
        }
    }
}
