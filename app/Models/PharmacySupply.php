<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacySupply extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'pharmacy_branch_id',
        'name',
        'category',
        'quantity_type',

        'sku_code',
        'po_contract_number',
        'supplier',
        'description',
        'dosage_form',
        'dosage_strength',
        'unit_measure',
        'entity_name',
        'source_of_funds',
        'unit_cost',
        'mode_of_procurement',
        'end_user',

        'config_piecePerBox',
        'master_box_stock',
        'master_piece_stock',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getQtyType() {
        if($this->quantity_type == 'BOX') {
            return 'Boxes';
        }
        else {
            return 'Bottles';
        }
    }
}
