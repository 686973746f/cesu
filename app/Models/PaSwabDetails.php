<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaSwabDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'majikCode',
        'pType',
        'isForHospitalization',
        'interviewDate',
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

        'senderIP',
    ];

    public function getName() {
        return $this->lname.", ".$this->fname." ".$this->mname;
    }

    public function getAddress() {
        return $this->address_street.", BRGY. ".$this->address_brgy.", ".$this->address_city.", ".$this->address_province;
    }

    public function getAge() {
        if(Carbon::parse($this->attributes['bdate'])->age > 0) {
            return Carbon::parse($this->attributes['bdate'])->age;
        }
        else {
            return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m MOS');
        }
    }
}
