<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondaryTertiaryRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'morbidityMonth',
        'dateReported',
        'lname',
        'fname',
        'mname',
        'gender',
        'bdate',
        'email',
        'mobile',
        'address_houseno',
        'address_street',
        'address_brgy',
        'address_city',
        'address_cityjson',
        'address_province',
        'address_provincejson',
        'temperature',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getName() {
        return $this->lname.', '.$this->fname.' '.$this->mname;
    }
}
