<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dengue extends Model
{
    use HasFactory;

    protected $table = 'dengue';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];

    public function getEdcsFacilityName() {
        $s = DohFacility::where('healthfacility_code', $this->edcs_healthFacilityCode)->first();

        if($s) {
            return $s->facility_name;
        }
        else {
            return 'UNKNOWN';
        }
    }

    public function getName() {
        $full = $this->FamilyName.', '.$this->FirstName;

        if(!is_null($this->middle_name)) {
            $full = $full.' '.$this->middle_name;
        }

        if(!is_null($this->suffix)) {
            $full = $full.' '.$this->suffix;
        }

        return $full;
    }
}
