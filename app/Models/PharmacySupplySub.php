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
    ];
}
