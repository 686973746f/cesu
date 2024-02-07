<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PregnancyTrackingForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'catchment_brgy',
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'age',
        'street_purok',
        'lmp',
        'edc',

        'pc_done1_check',
        'pc_done2_check',
        'pc_done3_check',
        'pc_done4_check',

        'pc_done1',
        'pc_done2',
        'pc_done3',
        'pc_done4',

        'wht_in_charge',
        'midwife_name',
        'duty_station',
        'address1',
        'referral_unit',
        'address2',
        'address3',
        
        'outcome',
        'accomplished_by',
    ];

    public function getNameFormal() {
        $str =  $this->lname.', '.$this->fname;

        if(!is_null($this->mname)) {
            return $str.' '.$this->mname;
        }
        else {
            return $str;
        }
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function ifDuplicateFound($lname, $fname, $mname) {
        $lname = mb_strtoupper(str_replace([' ','-'], '', $lname));
        $fname = mb_strtoupper(str_replace([' ','-'], '', $fname));

        $check = PregnancyTrackingForm::where(DB::raw("REPLACE(REPLACE(REPLACE(lname,'.',''),'-',''),' ','')"), $lname)
        ->where(function($q) use ($fname) {
            $q->where(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), $fname)
            ->orWhere(DB::raw("REPLACE(REPLACE(REPLACE(fname,'.',''),'-',''),' ','')"), 'LIKE', "$fname%");
        })
        ->whereYear('created_at', date('Y'));

        if(!($check->first())) {
            if(!is_null($mname)) {
                $mname = mb_strtoupper(str_replace([' ','-'], '', $mname));
    
                $check = $check->where(DB::raw("REPLACE(REPLACE(REPLACE(mname,'.',''),'-',''),' ','')"), $mname);
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
}
