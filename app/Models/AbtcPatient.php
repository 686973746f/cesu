<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbtcPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'register_status',
        'referred_from',
        'referred_date',
        'enabled',
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'age',
        'gender',
        'is_pregnant',
        'contact_number',
        'philhealth',
        'philhealth_statustype',
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

        'is_indg',
        'is_4ps',
        'is_nhts',
        'is_seniorcitizen',
        'is_pwd',
        'is_singleparent',
        'is_others',
        'is_others_specify',

        'linkphilhealth_lname',
        'linkphilhealth_fname',
        'linkphilhealth_mname',
        'linkphilhealth_suffix',
        'linkphilhealth_sex',
        'linkphilhealth_bdate',
        'linkphilhealth_phnumber',
        'linkphilhealth_relationship',

        'linkphilhealth_hasjob',
        'linkphilhealth_businessname',
        'linkphilhealth_pen',
        'linkphilhealth_contactno',
        'linkphilhealth_employer_name',
        'linkphilhealth_employer_position',

        'remarks',
        'qr',

        'created_by',
        'updated_by',
        'ip',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
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

    public function getNameFormal() {
        $fullname = $this->fname;

        if(!is_null($this->mname)) {
            $fullname = $fullname." ".substr($this->mname,0,1).'.';
        }

        $fullname = $fullname." ".$this->lname;

        if(!is_null($this->suffix)) {
            $fullname = $fullname." ".$this->suffix;
        }

        return $fullname;
    }

    public function getNameFormalOfPhilhealthMember() {
        $fullname = $this->linkphilhealth_fname;

        if(!is_null($this->linkphilhealth_mname)) {
            $fullname = $fullname." ".substr($this->linkphilhealth_mname,0,1).'.';
        }

        $fullname = $fullname." ".$this->linkphilhealth_lname;

        if(!is_null($this->linkphilhealth_suffix)) {
            $fullname = $fullname." ".$this->linkphilhealth_suffix;
        }

        return $fullname;
    }

    public function getAddress() {
        if(!is_null($this->address_houseno) || !is_null($this->address_street)) {
            return $this->address_houseno.' '.$this->address_street.', BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;
        }
        else {
            return $this->getAddressMini();
        }
    }

    public function getAddressMini() {
        return 'BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;
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

    public function sg() {
        return substr($this->gender,0,1);
    }

    public function getAge() {
        /*
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
        */

        return $this->age;
    }

    public function getAgeInt() {
        if(!is_null($this->bdate)) {
            return Carbon::parse($this->attributes['bdate'])->age;
        }
        else {
            return $this->age;
        }
    }

    public static function ifDuplicateFound($lname, $fname, $mname, $suffix, $bdate) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));

        $check = AbtcPatient::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->whereDate('bdate', $bdate);

        if($mname == 'N/A') {
            $mname = NULL;
        }
        else {
            $mname = $mname;
        }

        if($suffix == 'N/A') {
            $suffix = NULL;
        }
        else {
            $suffix = $suffix;
        }

        if(!($check->first())) {
            if(!is_null($mname)) {
                $mname = mb_strtoupper(str_replace([' ','-'], '', $mname));
    
                $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), $mname);
            }
    
            if(!is_null($suffix)) {
                $suffix = mb_strtoupper(str_replace([' ','-'], '', $suffix));
    
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

        /*
        Old Codebase
        $check = AbtcPatient::where(function ($q) use ($lname, $fname, $mname, $suffix, $bdate) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
            ->where(function ($r) use ($mname) {
                $r->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)))
                ->orWhereNull('mname');
            })
            ->where(function ($r) use ($suffix) {
                $r->where(DB::raw("REPLACE(REPLACE(REPLACE(suffix,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-','.'], '', $suffix)))
                ->orWhereNull('suffix');
            });
        })
        ->first();

        if($check) {
            return $check;
        }
        else {
            return NULL;
        }
        */
    }

    public static function detectChangeName($lname, $fname, $mname, $suffix, $bdate, $id) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));

        $check = AbtcPatient::where('id', '!=', $id)
        ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->whereDate('bdate', $bdate);

        if($mname == 'N/A') {
            $mname = NULL;
        }
        else {
            $mname = $mname;
        }

        if($suffix == 'N/A') {
            $suffix = NULL;
        }
        else {
            $suffix = $suffix;
        }

        if(!($check->first())) {
            if(!is_null($mname)) {
                $mname = mb_strtoupper(str_replace([' ','-'], '', $mname));
    
                $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), $mname);
            }
    
            if(!is_null($suffix)) {
                $suffix = mb_strtoupper(str_replace([' ','-'], '', $suffix));
    
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
            return $check;
        }

        /*
        Old Duplicate Checking Codebase
        if(!is_null($mname)) {
            $check = AbtcPatient::where('id', '!=', $id)
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $mname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(suffix,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-','.'], '', $suffix)))
            ->first();

            if($check) {
                /*
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
                
                return $check;
            }
            else {
                $check1 = AbtcPatient::where('id', '!=', $id)
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $suffix)))
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
            $check = AbtcPatient::where('id', '!=', $id)
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
            ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $suffix)))
            ->whereNull('mname')
            ->first();
            
            if($check) {
                $checkwbdate = AbtcPatient::where('id', '!=', $id)
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $suffix)))
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
                $check1 = AbtcPatient::where('id', '!=', $id)
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $lname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $fname)))
                ->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), mb_strtoupper(str_replace([' ','-'], '', $suffix)))
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
        */
    }

    public function getCreatedBy() {
        if(!is_null($this->created_by)) {
            $a = User::find($this->created_by);
            
            return $a->name;
        }
        else {
            return 'N/A';
        }
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            $a = User::find($this->updated_by);
        
            return $a->name;
        }
        else {
            return 'N/A';
        }
    }

    public function icsGetPhilhealthStatusType() {
        if($this->getAge() <= 18) {
            return 'DEPENDENT';
        }
        else {
            return 'MEMBER';
        }
    }

    public function philhealthGetRelationshipToMember() {
        if($this->linkphilhealth_relationship == 'PARENT') {
            if($this->linkphilhealth_sex == 'M') {
                return 'FATHER';
            }
            else {
                return 'MOTHER';
            }
        }
        else if($this->linkphilhealth_relationship == 'CHILD') {
            return 'CHILD';
        }
        else if($this->linkphilhealth_relationship == 'SPOUSE') {
            return 'SPOUSE';
        }
    }

    public function cardPriority() {
        $str = [];

        if($this->is_pregnant == 'Y') {
            $str[] = 'BUNTIS';
        }

        if($this->is_seniorcitizen == 'Y') {
            $str[] = 'SENIOR';
        }

        if($this->is_pwd == 'Y') {
            $str[] = 'PWD';
        }

        if(!empty($str)) {
            return implode(',', $str);
        }
        else {
            return '';
        }
    }

    public function isPriority() {
        if($this->cardPriority() != '') {
            return true;
        }
        else {
            return false;
        }
    }

    public function isSeniorCitizen() {
        if($this->getAgeInt() >= 60) {
            return true;
        }
        else {
            return false;
        }
    }

    public function getPhilhealthMemberName() {
        $fullname = $this->linkphilhealth_fname;

        if(!is_null($this->linkphilhealth_mname)) {
            $fullname = $fullname." ".substr($this->linkphilhealth_mname,0,1).'.';
        }

        $fullname = $fullname." ".$this->linkphilhealth_lname;

        if(!is_null($this->linkphilhealth_suffix)) {
            $fullname = $fullname." ".$this->linkphilhealth_suffix;
        }

        return $fullname;
    }
}