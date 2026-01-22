<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InhouseFamilySerial extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'inhouse_householdno',
        'inhouse_familyserialno',
        'ics_householdno',
        'ics_familyserialno',
    ];

    public function patient() {
        return $this->belongsTo(SyndromicPatient::class, 'patient_id');
    }
}
