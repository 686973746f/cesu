<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbtcVaccinationSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'enabled',
        'referral_code',
        'sched_days',
        'new_start',
        'new_end',
        'ff_start',
        'ff_end',
        'new_and_ff_time_same',
        'facility_type',

        'ph_facility_name',
        'ph_facility_code',
        'ph_address_houseno',
        'ph_doh_certificate',
        'ph_professional1_id',
        'ph_professional2_id',
        'ph_professional3_id',
        'ph_head_id',
        'ph_accountant_name_position',
    ];

    public function getHeadSignatory() {
        return $this->belongsTo(Employee::class, 'ph_head_id');
    }

    public function getProfessional1() {
        return $this->belongsTo(Employee::class, 'ph_professional1_id');
    }

    public function getProfessional2() {
        return $this->belongsTo(Employee::class, 'ph_professional2_id');
    }

    public function getProfessional3() {
        return $this->belongsTo(Employee::class, 'ph_professional3_id');
    }
}
