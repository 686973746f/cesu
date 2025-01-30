<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlsMain extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'batch_name',
        'is_refresher',
        'agency',
        'training_date_start',
        'training_date_end',
        'venue',
        'instructors_list',
        'prepared_by',
        'created_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getUpdatedBy() {
        if(!is_null($this->updated_by)) {
            return $this->belongsTo(User::class, 'updated_by');
        }
        else {
            return NULL;
        }
    }

    public function getTotalParticipantsCount() {
        $c = BlsBatchParticipant::where('batch_id', $this->id)->count();

        return $c;
    }

    public function getTotalParticipantsCountMale() {
        $c = BlsBatchParticipant::where('batch_id', $this->id)
        ->whereHas('member', function ($q) {
            $q->where('gender', 'M');
        })
        ->count();

        return $c;
    }

    public function getTotalParticipantsCountFemale() {
        $c = BlsBatchParticipant::where('batch_id', $this->id)
        ->whereHas('member', function ($q) {
            $q->where('gender', 'F');
        })
        ->count();

        return $c;
    }

    public function getTotalParticipantsCountLr() {
        $c = BlsBatchParticipant::where('batch_id', $this->id)
        ->whereHas('member', function ($q) {
            $q->where('provider_type', 'LR');
        })
        ->count();

        return $c;
    }

    public function getTotalParticipantsCountHcp() {
        $c = BlsBatchParticipant::where('batch_id', $this->id)
        ->whereHas('member', function ($q) {
            $q->where('provider_type', 'HCP');
        })
        ->count();

        return $c;
    }

    public function getTotalParticipantsPassedCount() {
        $c = BlsBatchParticipant::where('batch_id', $this->id)
        ->where('bls_finalremarks', 'P')
        ->count();

        return $c;
    }
}
