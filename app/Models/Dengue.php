<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dengue extends Model
{
    use HasFactory;

    protected $table = 'dengue';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];

    public function getEdcsFacilityName() {
        $s = DohFacility::where('healthfacility_code', $this->edcs_healthFacilityCode)->first();

        if($s) {
            return $s->facility_name;
        }
        else {
            return 'UNKNOWN';
        }
    }

    public function getName() {
        $full = $this->FamilyName.', '.$this->FirstName;

        if(!is_null($this->middle_name)) {
            $full = $full.' '.$this->middle_name;
        }

        if(!is_null($this->suffix)) {
            $full = $full.' '.$this->suffix;
        }

        return $full;
    }

    public function displayAgeStringToReport() {
        if($this->AgeYears == 0) {
            if($this->AgeMons == 0) {
                return $this->AgeDays.' '.Str::plural('day', $this->AgeDays).' old';
            }
            else {
                return $this->AgeMons.' '.Str::plural('month', $this->AgeMons).' old';
            }
        }
        else {
            return $this->AgeYears.' '.Str::plural('year', $this->AgeYears).' old';
        }
    }

    public function getStreetPurok() {
        if(!is_null($this->Streetpurok)) {
            return $this->Streetpurok;
        }
        else {
            return 'Street/Purok not Encoded';
        }
    }

    public function getClassificationString() {
        if($this->CaseClassification == 'C') {
            if($this->is_ns1positive != 1) {
                return 'Confirmed Case';
            }
            else {
                return 'Confirmed Case (NS1 Positive)';
            }
        }
        else if($this->CaseClassification == 'P') {
            return 'Probable Case';
        }
        else {
            return 'Suspected Case';
        }
    }

    public function ifInsideClusteringDistance() {
        $lat1 = deg2rad($this->sys_coordinate_y);
        $lng1 = deg2rad($this->sys_coordinate_x);

        if(request()->input('year')) {
            $year = request()->input('year');
        }
        else {
            $year = date('Y');
        }

        $coord_data = Dengue::where('EPIID', '!=', $this->EPIID)
        ->whereNotNull('sys_coordinate_x')
        ->where('Year', $year)
        ->get();

        $condition = false;

        $earthRadius = 6371000;

        if($coord_data->count() != 0) {
            foreach($coord_data as $c) {
                $lat2 = deg2rad($c->sys_coordinate_y);
                $lng2 = deg2rad($c->sys_coordinate_x);

                // Haversine formula
                $latDelta = $lat2 - $lat1;
                $lngDelta = $lng2 - $lng1;

                $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($lngDelta / 2), 2)));

                $total = $angle * $earthRadius;

                if($total <= 300) {
                    return true;
                }
            }
        }
        else {
            return false;
        }

        
    }
}
