<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\School;
use App\Models\SbsPatient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SchoolBasedSurveillanceController extends Controller
{
    public function index($code) {
        $s = School::where('qr', $code)->first();

        if(!$s) {
            return abort(401);
        }

        return view('pidsr.sbs.index', [
            's' => $s,
        ]);
    }

    public function newCase($code) {
        $s = School::where('qr', $code)->first();

        if(!$s) {
            return abort(401);
        }

        return view('pidsr.sbs.new', [
            's' => $s,
        ]);
    }

    public function storeCase($code, Request $r) {
        $s = School::where('qr', $code)->first();

        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);
        $bdate = $r->bdate;

        $exist_check = SbsPatient::where('school_id', $s->id)
        ->where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate)
        ->whereDate('created_at', '>=', Carbon::now()->subDays(3)->format('Y-m-d'))
        ->first();

        if($exist_check) {
            return redirect()->back()
            ->with('msg', 'ERROR: Duplicate Patient Data already encoded to the system.')
            ->with('msgtype', 'warning');
        }

        $birthdate = Carbon::parse($r->bdate);
        $currentDate = Carbon::parse($r->date_reported);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $get_onset = Carbon::parse($r->onset_illness_date);

        $foundunique = false;

        while(!$foundunique) {
            $for_qr = Str::random(10);
            
            $search = SbsPatient::where('qr', $for_qr)->first();
            if(!$search) {
                $foundunique = true;
            }
        }

        $c = SbsPatient::create([
            'school_id' => $s->id,
            'date_reported' => $r->date_reported,
            
            'lname' => $lname,
            'fname' => $fname,
            'mname' => (!is_null($r->mname)) ? mb_strtoupper($r->mname) : NULL,
            'suffix' => (!is_null($r->suffix)) ? mb_strtoupper($r->suffix) : NULL,
            'sex' => $r->sex,
            'bdate' => $bdate,
            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,

            'patient_type' => $r->patient_type,
            'staff_designation' => ($r->patient_type == 'TEACHER' || $r->patient_type == 'STAFF') ? mb_strtoupper($r->staff_designation) : NULL,
            'grade_level' => ($r->patient_type == 'STUDENT') ? $r->grade_level : NULL,
            'section'  => ($r->patient_type == 'STUDENT') ? $r->section : NULL,
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->address_brgy_code,

            'contact_no' => $r->contact_no,
            //'guardian_name',
            //'guardian_contactno',
            'is_pwd' => $r->is_pwd,
            'pwd_condition' => ($r->pwd_condition == 'Y') ? mb_strtoupper($r->pwd_condition) : NULL,

            //'height',
            //'weight',
            //'bp_systolic',
            //'bp_diastolic',
            //'had_dinner_yesterday',
            //'had_breakfast_today',
            //'had_lunch_today',
            'onset_illness_date' => $r->onset_illness_date,
            'signs_and_symptoms' => implode(",", $r->signs_and_symptoms),
            'fever_temperature' => (in_array("FEVER", $r->signs_and_symptoms)) ? $r->fever_temperature : NULL,
            'signs_and_symptoms_others' => (in_array("OTHERS", $r->signs_and_symptoms)) ? mb_strtoupper($r->signs_and_symptoms_others) : NULL,
            'remarks' => $r->remarks,

            'reported_by' => mb_strtoupper($r->reported_by),
            'reported_by_position' => mb_strtoupper($r->reported_by_position),
            'reported_by_contactno' => $r->reported_by_contactno,

            //'enabled' 
            //'is_verified',
            //'is_sent',
            //'suspected_disease_tag',
            'report_year' => $get_onset->format('Y'),
            'report_month' => $get_onset->format('n'),
            'report_week' => $get_onset->format('W'),

            //'had_checkuponfacilityafter',
            //'name_facility',
            'qr' => $for_qr,
        ]);

        return redirect()->route('sbs_index', $s->qr)
        ->with('msg', 'Patient was successfully encoded.')
        ->with('msgtype', 'success');
    }

    public function initializeAccount($code, Request $r) {
        $validated = $r->validate([
            'email' => 'required|email|unique:schools,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $s = School::where('qr', $code)->first();

        if(!$s) {
            return abort(401);
        }

        $u = School::where('qr', $code)->update([
            'email' => $r->email,
            'password' => Hash::make($r->password),
        ]);

        return redirect()->route('sbs_index', $s->qr)
        ->with('msg', 'Account was successfully initialized. You may now login.')
        ->with('msgtype', 'success');
    }

    public function login(Request $r) {
        $credentials = $r->only('email', 'password');

        if (Auth::guard('school')->attempt($credentials)) {
            return redirect()->route('sbs_view');
        }

        return back()->withErrors(['email' => 'Invalid login credentials.']);
    }

    public function viewList($code) {
        $s = auth('school')->user();

        dd($s->id);
    }
}
