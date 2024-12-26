<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/*
ALTER TABLE dengue ADD sys_interviewer_name VARCHAR(255);
ALTER TABLE dengue ADD sys_interviewer_contactno VARCHAR(255);
ALTER TABLE dengue ADD sys_occupationtype VARCHAR(255);
ALTER TABLE dengue ADD sys_businessorschool_name TEXT;
ALTER TABLE dengue ADD sys_businessorschool_address TEXT;
ALTER TABLE dengue ADD sys_feverdegrees VARCHAR(2);

ALTER TABLE dengue ADD sys_headache VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_retropain VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_jointpain VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_jointswelling VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_musclepain VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_sorethroat VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_nausea VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_vomiting VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_diarrhea VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_abdominalpain VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_positivetonique VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_petechiae VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_echhymosis VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_maculopapularrash VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_gumbleeding VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_gibleeding VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_nosebleeding VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_hepatomegaly VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_lymphadenopathy VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_leucopenia VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_thrombocytopenia VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_thrombo_initial VARCHAR(10);
ALTER TABLE dengue ADD sys_thrombo_lowest VARCHAR(10);
ALTER TABLE dengue ADD sys_haemaconcentration VARCHAR(1) DEFAULT 'N';

ALTER TABLE dengue ADD sys_medication_taken TEXT;
ALTER TABLE dengue ADD sys_hospitalized_name TEXT;
ALTER TABLE dengue ADD sys_hospitalized_datestart DATE;
ALTER TABLE dengue ADD sys_hospitalized_dateend DATE;
ALTER TABLE dengue ADD sys_outcome VARCHAR(10);
ALTER TABLE dengue ADD sys_outcome_date DATE;
ALTER TABLE dengue ADD sys_historytravel2weeks VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD sys_historytravel2weeks_where TEXT;
ALTER TABLE dengue ADD sys_exposedtosimilarcontact VARCHAR(1) DEFAULT 'N';

ALTER TABLE dengue ADD sys_contactnames TEXT;
ALTER TABLE dengue ADD sys_contactaddress TEXT;

ALTER TABLE dengue ADD sys_animal_presence_list TEXT;
ALTER TABLE dengue ADD sys_animal_presence_others TEXT;

ALTER TABLE dengue ADD sys_water_presence_inside_list TEXT;
ALTER TABLE dengue ADD sys_water_presence_outside_list TEXT;
ALTER TABLE dengue ADD sys_water_presence_outside_others TEXT;

ALTER TABLE dengue ADD is_igmpositive VARCHAR(1) DEFAULT 'N';
ALTER TABLE dengue ADD igm_date DATE;
ALTER TABLE dengue ADD ns1_date DATE;
*/

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

    public function getCaseClassification() {
        if($this->CaseClassification == 'C') {
            return 'Confirmed';
        }
        else if($this->CaseClassification == 'P') {
            return 'Probable';
        }
        else {
            return 'Suspected';
        }
    }

    public function getOutcome() {
        if($this->Outcome == 'A') {
            return 'Alive';
        }
        else if($this->Outcome == 'D') {
            return 'Died';
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
        ->where('Year', $year);

        if(request()->input('mwStart') && request()->input('mwEnd')) {
            $mwStart = request()->input('mwStart');
            $mwEnd = request()->input('mwEnd');

            $coord_data = $coord_data->whereBetween('MorbidityWeek', [$mwStart, $mwEnd])->get();
        }
        else {
            $coord_data = $coord_data->get();
        }

        $earthRadius = 6371000;

        $condition = false;

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
                    $condition = true;
                }
            }

            return $condition;
        }
        else {
            return $condition;
        }
    }
}
