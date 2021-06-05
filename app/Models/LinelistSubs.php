<?php

namespace App\Models;

use App\Models\Records;
use App\Models\LinelistMasters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LinelistSubs extends Model
{
    use HasFactory;

    protected $fillable = [
        'linelist_masters_id',
        'specNo',
        'dateAndTimeCollected',
        'accessionNo',
        'records_id',
        'remarks',
        'oniSpecType',
        'oniReferringHospital'
    ];

    public function linelistmaster() {
        return $this->belongsTo(LinelistMasters::class);
    }

    public function records() {
        return $this->belongsTo(Records::class);
    }
}
