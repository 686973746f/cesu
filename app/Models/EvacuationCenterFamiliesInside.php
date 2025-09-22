<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenterFamiliesInside extends Model
{
    use HasFactory;

    protected $fillable = [
        'evacuation_center_id',
        'familyhead_id',
        'date_registered',
        'family_status',
        'date_returnedhome',
        'outcome',
        'date_missing',
        'date_returned',
        'date_died',
        'is_injured',
        'shelterdamage_classification',
        'remarks',
        'focal_name',
        'supervisor_name',
        'created_by',
    ];

    public function evacuationcenter() {
        return $this->belongsTo(EvacuationCenter::class, 'evacuation_center_id');
    }

    public function familyhead() {
        return $this->belongsTo(EvacuationCenterFamilyHead::class, 'familyhead_id');
    }
}
