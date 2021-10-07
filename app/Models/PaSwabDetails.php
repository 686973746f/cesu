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
        if(!is_null($mname)) {
            $check = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
            ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
            ->where(DB::raw("REPLACE(mname,' ','')"), mb_strtoupper(str_replace(' ', '', $mname)))
            ->where('status', 'pending')
            ->first();

            if($check) {
                $checkwbdate = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
                ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
                ->where(DB::raw("REPLACE(mname,' ','')"), mb_strtoupper(str_replace(' ', '', $mname)))
                ->whereDate('bdate', $bdate)
                ->where('status', 'pending')
                ->first();

                if($checkwbdate) {
                    return $checkwbdate;
                }
                else {
                    return $check;
                }  
            }
            else {
                $check1 = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
                ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
                ->whereDate('bdate', $bdate)
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
        else {
            $check = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
            ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
            ->whereNull('mname')
            ->where('status', 'pending')
            ->first();

            if($check) {
                $checkwbdate = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
                ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
                ->whereNull('mname')
                ->whereDate('bdate', $bdate)
                ->where('status', 'pending')
                ->first();

                if($checkwbdate) {
                    return $checkwbdate;
                }
                else {
                    return $check;
                }
            }
            else {
                $check1 = Records::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
                ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
                ->whereDate('bdate', $bdate)
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
    }

    public static function ifEntryPending ($lname, $fname, $mname) {
        if(!is_null($mname)) {
            $check = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
            ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
            ->where(DB::raw("REPLACE(mname,' ','')"), mb_strtoupper(str_replace(' ', '', $mname)))
            ->where('status', 'pending')
            ->first();

            if($check) {
                return $check;
            }
            else {
                $check1 = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
                ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
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
        else {
            $check = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
            ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
            ->where('status', 'pending')
            ->first();

            if($check) {
                return $check;
            }
            else {
                return NULL;
            }
        }
    }

    public static function ifHaveEntryToday ($lname, $fname, $mname) {
        if(!is_null($mname)) {
            $check = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
            ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
            ->where(DB::raw("REPLACE(mname,' ','')"), mb_strtoupper(str_replace(' ', '', $mname)))
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('created_at', date('Y-m-d'))
            ->first();

            if($check) {
                return $check;
            }
            else {
                $check1 = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
                ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
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
        else {
            $check = PaSwabDetails::where(DB::raw("REPLACE(lname,' ','')"), mb_strtoupper(str_replace(' ', '', $lname)))
            ->where(DB::raw("REPLACE(fname,' ','')"), mb_strtoupper(str_replace(' ', '', $fname)))
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('created_at', date('Y-m-d'))
            ->first();

            if($check) {
                return $check;
            }
            else {
                return NULL;
            }
        }
    }
}
