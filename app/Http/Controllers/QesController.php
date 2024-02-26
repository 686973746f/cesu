<?php

namespace App\Http\Controllers;

use App\Models\QesMain;
use App\Models\QesSub;
use Illuminate\Http\Request;

class QesController extends Controller
{
    public function index() {
        $main_list = QesMain::orderBy('created_by', 'DESC')->paginate(10);

        return view('qes.home', [
            'main_list' => $main_list,
        ]);
    }

    public function storeMain(Request $r) {
        $s = QesMain::where('name', mb_strtoupper($r->name))->first();

        if($s) {
            return redirect()->back()
            ->withInput()
            ->with('msg', 'Error: Case name already exists in the system.')
            ->with('msgtype', 'warning');
        }
        else {
            $c = $r->user()->qesmain()->create([
                'name' => mb_strtoupper($r->name),
                'description' => $r->description,
            ]);

            return redirect()->route('qes_view_main', $c->id)
            ->with('msg', 'QES Case successfully created. You may now encode patient details inside the case.')
            ->with('msgtype', 'success');
        }
    }

    public function viewMain($main_id) {
        $d = QesMain::findOrFail($main_id);

        return view('qes.viewmain', [
            'd' => $d,
        ]);
    }

    public function newRecord($main_id) {

    }

    public function storeRecord($main_id, Request $r) {
        //Search Existing Patient on the Main
        $s = QesSub::where('lname', mb_strtoupper($r->lname))
        ->where('fname', mb_strtoupper($r->fname))
        ->first();

        if($s) {
            
        }

        $r->user()->qessub()->create([
            'qes_main_id' => $main_id,
            'lname' => mb_strtoupper($r->lname),
            'fname' => mb_strtoupper($r->fname),
            'mname' => ($r->filled('mname')) ? mb_strtoupper($r->mname) : NULL,
            'suffix' => ($r->filled('suffix')) ? mb_strtoupper($r->suffix) : NULL,
            'age' => $r->age,
            'sex' => $r->gender,
            'contact_number' => $r->contact_number,
            'address_region_code' => $r->address_region_code,
            'address_region_text' => $r->address_region_text,
            'address_province_code' => $r->address_province_code,
            'address_province_text' => $r->address_province_text,
            'address_muncity_code' => $r->address_muncity_code,
            'address_muncity_text' => $r->address_muncity_text,
            'address_brgy_code' => $r->address_brgy_text,
            'address_brgy_text' => $r->address_brgy_text,
            'address_street' => ($r->filled('address_street')) ? mb_strtoupper($r->address_street) : NULL,
            'address_houseno' => ($r->filled('address_houseno')) ? mb_strtoupper($r->address_houseno) : NULL,
            'occupation' => ($r->filled('occupation')) ? mb_strtoupper($r->occupation) : NULL,
            'placeof_work_school' => ($r->filled('placeof_work_school')) ? mb_strtoupper($r->placeof_work_school) : NULL,

            'has_symptoms' => $r->has_symptoms,
            'onset_datetime' => ($r->has_symptoms == 'Y') ? $r->onset_datetime : NULL,
            'illness_duration' => ($r->has_symptoms == 'Y') ? $r->illness_duration : NULL,
            'diagnosis_date' => ($r->has_symptoms == 'Y') ? $r->diagnosis_date : NULL,
            'hospitalized' => ($r->has_symptoms == 'Y') ? $r->hospitalized : NULL,
            'admission_date'=> ($r->has_symptoms == 'Y' && $r->hospitalized == 'Y') ? $r->admission_date : NULL,
            'discharge_date' => ($r->has_symptoms == 'Y' && $r->hospitalized == 'Y') ? $r->discharge_date : NULL,
            'hospital_name' => ($r->has_symptoms == 'Y' && $r->hospitalized == 'Y') ? mb_strtoupper($r->hospital_name) : NULL,
            'outcome' => $r->outcome,
            'lbm_3xday' => ($r->has_symptoms == 'Y') ? $r->lbm_3xday : NULL,
            'fever' => ($r->has_symptoms == 'Y') ? $r->fever : NULL,
            'nausea' => ($r->has_symptoms == 'Y') ? $r->nausea : NULL,
            'vomiting' => ($r->has_symptoms == 'Y') ? $r->vomiting : NULL,
            'bodyweakness' => ($r->has_symptoms == 'Y') ? $r->bodyweakness : NULL,
            'abdominalcramps' => ($r->has_symptoms == 'Y') ? $r->abdominalcramps : NULL,
            'rectalpain' => ($r->has_symptoms == 'Y') ? $r->rectalpain : NULL,
            'tenesmus' => ($r->has_symptoms == 'Y') ? $r->tenesmus : NULL,
            'bloodystool' => ($r->has_symptoms == 'Y') ? $r->bloodystool : NULL,
            'brownish' => ($r->has_symptoms == 'Y') ? $r->brownish : NULL,
            'yellowish' => ($r->has_symptoms == 'Y') ? $r->yellowish : NULL,
            'greenish' => ($r->has_symptoms == 'Y') ? $r->greenish : NULL,
            'others' => ($r->has_symptoms == 'Y') ? $r->others : NULL,
            'others_specify' => ($r->has_symptoms == 'Y' && $r->others == 'Y') ? mb_strtoupper($r->others_specify) : NULL,
            'volumeofstool' => ($r->has_symptoms == 'Y') ? $r->volumeofstool : NULL,
            'quantify' => ($r->has_symptoms == 'Y') ? $r->quantify : NULL,

            //'other_affected_names',
            //'other_affected_ages',
            //'other_affected_sex',
            //'other_affected_donset',

            'question1' => $r->question1,
            'question2' => $r->question2,
            'question3' => $r->question3,
            'question4' => $r->question4,
            'question5' => $r->question5,
            'question5_souce' => ($r->question5 == 'N') ? $r->question5_source : NULL,
            'question5_others' => ($r->question5 == 'N' && $r->question5_source == 'OTHERS') ? $r->question5_others : NULL,
            'question6' => $r->question6,
            'question6_where' => ($r->question6 == 'Y') ? $r->question6_where : NULL,
            'question6_source' => ($r->question6 == 'Y' && $r->question6_where == 'OTHERS') ? mb_strtoupper($r->question6_source) : NULL,
            'question7' => $r->question7,
            'question7_others' => ($r->question7 == 'OTHERS') ? mb_strtoupper($r->question7_others) : NULL,
            'question8' => $r->question8,
            'question9' => $r->question9,
            'question10' => ($r->filled('question10')) ? mb_strtoupper($r->question10) : NULL,
            'question11' => $r->question11,
            'question12' => ($r->question11 == 'Y') ? $r->question12 : NULL,

            'am_snacks_names' => implode(",", $r->am_snacks_names),
            'am_snacks_datetime' => implode(",", $r->am_snacks_datetime),
            'lunch_names' => implode(",", $r->lunch_names),
            'lunch_datetime' => implode(",", $r->lunch_datetime),
            'pm_snacks_names' => implode(",", $r->pm_snacks_names),
            'pm_snacks_datetime' => implode(",", $r->pm_snacks_datetime),
            'dinner_names' => implode(",", $r->dinner_names),
            'dinner_datetime' => implode(",", $r->dinner_datetime),
            
            'remarks' => ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,
        ]);
    }

    public function report1($main_id) {

    }
}
