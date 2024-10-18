<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdcsWeeklySubmissionChecker extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_name',
        'year',
        'week',
        'status',
        'waive_status',
        'waive_date',

        'abd_count',
        'afp_count',
        'ames_count',
        'hepa_count',
        'chikv_count',
        'cholera_count',
        'dengue_count',
        'diph_count',
        'hfmd_count',
        'ili_count',
        'lepto_count',
        'measles_count',
        'meningo_count',
        'nt_count',
        'nnt_count',
        'pert_count',
        'rabies_count',
        'rota_count',
        'sari_count',
        'typhoid_count',
    ];
}
