<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ames extends Model
{
    //AMES
    
    use HasFactory;
    
    protected $table = 'ames';
    protected $primaryKey = 'EPIID';
    public $incrementing = false;

    public $guarded = [];

    public function getEdcsFacilityName() {
        if(!is_null($this->edcs_healthFacilityCode)) {
            $s = DohFacility::where('healthfacility_code', $this->edcs_healthFacilityCode)->first();

            if($s) {
                return $s->facility_name;
            }
            else {
                return 'UNKNOWN';
            }
        }
        else {
            return 'N/A';
        }
    }
}
