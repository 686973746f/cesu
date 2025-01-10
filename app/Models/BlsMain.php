<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlsMain extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'batch_name',
        'is_refresher',
        'agency',
        'training_date_start',
        'training_date_end',
        'venue',
        'instructors_list',
        'prepared_by',
        'created_by',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
