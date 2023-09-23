<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\VaxcertConcern;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CovidVaccinePatientMasterlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_name',
        'category',
        'comorbidity',
        'unique_person_id',
        'pwd',
        'indigenous_member',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'contact_no',
        'guardian_name',
        'region',
        'province',
        'muni_city',
        'barangay',
        'sex',
        'birthdate',
        'deferral',
        'reason_for_deferral',
        'vaccination_date',
        'vaccine_manufacturer_name',
        'batch_number',
        'lot_no',
        'bakuna_center_cbcr_id',
        'vaccinator_name',
        'first_dose',
        'second_dose',
        'additional_booster_dose',
        'second_additional_booster_dose',
        'adverse_event',
        'adverse_event_condition',
        'row_hash',
    ];

    public function showDoseType() {
        $str = '';

        if($this->first_dose == 'Y') {
            $str = $str.'(1st Dose) ';
        }

        if($this->second_dose == 'Y') {
            if($this->vaccine_manufacturer_name == 'J&J') {
                $str = $str.'(1st & 2nd Dose) ';
            }
            else {  
                $str = $str.'(2nd Dose) ';
            }
        }

        if($this->additional_booster_dose == 'Y') {
            $str = $str.'(3rd Dose) ';
        }

        if($this->second_additional_booster_dose == 'Y') {
            $str = $str.'(4th Dose)';
        }

        return $str;
    }

    public function showCbcrName() {
        $input = $this->bakuna_center_cbcr_id;

        $key = array_search($input, array_column(VaxcertConcern::getCbcrList(), 'cbcr_code'));

        if ($key !== false) {
            return VaxcertConcern::getCbcrList()[$key]['cbcr_name'];
        }
    
        // Return null or an appropriate value if no matching code is found
        return null;
    }

    public function getAge() {
        return Carbon::parse($this->birthdate)->age;
    }

    public function doseCheckColor() {
        if($this->first_dose == 'Y') {

        }
        else if($this->second_dose == 'Y') {
            if($this->vaccine_manufacturer_name != 'J&J') {
                //find first dose
                $f = CovidVaccinePatientMasterlist::where('first_dose', 'Y')
                ->where('last_name', $this->last_name)
                ->where('first_name', $this->first_name)
                ->whereDate('birthdate', $this->birthdate);

                if(!is_null($this->middle_name)) {
                    $f = $f->where('middle_name', $this->middle_name);
                }

                if(!is_null($this->suffix)) {
                    $f = $f->where('suffix', $this->suffix);
                }

                $f = $f->first();

                if($f) {
                    if(strtotime($f->vaccination_date) <= strtotime($this->vaccination_date)) {
                        return '';
                    }
                    else {
                        return 'bg-danger';
                    }
                }
                else {
                    return 'bg-danger';
                }
            }
            else {
                return '';
            }
        }
        else if($this->additional_booster_dose == 'Y') {
            //find second dose
            
            $f = CovidVaccinePatientMasterlist::where('second_dose', 'Y')
            ->where('last_name', $this->last_name)
            ->where('first_name', $this->first_name)
            ->whereDate('birthdate', $this->birthdate);

            if(!is_null($this->middle_name)) {
                $f = $f->where('middle_name', $this->middle_name);
            }

            if(!is_null($this->suffix)) {
                $f = $f->where('suffix', $this->suffix);
            }

            $f = $f->first();

            if($f) {
                if(strtotime($f->vaccination_date) <= strtotime($this->vaccination_date)) {
                    return '';
                }
                else {
                    return 'bg-danger';
                }
            }
            else {
                return 'bg-danger';
            }
        }
        else if($this->second_additional_booster_dose == 'Y') {
            //find second dose
            $f = CovidVaccinePatientMasterlist::where('additional_booster_dose', 'Y')
            ->where('last_name', $this->last_name)
            ->where('first_name', $this->first_name)
            ->whereDate('birthdate', $this->birthdate);

            if(!is_null($this->middle_name)) {
                $f = $f->where('middle_name', $this->middle_name);
            }

            if(!is_null($this->suffix)) {
                $f = $f->where('suffix', $this->suffix);
            }

            $f = $f->first();

            if($f) {
                if(strtotime($f->vaccination_date) <= strtotime($this->vaccination_date)) {
                    return '';
                }
                else {
                    return 'bg-danger';
                }
            }
            else {
                return 'bg-danger';
            }
        }
    }
    public function convertRegionToJson() {
        $s = Regions::where('regionName', $this->region)->first();

        return $s->json_code;
    }

    public function convertProvinceToJson() {
        $s = substr($this->province, 0, 4);

        return $s;
    }

    public function convertMuncityToJson() {
        $s = substr($this->muni_city, 0, 6);

        return $s;
    }
}
