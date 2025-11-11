<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'disaster_id',
        'name',
        'description',
        'street_purok',
        'address_brgy_code',
        'longlat',
        'status',
        'date_start',
        'date_end',

        'has_electricity',
        'has_water',
        'has_communication',
        'has_internet',
        'rcho_functional',
        'bhs_functional',
        'has_flood',
        'has_landslide',
        'weather',
        'roads_passable',
        'remarks',
        'hash',
        'created_by',
    ];

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'address_brgy_code');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function disaster()
    {
        return $this->belongsTo(Disaster::class, 'disaster_id');
    }

    public function familiesinside() {
        return $this->hasMany(EvacuationCenterFamiliesInside::class, 'evacuation_center_id');
    }

    public function allMembers()
    {
        return $this->familyHeads()->with('members');
    }

    public function getTotalIndividualsAttribute()
    {
        // Count family heads (each family inside = 1 head)
        $headsCount = $this->familiesinside()->count();

        // Count all members under those families
        $membersCount = EvacuationCenterFamilyMembersInside::whereIn(
            'familyinside_id',
            $this->familiesinside()->pluck('id')
        )->count();

        return $headsCount + $membersCount;
    }

    public function countIndividualsByGender($gender)
    {
        // Get all family IDs in this evacuation center
        $familyIds = $this->familiesinside()->pluck('id');

        // Count family heads (in EvacuationCenterFamilyHead)
        $headsCount = EvacuationCenterFamiliesInside::whereIn('id', $familyIds)
            ->whereHas('familyHead', function ($query) use ($gender) {
                $query->where('sex', $gender);
            })
            ->count();

        // Count family members (in EvacuationCenterFamilyMembersInside)
        $membersCount = EvacuationCenterFamilyMembersInside::whereIn('familyinside_id', $familyIds)
            ->whereHas('member', function ($query) use ($gender) {
                $query->where('sex', $gender);
            })
            ->count();

        return $headsCount + $membersCount;
    }

    public function countIndividualsByAgeGender($gender, $age1, $age2)
    {
        // Get all family IDs in this evacuation center
        $familyIds = $this->familiesinside()->pluck('id');

        // Count family heads (in EvacuationCenterFamilyHead)
        $headsCount = EvacuationCenterFamiliesInside::whereIn('id', $familyIds)
            ->whereHas('familyHead', function ($query) use ($gender) {
                $query->where('sex', $gender);
            })
            ->whereBetween('age_years', [$age1, $age2])
            ->count();

        // Count family members (in EvacuationCenterFamilyMembersInside)
        $membersCount = EvacuationCenterFamilyMembersInside::whereIn('familyinside_id', $familyIds)
            ->whereHas('member', function ($query) use ($gender) {
                $query->where('sex', $gender);
            })
            ->whereBetween('age_years', [$age1, $age2])
            ->count();

        return $headsCount + $membersCount;
    }

    public function countAge($age1, $age2, $condition) {
        // Get all family IDs in this evacuation center
        $familyIds = $this->familiesinside()->pluck('id');

        if($age1 == $age2) {
            // Count family heads (in EvacuationCenterFamilyHead)
            $headsCount = EvacuationCenterFamiliesInside::whereIn('id', $familyIds)
                ->where('age_years', $condition, $age1)
                ->count();

            // Count family members (in EvacuationCenterFamilyMembersInside)
            $membersCount = EvacuationCenterFamilyMembersInside::whereIn('familyinside_id', $familyIds)
                ->where('age_years', $condition, $age1)
                ->count();
        }
        else {
            $headsCount = EvacuationCenterFamiliesInside::whereIn('id', $familyIds)
                ->whereBetween('age_years', [$age1,$age2])
                ->count();

            // Count family members (in EvacuationCenterFamilyMembersInside)
            $membersCount = EvacuationCenterFamilyMembersInside::whereIn('familyinside_id', $familyIds)
                ->whereBetween('age_years', [$age1,$age2])
                ->count();
        }
        
        return $headsCount + $membersCount;
    }

    public function countAgeWithCondition($age1, $age2, $condition, $gender, $age_lookup) {
        // Get all family IDs in this evacuation center
        $familyIds = $this->familiesinside()->pluck('id');

        if($age1 == $age2) {
            // Count family heads (in EvacuationCenterFamilyHead)
            $headsCount = EvacuationCenterFamiliesInside::whereIn('id', $familyIds)
                ->whereHas('familyHead', function ($query) use ($gender) {
                    $query->where('sex', $gender);
                })
                ->where($age_lookup, $condition, $age1)
                ->count();

            // Count family members (in EvacuationCenterFamilyMembersInside)
            $membersCount = EvacuationCenterFamilyMembersInside::whereIn('familyinside_id', $familyIds)
                ->whereHas('member', function ($query) use ($gender) {
                    $query->where('sex', $gender);
                })
                ->where($age_lookup, $condition, $age1)
                ->count();
        }
        else {
            $headsCount = EvacuationCenterFamiliesInside::whereIn('id', $familyIds)
                ->whereBetween('age_years', [$age1,$age2])
                ->count();

            // Count family members (in EvacuationCenterFamilyMembersInside)
            $membersCount = EvacuationCenterFamilyMembersInside::whereIn('familyinside_id', $familyIds)
                ->whereBetween('age_years', [$age1,$age2])
                ->count();
        }
        
        return $headsCount + $membersCount;
    }

    public function countconds($variable, $condition)
    {
        // Get all family IDs in this evacuation center
        $familyIds = $this->familiesinside()->pluck('id');

        // Count family heads (in EvacuationCenterFamilyHead)
        $headsCount = EvacuationCenterFamiliesInside::whereIn('id', $familyIds)
            ->where($variable, $condition)
            ->count();

        // Count family members (in EvacuationCenterFamilyMembersInside)
        $membersCount = EvacuationCenterFamilyMembersInside::whereIn('familyinside_id', $familyIds)
            ->where($variable, $condition)
            ->count();

        return $headsCount + $membersCount;
    }
}
