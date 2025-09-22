<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvacuationCenterFamilyMembersInside extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_registered',
    ];
}
