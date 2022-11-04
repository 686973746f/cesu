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

        }
        else {
            $r = Records::findOrFail($record_id);

            return view('monkeypox.cif_create', ['d' => $r]);
        }
    }

    public function store_cif($record_id, Request $request) {
        $r = Records::findOrFail($record_id);

        $c = MonkeyPox::create([
            'records_id' => $r->id,

            'morbidity_month' => $request->morbidity_month,
            'date_reported' => $request->date_reported,
            'epid_number' => $request->epid_number,
            'date_investigation' => $request->date_investigation,

            'dru_name' => $request->dru_name,
            'dru_region' => $request->dru_region,
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
            'have_cutaneous_rash_date' => ($request->have_cutaneous_rash == Y) ? $request->have_cutaneous_rash_date : NULL,

            'have_fever' => $request->have_fever,
            'have_fever_date' => ($request->have_fever == 'Y') ? $request->have_fever_date : NULL,
            'have_fever_days_duration' => ($request->have_fever == 'Y') ? $request->have_fever_days_duration : NULL,

            'have_activedisease_lesion_samestate' => $request->have_activedisease_lesion_samestate,
            'have_activedisease_lesion_samesize' => $request->have_activedisease_lesion_samesize,
            'have_activedisease_lesion_deep' => $request->have_activedisease_lesion_deep,
            'have_activedisease_develop_ulcers' => $request->have_activedisease_develop_ulcers,
            'have_activedisease_lesion_type' => $request->have_activedisease_lesion_type,
            'have_activedisease_lesion_localization' => $request->have_activedisease_lesion_localization,
            'have_activedisease_lesion_localization_otherareas' => $request->have_activedisease_lesion_localization_otherareas,

            'symptoms_list' => implode(',', $request->symptoms_list),
            'symptoms_lymphadenopathy_localization' => $request->symptoms_lymphadenopathy_localization,
            
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
            'history4_type' => $request->history4_type,
            'history4_type_others' => $request->history4_type_others,

            
        ]);
    }

    public function edit_cif($cif_id) {

    }

    public function update_cif($cif_id, Request $request) {
        
    }
}
