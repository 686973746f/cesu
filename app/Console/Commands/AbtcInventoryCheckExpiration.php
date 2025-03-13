<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\AbtcInventoryStock;
use App\Models\AbtcInventoryTransaction;

class AbtcInventoryCheckExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'abtcinventory_checkexpiration';

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
        $date = Carbon::now();
        $yesterday = $date->copy()->subDay(1);

        $list_expired = AbtcInventoryStock::whereDate('expiration_date', $yesterday->format('Y-m-d'))->get();

        foreach($list_expired as $d) {
            $before_qty = $d->current_qty;
            $process_qty = $d->current_qty;
            $after_qty = 0;

            $d->current_qty = 0;
            
            $c = AbtcInventoryTransaction::create([
                'transaction_date' => $yesterday->format('Y-m-d'),
                'stock_id' => $d->id,
                'type' => 'EXPIRED',
                //'transferto_facility',
                'process_qty' => $process_qty,
                'before_qty' => $before_qty,
                'after_qty' => $after_qty,
                //'po_number',
                //'unit_price',
                //'unit_price_amount',
                'remarks' => 'EXPIRED ON ('.$yesterday->format('M d, Y').')',
                'created_by' => 42, //CESU BOT
            ]);
        }
    }
}
