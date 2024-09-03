<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportJobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'for_module',
        'status',
        'date_finished',
        'filename',
        'created_by',
        'facility_id',
    ];
}
