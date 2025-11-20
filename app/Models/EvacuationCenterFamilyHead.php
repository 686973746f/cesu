<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvacuationCenterFamilyHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'suffix',
        'nickname',
        'sex',
        //'is_pregnant',
        //'is_lactating',
        'bdate',
        'birthplace',
        'cs',
        'religion',
        'occupation',
        'mothermaiden_name',
        'monthlyfamily_income',
        //'is_pwd',
        'is_soloparent',
        'is_4ps',
        'is_indg',
        'indg_specify',
        'id_presented',
        'id_number',
        'id_file',
        'picture_file',
        'email',
        'contact_number',
        'contact_number2',
        'philhealth_number',
        'street_purok',
        'address_brgy_code',
        'house_ownership',
        'cswd_serialno',
        'dswd_serialno',
        'created_by',
        'updated_by',
        'hash',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            return date('m/d/Y h:i A', strtotime($this->updated_at)).' by '.User::find($this->updated_by)->name;
        }
        else {
            return 'N/A';
        }
    }

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'address_brgy_code');
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

    public function getAge() {
        return Carbon::parse($this->bdate)->age;
    }

    public function getNumberOfMembers() {
        return EvacuationCenterFamilyMember::where('familyhead_id', $this->id)->where('enabled', 'Y')->count();
    }
}
