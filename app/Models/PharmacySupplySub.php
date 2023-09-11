<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacySupplySub extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_master_id',
        'pharmacy_branch_id',
        'self_sku_code',
        'self_description',

        'po_contract_number',
        'supplier',
        'dosage_form',
        'dosage_strength',
        'unit_measure',
        'entity_name',
        'source_of_funds',
        'unit_cost',
        'mode_of_procurement',
        'end_user',
        'default_issuance_per_box',
        'default_issuance_per_piece',

        'master_box_stock',
        'master_piece_stock',

        'created_by',
    ];

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

    public function pharmacysupplymaster() {
        return $this->belongsTo(PharmacySupplyMaster::class, 'supply_master_id');
    }

    public function pharmacybranch() {
        return $this->belongsTo(PharmacyBranch::class, 'pharmacy_branch_id');
    }

    public function getMainQty() {
        if($this->pharmacysupplymaster->quantity_type == 'BOX') {
            $get_qty = $this->master_box_stock;
        }
        else {
            $get_qty = $this->master_piece_stock;
        }
        
        return $get_qty;
    }

    public function getMasterStock() {
        
    }
}
