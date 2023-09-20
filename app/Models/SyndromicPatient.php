<?php

namespace App\Models;

use Carbon\Carbon;
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

        'shared_access_list',
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

    public function getContactNumber() {
        if($this->contact_number || $this->$this->contact_number2) {
            $txt = $this->contact_number;

            if(!is_null($this->contact_number2)) {
                return $txt.'/'.$this->contact_number2;
            }
            else {
                return $txt;
            }
        }
        else {
            return 'N/A';
        }
        
    }

    public function getFullAddress() {
        //will be used at MedCert
        return $this->address_houseno.', '.$this->address_street.', BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;
    }

    public function getBrgyId() {
        $get_province_id = Provinces::where('provinceName', $this->address_province_text)->pluck('id')->first();

        $get_city_id = City::where('province_id', $get_province_id)->where('cityName', $this->address_muncity_text)->pluck('id')->first();

        $get_brgy_id = Brgy::where('city_id', $get_city_id)->where('brgyName', $this->address_brgy_text)->pluck('id')->first();

        return $get_brgy_id;
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

    public function userHasPermissionToAccess() {
        if(in_array("GLOBAL_ADMIN", auth()->user()->getPermissions()) || in_array("ITR_ADMIN", auth()->user()->getPermissions()) || in_array("ITR_ENCODER", auth()->user()->getPermissions())) {
            return true;
        }
        else {
            if(auth()->user()->id == $this->created_by) {
                return true;
            }
            else if($this->getBrgyId() == auth()->user()->brgy_id) {
                return true;
            }
            else if(in_array(auth()->user()->id, explode(",", $this->shared_access_list))) {
                return true;
            }
            else {
                return false;
            }
        }
    }

    public function userHasPermissionToShareAccess() {
        if(in_array('GLOBAL_ADMIN', auth()->user()->getPermissions()) || in_array('ITR_ADMIN', auth()->user()->getPermissions()) || in_array('ITR_ENCODER', auth()->user()->getPermissions())) {
            return true;
        }
        else if(auth()->user()->id == $this->created_by) {
            return true;
        }
        else {
            return false;
        }
    }
}
