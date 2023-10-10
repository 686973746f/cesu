<?php

namespace App\Console\Commands;

use App\Models\PharmacyStockCard;
use Illuminate\Console\Command;

class PharmaSentBranchFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pharmasentbranchfix';

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
        $list = PharmacyStockCard::get();

        foreach($list as $l) {
            $d = PharmacyStockCard::findOrfail($l->id);

            if(is_null($d->sentby_branch_id)) {
                if(!is_null($d->receiving_branch_id) || !is_null($d->receiving_patient_id) || !is_null($d->recipient)) {
                    $d->sentby_branch_id = $d->user->pharmacy_branch_id;
    
                    if($d->isDirty()) {
                        $d->save();
                    }
                }
            }
        }
    }
}
