<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringSheetMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'forms_id',
        'region',
        'ccname',
        'date_lastexposure',
        'date_endquarantine',
        'magicURL',
    ];

    public function forms() {
        return $this->belongsTo(Forms::class);
    }
}
