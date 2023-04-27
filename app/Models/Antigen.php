<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Antigen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'antigenKitName',
        'antigenKitShortName',
        'lotNo',
        'isDOHAccredited',
        'tkc_code',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
