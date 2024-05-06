<?php

namespace App\Models;

use App\Models\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brgy extends Model
{
    use HasFactory;

    protected $table = 'brgy';

    protected $fillable = [
        'user_id',
        'city_id',
        'brgyName',
        'displayInList',
        'json_code',
        'alt_name',
        'edcs_pw',
        'edcs_quicklogin_code',
        'edcs_lastlogin_date',
        'edcs_session_code',
        'edcs_ip',
    ];

    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }

    public function brgyCode() {
        return $this->hasMany(BrgyCodes::class);
    }

    public function isPoblacion() {
        if($this->brgyName == 'ARNALDO POB. (BGY. 7)' ||
        $this->brgyName == 'BAGUMBAYAN POB. (BGY. 5)' ||
        $this->brgyName == 'CORREGIDOR POB. (BGY. 10)' ||
        $this->brgyName == 'DULONG BAYAN POB. (BGY. 3)' ||
        $this->brgyName == 'GOV. FERRER POB. (BGY. 1)' ||
        $this->brgyName == 'NINETY SIXTH POB. (BGY. 8)' ||
        $this->brgyName == 'PRINZA POB. (BGY. 9)' ||
        $this->brgyName == 'SAMPALUCAN POB. (BGY. 2)' ||
        $this->brgyName == 'SAN GABRIEL POB. (BGY. 4)' ||
        $this->brgyName == 'VIBORA POB. (BGY. 6)') {
            return true;
        }
        else {
            return false;
        }
    }
}
