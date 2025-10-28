<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\School;
use App\Models\SbsPatient;
use App\Models\SchoolGradeLevel;
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

        if (Auth::guard('school')->check()) {
            return redirect()->route('sbs_list');
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

    public static function searchSuspectedCase($r) {
        $get_onset = Carbon::parse($r->onset_illness_date);
        $suspected_disease_list = [];
        $days_difference = $get_onset->diffInDays($r->date_reported);

        if($days_difference >= 2 && $days_difference <= 7) {
            $dengue_symp_count = 0;

            if(in_array('FEVER', $r->signs_and_symptoms)) {
                if(in_array('HEADACHE', $r->signs_and_symptoms)) {
                    $dengue_symp_count++;
                }

                if(in_array('BODY WEAKNESS', $r->signs_and_symptoms)) {
                    $dengue_symp_count++;
                }

                if(in_array('BODY PAIN', $r->signs_and_symptoms)) {
                    $dengue_symp_count++;
                }

                if(in_array('ANOREXIA', $r->signs_and_symptoms)) {
                    $dengue_symp_count++;
                }

                if(in_array('NAUSEA', $r->signs_and_symptoms)) {
                    $dengue_symp_count++;
                }

                if(in_array('VOMITING', $r->signs_and_symptoms)) {
                    $dengue_symp_count++;
                }

                if(in_array('DIARRHEA', $r->signs_and_symptoms)) {
                    $dengue_symp_count++;
                }

                if(in_array('RASH', $r->signs_and_symptoms)) {
                    $dengue_symp_count++;
                }

                if(in_array('NOSE BLEEDING', $r->signs_and_symptoms)) {
                    $dengue_symp_count++;
                }

                if($dengue_symp_count >= 2) {
                    $suspected_disease_list[] = 'DENGUE';
                }
            }
        }

        if($days_difference <= 10) {
            if(in_array('FEVER', $r->signs_and_symptoms) && $r->fever_temperature >= 38) {
                if(in_array('COUGH', $r->signs_and_symptoms) || in_array('SORE THROAT', $r->signs_and_symptoms)) {
                    $suspected_disease_list[] = 'INFLUENZA-LIKE ILLNESS';
                }
            }
        }

        if(in_array('FEVER', $r->signs_and_symptoms) && in_array('RASH', $r->signs_and_symptoms)) {
            if(in_array('RED EYES', $r->signs_and_symptoms) || in_array('COUGH', $r->signs_and_symptoms) || in_array('COLDS', $r->signs_and_symptoms)) {
                $suspected_disease_list[] = 'MEASLES';
            }
        }
        
        return $suspected_disease_list;
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
        ->whereDate('created_at', '>=', Carbon::now()->subDays(7)->format('Y-m-d'))
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
            'section'  => ($r->patient_type == 'STUDENT') ? mb_strtoupper($r->section) : NULL,
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->address_brgy_code,

            'contact_no' => $r->contact_no,
            //'guardian_name',
            //'guardian_contactno',
            'is_pwd' => $r->is_pwd,
            'pwd_condition' => ($r->is_pwd == 'Y') ? mb_strtoupper($r->pwd_condition) : NULL,

            //'height',
            //'weight',
            //'bp_systolic',
            //'bp_diastolic',
            //'had_dinner_yesterday',
            //'had_breakfast_today',
            //'had_lunch_today',
            'onset_illness_date' => $r->onset_illness_date,
            'signs_and_symptoms' => implode(", ", $r->signs_and_symptoms),
            'fever_temperature' => (in_array("FEVER", $r->signs_and_symptoms)) ? $r->fever_temperature : NULL,
            'signs_and_symptoms_others' => (in_array("OTHERS", $r->signs_and_symptoms)) ? mb_strtoupper($r->signs_and_symptoms_others) : NULL,
            'remarks' => $r->remarks,

            'reported_by' => mb_strtoupper($r->reported_by),
            'reported_by_position' => mb_strtoupper($r->reported_by_position),
            'reported_by_contactno' => $r->reported_by_contactno,
        
            'admitted' => $r->admitted,
            'date_admitted' => ($r->admitted == 'Y') ? $r->date_admitted : NULL,
            'admitted_facility' => ($r->admitted == 'Y' && !is_null($r->admitted_facility)) ? mb_strtoupper($r->admitted_facility) : NULL,

            //'enabled' 
            //'is_verified',
            //'is_sent',
            'suspected_disease_tag' => (!empty($this->searchSuspectedCase($r))) ? implode(", ", $this->searchSuspectedCase($r)) : NULL,
            'report_year' => $get_onset->format('Y'),
            'report_month' => $get_onset->format('n'),
            'report_week' => $get_onset->format('W'),

            //'had_checkuponfacilityafter',
            //'name_facility',
            'qr' => $for_qr,
        ]);

        if (Auth::guard('school')->check()) {
            return redirect()->route('sbs_list')
            ->with('msg', 'Patient was successfully encoded.')
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->route('sbs_index', $s->qr)
            ->with('msg', 'Patient was successfully encoded.')
            ->with('msgtype', 'success');
        }   
    }

    public function updateCase($id, Request $r) {
        $s = auth('school')->user();

        $d = SbsPatient::findOrFail($id);

        if($d->school_id != $s->id) {
            return redirect()->back()
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }
        
        $lname = mb_strtoupper($r->lname);
        $fname = mb_strtoupper($r->fname);
        $bdate = $r->bdate;

        $exist_check = SbsPatient::where('id', '!=', $d->id)
        ->where('school_id', $s->id)
        ->where('lname', $lname)
        ->where('fname', $fname)
        ->whereDate('bdate', $bdate)
        ->whereDate('created_at', '>=', Carbon::parse($d->created_at)->subDays(7)->format('Y-m-d'))
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

        $u = $d->update([
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
            'section'  => ($r->patient_type == 'STUDENT') ? mb_strtoupper($r->section) : NULL,
            'street_purok' => mb_strtoupper($r->street_purok),
            'address_brgy_code' => $r->address_brgy_code,

            'contact_no' => $r->contact_no,
            //'guardian_name',
            //'guardian_contactno',
            'is_pwd' => $r->is_pwd,
            'pwd_condition' => ($r->is_pwd == 'Y') ? mb_strtoupper($r->pwd_condition) : NULL,

            //'height',
            //'weight',
            //'bp_systolic',
            //'bp_diastolic',
            //'had_dinner_yesterday',
            //'had_breakfast_today',
            //'had_lunch_today',
            'onset_illness_date' => $r->onset_illness_date,
            'signs_and_symptoms' => implode(", ", $r->signs_and_symptoms),
            'fever_temperature' => (in_array("FEVER", $r->signs_and_symptoms)) ? $r->fever_temperature : NULL,
            'signs_and_symptoms_others' => (in_array("OTHERS", $r->signs_and_symptoms)) ? mb_strtoupper($r->signs_and_symptoms_others) : NULL,
            'remarks' => $r->remarks,

            'reported_by' => mb_strtoupper($r->reported_by),
            'reported_by_position' => mb_strtoupper($r->reported_by_position),
            'reported_by_contactno' => $r->reported_by_contactno,
        
            'admitted' => $r->admitted,
            'date_admitted' => ($r->admitted == 'Y') ? $r->date_admitted : NULL,
            'admitted_facility' => ($r->admitted == 'Y' && !is_null($r->admitted_facility)) ? mb_strtoupper($r->admitted_facility) : NULL,

            //'enabled' 
            //'is_verified',
            //'is_sent',
            'suspected_disease_tag' => (!empty($this->searchSuspectedCase($r))) ? implode(", ", $this->searchSuspectedCase($r)) : NULL,
            'report_year' => $get_onset->format('Y'),
            'report_month' => $get_onset->format('n'),
            'report_week' => $get_onset->format('W'),

            //'had_checkuponfacilityafter',
            //'name_facility',
        ]);

        return redirect()->route('sbs_list')
        ->with('msg', 'Patient was successfully updated.')
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

    public function viewList() {
        $s = auth('school')->user();

        $list = SbsPatient::where('school_id', $s->id)
        ->where('enabled', 'Y');

        if(request()->input('year')) {
            $list = $list->whereYear('created_at', request()->input('year'))
            ->get();
        }
        else {
            $list = $list->whereYear('created_at', date('Y'))
            ->get();
        }

        return view('pidsr.sbs.list', [
            's' => $s,
            'list' => $list,
        ]);
    }

    public function login(Request $r) {
        $credentials = $r->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('school')->attempt($credentials)) {
            $r->session()->regenerate();
            
            return redirect()->intended(route('sbs_list'));
        }

        return back()->withErrors(['email' => 'Invalid login credentials.']);
    }

    public function viewCase($id) {
        $d = SbsPatient::findOrFail($id);

        $s = auth('school')->user();

        if($d->school_id != $s->id) {
            return redirect()->back()
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }

        return view('pidsr.sbs.edit', [
            'd' => $d,
            's' => $s,
        ]);
    }

    public function adminPanel() {
        $list = School::where('enabled', 'Y')->get();

        return view('pidsr.sbs.admin.home', [
            'list' => $list,
        ]);
    }

    public function storeSchool(Request $r) {
        $foundunique = false;

        while(!$foundunique) {
            $for_qr = Str::random(7);
            
            $search = School::where('qr', $for_qr)->first();
            if(!$search) {
                $foundunique = true;
            }
        }

        $name = mb_strtoupper($r->name);

        $check = School::where('name', $name)->first();
        
        if($check) {
            return redirect()->back()
            ->with('msg', 'ERROR: School ['.$name.'] already exists in the system.')
            ->with('msgtype', 'warning');
        }

        $c = School::create([
            'name' => $name,
            'ownership_type' => $r->ownership_type,
            'school_type' => $r->school_type,
            'school_id' => $r->school_id,
            'address_brgy_code' => $r->address_brgy_code,
            'contact_number' => $r->contact_number,
            'contact_number_telephone' => $r->contact_number_telephone,
            'schoolhead_name' => mb_strtoupper($r->schoolhead_name),
            'schoolhead_position' => mb_strtoupper($r->schoolhead_position),
            'focalperson_name' => (!is_null($r->focalperson_name)) ? mb_strtoupper($r->focalperson_name) : NULL,

            'qr' => $for_qr,
        ]);

        return redirect()->back()
        ->with('msg', 'School ['.$c->name.'] was successfully created.')
        ->with('msgtype', 'success');
    }

    public function viewSchool($id) {
        $s = School::findOrFail($id);

        return view('pidsr.sbs.admin.view_school', [
            's' => $s,
        ]);
    }

    public function createLevel($school_id, Request $r) {
        $s = School::findOrFail($school_id);

        $level_name = mb_strtoupper($r->level_name);

        $check = SchoolGradeLevel::where('school_id', $s->id)
        ->where('level_name', $level_name)
        ->check();

        if($check) {
            return redirect()->back()
            ->with('msg', 'Error: School Grade Level already exists.')
            ->with('msgtype', 'warning');
        }

        $c = SchoolGradeLevel::create([
            'school_id' => $s->id,
            'type' => $s->type,
            'level_name' => $level_name,
        ]);

        return redirect()->back()
        ->with('msg', 'School Grade Level was successfully added.')
        ->with('msgtype', 'success');
    }

    public function viewLevel($level_id) {

    }

    public function createSection($level_id, Request $r) {

    }

    public function logout() {
        $s = auth('school')->user();
        
        Auth::guard('school')->logout();
        return redirect()->route('sbs_index', $s->qr)
        ->with('msg', 'You have been logged out.')
        ->with('msgtype', 'success');
    }
}
