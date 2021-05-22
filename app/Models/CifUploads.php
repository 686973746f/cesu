<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CifUploads extends Model
{
    use HasFactory;

    protected $fillable = [
        'forms_id',
        'file_type',
        'filepath',
        'remarks',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
