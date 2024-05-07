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
                'facility_id' => auth()->user()->itr_facility_id,
            ]);

            return redirect()->route('qes_view_main', $c->id)
            ->with('msg', 'QES Case successfully created. You may now encode patient details inside the case.')
            ->with('msgtype', 'success');
        }
    }

    public function viewMain($main_id) {
        $d = QesMain::findOrFail($main_id);

        $list_patient = QesSub::where('qes_main_id', $main_id)->get();

        return view('qes.viewmain', [
            'd' => $d,
            'list_patient' => $list_patient,
        ]);
    }

    public function storeRecord($main_id, Request $r) {
        //Search Existing Patient on the Main
        $s = QesSub::where('qes_main_id', $main_id)
        ->where('lname', mb_strtoupper($r->lname))
        ->where('fname', mb_strtoupper($r->fname))
        ->first();

        if($s) {
            
        }

        $create = $r->user()->qessub()->create([
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
            'hospitalized' => ($r->has_symptoms == 'Y') ? $r->hospitalized : 'N',
            'admission_date'=> ($r->has_symptoms == 'Y' && $r->hospitalized == 'Y') ? $r->admission_date : NULL,
            'discharge_date' => ($r->has_symptoms == 'Y' && $r->hospitalized == 'Y') ? $r->discharge_date : NULL,
            'hospital_name' => ($r->has_symptoms == 'Y' && $r->hospitalized == 'Y') ? mb_strtoupper($r->hospital_name) : NULL,
            'outcome' => $r->outcome,
            'lbm_3xday' => ($r->has_symptoms == 'Y') ? $r->lbm_3xday : 'N',
            'fever' => ($r->has_symptoms == 'Y') ? $r->fever : 'N',
            'nausea' => ($r->has_symptoms == 'Y') ? $r->nausea : 'N',
            'vomiting' => ($r->has_symptoms == 'Y') ? $r->vomiting : 'N',
            'bodyweakness' => ($r->has_symptoms == 'Y') ? $r->bodyweakness : 'N',
            'abdominalcramps' => ($r->has_symptoms == 'Y') ? $r->abdominalcramps : 'N',
            'rectalpain' => ($r->has_symptoms == 'Y') ? $r->rectalpain : 'N',
            'tenesmus' => ($r->has_symptoms == 'Y') ? $r->tenesmus : 'N',
            'bloodystool' => ($r->has_symptoms == 'Y') ? $r->bloodystool : 'N',
            'brownish' => ($r->has_symptoms == 'Y') ? $r->brownish : 'N',
            'yellowish' => ($r->has_symptoms == 'Y') ? $r->yellowish : 'N',
            'greenish' => ($r->has_symptoms == 'Y') ? $r->greenish : 'N',
            'others' => ($r->has_symptoms == 'Y') ? $r->others : 'N',
            'others_specify' => ($r->has_symptoms == 'Y' && $r->others == 'Y') ? mb_strtoupper($r->others_specify) : 'N',
            'volumeofstool' => ($r->has_symptoms == 'Y') ? $r->volumeofstool : 'N',
            'quantify' => ($r->has_symptoms == 'Y') ? $r->quantify : 'N',

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
            'question5_others' => ($r->question5 == 'N' && $r->question5_source == 'OTHERS') ? mb_strtoupper($r->question5_others) : NULL,
            'question6' => $r->question6,
            'question6_where' => ($r->question6 == 'Y') ? $r->question6_where : NULL,
            'question6_source' => ($r->question6 == 'Y' && $r->question6_where == 'OTHERS') ? mb_strtoupper($r->question6_source) : NULL,
            'question7' => $r->question7,
            'question7_others' => ($r->question7 == 'OTHERS') ? mb_strtoupper($r->question7_others) : NULL,
            'question8' => $r->question8,
            'question9' => $r->question9,
            'question10' => ($r->filled('question10')) ? mb_strtoupper($r->question10) : NULL,
            'question11' => $r->question11,
            'question12' => ($r->question11 == 'Y') ? $r->question12 : 'N',

            'am_snacks_names' => (strlen(implode(",", $r->am_snacks_names)) != 0) ? implode(",", $r->am_snacks_names) : NULL,
            'am_snacks_datetime' => (strlen(implode(",", $r->am_snacks_datetime)) != 0) ? implode(",", $r->am_snacks_datetime) : NULL,
            'lunch_names' => (strlen(implode(",", $r->lunch_names)) != 0) ? implode(",", $r->lunch_names) : NULL,
            'lunch_datetime' => (strlen(implode(",", $r->lunch_datetime)) != 0) ? implode(",", $r->lunch_datetime) : NULL,
            'pm_snacks_names' => (strlen(implode(",", $r->pm_snacks_names)) != 0) ? implode(",", $r->pm_snacks_names) : NULL,
            'pm_snacks_datetime' => (strlen(implode(",", $r->pm_snacks_datetime)) != 0) ? implode(",", $r->pm_snacks_datetime) : NULL,
            'dinner_names' => (strlen(implode(",", $r->dinner_names)) != 0) ? implode(",", $r->dinner_names) : NULL,
            'dinner_datetime' => (strlen(implode(",", $r->dinner_datetime)) != 0) ? implode(",", $r->dinner_datetime) : NULL,
            
            'remarks' => ($r->filled('remarks')) ? mb_strtoupper($r->remarks) : NULL,
        ]);

        return redirect()->route('qes_view_main', $main_id)
        ->with('msg', 'Patient ['.$create->getName().' was successfully added to the list.')
        ->with('msgtype', 'success');
    }

    public function report1($main_id) {

    }

    public function exportLinelist($main_id) {

    }
}
