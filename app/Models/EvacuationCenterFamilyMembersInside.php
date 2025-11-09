<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenterFamilyMembersInside extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_registered',
        'familyinside_id',
        'member_id',

        'is_injured',
        'is_admitted',
        'date_admitted',
        'date_discharged',

        'outcome',
        'date_missing',
        'date_returned',
        'date_died',

        'remarks',
        'age_years',
        'age_months',
        'age_days',
        
        'created_by',
    ];

    public function familyinside() {
        return $this->belongsTo(EvacuationCenterFamiliesInside::class, 'familyinside_id');
    }

    public function member() {
        return $this->belongsTo(EvacuationCenterFamilyMember::class, 'member_id');
    }
}
