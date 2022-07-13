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
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function linelistsub() {
        return $this->hasMany(LinelistSubs::class);
    }
}
