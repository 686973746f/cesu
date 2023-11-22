<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Forms;
use App\Models\MonkeyPox;
use App\Models\Provinces;
use App\Models\LinelistSubs;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\City;

class Records extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_confidential',
        'user_id',
        'status',
        'lname',
        'fname',
        'mname',
        'gender',
        'isPregnant',
        'cs',
        'nationality',
        'bdate',
        'mobile',
        'phoneno',
        'email',
        'philhealth',
        'address_houseno',
        'address_street',
        'address_brgy',
        'address_city',
        'address_cityjson',
        'address_province',
        'address_provincejson',
        'permaaddressDifferent',
        'permaaddress_houseno',
        'permaaddress_street',
        'permaaddress_brgy',
        'permaaddress_city',
        'permaaddress_cityjson',
        'permaaddress_province',
        'permaaddress_provincejson',
        'permamobile',
        'permaphoneno',
        'permaemail',
        'hasOccupation',
        'occupation',
        'worksInClosedSetting',
        'occupation_lotbldg',
        'occupation_street',
        'occupation_brgy',
        'occupation_city',
        'occupation_cityjson',
        'occupation_province',
        'occupation_provincejson',
        'occupation_name',
        'occupation_mobile',
        'occupation_email',
        
        'natureOfWork',
        'natureOfWorkIfOthers',

        'vaccinationDate1',
        'vaccinationName1',
        'vaccinationNoOfDose1',
        'vaccinationFacility1',
        'vaccinationRegion1',
        'haveAdverseEvents1',
        'vaccinationDate2',
        'vaccinationName2',
        'vaccinationNoOfDose2',
        'vaccinationFacility2',
        'vaccinationRegion2',
        'haveAdverseEvents2',

        'vaccinationDate3',
        'vaccinationName3',
        'vaccinationNoOfDose3',
        'vaccinationFacility3',
        'vaccinationRegion3',
        'haveAdverseEvents3',

        'vaccinationDate4',
        'vaccinationName4',
        'vaccinationNoOfDose4',
        'vaccinationFacility4',
        'vaccinationRegion4',
        'haveAdverseEvents4',

        'remarks',
        'sharedOnId',

        'isHCW',
        'isPriority',
        'isindg',
        'indg_specify',

        'address_lat',
        'address_lng',
        'address_region_psgc',
        'address_province_psgc',
        'address_muncity_psgc',
        'address_brgy_psgc',

        'perma_address_lat',
        'perma_address_lng',
        'perma_address_region_psgc',
        'perma_address_province_psgc',
        'perma_address_muncity_psgc',
        'perma_address_brgy_psgc',
        
        'occupation_address_lat',
        'occupation_address_lng',
        'occupation_address_region_psgc',
        'occupation_address_province_psgc',
        'occupation_address_muncity_psgc',
        'occupation_address_brgy_psgc',

        'from_tkc',
    ];

    public function getAge() {
        if(Carbon::parse($this->attributes['bdate'])->age > 0) {
            return Carbon::parse($this->attributes['bdate'])->age;
        }
        else {
            if (Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m') == 0) {
                return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%d DAYS');
            }
            else {
                return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m MOS');
            }
        }
    }

    public function getAgeMonths() {
        $months = Carbon::parse($this->bdate)->diffInMonths(Carbon::now());

        return $months;
    }

    public function getAgeDays() {
        $months = Carbon::parse($this->bdate)->diffInDays(Carbon::now());

        return $months;
    }

    public function getAgeInt() {
        return Carbon::parse($this->attributes['bdate'])->age;
    }

    public function getEditedBy() {
        if(!is_null($this->updated_by)) {
            $u = User::find($this->updated_by);
            return $u->name;
        }
        else {
            return NULL;
        }
    }

    public function getAddress() {
        return $this->address_houseno.', '.$this->address_street.', BRGY. '.$this->address_brgy.', '.$this->address_city.', '.$this->address_province;
    }

    public function getName() {
        $final = $this->lname.', '.$this->fname;

        if(!is_null($this->mname)) {
            $final = $final.' '.$this->mname;
            
        }
        else {
            return $final;
        }

        return $final;
    }

    public function form(){
        return $this->hasOne(Forms::class);
    }

    public function monkeypox() {
        return $this->hasOne(MonkeyPox::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function linelistsub() {
        return $this->hasMany(LinelistSubs::class);
    }

    public function getPhilhealth() {
        if(!is_null($this->philhealth)) {
            return substr($this->philhealth,0,2)."-".substr($this->philhealth,2,9)."-".substr($this->philhealth,11,1);
        }
        else {
            return 'N/A';
        }
    }

    public static function ifDuplicateFound($lname, $fname, $mname, $bdate) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));

        $check = Records::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->whereDate('bdate', $bdate);

        if(!is_null($mname)) {
            $mname = mb_strtoupper(str_replace([' ','-'], '', $mname));

            $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), $mname)->first();
        }
        else {
            $check = $check->whereNull('mname')->first();
        }

        if($check) {
            return $check;
        }
        else {
            /*
            $check1 = Records::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
            ->where(function($q) use ($fname) {
                $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
                ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
            })
            ->whereDate('bdate', $bdate)
            ->first();

            if($check1) {
                return $check1;
            }
            else {
                return NULL;
            }
            */
            return NULL;
        }
    }

    public static function detectChangeName($lname, $fname, $mname, $bdate, $id) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));

        $check = Records::where('id', '!=', $id)
        ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->whereDate('bdate', $bdate);

        if(!is_null($mname)) {
            $mname = mb_strtoupper(str_replace([' ','-'], '', $mname));

            $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), $mname)->first();
        }
        else {
            $check = $check->whereNull('mname')->first();
        }

        if($check) {
            return $check;
        }
        else {
            /*
            $check1 = Records::where('id', '!=', $id)
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
            ->where(function($q) use ($fname) {
                $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
                ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
            })
            ->whereDate('bdate', $bdate)
            ->first();

            if($check1) {
                return $check1;
            }
            else {
                return NULL;
            }
            */
            return NULL;
        }
    }

    public static function eligibleToUpdate($id) {
        $record = Records::findOrFail($id);

        if(auth()->user()->isCesuAccount()) {
            return true;
        }
        else {
            if($record->user_id == auth()->user()->id || in_array(auth()->user()->id, explode(",", $record->sharedOnId))) {
                return true;
            }
            else {
                if(auth()->user()->isBrgyAccount()) {
                    //Barangay Account
                    if($record->address_province == auth()->user()->brgy->city->province->provinceName && $record->address_city == auth()->user()->brgy->city->cityName && $record->address_brgy == auth()->user()->brgy->brgyName) {
                        return true;
                    }
                    else if($record->user->brgy_id == auth()->user()->brgy_id) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
                else {
                    //Company Account
                    if($record->user->company_id == auth()->user()->company_id) {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
            }
        }
    }

    public function ifCompleteWorkplaceInfo() {
        if(!is_null($this->occupation)) {
            if(!is_null($this->occupation_province) && !is_null($this->occupation_city) && !is_null($this->occupation_brgy)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function getNewCif() {
        $form = Forms::where('records_id', $this->records_id)->orderBy('created_at', 'DESC')->first();

        if($form) {
            return $form->id;
        }
        else {
            return false;
        }
    }

    public function ifAllowedToViewConfidential() {
        if(auth()->user()->isAdmin == 1) {
            return true;
        }
        else {
            if($this->is_confidential == 1) {
                return false;
            }
            else {
                return true;
            }
        }
    }

    public function showVaxInfo() {
        if(!is_null($this->vaccinationDate4)) {
            $date1 = Carbon::parse($this->vaccinationDate4);
            $days_diff = $date1->diffInDays(Carbon::now());

            if($days_diff >= 14) {
                return '2ND BOOSTERED ('.$this->vaccinationName4.' + '.$this->vaccinationName3.' + '.$this->vaccinationName1.')';
            }
            else {
                return 'BOOSTERED ('.$this->vaccinationName3.')';
            }
        }
        else if(!is_null($this->vaccinationDate3)) {
            $date1 = Carbon::parse($this->vaccinationDate3);
            $days_diff = $date1->diffInDays(Carbon::now());

            if($days_diff >= 14) {
                return 'BOOSTERED ('.$this->vaccinationName3.')';
            }
            else {
                return 'FULL VAX ('.$this->vaccinationName1.')';
            }
        }
        else if(!is_null($this->vaccinationDate2)) {
            $date1 = Carbon::parse($this->vaccinationDate2);
            $days_diff = $date1->diffInDays(Carbon::now());

            if($days_diff >= 14) {
                return 'FULL VAX ('.$this->vaccinationName1.')';
            }
            else {
                return 'HALF VAX ('.$this->vaccinationName1.')';
            }
        }
        else if(!is_null($this->vaccinationDate1)) {
            $date1 = Carbon::parse($this->vaccinationDate1);
            $days_diff = $date1->diffInDays(Carbon::now());

            if($this->vaccinationName1 == 'JANSSEN') {
                if($days_diff >= 14) {
                    return 'FULL VAX ('.$this->vaccinationName1.')';
                }
                else {
                    return 'HALF VAX ('.$this->vaccinationName1.')';
                }
            }
            else {
                return 'HALF VAX ('.$this->vaccinationName1.')';
            }
        }
        else {
            return 'N/A';
        }
    }

    public function ifFullyVaccinated() {
        if(!is_null($this->vaccinationDate2)) {
            $date1 = Carbon::parse($this->vaccinationDate2);
            $days_diff = $date1->diffInDays(Carbon::now());

            if($days_diff >= 14) {
                return true;
            }
            else {
                return false;
            }
        }
        else if(!is_null($this->vaccinationDate1)) {
            $date1 = Carbon::parse($this->vaccinationDate1);
            $days_diff = $date1->diffInDays(Carbon::now());

            if($this->vaccinationName1 == 'JANSSEN') {
                if($days_diff >= 14) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function isPregnant() {
        if($this->gender != 'MALE') {
            if($this->isPregnant == 1) {
                return 'Y';
            }
            else {
                return 'N';
            }
        }
        else {
            return 'N';
        }
    }

    public function getBrgyPsgc() {
        $city = City::where('json_code', $this->address_cityjson)->pluck('id')->first();

        $get_code = Brgy::where('city_id', $city)->where('brgyName', $this->address_brgy)->pluck('json_code')->first();

        if(substr($get_code,0,1) === '0') {
            return substr($get_code,1);
        }
        else {
            return $get_code;
        }
    }

    public function getCityPsgc() {
        $get_code = City::where('json_code', $this->address_cityjson)->pluck('json_code')->first();

        if(substr($get_code,0,1) === '0') {
            return substr($get_code,1);
        }
        else {
            return $get_code;
        }
    }

    public function getProvincePsgc() {
        $get_code = Provinces::where('json_code', $this->address_provincejson)->pluck('json_code')->first();

        if(substr($get_code,0,1) === '0') {
            return substr($get_code,1);
        }
        else {
            return $get_code;
        }
    }

    public function getRegionPsgc() {
        $get_code = Provinces::where('json_code', $this->address_provincejson)->first()->region->json_code;

        if(substr($get_code,0,1) === '0') {
            return substr($get_code,1);
        }
        else {
            return $get_code;
        }
    }

    public function getWorkBrgyPsgc() {
        $city = City::where('json_code', $this->occupation_cityjson)->pluck('id')->first();

        $get_code = Brgy::where('city_id', $city)->where('brgyName', $this->occupation_brgy)->pluck('json_code')->first();

        if(substr($get_code,0,1) === '0') {
            return substr($get_code,1);
        }
        else {
            return $get_code;
        }
    }

    public function getWorkCityPsgc() {
        $get_code = City::where('json_code', $this->occupation_cityjson)->pluck('json_code')->first();

        if(substr($get_code,0,1) === '0') {
            return substr($get_code,1);
        }
        else {
            return $get_code;
        }
    }

    public function getWorkProvincePsgc() {
        $get_code = Provinces::where('json_code', $this->occupation_provincejson)->pluck('json_code')->first();

        if(substr($get_code,0,1) === '0') {
            return substr($get_code,1);
        }
        else {
            return $get_code;
        }
    }

    public function getWorkRegionPsgc() {
        $get_code = Provinces::where('json_code', $this->occupation_provincejson)->first()->region->json_code;

        if(substr($get_code,0,1) === '0') {
            return substr($get_code,1);
        }
        else {
            return $get_code;
        }
    }

    /*
    public function getRegionPsgc() {
        $regionString = file_get_contents(storage_path('json/refregion.json'));
        $provinceString = file_get_contents(storage_path('json/refprovince.json'));

        $region_data = json_decode($regionString, true);
        $province_data = json_decode($provinceString, true);

        $province_key = array_search($this->address_provincejson, array_map('strtoupper', array_column($province_data, 'provCode')));
        $region_key = array_search($province_data[$province_key]['regCode'], array_map('strtoupper', array_column($region_data, 'regCode')));

        return $region_data[$region_key]['regCode'];
    }

    public function getProvincePsgc() {
        $provinceString = file_get_contents(storage_path('json/refprovince.json'));
        $province_data = json_decode($provinceString, true);

        $province_key = array_search($this->address_provincejson, array_map('strtoupper', array_column($province_data, 'provCode')));

        return $province_data[$province_key]['psgcCode'];
    }

    public function getCityPsgc() {
        $cityString = file_get_contents(storage_path('json/refcitymun.json'));
        $city_data = json_decode($cityString, true);
        $city_key = array_search($this->address_cityjson, array_map('strtoupper', array_column($city_data, 'citymunCode')));

        return $city_data[$city_key]['psgcCode'];
    }

    public function getBrgyPsgc() {
        $cityString = file_get_contents(storage_path('json/refcitymun.json'));
        $city_data = json_decode($cityString, true);
        $city_key = array_search($this->address_cityjson, array_map('strtoupper', array_column($city_data, 'citymunCode')));

        $brgyString = file_get_contents(storage_path('json/refbrgy.json'));
        $brgy_data = json_decode($brgyString, true);

        foreach ($brgy_data as $brgy_key => $value) {
            if (strtoupper($value['brgyDesc']) === $this->address_brgy && $value['citymunCode'] === $city_data[$city_key]['citymunCode']) {
                // Found the desired element, so $key contains the array key
                break;
            }
        }

        return $brgy_data[$brgy_key]['brgyCode'];
    }

    public function getWorkRegionPsgc() {
        $regionString = file_get_contents(storage_path('json/refregion.json'));
        $provinceString = file_get_contents(storage_path('json/refprovince.json'));

        $region_data = json_decode($regionString, true);
        $province_data = json_decode($provinceString, true);

        $province_key = array_search($this->occupation_provincejson, array_map('strtoupper', array_column($province_data, 'provCode')));
        $region_key = array_search($province_data[$province_key]['regCode'], array_map('strtoupper', array_column($region_data, 'regCode')));

        return $region_data[$region_key]['regCode'];
    }

    public function getWorkProvincePsgc() {
        $provinceString = file_get_contents(storage_path('json/refprovince.json'));
        $province_data = json_decode($provinceString, true);

        $province_key = array_search($this->occupation_provincejson, array_map('strtoupper', array_column($province_data, 'provCode')));

        return $province_data[$province_key]['psgcCode'];
    }

    public function getWorkCityPsgc() {
        $cityString = file_get_contents(storage_path('json/refcitymun.json'));
        $city_data = json_decode($cityString, true);
        $city_key = array_search($this->occupation_cityjson, array_map('strtoupper', array_column($city_data, 'citymunCode')));

        return $city_data[$city_key]['psgcCode'];
    }

    public function getWorkBrgyPsgc() {
        $cityString = file_get_contents(storage_path('json/refcitymun.json'));
        $city_data = json_decode($cityString, true);
        $city_key = array_search($this->occupation_cityjson, array_map('strtoupper', array_column($city_data, 'citymunCode')));

        $brgyString = file_get_contents(storage_path('json/refbrgy.json'));
        $brgy_data = json_decode($brgyString, true);

        foreach ($brgy_data as $brgy_key => $value) {
            if (strtoupper($value['brgyDesc']) === $this->address_brgy && $value['citymunCode'] === $city_data[$city_key]['citymunCode']) {
                // Found the desired element, so $key contains the array key
                break;
            }
        }

        return $brgy_data[$brgy_key]['brgyCode'];
    }

    */

    public function getLatestSwabType() {
        $s = Forms::where('records_id', $this->id)->orderBy('created_at', 'DESC')->first();

        if(is_null($s->testDateCollected2)) {
            if($s->testType1 == 'OPS AND NPS') {
                return 'OPS+NPS';
            }
            else {
                return $s->testType1;
            }
        }
        else {
            if($s->testType2 == 'OPS AND NPS') {
                return 'OPS+NPS';
            }
            else {
                return $s->testType2;
            }
        }
    }
}
