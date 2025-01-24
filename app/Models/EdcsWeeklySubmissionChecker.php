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
        'consider_submitted_override',
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
        if(request()->input('mw') && request()->input('year')) {
            $input_mw = request()->input('mw');
            $input_year = request()->input('year');

            $currentDay = Carbon::now();
        }
        else {
            if(date('W') == 02) {
                $currentDay = Carbon::now();

                $input_mw = $currentDay->clone()->subWeek(1)->format('W');
                $input_year = $currentDay->clone()->subDay(1)->format('Y');
            }
            else {
                $currentDay =  Carbon::now()->subWeek(1);

                $input_mw = $currentDay->format('W');
                $input_year = $currentDay->format('Y');
            }
        }

        if($input_year == $currentDay->format('Y')) {
            if($input_mw == $currentDay->format('W')) {
                if($currentDay->dayOfWeek == Carbon::MONDAY) {
                    return 'CURRENT_WEEK';
                }
                else {
                    /*
                    if($currentDay->dayOfWeek == 0) {
                        return 'EARLY_CURRENT_WEEK';
                        //return 'CURRENT_WEEK';
                    }
                    else {
                        
                    }
                    */

                    return 'LATE_CURRENT_WEEK';
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

    public function getAlreadySubmittedTypeFunction() {
        if(Carbon::now()->setISODate($this->year, $this->week)->addWeek(1)->isSameDay(Carbon::parse($this->created_at))) {
            return 'SUBMITTED_ONTIME';
        }
        else {
            if($this->type == 'AUTO') {
                if($this->status == 'SUBMITTED') {
                    return 'AUTOSUBMIT_BUT_NOREPORT';
                }
                else {
                    if(!is_null($this->waive_status)) {
                        return 'SUBMITTED_BUT_LATE';
                    }
                    else {
                        return 'AUTO_NO_SUBMISSION';
                    }
                }
            }
            else {
                return 'SUBMITTED_BUT_LATE';
            }
        }
    }

    public static function getAlreadySubmittedType($facility_code) {
        $f = DohFacility::where('sys_code1', $facility_code)->first();

        if(request()->input('mw') && request()->input('year')) {
            $input_mw = request()->input('mw');
            $input_year = request()->input('year');
        }
        else {
            if(date('W') == 02) {
                $currentDay = Carbon::now();

                $input_mw = $currentDay->clone()->subWeek(1)->week;
                $input_year = $currentDay->clone()->subDay(1)->format('Y');
            }
            else {
                $currentDay =  Carbon::now()->subWeek(1);

                $input_mw = $currentDay->week;
                $input_year = $currentDay->format('Y');
            }
        }

        $d = EdcsWeeklySubmissionChecker::where('facility_name', $f->facility_name)
        ->where('year', $input_year)
        ->where('week', $input_mw)
        ->first();

        if($d) {
            if(Carbon::parse($d->created_at)->dayOfWeek == Carbon::MONDAY) {
                return 'SUBMITTED_ONTIME';
            }
            else {
                if($d->type == 'AUTO') {
                    if($d->status == 'SUBMITTED') {
                        return 'AUTOSUBMIT_BUT_NOREPORT';
                    }
                    else {
                        if(!is_null($d->waive_status)) {
                            return 'SUBMITTED_BUT_LATE';
                        }
                        else {
                            return 'AUTO_NO_SUBMISSION';
                        }
                    }
                }
                else {
                    return 'SUBMITTED_BUT_LATE';
                }
            }
        }
        else {
            if(Carbon::now()->dayOfWeek == Carbon::MONDAY) {
                return 'NOTYET_SUBMITTED_ONTIME';
            }
            else {
                return 'EMPTY_LATE';
            }
        }
    }
}
