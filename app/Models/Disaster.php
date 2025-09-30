<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'name',
        'event_type',
        'description',
        'city_id',
        'date_start',
        'date_end',
        'status',
        'hash',
        'created_by',
    ];

    public function city() {
        return $this->belongsTo(EdcsCity::class, 'city_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function evacuationCenters()
    {
        return $this->hasMany(EvacuationCenter::class, 'disaster_id');
    }
}
