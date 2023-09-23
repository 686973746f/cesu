<?php

namespace App\Console\Commands;

use App\Models\PharmacySupplySub;
use App\Models\PharmacySupplySubStock;
use Illuminate\Console\Command;

class PharmacyCheckExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pharmacy:check_expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To check expiration and automatic reduce the base stock of sub_item';

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
        $expired_list = PharmacySupplySubStock::whereDate('expiration_date', date('Y-m-d'))->get();

        if($expired_list->count() != 0) {
            foreach($expired_list as $d) {
                $get_subsupply = PharmacySupplySub::findOrFail($d->subsupply_id);

                if($get_subsupply->pharmacysupplymaster->quantity_type == 'BOX') {
                    $get_subsupply->master_box_stock -= $d->current_box_stock;
                    $get_subsupply->master_piece_stock -= ($d->current_box_stock * $get_subsupply->pharmacysupplymaster->config_piecePerBox);
                }
                else {
                    $get_subsupply->master_piece_stock -= $d->current_piece_stock;
                }

                if($get_subsupply->isDirty()) {
                    $get_subsupply->save();
                }
            }
        }
    }
}
