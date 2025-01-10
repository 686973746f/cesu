<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlsMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'cho_employee',
        'employee_id',
        'lname',
        'fname',
        'mname',
        'provider_type',
        'position',
        'institution',
        'employee_type',
        'bdate',
        'street_purok',
        'address_brgy_code ',
        'email',
        'contact_number',
        'codename',
        'sfa_pretest',
        'sfa_posttest',
        'sfa_remedial',
        'sfa_ispassed',
        'sfa_notes',
        'bls_pretest',
        'bls_posttest',
        'bls_remedial',
        'bls_cognitive_ispassed',
        'bls_cpr_adult',
        'bls_cpr_infant',
        'bls_fbao_adult',
        'bls_fbao_infant',
        'bls_rb_adult',
        'bls_rb_infant',
        'bls_psychomotor_ispassed',
        'bls_affective',
        'bls_finalremarks',
        'bls_notes',
        'bls_id_number',
        'sfa_id_number',
        'bls_expiration_date',
        'picture',
    ];
}
