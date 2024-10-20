<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EdcsWeeklySubmissionChecker extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_name',
        'year',
        'week',
        'status',
        'type',
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

        'excel_file',
    ];

    public static function getSubmissionType() {
        $currentDay =  Carbon::now()->subWeek(1);

        if(request()->input('mw') && request()->input('year')) {
            $input_mw = request()->input('mw');
            $input_year = request()->input('year');
        }
        else {
            $input_mw = $currentDay->format('W');
            $input_year = $currentDay->format('Y');
        }

        if($input_year == $currentDay->format('Y')) {
            if($input_mw == $currentDay->format('W')) {
                if($currentDay->dayOfWeek == Carbon::MONDAY) {
                    return 'CURRENT_WEEK';
                }
                else {
                    if($currentDay->dayOfWeek == 0) {
                        return 'EARLY_CURRENT_WEEK';
                        //return 'CURRENT_WEEK';
                    }
                    else {
                        return 'LATE_CURRENT_WEEK';
                    }
                }
            }
            else if($input_mw > $currentDay->format('W')) {
                return abort(401);
            }
            else {
                return 'LATE';
            }
        }
        else {
            if($input_year > $currentDay->format('Y')) {
                return abort(401);
            }
            else {
                return 'LATE';
            }
        }
    }
}
