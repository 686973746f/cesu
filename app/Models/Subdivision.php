<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdivision extends Model
{
    use HasFactory;

    protected $fillable = [
        'displayInList',
        'brgy_id',
        'subdName',
        'type',
        'total_projectarea',
        'total_lotsunits',
        'numof_population',
        'numof_household',
        'dilgCustCode',
        'gps_x',
        'gps_y',
        'user_id',
    ];

    public function brgy() {
        return $this->belongsTo(Brgy::class, 'brgy_id');
    }
}
