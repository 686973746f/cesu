<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HertDutyPatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'event_id',

        'lname',
        'fname',
        'mname',
        'age_years',
        'sex',

        'contact_number',
        'street_purok',
        'address_brgy_code',

        'chief_complaint',
        'other_complains',
        'bp',
        'lastmeal_taken',
        'diagnosis',
        'actions_taken',
        'remarks',
        'created_by',
    ];

    public function getName() {
        $final = $this->lname.', '.$this->fname;

        if(!is_null($this->mname)) {
            $final = $final.' '.substr($this->mname,0,1).'.';
        }

        return $final;
    }

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'address_brgy_code');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function listDiagnosis() {
        $complaints = explode(',', $this->chief_complaint);
        $complaints = array_map('trim', $complaints);

        foreach ($complaints as &$complaint) {
            if ($complaint === 'OTHERS') {
                $complaint = 'OTHERS (' . $this->other_complains . ')';
            }
        }

        return implode(', ', $complaints);
    }
}
