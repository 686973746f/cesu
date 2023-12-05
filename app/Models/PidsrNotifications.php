<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PidsrNotifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'disease',
        'disease_id',
        'message',
        'viewedby_id',
    ];
}
