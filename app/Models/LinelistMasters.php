<?php

namespace App\Models;

use App\Models\LinelistSubs;
use function PHPSTORM_META\map;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LinelistMasters extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'dru',
        'laSallePhysician',
        'laSalleDateAndTimeShipment',
        'contactPerson',
        'contactTelephone',
        'contactMobile',
        'email',
        'laSallePreparedBy',
        'laSallePreparedByDate',
        'is_override',
        'time_started',
        'date_started',
        'is_locked',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function linelistsub() {
        return $this->hasMany(LinelistSubs::class);
    }

    public function getType() {
        if($this->type == 1) {
            return 'City of Imus Molecular Laboratory (CIML)';
        }
        else if($this->type == 2) {
            return 'LaSalle (CDCDC)';
        }
        else if($this->type == 3) {
            return 'City of Dasmariñas Molecular Diagnostic Laboratory (CDMDL)';
        }
    }
}
