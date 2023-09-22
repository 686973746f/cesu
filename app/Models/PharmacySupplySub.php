<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

        'self_maxbox_perduration',
        'self_maxpiece_perduration',
        'self_duration_days',

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

    public function stockcard() {
        return $this->hasMany(PharmacyStockCard::class, 'subsupply_id');
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

    public function displayQty() {
        if($this->pharmacysupplymaster->quantity_type == 'BOX') {
            return $this->master_box_stock.' '.Str::plural('BOX', $this->master_box_stock).' ('.($this->master_piece_stock).' '.Str::plural('PC', ($this->master_piece_stock)).')';
        }
        else {
            return $this->master_piece_stock.' '.Str::plural('PC', $this->master_piece_stock);
        }
    }

    public function getMasterStock() {
        
    }

    public function ifAuthorizedToUpdate() {
        if(auth()->user()->isAdminPharmacy() || auth()->user()->pharmacy_branch_id == $this->pharmacy_branch_id) {
            return true;
        }
        else {
            return false;
        }
    }

    public function ifHasStock() {
        if($this->pharmacysupplymaster->quantity_type == 'BOX') {
            if($this->master_box_stock != 0 || $this->master_piece_stock != 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            if($this->master_piece_stock != 0) {
                return true;
            }
            else {
                return false;
            }
        }
    }

    public function ifPatientReachedDurationLimit($patient_id, $selected_qty_type) {
        if($this->self_duration_days) {
            $duration_days = $this->self_duration_days;
        }
        else {
            $duration_days = $this->pharmacysupplymaster->duration_days;
        }

        if($duration_days) {
            if($selected_qty_type == 'BOX') {
                if($this->self_maxbox_perduration) {
                    $src_stockcard = PharmacyStockCard::where('receiving_patient_id', $patient_id)
                    ->where('type', 'ISSUED')
                    ->where('qty_type', 'BOX')
                    ->whereHas('pharmacysub', function ($q) {
                        $q->whereHas('pharmacysupplymaster', function ($r) {
                            $r->where('sku_code', $this->pharmacysupplymaster->sku_code);
                        });
                    })
                    ->sum('qty_to_process');
                }
                else if($this->pharmacysupplymaster->maxbox_perduration) {

                }
                else {
                    return true;
                }
            }
            else {
                if($this->pharmacysupplymaster->quantity_type == 'BOX') {

                }
                else {

                }
            }
        }
        else {
            return true;
        }
    }

    public function ifPatientReachedQtyLimit($patient_id) {
        
    }
}
