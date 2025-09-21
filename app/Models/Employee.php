<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'suffix',
        'profession_suffix',
        'gender',
        'bdate',
        'contact_number',
        'email',
        'address_region_code',
        'address_region_text',
        'address_province_code',
        'address_province_text',
        'address_muncity_code',
        'address_muncity_text',
        'address_brgy_code',
        'address_brgy_text',
        'address_street',
        'address_houseno',
        'weight_kg',
        'height_cm',
        'shirt_size',
        'type',
        'job_position',
        'office',
        'sub_office',
        'date_hired',
        'employment_status',
        'date_resigned',
        'remarks',
        'picture_file',
        'fingerprint_hash',
        'is_blstrained',
        'recent_bls_date',
        'bls_id',
        'bls_typeofrescuer',
        'bls_codename',
        'duty_canbedeployed',
        'duty_canbedeployedagain',
        'duty_team',
        'duty_completedcycle',
        'duty_balance',

        'prc_license_no',
        'tin_no',
        'philhealth_pan',

        'abtc_vaccinator_branch',
        'systemuser_id',

        'emp_access_list',

        'created_by',
        'updated_by',
    ];

    public function getName() {
        $final = $this->lname.', '.$this->fname;

        if(!is_null($this->mname)) {
            $final = $final.' '.substr($this->mname,0,1).'.';
        }

        return $final;
    }

    public function getFullName() {
        $final = $this->lname.', '.$this->fname;

        if(!is_null($this->mname)) {
            $final = $final.' '.$this->mname;
        }

        return $final;
    }

    public function getNameWithPr() {
        $final = $this->fname;

        if(!is_null($this->mname)) {
            $final = $final.' '.substr($this->mname,0,1).'.';
        }

        $final = $final.' '.$this->lname;

        if(!is_null($this->profession_suffix)) {
            $final = $final.', '.$this->profession_suffix;
        }
        
        return $final;
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function getEmpAccessList() {
        return [
            'PHYSICIAN',
            'DENTIST',
            'MEDTECH',
            'ABTC_DOCTOR',
        ];
    }

    public static function getMedtechList() {
        $list = Employee::where('employment_status', 'ACTIVE')
        ->where('emp_access_list', 'LIKE', '%MEDTECH%')
        ->get();

        return $list;
    }

    public static function getMedtechListOnCurrentFacility() {
        //THIS RELIES ON DOH FACILITIES SYS_EMPLOYEE_CODENAME COLUMN

        $list = Employee::where('office', auth()->user()->opdfacility->sys_employee_codename)
        ->where('employment_status', 'ACTIVE')
        ->where('emp_access_list', 'LIKE', '%MEDTECH%')
        ->get();

        return $list;
    }

    public function ifAlreadyInEvent($event_id) {
        $check = HertDutyMember::where('event_id', $event_id)
        ->where('employee_id', $this->id)
        ->first();

        if(!$check) {
            return false;
        }
        else {
            return true;
        }
    }

    public function duties()
    {
        return $this->hasMany(HertDuty::class);
    }

    public function getLatestDuty() {
        $d = HertDutyMember::where('employee_id', $this->id)->latest()->first();

        return $d;
    }
}
