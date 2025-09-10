<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'focal_person',
        'contact_number',
        'description',
        'level',
        'qr',
        'if_bhs_id',
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

    public function pharmacysub() {
        return $this->hasMany(PharmacySupplySub::class, 'pharmacy_branch_id');
    }

    public function bhs() {
        return $this->belongsTo(BarangayHealthStation::class, 'if_bhs_id');
    }
}
