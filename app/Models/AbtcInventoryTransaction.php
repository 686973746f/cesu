<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcInventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'transaction_date',
        'stock_id',
        'type',
        'transferto_facility',
        'process_qty',
        'before_qty',
        'after_qty',
        'po_number',
        'unit_price',
        'unit_price_amount',
        'remarks',
        'created_by',
    ];

    public function stock() {
        return $this->belongsTo(AbtcInventoryStock::class, 'stock_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            return $this->belongsTo(User::class, 'updated_by');
        }
        else {
            return NULL;
        }
    }

    public function displayProcessQty() {
        if($this->type == 'ISSUED' || $this->type == 'TRANSFERRED') {
            return '- '.$this->process_qty;
        }
        else {
            return '+ '.$this->process_qty;
        }
    }

    

    public function transferred_to() {
        return $this->belongsTo(AbtcVaccinationSite::class, 'transferto_facility');
    }

    public function displayType() {
        if($this->type == 'ISSUED') {
            return 'USED';
        }
        else if($this->type == 'RECEIVED') {
            return 'RECEIVED';
        }
        else {
            return 'TRANSFERRED TO '.$this->transferred_to->site_name;
        }
    }
}
