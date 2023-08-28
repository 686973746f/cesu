<?php

namespace App\Console\Commands;

use App\Models\PharmacyStockCard;
use App\Models\PharmacyStockLog;
use App\Models\PharmacySupply;
use App\Models\PharmacySupplyStock;
use Illuminate\Console\Command;

class PharmacyLogWeekly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pharmacylog:weekly';

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
        //list all supplies
        $list = PharmacySupply::get();

        foreach($list as $l) {
            $get_cd = PharmacyStockCard::where('supply_id', $l->id)
            ->whereBetween('created_at', [date('Y-m-d', strtotime('-6 Days')), date('Y-m-d')])
            ->get();

            $issued_total = 0;
            $received_total = 0;

            foreach($get_cd as $cd) {
                if($cd->type == 'ISSUED') {
                    $issued_total += $cd->qty_to_process;
                }
                else {
                    $received_total += $cd->qty_to_process;
                }
            }
            
            $c = PharmacyStockLog::create([
                'supply_id' => $l->id,
                'type' => 'WEEKLY',
                'get_stock' => $l->master_box_stock,
                'stock_credit' => $l->received_total,
                'stock_debit' => $l->issued_total,
            ]);
        }
    }
}
