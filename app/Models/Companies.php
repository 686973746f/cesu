<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;

    protected $fillable = [
        'companyName',
        'contactNumber',
        'email',
        'loc_lotbldg',
        'loc_street',
        'loc_brgy',
        'loc_city',
        'loc_cityjson',
        'loc_province',
        'loc_provincejson',
        'loc_region',
        'loc_regionjson',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
