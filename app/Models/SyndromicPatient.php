<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SyndromicPatient extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'suffix',
        'bdate',
        'gender',
        'cs',
        'contact_number',
        'contact_number2',
        'email',
        'philhealth',

        'spouse_name',
        'mother_name',
        'father_name',

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

        'ifminor_resperson',
        'ifminor_resrelation',
        
        'qr',
        'id_file',
        'selfie_file',
    ];

    public function user() {
        return $this->belongsTo(User::class);
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
}
