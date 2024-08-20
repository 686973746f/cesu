<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRelatedEventMain extends Model
{
    use HasFactory;

    protected $fillable = [
        'enabled',
        'event_name',
        'facility_id',
        'description',
        'qr',
    ];
}
