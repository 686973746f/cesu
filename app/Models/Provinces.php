<?php

namespace App\Models;

use App\Models\Regions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provinces extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    protected $fillable = [
        'region_id',
        'provinceName',
        'json_code',
        'alt_name',
    ];

    public function region() {
        return $this->belongsTo(Regions::class);
    }

    public function getPsgcCode() {
        return $this->json_code.'00000';
    }
}
