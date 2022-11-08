<?php

namespace App\Http\Controllers;

use App\Models\Records;
use App\Models\MonkeyPox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonkeyPoxController extends Controller
{
    public function home() {
        return view('monkeypox.home');
    }

    public function ajaxlist(Request $request) {
        $list = [];

        if($request->has('q') && strlen($request->input('q')) > 1) {
            $search = mb_strtoupper($request->q);
            
            $search_rep = str_replace(',','', $search);

            $data = Records::where(function ($query) use ($search, $search_rep) {
                $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%$search_rep%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%$search_rep%")
                ->orWhere('id', $search);
            })->get();

            foreach($data as $item) {
                array_push($list, [
                    'id' => $item->id,
                    'text' => '#'.$item->id.' - '.$item->getName().' | '.$item->getAge().'/'.substr($item->gender,0,1).' | '.date('m/d/Y', strtotime($item->bdate)),
                    'class' => 'cif',
                ]);
            }
        }

        return response()->json($list);
    }

    public function view_report() {

    }

    public function export_report() {

    }

    public function view_records() {

    }

    public function view_cif() {

    }

    public function create_cif($record_id) {
        $id = $record_id;

        $d = MonkeyPox::where('records_id', $id)->first();

        if($d) {
            return view('monkeypox.cif_exist', ['d' => $d]);
        }
        else {
            $r = Records::findOrFail($record_id);

            return $this->edit_cif(new MonkeyPox())->with('d', $r);
        }
    }

    public function edit_cif(MonkeyPox $mk) {

        return view('monkeypox.cif_form', ['c' => $mk]);
    }

    public function store_cif($record_id, Request $request) {
        $r = Records::findOrFail($record_id);

        $s = MonkeyPox::where('records_id', $record_id)
        ->where('morbidity_month', $request->morbidity_month)
        ->first();

        if($s) {
            return redirect()->back()
            ->with('msg', 'Duplicate')
            ->with('msgtype', 'danger');
        }
        else {
            $c = MonkeyPox::create([
                'records_id' => $r->id,
    
                'morbidity_month' => $request->morbidity_month,
                'date_reported' => $request->date_reported,
                'epid_number' => $request->epid_number,
                'date_investigation' => $request->date_investigation,
    
                'dru_name' => $request->dru_name,
                'dru_region' => $request->dru_region,
                'dru_province' => $request->dru_province,
                'dru_muncity' => $request->dru_muncity,
                'dru_street' => $request->dru_street,
                'epid_number' => $request->epid_number,
    
                'type' => $request->type,
                'laboratory_id' => $request->laboratory_id,
    
                'informant_name' => $request->informant_name,
                'informant_relationship' => $request->informant_relationship,
                'informant_contactnumber' => $request->informant_contactnumber,
    
                'date_admitted' => $request->date_admitted,
                'admission_er' => $request->admission_er,
                'admission_ward' => $request->admission_ward,
                'admission_icu' => $request->admission_icu,
    
                'ifhashistory_blooddonation_transfusion' => $request->ifhashistory_blooddonation_transfusion,
                'ifhashistory_blooddonation_transfusion_place' => $request->ifhashistory_blooddonation_transfusion_place,
                'ifhashistory_blooddonation_transfusion_date' => $request->ifhashistory_blooddonation_transfusion_date,
    
                'other_medicalinformation' => $request->other_medicalinformation,
    
                'date_onsetofillness' => $request->date_onsetofillness,
    
                'have_cutaneous_rash' => $request->have_cutaneous_rash,
                'have_cutaneous_rash_date' => ($request->have_cutaneous_rash == 'Y') ? $request->have_cutaneous_rash_date : NULL,
    
                'have_fever' => $request->have_fever,
                'have_fever_date' => ($request->have_fever == 'Y') ? $request->have_fever_date : NULL,
                'have_fever_days_duration' => ($request->have_fever == 'Y') ? $request->have_fever_days_duration : NULL,
    
                'have_activedisease_lesion_samestate' => $request->have_activedisease_lesion_samestate,
                'have_activedisease_lesion_samesize' => $request->have_activedisease_lesion_samesize,
                'have_activedisease_lesion_deep' => $request->have_activedisease_lesion_deep,
                'have_activedisease_develop_ulcers' => $request->have_activedisease_develop_ulcers,
                'have_activedisease_lesion_type' => implode(',', $request->have_activedisease_lesion_type),
                'have_activedisease_lesion_localization' => implode(',', $request->have_activedisease_lesion_localization),
                'have_activedisease_lesion_localization_otherareas' => $request->have_activedisease_lesion_localization_otherareas,
    
                'symptoms_list' => (!is_null($request->symptoms_list)) ? implode(',', $request->symptoms_list) : NULL,
                'symptoms_lymphadenopathy_localization' => (!is_null($request->symptoms_lymphadenopathy_localization)) ? implode(',', $request->symptoms_lymphadenopathy_localization) : NULL,
                
                'history1_yn' => $request->history1_yn,
                'history1_specify' => $request->history1_specify,
                'history1_date_travel' => $request->history1_date_travel,
                'history1_flightno' => $request->history1_flightno,
                'history1_date_arrival' => $request->history1_date_arrival,
                'history1_pointandexitentry' => $request->history1_pointandexitentry,
    
                'history2_yn' => $request->history2_yn,
                'history2_specify' => $request->history2_specify,
                'history2_date_travel' => $request->history2_date_travel,
                'history2_flightno' => $request->history2_flightno,
                'history2_date_arrival' => $request->history2_date_arrival,
                'history2_pointandexitentry' => $request->history2_pointandexitentry,
    
                'history3_yn' => $request->history3_yn,
    
                'history4_yn' => $request->history4_yn,
                'history4_typeofanimal' => $request->history4_typeofanimal,
                'history4_firstexposure' => $request->history4_firstexposure,
                'history4_lastexposure' => $request->history4_lastexposure,
                'history4_type' => (!is_null($request->history4_type)) ? implode(',', $request->history4_type) : NULL,
                'history4_type_others' => $request->history4_type_others,
    
                'history5_genderidentity' => $request->history5_genderidentity,
    
                'history6_yn' => $request->history6_yn,
                'history6_mtm' => $request->history6_mtm,
                'history6_mtm_nosp' => $request->history6_mtm_nosp,
                'history6_mtf' => $request->history6_mtf,
                'history6_mtf_nosp' => $request->history6_mtf_nosp,
                'history6_uknown' => $request->history6_uknown,
                'history6_uknown_nosp' => $request->history6_uknown_nosp,
    
                'history7_yn' => $request->history7_yn,
                
                'history8_yn' => $request->history8_yn,
    
                'history9_choice' => $request->history9_choice,
                'history9_choice_othercountry' => $request->history9_choice_othercountry,
    
                'test_npsops' => $request->test_npsops,
                'test_npsops_date_collected' => $request->test_npsops_date_collected,
                'test_npsops_laboratory' => $request->test_npsops_laboratory,
                'test_npsops_result' => $request->test_npsops_result,
                'test_npsops_date_released' => $request->test_npsops_date_released,
                'test_lesionfluid' => $request->test_lesionfluid,
                'test_lesionfluid_date_collected' => $request->test_lesionfluid_date_collected,
                'test_lesionfluid_laboratory' => $request->test_lesionfluid_laboratory,
                'test_lesionfluid_result' => $request->test_lesionfluid_result,
                'test_lesionfluid_date_released' => $request->test_lesionfluid_date_released,
                'test_lesionroof' => $request->test_lesionroof,
                'test_lesionroof_date_collected' => $request->test_lesionroof_date_collected,
                'test_lesionroof_laboratory' => $request->test_lesionroof_laboratory,
                'test_lesionroof_result' => $request->test_lesionroof_result,
                'test_lesionroof_date_released' => $request->test_lesionroof_date_released,
                'test_lesioncrust' => $request->test_lesioncrust,
                'test_lesioncrust_date_collected' => $request->test_lesioncrust_date_collected,
                'test_lesioncrust_laboratory' => $request->test_lesioncrust_laboratory,
                'test_lesioncrust_result' => $request->test_lesioncrust_result,
                'test_lesioncrust_date_released' => $request->test_lesioncrust_date_released,
                'test_serum' => $request->test_serum,
                'test_serum_date_collected' => $request->test_serum_date_collected,
                'test_serum_laboratory' => $request->test_serum_laboratory,
                'test_serum_result' => $request->test_serum_result,
                'test_serum_date_released' => $request->test_serum_date_released,
    
                'health_status' => $request->health_status,
                'health_status_date_discharged' => $request->health_status_date_discharged,
                'health_status_final_diagnosis' => $request->health_status_final_diagnosis,
    
                'outcome' => $request->outcome,
                'outcome_unknown_type' => $request->outcome_unknown_type,
                'outcome_date_recovered' => $request->outcome_date_recovered,
                'outcome_date_died' => $request->outcome_date_died,
                'outcome_causeofdeath' => $request->outcome_causeofdeath,
                'case_classification' => $request->case_classification,
    
                'remarks' => $request->remarks,
                'user_id' => auth()->user()->id,
            ]);

            return redirect()->route('mp.home')->with('msg', 'Monkeypox CIF has been created successfully.')
            ->with('msgtype', 'success');
        }
    }

    public function update_cif($cif_id, Request $request) {
        
    }
}
