<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacyStockCard extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'subsupply_id',
        'type',
        'before_qty_box',
        'before_qty_piece',
        'qty_to_process',
        'qty_type',
        'after_qty_box',
        'after_qty_piece',
        'total_cost',
        'drsi_number',

        'recipient',
        'receiving_branch_id',
        'receiving_patient_id',
        
        'remarks',

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
}
