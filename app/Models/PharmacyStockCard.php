<?php

namespace App\Models;

use Illuminate\Support\Str;
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

    public function getReceivingBranch() {
        return $this->belongsTo(PharmacyBranch::class, 'receiving_branch_id');
    }

    public function getReceivingPatient() {
        return $this->belongsTo(PharmacyBranch::class, 'receiving_patient_id');
    }

    public function getQtyAndType() {
        if($this->qty_type == 'BOX') {
            if($this->qty_to_process <= 1) {
                return $this->qty_to_process.' BOX';
            }
            else {
                return $this->qty_to_process.' BOXES';
            }
        }
        else {
            if($this->qty_to_process <= 1) {
                return $this->qty_to_process.' PC';
            }
            else {
                return $this->qty_to_process.' PCS';
            }
        }
    }

    public function getBalance() {
        if($this->qty_type == 'BOX') {
            return $this->after_qty_box.' '.Str::plural('BOX', $this->after_qty_box).' ('.$this->after_qty_piece.' '.Str::plural('PC', $this->after_qty_piece).')';
        }
        else {
            return $this->after_qty_piece.' '.Str::plural('PC', $this->after_qty_piece);
        }
    }

    public function getRecipientAndRemarks() {
        $finalstr = '';

        if($this->receiving_branch_id) {
            $finalstr = $finalstr.$this->getReceivingBranch->name;
        }
        else if ($this->receiving_patient_id){
            $finalstr = $finalstr.$this->getReceivingBranch->getName();
        }
        else {
            $finalstr = $this->recipient;
        }

        if($this->remarks) {
            if($this->receiving_branch_id || $this->receiving_patient_id || $this->recipient) {
                $finalstr = $finalstr.' / '.$this->remarks;
            }
            else {
                $finalstr = $this->remarks;
            }
            
        }

        return $finalstr;
    }
}