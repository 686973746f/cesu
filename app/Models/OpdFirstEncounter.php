<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdFirstEncounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'year',
        'date_of_first_encounter',
        'philhealth_pcu',
    ];

    public function patient() {
        return $this->belongsTo(SyndromicPatient::class, 'patient_id');
    }
}
