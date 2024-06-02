<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        
        'status',
        'description',
        'has_duration',
        'until',
        'grabbed_by',
        'grabbed_date',
        'transferred_to',
        'transferred_date',
        'finished_by',
        'finished_date',
        'remarks',
        
        'encodedcount_enable',
        'encodedcount',
        'has_tosendimageproof',
        'proof_image',
    ];

    public function grabbedBy() {
        return $this->belongsTo(User::class, 'grabbed_by');
    }

    public function finishedBy() {
        return $this->belongsTo(User::class, 'finished_by');
    }

    public function transferredTo() {
        return $this->belongsTo(User::class, 'transferred_to');
    }

    public function areYouTheOwner() {
        if($this->grabbed_by == Auth::id() || $this->transferred_to == Auth::id()) {
            return true;
        }
        else {
            return false;
        }
    }
}
