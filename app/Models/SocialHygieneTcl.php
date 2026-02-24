<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialHygieneTcl extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'address_brgy_code',

        'r_preg_syphilis_a',
        'nr_preg_syphilis_a',
        'treated_preg_syphilis_a',
        'r_preg_hiv_a',
        'nr_preg_hiv_a',
        'r_preg_hepab_a',
        'nr_preg_hepab_a',

        'r_preg_syphilis_b',
        'nr_preg_syphilis_b',
        'treated_preg_syphilis_b',
        'r_preg_hiv_b',
        'nr_preg_hiv_b',
        'r_preg_hepab_b',
        'nr_preg_hepab_b',

        'r_preg_syphilis_c',
        'nr_preg_syphilis_c',
        'treated_preg_syphilis_c',
        'r_preg_hiv_c',
        'nr_preg_hiv_c',
        'r_preg_hepab_c',
        'nr_preg_hepab_c',

        'request_uuid',
    ];

    public function brgy() {
        return $this->belongsTo(EdcsBrgy::class, 'address_brgy_code');
    }
}
