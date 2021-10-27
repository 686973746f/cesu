<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Forms;
use App\Models\LinelistSubs;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Records extends Model
{
    use HasFactory;

    protected $fillable = [
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

        'sharedOnId',
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
        return $this->address_houseno.', '.$this->address_street.', BRGY.'.$this->address_brgy.', '.$this->address_city.', '.$this->address_province;
    }

    public function getName() {
        return $this->lname.", ".$this->fname." ".$this->mname;
    }

    public function form(){
        return $this->hasOne(Forms::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function linelistsub() {
        return $this->hasMany(LinelistSubs::class);
    }

    public function getPhilhealth() {
        return substr($this->philhealth,0,2)."-".substr($this->philhealth,2,9)."-".substr($this->philhealth,11,1);
    }

    public static function ifDuplicateFound($lname, $fname, $mname, $bdate) {
        if(!is_null($mname)) {
            $check = Records::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)))
            ->first();

            if($check) {
                $checkwbdate = Records::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)))
                ->whereDate('bdate', $bdate)
                ->first();

                if($checkwbdate) {
                    return $checkwbdate;
                }
                else {
                    return $check;
                }
            }
            else {
                $check1 = Records::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->whereDate('bdate', $bdate)
                ->first();

                if($check1) {
                    return $check1;
                }
                else {
                    return NULL;
                }
            }
        }
        else {
            $check = Records::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
            ->whereNull('mname')
            ->first();
            
            if($check) {
                $checkwbdate = Records::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->whereNull('mname')
                ->whereDate('bdate', $bdate)
                ->first();

                if($checkwbdate) {
                    return $checkwbdate;
                }
                else {
                    return $check;
                }
            }
            else {
                $check1 = Records::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->whereDate('bdate', $bdate)
                ->first();

                if($check1) {
                    return $check1;
                }
                else {
                    return NULL;
                }
            }
        }
    }

    public static function detectChangeName($lname, $fname, $mname, $bdate, $id) {
        if(!is_null($mname)) {
            $check = Records::where('id', '!=', $id)
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)))
            ->first();

            if($check) {
                $checkwbdate = Records::where('id', '!=', $id)
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)))
                ->whereDate('bdate', $bdate)
                ->first();

                if($checkwbdate) {
                    return $checkwbdate;
                }
                else {
                    return $check;
                }
            }
            else {
                $check1 = Records::where('id', '!=', $id)
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->whereDate('bdate', $bdate)
                ->first();

                if($check1) {
                    return $check1;
                }
                else {
                    return NULL;
                }
            }
        }
        else {
            $check = Records::where('id', '!=', $id)
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
            ->whereNull('mname')
            ->first();
            
            if($check) {
                $checkwbdate = Records::where('id', '!=', $id)
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->whereNull('mname')
                ->whereDate('bdate', $bdate)
                ->first();

                if($checkwbdate) {
                    return $checkwbdate;
                }
                else {
                    return $check;
                }
            }
            else {
                $check1 = Records::where('id', '!=', $id)
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->whereDate('bdate', $bdate)
                ->first();

                if($check1) {
                    return $check1;
                }
                else {
                    return NULL;
                }
            }
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
}
