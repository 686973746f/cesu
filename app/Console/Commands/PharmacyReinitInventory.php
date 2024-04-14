<?php

namespace App\Console\Commands;

use App\Models\PharmacySupplyMaster;
use App\Models\PharmacySupplySub;
use App\Models\PharmacySupplySubStock;
use Illuminate\Console\Command;

class PharmacyReinitInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pharmacyreinitinv';

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
        $list_item = PharmacySupplySub::get();

        foreach($list_item as $i) {
            //get substock and recount box/piece

            $sub = PharmacySupplySubStock::where('subsupply_id', $i->id)
            ->where('is_expired', 'N')
            ->get();

            $qty_count = 0;

            foreach($sub as $s) {
                if($i->pharmacysupplymaster->quantity_type == 'BOX') {
                    $qty_count += $s->current_box_stock;
                }
                else {
                    $qty_count += $s->current_piece_stock;
                }
            }

            if($i->pharmacysupplymaster->quantity_type == 'BOX') {
                $i->master_box_stock = $qty_count;
                $i->master_piece_stock = $qty_count * $i->pharmacysupplymaster->config_piecePerBox;
            }
            else {
                $i->master_piece_stock = $qty_count;
            }
            
            if($i->isDirty()) {
                $i->save();
            }
        }
    }
}
