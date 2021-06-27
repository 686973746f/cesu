<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Forms;
use App\Models\LinelistSubs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Records extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'lname',
        'fname',
        'mname',
        'gender',
        'isPregnant',
        'cs',
        'nationality',
        'bdate',
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
        'permaaddressDifferent',
        'permaaddress_houseno',
        'permaaddress_street',
        'permaaddress_brgy',
        'permaaddress_city',
        'permaaddress_cityjson',
        'permaaddress_province',
        'permaaddress_provincejson',
        'permamobile',
        'permaphoneno',
        'permaemail',
        'hasOccupation',
        'occupation',
        'worksInClosedSetting',
        'occupation_lotbldg',
        'occupation_street',
        'occupation_brgy',
        'occupation_city',
        'occupation_cityjson',
        'occupation_province',
        'occupation_provincejson',
        'occupation_name',
        'occupation_mobile',
        'occupation_email',
        
        'natureOfWork',
        'natureOfWorkIfOthers',
    ];

    public function getAge() {
        if(Carbon::parse($this->attributes['bdate'])->age > 0) {
            return Carbon::parse($this->attributes['bdate'])->age;
        }
        else {
            return Carbon::parse($this->attributes['bdate'])->diff(\Carbon\Carbon::now())->format('%m MOS');
        }
    }

    public function getName() {
        return $this->lname.", ".$this->fname." ".$this->mname;
    }

    public function form(){
        return $this->hasMany(Forms::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function linelistsub() {
        return $this->hasMany(LinelistSubs::class);
    }
}
