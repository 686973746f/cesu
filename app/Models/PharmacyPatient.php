<?php

namespace App\Models;

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
        
        'concerns_list',
        'qr',

        'id_file',
        'selfie_file',

        'status',
        'status_remarks',

        'pharmacy_branch_id',
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

    public static function ifDuplicateFound($lname, $fname, $mname, $suffix, $bdate) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));

        $check = PharmacyPatient::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
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
            $suffix = mb_strtoupper(str_replace([' ','-'], '', $suffix));

            $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), $suffix)->first();
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
}
