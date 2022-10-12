<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\PaSwabLinks;
use App\Models\Interviewers;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaSwabDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'isNewRecord',
        'records_id',
        'linkCode',
        'majikCode',
        'pType',
        'isForHospitalization',
        'interviewDate',
        'forAntigen',

        'lname',
        'fname',
        'mname',
        'bdate',
        'gender',
        'isPregnant',
        'ifPregnantLMP',
        'cs',
        'nationality',
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

        'occupation',
        'occupation_name',
        'natureOfWork',
        'natureOfWorkIfOthers',
        'worksInClosedSetting',

        'occupation_lotbldg',
        'occupation_street',
        'occupation_brgy',
        'occupation_city',
        'occupation_cityjson',
        'occupation_province',
        'occupation_provincejson',
        'occupation_mobile',
        'occupation_email',

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

        'dateOnsetOfIllness',
        'SAS',
        'SASFeverDeg',
        'SASOtherRemarks',

        'COMO',
        'COMOOtherRemarks',

        'imagingDoneDate',
        'imagingDone',
        'imagingResult',
        'imagingOtherFindings',

        'expoitem1',
        'expoDateLastCont',
        
        'contact1Name',
        'contact1No',
        'contact2Name',
        'contact2No',
        'contact3Name',
        'contact3No',
        'contact4Name',
        'contact4No',

        'patientmsg',

        'senderIP',
    ];

    public function getName() {
        return $this->lname.", ".$this->fname." ".$this->mname;
    }

    public function getAddress() {
        return $this->address_street.", BRGY. ".$this->address_brgy.", ".$this->address_city.", ".$this->address_province;
    }

    public function getPatientType() {
        if($this->pType == 'PROBABLE') {
            return 'SUSPECTED';
        }
        else if($this->pType == 'CLOSE CONTACT') {
            return 'CLOSE CONTACT';
        }
        else if($this->pType == 'TESTING') {
            return 'NOT A CASE OF COVID';
        }
        else {
            return $this->pType;
        }
    }

    public function getAge() {
        if(Carbon::parse($this->attributes['bdate'])->age > 0) {
            return Carbon::parse($this->attributes['bdate'])->age;
        }
        else {
            return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m MOS');
        }
    }

    public function getAgeInt() {
        return Carbon::parse($this->attributes['bdate'])->age;
    }

    public function getDefaultInterviewerName() {
        $referralCode = PaSwabLinks::where('code', $this->linkCode)->first();
        $interviewer = Interviewers::where('id', $referralCode->interviewer_id)->first();

        return $interviewer->getCifName();
    }

    public function toDateTimeString() {
        return Carbon::createFromTimeStamp(strtotime($this->expoDateLastCont))->diffForHumans();
    }

    public function diff4Humans($idate) {
        return Carbon::createFromTimeStamp(strtotime($idate))->diffForHumans();
    }

    public static function ifDuplicateFound($lname, $fname, $mname, $bdate) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));

        $check = PaSwabDetails::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function ($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->whereDate('bdate', $bdate)
        ->where('status', 'pending');

        if(!is_null($mname)) {
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
            $check1 = PaSwabDetails::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
            ->where(function ($q) use ($fname) {
                $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
                ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
            })
            ->whereDate('bdate', $bdate)
            ->where('status', 'pending')
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

    public static function ifEntryPending($lname, $fname, $mname) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));
        
        $check = PaSwabDetails::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function ($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->where('status', 'pending');

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
            $check1 = PaSwabDetails::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->where('status', 'pending')
            ->first();

            if($check1) {
                return $check1;
            }
            else {
                return NULL;
            }
        }
    }

    public static function ifHaveEntryToday ($lname, $fname, $mname) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));
        
        $check = PaSwabDetails::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function ($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->whereIn('status', ['pending', 'approved'])
        ->whereDate('created_at', date('Y-m-d'));
        
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
            $check1 = PaSwabDetails::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
            ->where(function ($q) use ($fname) {
                $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
                ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
            })
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('created_at', date('Y-m-d'))
            ->first();

            if($check1) {
                return $check1;
            }
            else {
                return NULL;
            }
        }
    }

    public function checkPaswabBrgyData() {
        if(auth()->user()->isCesuAccount()) {
            return true;
        }
        else {
            if($this->address_province == auth()->user()->brgy->city->province->provinceName && $this->address_city == auth()->user()->brgy->city->cityName && $this->address_brgy == auth()->user()->brgy->brgyName) {
                return true;
            }
            else {
                return false;
            }
        }
    }
}
