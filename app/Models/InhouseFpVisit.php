<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InhouseFpVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'fp_tcl_id',
        'client_type',
        'method_used',
        'visit_date_estimated',
        'visit_date_actual',
        'status',
        'is_permanent',
        'is_visible',

        'dropout_date',
        'dropout_reason',
        
        'created_by',
        'updated_by',
        'age_years',
        'age_months',
        'age_days',
        'request_uuid',
    ];
    

    public function familyplanning() {
        return $this->belongsTo(InhouseFamilyPlanning::class, 'fp_tcl_id');
    }

    public function ifEligibleForUpdate() {
        //Check if visit_date_estimated is 1 week before the current date
        /*
        if(Carbon::parse($this->visit_date_estimated)->subDays(7)->lte(Carbon::now())) {
            return true;
        }
        else {
            return false;
        }
        */

        return true;
    }

    public function getClientType() {
        return $this->familyplanning->getClientType($this->client_type);
    }

    public function getMethod($method_code) {
        return $this->familyplanning->getMethod($method_code);
    }
}
