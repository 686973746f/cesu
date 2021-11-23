<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrgyCodes extends Model
{
    use HasFactory;

    protected $fillable = [
        'brgy_id',
        'bCode',
        'adminType'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function brgy() {
        return $this->belongsTo(Brgy::class);
    }

    public function ifEnabled() {
        if($this->enabled == 0) {
            return false;
        }
        else {
            return true;
        }
    }

    public function getType() {
        if(!is_null($this->brgy_id)) {
            return 'Brgy. Account ('.$this->brgy->brgyName.')';
        }
        else {
            if($this->adminType == 1) {
                return 'CESU Admin';
            }
            else if($this->adminType == 2) {
                return 'CESU Staff (Encoder)';
            }
            else if($this->adminType == 3) {
                return 'Contact Tracer';
            }
            else if($this->adminType == 4) {
                return 'Facility Account';
            }
        }
    }
}
