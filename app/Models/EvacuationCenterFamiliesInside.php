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
        'evac_type',
        'remarks',
        'focal_name',
        'supervisor_name',
        'created_by',
    ];

    public function evacuationCenter() {
        return $this->belongsTo(EvacuationCenter::class, 'evacuation_center_id');
    }

    public function familyHead() {
        return $this->belongsTo(EvacuationCenterFamilyHead::class, 'familyhead_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->hasMany(EvacuationCenterFamilyMembersInside::class, 'familyinside_id');
    }

    public function getNumberOfMembers() {
        return EvacuationCenterFamilyMembersInside::where('familyinside_id', $this->id)->count();
    }
}
