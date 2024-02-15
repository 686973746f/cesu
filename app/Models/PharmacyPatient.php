<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\PharmacyCartMain;
use App\Models\SyndromicRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacyPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'gender',
        'contact_number',
        'contact_number2',
        'email',
        'philhealth',

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
        
        //'concerns_list',
        'qr',
        'global_qr',

        'id_file',
        'selfie_file',

        'status',
        'status_remarks',
        'from_outside',
        'outside_name',

        'is_lgustaff',
        'lgu_office_name',

        'itr_id',
        'pharmacy_branch_id',
        'created_by',
        'updated_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            return $this->belongsTo(User::class, 'updated_by');
        }
        else {
            return NULL;
        }
    }

    public static function getReasonList() {
        $array = [
            'ACCIDENT/INJURIES/WOUNDS',
            'CHILDREN',
            'COLDS',
            'DIABETES',
            'DERMA/SKIN PROBLEM',
            'FAMILY PLANNING',
            'FEVER/HEADACHE',
            'HYPERTENSION/HEART/HIGH CHOLESTEROL',
            'IMMUNE DEFICIENCY',
            'IMMUNIZATION',
            'INFECTION',
            'KIDNEY PROBLEM',
            'LIVER PROBLEM',
            'MENTAL HEALTH',
            'MICROBIAL INFECTIONS',
            'MILD/SEVERE PAIN',
            'MUSCLE PROBLEM',
            'NERVES PROBLEM',
            'RESPIRATORY PROBLEM',
            'TB-DOTS',
            'WOMEN',
            'OTHERS',
            'DIALYSIS',
            //'FEVER',
            'GIT',
            'URIC ACID',
            'VERTIGO/DIZZY',
            //'HEADACHE',
            'UTI',
            'TOOTH ACHE',
            'INSOMIA',
            'CHEMOTHERAPHY/CANCER',
            'ALLERGY',
            'DIARRHEA',
            'PREGNANT',
            'ELECTROLYTES DEFFICIENT',
            'ASTHMA',
            'BLEEDING',
            'ANTICOAGULANT',
            'FUNGAL INFECTION',
            'ANTI-AMOEBA',
            'DIURETIC',
            'CORTICOSTEROIDS',
            'ANESTHESIA',
            'EAR INFECTION',
        ];

        sort($array);

        return $array;
    }

    public function pharmacybranch() {
        return $this->belongsTo(PharmacyBranch::class, 'pharmacy_branch_id');
    }

    public function getLatestPrescription() {
        $data = PharmacyPrescription::where('patient_id', $this->id)
        ->where('finished', 0)
        ->latest()
        ->first();

        return $data;
    }

    public function getPendingCartMain() {
        $data = PharmacyCartMain::where('patient_id', $this->id)
        ->where('status', 'PENDING')
        ->where('prescription_id', $this->getLatestPrescription()->id)
        ->where('branch_id', auth()->user()->pharmacy_branch_id)
        ->latest()
        ->first();

        return $data;
    }

    public function getName() {
        $fullname = $this->lname.", ".$this->fname;

        if(!is_null($this->mname)) {
            $fullname = $fullname." ".$this->mname;
        }

        if(!is_null($this->suffix)) {
            $fullname = $fullname." ".$this->suffix;
        }

        return $fullname;
        //return $this->lname.", ".$this->fname.' '.$this->suffix." ".$this->mname;
    }

    public function sg() {
        return substr($this->gender,0,1);
    }

    public function getAge() {
        if(!is_null($this->bdate)) {
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
        else {
            return $this->age;
        }
    }

    public function getAgeInt() {
        return Carbon::parse($this->bdate)->age;
    }

    public function getStreetPurok() {
        if($this->address_houseno && $this->address_street) {
            $get_txt = $this->address_houseno.', '.$this->address_street;
        }
        else if($this->address_houseno || $this->address_street) {
            if($this->address_houseno) {
                $get_txt = $this->address_houseno;
            }
            else if($this->address_street) {
                $get_txt = $this->address_street;
            }
        }
        else {
            $get_txt = 'N/A';
        }
        
        return $get_txt;
    }

    public function getCompleteAddress() {
        $get_txt = '';

        if($this->address_houseno || $this->address_street) {
            $get_txt = $this->getStreetPurok();
        }
        
        $get_txt = $get_txt.' BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;

        return $get_txt;
    }

    public static function ifDuplicateFound($lname, $fname, $mname, $suffix, $bdate) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));

        $check = PharmacyPatient::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->whereDate('bdate', $bdate);

        if(!($check->first())) {
            if(!is_null($mname)) {
                $mname = mb_strtoupper(str_replace([' ','-'], '', $mname));
    
                $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), $mname);
            }
    
            if(!is_null($suffix)) {
                $suffix = mb_strtoupper(str_replace([' ','-','.'], '', $suffix));
    
                $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(suffix,'.',''),'-',''),' ','')"), $suffix)->first();
            }
            else {
                $check = $check->first();
            }
    
            if($check) {
                return $check;
            }
            else {
                return NULL;
            }
        }
        else {
            return $check->first();
        }
    }

    public static function ifDuplicateFoundOnUpdate($id, $lname, $fname, $mname, $suffix, $bdate) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));

        $check = PharmacyPatient::where('id', '!=', $id)
        ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->whereDate('bdate', $bdate);

        if(!is_null($mname)) {
            $mname = mb_strtoupper(str_replace([' ','-'], '', $mname));

            $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), $mname);
        }

        if(!is_null($suffix)) {
            $suffix = mb_strtoupper(str_replace([' ','-','.'], '', $suffix));

            $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(suffix,'.',''),'-',''),' ','')"), $suffix)->first();
        }
        else {
            $check = $check->first();
        }

        if($check) {
            return $check;
        }
        else {
            return NULL;
        }
    }

    public function getLatestItr() {
        if(!is_null($this->itr_id)) {
            $s = SyndromicRecords::where('syndromic_patient_id', $this->itr_id)
            ->latest()
            ->first();

            if($s) {
                return $s;
            }
            else {
                return NULL;
            }
        }
        else {
            return NULL;
        }
    }
}
