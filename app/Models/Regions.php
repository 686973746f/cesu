<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    use HasFactory;

    protected $fillable = [
        'regionName',
        'json_code',
        'alt_name',
        'short_name1',
        'short_name2',
    ];

    public function getPsgcCode() {
        return $this->json_code.'0000000';
    }
}
