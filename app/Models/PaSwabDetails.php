<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    }

    public function getAge() {
        if(Carbon::parse($this->attributes['bdate'])->age > 0) {
            return Carbon::parse($this->attributes['bdate'])->age;
        }
        else {
            return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m MOS');
        }
    }

    public function toDateTimeString() {
        return Carbon::createFromTimeStamp(strtotime($this->expoDateLastCont))->diffForHumans();
    }
}
