<?php

namespace App\Models;

use App\Models\PharmacyStockCard;
use App\Models\PharmacyQtyLimitPatient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacyCartSub extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_cart_id',
        'subsupply_id',
        'qty_to_process',
        'type_to_process',
    ];

    public function pharmacycartmain() {
        return $this->belongsTo(PharmacyCartMain::class, 'main_cart_id');
    }

    public function pharmacysub() {
        return $this->belongsTo(PharmacySupplySub::class, 'subsupply_id');
    }

    public function displayPrescriptionLimit() {
        $get_master_id = $this->pharmacysub->pharmacysupplymaster->id;

        $d = PharmacyQtyLimitPatient::where('prescription_id', $this->pharmacycartmain->prescription_id)
        ->where('master_supply_id', $get_master_id)
        ->first();

        if($d) {
            //get current qty taken
            $curr_qty_obtained = PharmacyStockCard::whereHas('pharmacysub', function ($q) use ($get_master_id) {
                $q->whereHas('pharmacysupplymaster', function ($r) use ($get_master_id) {
                    $r->where('id', $get_master_id);
                });
            })
            ->whereDate('created_at', '>=', $d->date_started)
            ->where('qty_type', 'PIECE')
            ->sum('qty_to_process');


            return $curr_qty_obtained.'/'.$d->set_pieces_limit;
        }
        else {
            return NULL;
        }
    }

    public function getCurrentQtyObtained() {
        $get_master_id = $this->pharmacysub->pharmacysupplymaster->id;

        $d = PharmacyQtyLimitPatient::where('prescription_id', $this->pharmacycartmain->prescription_id)
        ->where('master_supply_id', $get_master_id)
        ->first();

        if($d) {
            //get current qty taken
            $curr_qty_obtained = PharmacyStockCard::whereHas('pharmacysub', function ($q) use ($get_master_id) {
                $q->whereHas('pharmacysupplymaster', function ($r) use ($get_master_id) {
                    $r->where('id', $get_master_id);
                });
            })
            ->whereDate('created_at', '>=', $d->date_started)
            ->where('qty_type', 'PIECE')
            ->sum('qty_to_process');


            return $curr_qty_obtained;
        }
        else {
            return NULL;
        }
    }
}
