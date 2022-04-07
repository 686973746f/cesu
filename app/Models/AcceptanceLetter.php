<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcceptanceLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'suffix',
        'sex',
        'address_region_code',
        'address_region_text',
        'address_province_code',
        'address_province_text',
        'address_muncity_code',
        'address_muncity_text',
        'address_brgy_code',
        'address_brgy_text',
        'address_houseno',
        'travelto',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getName() {
        return $this->lname.", ".$this->fname." ".$this->mname.' '.$this->suffix;
    }

    public function getAddress() {
        return $this->address_houseno.', BRGY. '.$this->address_brgy_text.', '.$this->address_muncity_text.', '.$this->address_province_text;
    }
}
