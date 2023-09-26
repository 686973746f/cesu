<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyPrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'finished',
        'concerns_list',
    ];

    public function pharmacypatient() {
        return $this->belongsTo(PharmacyPatient::class, 'patient_id');
    }
}
