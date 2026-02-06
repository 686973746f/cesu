<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\InhouseChildCare;
use App\Models\SyndromicPatient;
use App\Models\InhouseMaternalCare;
use App\Models\InhouseChildNutrition;
use App\Models\InhouseFamilyPlanning;
use Illuminate\Validation\Rules\In;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelFactory;

class ElectronicTclController extends Controller
{
    public static function viewListOfeTclBackend() {
        $list = [
            ['value' => 'maternal_care', 'text' => 'Maternal Care', 'enabled' => true],
            ['value' => 'child_care', 'text' => 'Child Care', 'enabled' => true],
            ['value' => 'child_nutrition', 'text' => 'Child Nutrition', 'enabled' => true],
            ['value' => 'family_planning', 'text' => 'Family Planning', 'enabled' => false],
        ];

        return collect($list)->sortBy('text', SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    public function eTclHome() {
        $type = request()->input('type');

        if($type == 'maternal_care') {
            $records = InhouseMaternalCare::where('facility_id', auth()->user()->etcl_bhs_id);
        }
        elseif($type == 'child_care') {
            $records = InhouseChildCare::where('facility_id', auth()->user()->etcl_bhs_id);
        }
        elseif($type == 'child_nutrition') {
            $records = InhouseChildNutrition::where('facility_id', auth()->user()->etcl_bhs_id);
        }
        elseif($type == 'family_planning') {
            $records = InhouseFamilyPlanning::where('facility_id', auth()->user()->etcl_bhs_id);
        }
        else {
            return abort(404);
        }

        $records = (clone $records)
        ->when(request()->input('year'), function($q) {
            $q->whereYear('registration_date', request()->input('year'));
        }, function($q) {
            $q->whereYear('registration_date', date('Y'));
        })->get();
        
        return view('efhsis.etcl.etcl_home', compact('type', 'records'));
    }

    public function switchBhs(Request $r) {
            $r->validate([
                'switch_bhs_list' => 'required|in:' . implode(',', auth()->user()->getBhsSwitchList()),
            ]);

            $d = User::findOrFail(auth()->user()->id);

            $d->etcl_bhs_id = $r->switch_bhs_list;
            $d->save();
    
            return redirect()
            ->route('etcl_home', ['type' => $r->etcl_type])
            ->with('msg', 'Successfully switched BHS.')
            ->with('msgtype', 'success');
    }

    public function newMaternalCare($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        if($d->gender != 'FEMALE') {
            return redirect()
            ->back()
            ->with('msg', 'Error: Maternal Care can only be encoded for female patients only.')
            ->with('msgtype', 'warning');
        }

        return $this->newOrEditMaternalCare(new InhouseMaternalCare(), 'NEW', $d->id);
    }

    public function storeMaternalCare(Request $r, $patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $check = InhouseMaternalCare::where('request_uuid', $r->request_uuid)->first();

        if($check) {
            return redirect()->
            back()
            ->with('msg', 'Error: This record has already been saved.')
            ->with('msgtype', 'warning');
        }

        //BMI Calculation
        $heightM = $r->height / 100;
        $bmi = $r->weight / ($heightM * $heightM);
        $bmi = round($bmi, 2);

        //Get Nutritional Assessment based on BMI
        if($bmi < 18.5) {
            $nutritional_assessment = 'L'; //Underweight
        }
        else if($bmi >= 18.5 && $bmi <= 22.9) {
            $nutritional_assessment = 'N'; //Normal
        }
        else if($bmi >= 23) {
            $nutritional_assessment = 'H';
        }

        if($d->getAge() <= 14) {
            $age_group = 'A'; //Adolescent
        }
        else if($d->getAge() >= 15 && $d->getAge() <= 19) { 
            $age_group = 'B'; //Young Adult
        }
        else {
            $age_group = 'C'; //Adult
        }

        $birthdate = Carbon::parse($d->bdate);
        $currentDate = Carbon::parse($r->registration_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $table_params = [
            'request_uuid' => $r->request_uuid,
            'patient_id' => $d->id,
            'facility_id' => auth()->user()->etcl_bhs_id,
            'registration_date' => $r->registration_date,
            'highrisk' => $r->highrisk,
            'lmp' => $r->lmp,
            'gravida' => $r->gravida,
            'parity' => $r->parity,
            'edc' => $r->edc,
            'age_group' => $age_group,

            'height' => $r->height,
            'weight' => $r->weight,
            'bmi' => $bmi,
            'nutritional_assessment' => $nutritional_assessment,

            'visit1_est' => $r->visit1_est,
            'visit1' => $r->visit1,
            'visit1_type' => $r->visit1_type,
            'visit2_est' => $r->visit2_est,
            'visit2' => $r->visit2,
            'visit2_type' => $r->visit2_type,
            'visit3_est' => $r->visit3_est,
            'visit3' => $r->visit3,
            'visit3_type' => $r->visit3_type,
            'visit4_est' => $r->visit4_est,
            'visit4' => $r->visit4,
            'visit4_type' => $r->visit4_type,
            'visit5_est' => $r->visit5_est,
            'visit5' => $r->visit5,
            'visit5_type' => $r->visit5_type,
            'visit6_est' => $r->visit6_est,
            'visit6' => $r->visit6,
            'visit6_type' => $r->visit6_type,
            'visit7_est' => $r->visit7_est,
            'visit7' => $r->visit7,
            'visit7_type' => $r->visit7_type,
            'visit8_est' => $r->visit8_est,
            'visit8' => $r->visit8,
            'visit8_type' => $r->visit8_type,

            'height' => $r->height,
            'weight' => $r->weight,

            'trans_remarks' => $r->trans_remarks,
            'transout_date' => ($r->trans_remarks == 'B') ? $r->transout_date : NULL,

            'td1' => $r->td1,
            'td1_type' => $r->td1_type,
            'td2' => $r->td2,
            'td2_type' => $r->td2_type,
            'td3' => $r->td3,
            'td3_type' => $r->td3_type,
            'td4' => $r->td4,
            'td4_type' => $r->td4_type,
            'td5' => $r->td5,
            'td5_type' => $r->td5_type,
            'deworming_date' => $r->deworming_date,

            'ifa1_date' => $r->ifa1_date,
            'ifa1_dosage' => $r->ifa1_dosage,
            'ifa1_type' => $r->ifa1_type,
            'ifa2_date' => $r->ifa2_date,
            'ifa2_dosage' => $r->ifa2_dosage,
            'ifa2_type' => $r->ifa2_type,
            'ifa3_date' => $r->ifa3_date,
            'ifa3_dosage' => $r->ifa3_dosage,
            'ifa3_type' => $r->ifa3_type,
            'ifa4_date' => $r->ifa4_date,
            'ifa4_dosage' => $r->ifa4_dosage,
            'ifa4_type' => $r->ifa4_type,
            'ifa5_date' => $r->ifa5_date,
            'ifa5_dosage' => $r->ifa5_dosage,
            'ifa5_type' => $r->ifa5_type,
            'ifa6_date' => $r->ifa6_date,
            'ifa6_dosage' => $r->ifa6_dosage,
            'ifa6_type' => $r->ifa6_type,

            'mms1_date' => $r->mms1_date,
            'mms1_dosage' => $r->mms1_dosage,
            'mms1_type' => $r->mms1_type,
            'mms2_date' => $r->mms2_date,
            'mms2_dosage' => $r->mms2_dosage,
            'mms2_type' => $r->mms2_type,
            'mms3_date' => $r->mms3_date,
            'mms3_dosage' => $r->mms3_dosage,
            'mms3_type' => $r->mms3_type,
            'mms4_date' => $r->mms4_date,
            'mms4_dosage' => $r->mms4_dosage,
            'mms4_type' => $r->mms4_type,
            'mms5_date' => $r->mms5_date,
            'mms5_dosage' => $r->mms5_dosage,
            'mms5_type' => $r->mms5_type,
            'mms6_date' => $r->mms6_date,
            'mms6_dosage' => $r->mms6_dosage,
            'mms6_type' => $r->mms6_type,

            'syphilis_date' => $r->syphilis_date,
            'syphilis_result' => ($r->syphilis_date) ? $r->syphilis_result : null,
            'hiv_date' => $r->hiv_date,
            'hiv_result' => ($r->hiv_date) ? $r->hiv_result : null,
            'hb_date' => $r->hb_date,
            'hb_result' => ($r->hb_date) ? $r->hb_result : null,
            'cbc_date' => $r->cbc_date,
            'cbc_result' => ($r->cbc_date) ? $r->cbc_result : null,
            'diabetes_date' => $r->diabetes_date,
            'diabetes_result' => ($r->diabetes_date) ? $r->diabetes_result : null,

            //'pregnancy_terminated_date',

            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,

            'system_remarks' => $r->system_remarks,

            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
        ];

        if($r->highrisk == 'Y') {
            $table_params = $table_params + [
                'calcium1_date' => $r->calcium1_date,
                'calcium1_dosage' => $r->calcium1_dosage,
                'calcium1_type' => $r->calcium1_type,
                'calcium2_date' => $r->calcium2_date,
                'calcium2_dosage' => $r->calcium2_dosage,
                'calcium2_type' => $r->calcium2_type,
                'calcium3_date' => $r->calcium3_date,
                'calcium3_dosage' => $r->calcium3_dosage,
                'calcium3_type' => $r->calcium3_type,
            ];
        }

        if(!is_null($r->outcome) && $r->trans_remarks != 'B') {
            $table_params = $table_params + [
                'outcome' => $r->outcome,
                'number_livebirths' => $r->number_livebirths,
                'number_livebirths_toencode' => $r->number_livebirths,
                'delivery_date' => $r->delivery_date,
                'delivery_type' => $r->delivery_type,

                'birth_weight' => $r->birth_weight,

                'facility_type' => $r->facility_type,
                'place_of_delivery' => ($r->facility_type == 'PUBLIC' || $r->facility_type == 'PRIVATE') ? mb_strtoupper($r->place_of_delivery) : null,
                'bcemoncapable' => $r->bcemoncapable,
                'attendant' => $r->attendant,

                'pnc1' => $r->pnc1,
                'pnc2' => $r->pnc2,
                'pnc3' => $r->pnc3,
                'pnc4' => $r->pnc4,

                'pp_td1' => $r->pp_td1,
                'pp_td1_dosage' => $r->pp_td1_dosage,
                'pp_td2' => $r->pp_td2,
                'pp_td2_dosage' => $r->pp_td2_dosage,
                'pp_td3' => $r->pp_td3,
                'pp_td3_dosage' => $r->pp_td3_dosage,
                'pp_td4' => $r->pp_td4,
                'pp_td4_dosage' => $r->pp_td4_dosage,

                'vita' => $r->vita,
                'pp_remarks' => $r->pp_remarks,
                'pp_transout_date' => ($r->pp_remarks == 'B') ? $r->pp_transout_date : NULL,
            ];
        }

        $c = InhouseMaternalCare::create($table_params);
        
        $d = InhouseMaternalCare::findOrFail($c->id);
        $d->runIndicatorUpdate();
        $d->save();

        return redirect()
        ->route('etcl_home', ['type' => 'maternal_care'])
        ->with('msg', 'Maternal Care Record successfully saved.')
        ->with('msgtype', 'success');
    }

    public function editMaternalCare($id) {
        $d = InhouseMaternalCare::findOrFail($id);
        
        if(!auth()->user()->isGlobalAdmin()) {
            if($d->facility_id != auth()->user()->etcl_bhs_id) {
                return abort(403, 'Unauthorized access to this record.');
            }
        }
        
        return $this->newOrEditMaternalCare($d, 'EDIT', $d->id);
    }

    public function newOrEditMaternalCare(InhouseMaternalCare $record, $mode, $patient_id = null) {
        if($patient_id != null) {
            $patient = SyndromicPatient::findOrFail($patient_id);
        }

        return view('efhsis.etcl.maternalcare_encode', [
            'd' => $record,
            'mode' => $mode,
            'patient' => $patient,
        ]);
    }

    public function updateMaternalCare(Request $r, $id) {
        $d = InhouseMaternalCare::findOrFail($id);

        $check = InhouseMaternalCare::where('request_uuid', $r->request_uuid)->first();

        if($check) {
            return redirect()
            ->back()
            ->with('msg', 'Error: This record has already been updated.')
            ->with('msgtype', 'warning');
        }

        //BMI Calculation
        $heightM = $r->height / 100;
        $bmi = $r->weight / ($heightM * $heightM);
        $bmi = round($bmi, 2);

        //Get Nutritional Assessment based on BMI
        if($bmi < 18.5) {
            $nutritional_assessment = 'L'; //Underweight
        }
        else if($bmi >= 18.5 && $bmi <= 22.9) {
            $nutritional_assessment = 'N'; //Normal
        }
        else if($bmi >= 23) {
            $nutritional_assessment = 'H';
        }

        if($d->patient->getAge() <= 14) {
            $age_group = 'A'; //Adolescent
        }
        else if($d->patient->getAge() >= 15 && $d->patient->getAge() <= 19) { 
            $age_group = 'B'; //Young Adult
        }
        else {
            $age_group = 'C'; //Adult
        }

        if(!is_null($r->outcome) && $r->trans_remarks == 'A') {
            $date_delivered = Carbon::parse($r->delivery_date);

            if(Carbon::parse($r->lmp)->addWeeks(40)->lt($date_delivered)) {
                return redirect()
                ->back()
                ->with('msg', 'Error: Delivery Date cannot be greater than the EDC (40 weeks from LMP).')
                ->with('msgtype', 'warning');
            }
        }

        $birthdate = Carbon::parse($d->patient->bdate);
        $currentDate = Carbon::parse($r->registration_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $table_params = [
            'request_uuid' => $r->request_uuid,
            //'registration_date' => $r->registration_date,
            'highrisk' => $r->highrisk,
            'lmp' => $r->lmp,
            'gravida' => $r->gravida,
            'parity' => $r->parity,
            'edc' => $r->edc,
            'age_group' => $age_group,

            'height' => $r->height,
            'weight' => $r->weight,
            'bmi' => $bmi,
            'nutritional_assessment' => $nutritional_assessment,

            'visit1_est' => $r->visit1_est,
            'visit1' => $r->visit1,
            'visit1_type' => $r->visit1_type,
            'visit2_est' => $r->visit2_est,
            'visit2' => $r->visit2,
            'visit2_type' => $r->visit2_type,
            'visit3_est' => $r->visit3_est,
            'visit3' => $r->visit3,
            'visit3_type' => $r->visit3_type,
            'visit4_est' => $r->visit4_est,
            'visit4' => $r->visit4,
            'visit4_type' => $r->visit4_type,
            'visit5_est' => $r->visit5_est,
            'visit5' => $r->visit5,
            'visit5_type' => $r->visit5_type,
            'visit6_est' => $r->visit6_est,
            'visit6' => $r->visit6,
            'visit6_type' => $r->visit6_type,
            'visit7_est' => $r->visit7_est,
            'visit7' => $r->visit7,
            'visit7_type' => $r->visit7_type,
            'visit8_est' => $r->visit8_est,
            'visit8' => $r->visit8,
            'visit8_type' => $r->visit8_type,

            'height' => $r->height,
            'weight' => $r->weight,

            'trans_remarks' => $r->trans_remarks,
            'transout_date' => ($r->trans_remarks == 'B') ? $r->transout_date : NULL,

            'td1' => $r->td1,
            'td1_type' => $r->td1_type,
            'td2' => $r->td2,
            'td2_type' => $r->td2_type,
            'td3' => $r->td3,
            'td3_type' => $r->td3_type,
            'td4' => $r->td4,
            'td4_type' => $r->td4_type,
            'td5' => $r->td5,
            'td5_type' => $r->td5_type,
            'deworming_date' => $r->deworming_date,

            'ifa1_date' => $r->ifa1_date,
            'ifa1_dosage' => $r->ifa1_dosage,
            'ifa1_type' => $r->ifa1_type,
            'ifa2_date' => $r->ifa2_date,
            'ifa2_dosage' => $r->ifa2_dosage,
            'ifa2_type' => $r->ifa2_type,
            'ifa3_date' => $r->ifa3_date,
            'ifa3_dosage' => $r->ifa3_dosage,
            'ifa3_type' => $r->ifa3_type,
            'ifa4_date' => $r->ifa4_date,
            'ifa4_dosage' => $r->ifa4_dosage,
            'ifa4_type' => $r->ifa4_type,
            'ifa5_date' => $r->ifa5_date,
            'ifa5_dosage' => $r->ifa5_dosage,
            'ifa5_type' => $r->ifa5_type,
            'ifa6_date' => $r->ifa6_date,
            'ifa6_dosage' => $r->ifa6_dosage,
            'ifa6_type' => $r->ifa6_type,

            'mms1_date' => $r->mms1_date,
            'mms1_dosage' => $r->mms1_dosage,
            'mms1_type' => $r->mms1_type,
            'mms2_date' => $r->mms2_date,
            'mms2_dosage' => $r->mms2_dosage,
            'mms2_type' => $r->mms2_type,
            'mms3_date' => $r->mms3_date,
            'mms3_dosage' => $r->mms3_dosage,
            'mms3_type' => $r->mms3_type,
            'mms4_date' => $r->mms4_date,
            'mms4_dosage' => $r->mms4_dosage,
            'mms4_type' => $r->mms4_type,
            'mms5_date' => $r->mms5_date,
            'mms5_dosage' => $r->mms5_dosage,
            'mms5_type' => $r->mms5_type,
            'mms6_date' => $r->mms6_date,
            'mms6_dosage' => $r->mms6_dosage,
            'mms6_type' => $r->mms6_type,

            'syphilis_date' => $r->syphilis_date,
            'syphilis_result' => ($r->syphilis_date) ? $r->syphilis_result : null,
            'hiv_date' => $r->hiv_date,
            'hiv_result' => ($r->hiv_date) ? $r->hiv_result : null,
            'hb_date' => $r->hb_date,
            'hb_result' => ($r->hb_date) ? $r->hb_result : null,
            'cbc_date' => $r->cbc_date,
            'cbc_result' => ($r->cbc_date) ? $r->cbc_result : null,
            'diabetes_date' => $r->diabetes_date,
            'diabetes_result' => ($r->diabetes_date) ? $r->diabetes_result : null,

            //'pregnancy_terminated_date',

            //'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,

            'system_remarks' => $r->system_remarks,

            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
        ];

        if($r->highrisk == 'Y') {
            $table_params = $table_params + [
                'calcium1_date' => $r->calcium1_date,
                'calcium1_dosage' => $r->calcium1_dosage,
                'calcium1_type' => $r->calcium1_type,
                'calcium2_date' => $r->calcium2_date,
                'calcium2_dosage' => $r->calcium2_dosage,
                'calcium2_type' => $r->calcium2_type,
                'calcium3_date' => $r->calcium3_date,
                'calcium3_dosage' => $r->calcium3_dosage,
                'calcium3_type' => $r->calcium3_type,
            ];
        }

        if(!is_null($r->outcome) && $r->trans_remarks != 'B') {
            if(!is_null($r->birth_weight)) {
                if($r->birth_weight < 2500) {
                    $weight_status = 'L'; //Low Birth Weight
                }
                else {
                    $weight_status = 'N'; //Normal Birth Weight
                }
            }
            else {
                $weight_status = 'U';
            }

            $table_params = $table_params + [
                'outcome' => $r->outcome,
                'number_livebirths' => $r->number_livebirths,
                'number_livebirths_toencode' => $r->number_livebirths,
                'delivery_date' => $r->delivery_date,
                'delivery_type' => $r->delivery_type,

                'birth_weight' => $r->birth_weight,
                'weight_status' => $weight_status,

                'facility_type' => $r->facility_type,
                'place_of_delivery' => ($r->facility_type == 'PUBLIC' || $r->facility_type == 'PRIVATE') ? mb_strtoupper($r->place_of_delivery) : null,
                'bcemoncapable' => $r->bcemoncapable,
                'attendant' => $r->attendant,

                'pnc1' => $r->pnc1,
                'pnc2' => $r->pnc2,
                'pnc3' => $r->pnc3,
                'pnc4' => $r->pnc4,

                'pp_td1' => $r->pp_td1,
                'pp_td1_dosage' => $r->pp_td1_dosage,
                'pp_td2' => $r->pp_td2,
                'pp_td2_dosage' => $r->pp_td2_dosage,
                'pp_td3' => $r->pp_td3,
                'pp_td3_dosage' => $r->pp_td3_dosage,
                'pp_td4' => $r->pp_td4,
                'pp_td4_dosage' => $r->pp_td4_dosage,

                'vita' => $r->vita,
                'pp_remarks' => $r->pp_remarks,
                'pp_transout_date' => ($r->pp_remarks == 'B') ? $r->pp_transout_date : NULL,
            ];
        }

        $d->update($table_params);
        $d->runIndicatorUpdate();
        $d->save();

        return redirect()
        ->route('etcl_home', ['type' => 'maternal_care'])
        ->with('msg', 'Maternal Care Record successfully updated.')
        ->with('msgtype', 'success');
    }

    public function newChildCare($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        if(is_null($d->mother_name)) {
            return redirect()
            ->back()
            ->with('msg', 'Error: Name of Mother must be specified as part of the patient\'s record before encoding Child Care.')
            ->with('msgtype', 'warning');
        }

        $find = InhouseChildCare::where('patient_id', $d->id)->first();

        if($find) {
            return redirect()
            ->route('etcl_childcare_view', $find->id)
            ->with('msg', 'Existing Child Care record found for this patient. You can only encode one (1) Child Care record per patient.')
            ->with('msgtype', 'info');
        }

        if($d->getAge() >= 5) {
            return redirect()
            ->back()
            ->with('msg', 'Error: Child Care can only be encoded for patients below 5 years old.')
            ->with('msgtype', 'warning');
        }

        return $this->newOrEditChildCare(new InhouseChildCare(), 'NEW', $d->id);
    }

    public function newOrEditChildCare(InhouseChildCare $record, $mode, $patient_id = null) {
        if($patient_id != null) {
            $patient = SyndromicPatient::findOrFail($patient_id);
        }

        return view('efhsis.etcl.childcare_encode', [
            'd' => $record,
            'mode' => $mode,
            'patient' => $patient,
        ]);
    }

    public function editChildCare($id) {
        $d = InhouseChildCare::findOrFail($id);
        
        if(!auth()->user()->isGlobalAdmin()) {
            if($d->facility_id != auth()->user()->etcl_bhs_id) {
                return abort(403, 'Unauthorized access to this record.');
            }
        }
        
        return $this->newOrEditChildCare($d, 'EDIT', $d->id);
    }
    
    public function updateChildCare($id, Request $r) {
        $d = InhouseChildCare::findOrFail($id);

        $check = InhouseChildCare::where('request_uuid', $r->request_uuid)->first();

        if($check) {
            return redirect()
            ->back()
            ->with('msg', 'Error: This record has already been updated.')
            ->with('msgtype', 'warning');
        }

        $birthdate = Carbon::parse($d->patient->bdate);
        $currentDate = Carbon::parse($r->registration_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        if($r->filled('ipv1')) {
            if(!$r->filled('dpt3')) {
                return redirect()
                ->back()
                ->withInput()
                ->with('msg', 'Error: DPT-HiB-Hepb 3rd Dose must be filled before encoding IPV 1st Dose.')
                ->with('msgtype', 'warning');
            }
            else if(!$r->filled('opv3')) {
                return redirect()
                ->back()
                ->withInput()
                ->with('msg', 'Error: OPV 3rd Dose must be filled before encoding IPV 1st Dose.')
                ->with('msgtype', 'warning');
            }
            else if(!$r->filled('pcv3')) {
                return redirect()
                ->back()
                ->withInput()
                ->with('msg', 'Error: PCV 3rd Dose must be filled before encoding IPV 1st Dose.')
                ->with('msgtype', 'warning');
            }
        }

        $table_params = [
            //'patient_id' => $d->id,
            //'facility_id' => auth()->user()->etcl_bhs_id,
            //'registration_date' => $r->registration_date,
            'mother_type' => $r->mother_type,

            'bcg1' => $r->bcg1,
            'bcg1_type' => $r->bcg1_type,
            'bcg2' => $r->bcg2,
            'bcg2_type' => $r->bcg2_type,
            'hepab1' => $r->hepab1,
            'hepab1_type' => $r->hepab1_type,
            'hepab2' => $r->hepab2,
            'hepab2_type' => $r->hepab2_type,

            'dpt1' => $r->dpt1,
            'dpt1_type' => $r->dpt1_type,
            'dpt1_months' => ($r->filled('dpt1')) ? Carbon::parse($r->dpt1)->diffInMonths($birthdate) : null,
            'dpt2' => $r->dpt2,
            'dpt2_type' => $r->dpt2_type,
            'dpt2_months' => ($r->filled('dpt2')) ? Carbon::parse($r->dpt2)->diffInMonths($birthdate) : null,
            'dpt3' => $r->dpt3,
            'dpt3_type' => $r->dpt3_type,
            'dpt3_months' => ($r->filled('dpt3')) ? Carbon::parse($r->dpt3)->diffInMonths($birthdate) : null,
            'opv1' => $r->opv1,
            'opv1_type' => $r->opv1_type,
            'opv1_months' => ($r->filled('opv1')) ? Carbon::parse($r->opv1)->diffInMonths($birthdate) : null,
            'opv2' => $r->opv2,
            'opv2_type' => $r->opv2_type,
            'opv2_months' => ($r->filled('opv2')) ? Carbon::parse($r->opv2)->diffInMonths($birthdate) : null,
            'opv3' => $r->opv3,
            'opv3_type' => $r->opv3_type,
            'opv3_months' => ($r->filled('opv3')) ? Carbon::parse($r->opv3)->diffInMonths($birthdate) : null,
            'ipv1' => $r->ipv1,
            'ipv1_type' => $r->ipv1_type,
            'ipv1_months' => ($r->filled('ipv1')) ? Carbon::parse($r->ipv1)->diffInMonths($birthdate) : null,
            'ipv2' => $r->ipv2,
            'ipv2_type' => $r->ipv2_type,
            'ipv2_months' => ($r->filled('ipv2')) ? Carbon::parse($r->ipv2)->diffInMonths($birthdate) : null,
            'ipv3' => $r->ipv3,
            'ipv3_type' => $r->ipv3_type,
            'ipv3_months' => ($r->filled('ipv3')) ? Carbon::parse($r->ipv3)->diffInMonths($birthdate) : null,
            'pcv1' => $r->pcv1,
            'pcv1_type' => $r->pcv1_type,
            'pcv1_months' => ($r->filled('pcv1')) ? Carbon::parse($r->pcv1)->diffInMonths($birthdate) : null,
            'pcv2' => $r->pcv2,
            'pcv2_type' => $r->pcv2_type,
            'pcv2_months' => ($r->filled('pcv2')) ? Carbon::parse($r->pcv2)->diffInMonths($birthdate) : null,
            'pcv3' => $r->pcv3,
            'pcv3_type' => $r->pcv3_type,
            'pcv3_months' => ($r->filled('pcv3')) ? Carbon::parse($r->pcv3)->diffInMonths($birthdate) : null,
            'mmr1' => $r->mmr1,
            'mmr1_type' => $r->mmr1_type,
            'mmr1_months' => ($r->filled('mmr1')) ? Carbon::parse($r->mmr1)->diffInMonths($birthdate) : null,
            'mmr2' => $r->mmr2,
            'mmr2_type' => $r->mmr2_type,
            'mmr2_months' => ($r->filled('mmr2')) ? Carbon::parse($r->mmr2)->diffInMonths($birthdate) : null,
            'remarks' => $r->remarks,
            'system_remarks' => $r->system_remarks,

            //'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'request_uuid' => $r->request_uuid,

            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
        ];

        if($r->mother_type == 'Y') {
            //Search Mother
            $mcr = InhouseMaternalCare::find($r->maternalcare_id);

            if(!$mcr) {
                return redirect()
                ->back()
                ->with('msg', 'Error: Mother\'s Maternal Care Record not found.')
                ->with('msgtype', 'warning');
            }

            if(!is_null($mcr->td5) || !is_null($mcr->td4) || !is_null($mcr->td3)) {
                $cpab = '2';
            }
            else if(!is_null($mcr->td2)) {
                $cpab = '1';
            }
            else {
                $cpab = '0';
            }

            $table_params = $table_params + [
                'maternalcare_id' => $r->maternalcare_id,
            ];
        }
        else {
            $cpab = $r->cpab_manual;

            $table_params = $table_params + [
                'mother_name' => mb_strtoupper($r->mother_name),
                'cpab_type' => mb_strtoupper($r->cpab_type),
            ];
        }

        $table_params = $table_params + [
            'cpab' => $cpab,
        ];

        $d->update($table_params);
        $d->runIndicatorUpdate();
        $d->save();

        return redirect()
        ->route('etcl_home', ['type' => 'child_care'])
        ->with('msg', 'Child Care Record successfully updated.')
        ->with('msgtype', 'success');
    }

    public function newChildNutrition($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        if(is_null($d->mother_name)) {
            return redirect()
            ->back()
            ->with('msg', 'Error: Name of Mother must be specified as part of the patient\'s record before encoding Child Care.')
            ->with('msgtype', 'warning');
        }

        $find = InhouseChildNutrition::where('patient_id', $d->id)->first();

        if($find) {
            return redirect()
            ->route('etcl_childnutrition_view', $find->id)
            ->with('msg', 'Existing Child Nutrition record found for this patient. You can only encode one (1) Child Nutrition record per patient.')
            ->with('msgtype', 'info');
        }

        if($d->getAge() >= 5) {
            return redirect()
            ->back()
            ->with('msg', 'Error: Child Care can only be encoded for patients below 5 years old.')
            ->with('msgtype', 'warning');
        }

        return $this->newOrEditChildNutrition(new InhouseChildNutrition(), 'NEW', $d->id);
    }

    public function storeChildNutrition($patient_id, Request $r) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $check = InhouseChildNutrition::where('request_uuid', $r->request_uuid)->first();

        if($check) {
            return redirect()->
            back()
            ->with('msg', 'Error: This record has already been saved.')
            ->with('msgtype', 'warning');
        }

        $find = InhouseChildNutrition::where('patient_id', $d->id)->first();

        if($find) {
            return redirect()
            ->back()
            ->with('msg', 'Existing Child Nutrition record found for this patient. You can only encode one (1) Child Nutrition record per patient.')
            ->with('msgtype', 'info');
        }

        $birthdate = Carbon::parse($d->bdate);
        $currentDate = Carbon::parse($r->registration_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        if(!is_null($r->weight_atbirth)) {
            if($r->weight_atbirth < 2.5) {
                $weight_status = 'L'; //Low Birth Weight
            }
            else {
                $weight_status = 'N'; //Normal Birth Weight
            }
        }
        else {
            $weight_status = 'U';
        }
        
        $table_params = [
            'patient_id' => $d->id,
            'registration_date' => $r->registration_date,
            'facility_id' => auth()->user()->etcl_bhs_id,
            
            'length_atbirth' => $r->length_atbirth,
            'weight_atbirth' => $r->weight_atbirth,
            'weight_status' => $weight_status,
            'breastfeeding' => $r->breastfeeding,

            'lb_iron1' => $r->lb_iron1,
            'lb_iron2' => $r->lb_iron2,
            'lb_iron3' => $r->lb_iron3,
            'vita1' => $r->vita1,
            'vita2' => $r->vita2,
            'vita3' => $r->vita3,
            'mnp1' => $r->mnp1,
            'mnp2' => $r->mnp2,
            'lns1' => $r->lns1,
            'lns2' => $r->lns2,

            'mam_identified' => $r->mam_identified,
            'enrolled_sfp' => $r->enrolled_sfp,
            'mam_cured' => $r->mam_cured,
            'mam_noncured' => $r->mam_noncured,
            'mam_defaulted' => $r->mam_defaulted,
            'mam_died' => $r->mam_died,

            'sam_identified' => $r->sam_identified,
            'sam_complication' => $r->sam_complication,
            'sam_cured' => $r->sam_cured,
            'sam_noncured' => $r->sam_noncured,
            'sam_defaulted' => $r->sam_defaulted,
            'sam_died' => $r->sam_died,

            'remarks' => $r->remarks,
            'system_remarks' => $r->system_remarks,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,

            'request_uuid' => $r->request_uuid,

            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
        ];

        $c = InhouseChildNutrition::create($table_params);

        return redirect()
        ->route('etcl_home', ['type' => 'child_nutrition'])
        ->with('msg', 'Child Nutrition Record successfully saved.')
        ->with('msgtype', 'success');
    }

    public function editChildNutrition($id) {
        $d = InhouseChildNutrition::findOrFail($id);
        
        if(!auth()->user()->isGlobalAdmin()) {
            if($d->facility_id != auth()->user()->etcl_bhs_id) {
                return abort(403, 'Unauthorized access to this record.');
            }
        }
        
        return $this->newOrEditChildNutrition($d, 'EDIT', $d->id);
    }

    public function newOrEditChildNutrition(InhouseChildNutrition $record, $mode, $patient_id = null) {
        if($patient_id != null) {
            $patient = SyndromicPatient::findOrFail($patient_id);
        }

        return view('efhsis.etcl.childnutrition_encode', [
            'd' => $record,
            'mode' => $mode,
            'patient' => $patient,
        ]);
    }

    public function updateChildNutrition($id, Request $r) {
        $d = InhouseChildNutrition::findOrFail($id);

        $check = InhouseChildNutrition::where('request_uuid', $r->request_uuid)->first();

        if($check) {
            return redirect()
            ->back()
            ->with('msg', 'Error: This record has already been updated.')
            ->with('msgtype', 'warning');
        }

        $birthdate = Carbon::parse($d->patient->bdate);
        $currentDate = Carbon::parse($r->registration_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        if(!is_null($r->weight_atbirth)) {
            if($r->weight_atbirth < 2.5) {
                $weight_status = 'L'; //Low Birth Weight
            }
            else {
                $weight_status = 'N'; //Normal Birth Weight
            }
        }
        else {
            $weight_status = 'U';
        }
        
        $table_params = [
            //'patient_id' => $d->id,
            //'registration_date' => $r->registration_date,
            //'facility_id' => auth()->user()->etcl_bhs_id,
            
            'length_atbirth' => $r->length_atbirth,
            'weight_atbirth' => $r->weight_atbirth,
            'weight_status' => $weight_status,
            'breastfeeding' => $r->breastfeeding,

            'lb_iron1' => $r->lb_iron1,
            'lb_iron2' => $r->lb_iron2,
            'lb_iron3' => $r->lb_iron3,
            'vita1' => $r->vita1,
            'vita2' => $r->vita2,
            'vita3' => $r->vita3,
            'mnp1' => $r->mnp1,
            'mnp2' => $r->mnp2,
            'lns1' => $r->lns1,
            'lns2' => $r->lns2,

            'mam_identified' => $r->mam_identified,
            'enrolled_sfp' => $r->enrolled_sfp,
            'mam_cured' => $r->mam_cured,
            'mam_noncured' => $r->mam_noncured,
            'mam_defaulted' => $r->mam_defaulted,
            'mam_died' => $r->mam_died,

            'sam_identified' => $r->sam_identified,
            'sam_complication' => $r->sam_complication,
            'sam_cured' => $r->sam_cured,
            'sam_noncured' => $r->sam_noncured,
            'sam_defaulted' => $r->sam_defaulted,
            'sam_died' => $r->sam_died,

            'remarks' => $r->remarks,
            'system_remarks' => $r->system_remarks,
            //'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,

            'request_uuid' => $r->request_uuid,

            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
        ];

        $d->update($table_params);

        return redirect()
        ->route('etcl_home', ['type' => 'child_nutrition'])
        ->with('msg', 'Child Nutrition record successfully updated.')
        ->with('msgtype', 'success');
    }

    public function newFamilyPlanning($patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $find = InhouseFamilyPlanning::where('patient_id', $d->id)->first();

        if($find) {
            return redirect()
            ->route('etcl_familyplanning_view', $find->id)
            ->with('msg', 'Existing Family Planning record found for this patient. You can only encode one (1) Family Planning record per patient.')
            ->with('msgtype', 'info');
        }

        if($d->getAge() <= 9 || $d->getAge() >= 50) {
            return redirect()
            ->back()
            ->with('msg', 'Error: Family Planning can only be encoded for patients between 10 and 49 years old.')
            ->with('msgtype', 'warning');
        }

        return $this->newOrEditFamilyPlanning(new InhouseFamilyPlanning(), 'NEW', $d->id);
    }

    public function storeFamilyPlanning($patient_id, Request $r) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $check = InhouseFamilyPlanning::where('request_uuid', $r->request_uuid)->first();

        if($check) {
            return redirect()->
            back()
            ->with('msg', 'Error: This record has already been saved.')
            ->with('msgtype', 'warning');
        }

        if($d->getAge() <= 14) {
            $age_group = 'A'; //Adolescent
        }
        else if($d->getAge() >= 15 && $d->getAge() <= 19) { 
            $age_group = 'B'; //Young Adult
        }
        else {
            $age_group = 'C'; //Adult
        }

        $birthdate = Carbon::parse($d->bdate);
        $currentDate = Carbon::parse($r->registration_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        $table_params = [
            'patient_id' => $d->id,
            'facility_id' => auth()->user()->etcl_bhs_id,
            'registration_date' => $r->registration_date,
            'age_group' => $age_group,

            'client_type' => $r->client_type,
            'source' => $r->source,
            'previous_method' => $r->previous_method,
            'current_method' => $r->current_method,
            //'is_permanent' => $r->is_permanent,
            //'is_dropout' => $r->is_dropout,
            //'dropout_date' => $r->dropout_date,
            //'dropout_reason' => $r->dropout_reason,

            'remarks' => $r->remarks,
            'system_remarks' => $r->system_remarks,

            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'request_uuid' => $r->request_uuid,
            
            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
        ];

        $c = InhouseFamilyPlanning::create($table_params);

        return redirect()
        ->route('etcl_familyplanning_view', $c->id)
        ->with('msg', 'Family Planning Record successfully saved. You can now encode the client\'s family planning method details.')
        ->with('msgtype', 'success');
    }

    public function initializeFamilyPlanning($tcl_fp_id, Request $r) {
        
    }

    public function updateFamilyPlanningVisit($visit_id, Request $r) {

    }

    public function editFamilyPlanning($id) {
        $d = InhouseFamilyPlanning::findOrFail($id);
        
        if(!auth()->user()->isGlobalAdmin()) {
            if($d->facility_id != auth()->user()->etcl_bhs_id) {
                return abort(403, 'Unauthorized access to this record.');
            }
        }
        
        return $this->newOrEditFamilyPlanning($d, 'EDIT', $d->id);
    }

    public function newOrEditFamilyPlanning(InhouseFamilyPlanning $record, $mode, $patient_id = null) {
        if($patient_id != null) {
            $patient = SyndromicPatient::findOrFail($patient_id);
        }

        return view('efhsis.etcl.familyplanning_encode', [
            'd' => $record,
            'mode' => $mode,
            'patient' => $patient,
        ]);
    }

    public function updateFamilyPlanning($id, Request $r) {
        $d = InhouseFamilyPlanning::findOrFail($id);

        $check = InhouseFamilyPlanning::where('request_uuid', $r->request_uuid)->first();

        if($check) {
            return redirect()
            ->back()
            ->with('msg', 'Error: This record has already been updated.')
            ->with('msgtype', 'warning');
        }

        $birthdate = Carbon::parse($d->patient->bdate);
        $currentDate = Carbon::parse($r->registration_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);
    }

    public function searchMaternalCareMother(Request $request) {
        $q = trim($request->q);

        $rows = InhouseMaternalCare::with('patient')
            ->when($q, function ($query) use ($q) {
                $query->where('id', 'like', "%{$q}%")

                ->orWhereHas('patient', function ($p) use ($q) {
                    $p->where('lname', 'like', "%{$q}%")
                    ->orWhere('fname', 'like', "%{$q}%")
                    ->orWhere('mname', 'like', "%{$q}%");
                });

            })
            ->where('enabled', 'Y')
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get();

        return response()->json($rows);
    }

    public function storeChildCare(Request $r, $patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $check = InhouseChildCare::where('request_uuid', $r->request_uuid)->first();

        if($check) {
            return redirect()->
            back()
            ->with('msg', 'Error: This record has already been saved.')
            ->with('msgtype', 'warning');
        }

        $find = InhouseChildCare::where('patient_id', $d->id)->first();

        if($find) {
            return redirect()
            ->back()
            ->with('msg', 'Existing Child Care record found for this patient. You can only encode one (1) Child Care record per patient.')
            ->with('msgtype', 'info');
        }

        $birthdate = Carbon::parse($d->bdate);
        $currentDate = Carbon::parse($r->registration_date);

        $get_ageyears = $birthdate->diffInYears($currentDate);
        $get_agemonths = $birthdate->diffInMonths($currentDate);
        $get_agedays = $birthdate->diffInDays($currentDate);

        if($r->filled('ipv1')) {
            if(!$r->filled('dpt3')) {
                return redirect()
                ->back()
                ->withInput()
                ->with('msg', 'Error: DPT-HiB-Hepb 3rd Dose must be filled before encoding IPV 1st Dose.')
                ->with('msgtype', 'warning');
            }
            else if(!$r->filled('opv3')) {
                return redirect()
                ->back()
                ->withInput()
                ->with('msg', 'Error: OPV 3rd Dose must be filled before encoding IPV 1st Dose.')
                ->with('msgtype', 'warning');
            }
            else if(!$r->filled('pcv3')) {
                return redirect()
                ->back()
                ->withInput()
                ->with('msg', 'Error: PCV 3rd Dose must be filled before encoding IPV 1st Dose.')
                ->with('msgtype', 'warning');
            }
        }

        $table_params = [
            'patient_id' => $d->id,
            'facility_id' => auth()->user()->etcl_bhs_id,
            'registration_date' => $r->registration_date,
            'mother_type' => $r->mother_type,

            'bcg1' => $r->bcg1,
            'bcg1_type' => $r->bcg1_type,
            'bcg2' => $r->bcg2,
            'bcg2_type' => $r->bcg2_type,
            'hepab1' => $r->hepab1,
            'hepab1_type' => $r->hepab1_type,
            'hepab2' => $r->hepab2,
            'hepab2_type' => $r->hepab2_type,

            'dpt1' => $r->dpt1,
            'dpt1_type' => $r->dpt1_type,
            'dpt1_months' => ($r->filled('dpt1')) ? Carbon::parse($r->dpt1)->diffInMonths($birthdate) : null,
            'dpt2' => $r->dpt2,
            'dpt2_type' => $r->dpt2_type,
            'dpt2_months' => ($r->filled('dpt2')) ? Carbon::parse($r->dpt2)->diffInMonths($birthdate) : null,
            'dpt3' => $r->dpt3,
            'dpt3_type' => $r->dpt3_type,
            'dpt3_months' => ($r->filled('dpt3')) ? Carbon::parse($r->dpt3)->diffInMonths($birthdate) : null,
            'opv1' => $r->opv1,
            'opv1_type' => $r->opv1_type,
            'opv1_months' => ($r->filled('opv1')) ? Carbon::parse($r->opv1)->diffInMonths($birthdate) : null,
            'opv2' => $r->opv2,
            'opv2_type' => $r->opv2_type,
            'opv2_months' => ($r->filled('opv2')) ? Carbon::parse($r->opv2)->diffInMonths($birthdate) : null,
            'opv3' => $r->opv3,
            'opv3_type' => $r->opv3_type,
            'opv3_months' => ($r->filled('opv3')) ? Carbon::parse($r->opv3)->diffInMonths($birthdate) : null,
            'ipv1' => $r->ipv1,
            'ipv1_type' => $r->ipv1_type,
            'ipv1_months' => ($r->filled('ipv1')) ? Carbon::parse($r->ipv1)->diffInMonths($birthdate) : null,
            'ipv2' => $r->ipv2,
            'ipv2_type' => $r->ipv2_type,
            'ipv2_months' => ($r->filled('ipv2')) ? Carbon::parse($r->ipv2)->diffInMonths($birthdate) : null,
            'ipv3' => $r->ipv3,
            'ipv3_type' => $r->ipv3_type,
            'ipv3_months' => ($r->filled('ipv3')) ? Carbon::parse($r->ipv3)->diffInMonths($birthdate) : null,
            'pcv1' => $r->pcv1,
            'pcv1_type' => $r->pcv1_type,
            'pcv1_months' => ($r->filled('pcv1')) ? Carbon::parse($r->pcv1)->diffInMonths($birthdate) : null,
            'pcv2' => $r->pcv2,
            'pcv2_type' => $r->pcv2_type,
            'pcv2_months' => ($r->filled('pcv2')) ? Carbon::parse($r->pcv2)->diffInMonths($birthdate) : null,
            'pcv3' => $r->pcv3,
            'pcv3_type' => $r->pcv3_type,
            'pcv3_months' => ($r->filled('pcv3')) ? Carbon::parse($r->pcv3)->diffInMonths($birthdate) : null,
            'mmr1' => $r->mmr1,
            'mmr1_type' => $r->mmr1_type,
            'mmr1_months' => ($r->filled('mmr1')) ? Carbon::parse($r->mmr1)->diffInMonths($birthdate) : null,
            'mmr2' => $r->mmr2,
            'mmr2_type' => $r->mmr2_type,
            'mmr2_months' => ($r->filled('mmr2')) ? Carbon::parse($r->mmr2)->diffInMonths($birthdate) : null,
            'remarks' => $r->remarks,
            'system_remarks' => $r->system_remarks,

            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'request_uuid' => $r->request_uuid,

            'age_years' => $get_ageyears,
            'age_months' => $get_agemonths,
            'age_days' => $get_agedays,
        ];

        if($r->mother_type == 'Y') {
            //Search Mother
            $mcr = InhouseMaternalCare::find($r->maternalcare_id);

            if(!$mcr) {
                return redirect()
                ->back()
                ->with('msg', 'Error: Mother\'s Maternal Care Record not found.')
                ->with('msgtype', 'warning');
            }

            if(!is_null($mcr->td5) || !is_null($mcr->td4) || !is_null($mcr->td3)) {
                $cpab = '2';
            }
            else if(!is_null($mcr->td2)) {
                $cpab = '1';
            }
            else {
                $cpab = '0';
            }

            $table_params = $table_params + [
                'maternalcare_id' => $r->maternalcare_id,
            ];
        }
        else {
            $cpab = $r->cpab_manual;

            $table_params = $table_params + [
                'mother_name' => mb_strtoupper($r->mother_name),
                'cpab_type' => mb_strtoupper($r->cpab_type),
            ];
        }

        $table_params = $table_params + [
            'cpab' => $cpab,
        ];

        $c = InhouseChildCare::create($table_params);

        $d = InhouseChildCare::findOrFail($c->id);
        $d->runIndicatorUpdate();
        $d->save();

        return redirect()
        ->route('etcl_home', ['type' => 'child_care'])
        ->with('msg', 'Child Care Record successfully saved.')
        ->with('msgtype', 'success');
    }

    public function generateM1(Request $r) {
        $spreadsheet = ExcelFactory::load(storage_path('FHSIS_M1.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        if($r->month == 1) {
            $monthText = 'January';
        }
        else if($r->month == 2) {
            $monthText = 'February';
        }
        else if($r->month == 3) {
            $monthText = 'March';
        }
        else if($r->month == 4) {
            $monthText = 'April';
        }
        else if($r->month == 5) {
            $monthText = 'May';
        }
        else if($r->month == 6) {
            $monthText = 'June';
        }
        else if($r->month == 7) {
            $monthText = 'July';
        }
        else if($r->month == 8) {
            $monthText = 'August';
        }
        else if($r->month == 9) {
            $monthText = 'September';
        }
        else if($r->month == 10) {
            $monthText = 'October';
        }
        else if($r->month == 11) {
            $monthText = 'November';
        }
        else if($r->month == 12) {
            $monthText = 'December';
        }

        $sheet->setCellValue('D1', "FHSIS REPORT for the Month: {$monthText}, Year: {$r->year}");
        $sheet->setCellValue('D2', "Name of Barangay: ".auth()->user()->tclbhs->address_barangay);
        $sheet->setCellValue('D3', "Name of BHS: ".auth()->user()->tclbhs->facility_name);
        $sheet->setCellValue('D4', "Name of Municipality/City: ".auth()->user()->tclbhs->address_muncity);
        $sheet->setCellValue('D5', "Name of Province: ".auth()->user()->tclbhs->address_province);

        if(auth()->user()->isMasterAdminEtcl()) {
            if($r->filter_type == 'BHS') {
                $base_qry = InhouseMaternalCare::where('enabled', 'Y')
                ->where('facility_id', $r->selected_bhs_id);

                $cc_base_qry = InhouseChildCare::where('enabled', 'Y')
                ->where('facility_id', $r->selected_bhs_id);
            }
            else {
                $base_qry = InhouseMaternalCare::where('enabled', 'Y')
                ->whereHas('facility.brgy', function($q) use ($r) {
                    $q->where('id', $r->selected_brgy_id);
                });

                $cc_base_qry = InhouseChildCare::where('enabled', 'Y')
                ->whereHas('facility.brgy', function($q) use ($r) {
                    $q->where('id', $r->selected_brgy_id);
                });
            }
        }
        else {
            $base_qry = InhouseMaternalCare::where('enabled', 'Y')
            ->where('facility_id', auth()->user()->etcl_bhs_id);

            $cc_base_qry = InhouseChildCare::where('enabled', 'Y')
            ->where('facility_id', auth()->user()->etcl_bhs_id);
        }

        $qry = (clone $base_qry)
        ->whereYear('delivery_date', $r->year)
        ->whereMonth('delivery_date', $r->month)
        ->whereNotNull('visit4');

        $sheet->setCellValue('B39', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C39', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D39', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereYear('delivery_date', $r->year)
        ->whereMonth('delivery_date', $r->month)
        ->whereNotNull('visit8');

        $sheet->setCellValue('B40', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C40', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D40', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereYear('delivery_date', $r->year)
        ->whereMonth('delivery_date', $r->month)
        ->whereNotNull('visit8')
        ->where('visit1_type', '!=', 'OTHER RHU/BHS')
        ->where('visit2_type', '!=', 'OTHER RHU/BHS')
        ->where('visit3_type', '!=', 'OTHER RHU/BHS')
        ->where('visit4_type', '!=', 'OTHER RHU/BHS')
        ->where('visit5_type', '!=', 'OTHER RHU/BHS')
        ->where('visit6_type', '!=', 'OTHER RHU/BHS')
        ->where('visit7_type', '!=', 'OTHER RHU/BHS')
        ->where('visit8_type', '!=', 'OTHER RHU/BHS');

        $b41_value = (clone $qry)->where('age_group', 'A')->count();
        $c41_value = (clone $qry)->where('age_group', 'B')->count();
        $d41_value = (clone $qry)->where('age_group', 'C')->count();

        $sheet->setCellValue('B41', $b41_value);
        $sheet->setCellValue('C41', $c41_value);
        $sheet->setCellValue('D41', $d41_value);

        $sheet->setCellValue('B46', $b41_value);
        $sheet->setCellValue('C46', $c41_value);
        $sheet->setCellValue('D46', $d41_value);

        $qry = (clone $base_qry)
        ->whereYear('delivery_date', $r->year)
        ->whereMonth('delivery_date', $r->month)
        ->whereNotNull('visit8')
        ->where('trans_remarks', 'A');

        $sheet->setCellValue('B42', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C42', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D42', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->where('gravida', '>=', 2)
        ->where('td_lastdose_count', '>=', 3)
        ->whereYear('td_lastdose_date', $r->year)
        ->whereMonth('td_lastdose_date', $r->month);

        $sheet->setCellValue('Q39', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R39', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S39', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('ifa6_date')
        ->whereYear('ifa6_date', $r->year)
        ->whereMonth('ifa6_date', $r->month);

        $sheet->setCellValue('Q40', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R40', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S40', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('mms6_date')
        ->whereYear('mms6_date', $r->year)
        ->whereMonth('mms6_date', $r->month);

        $sheet->setCellValue('Q41', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R41', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S41', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->where('highrisk', 'Y')
        ->whereNotNull('calcium3_date')
        ->whereYear('calcium3_date', $r->year)
        ->whereMonth('calcium3_date', $r->month);

        $sheet->setCellValue('Q42', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R42', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S42', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('deworming_date')
        ->whereYear('deworming_date', $r->year)
        ->whereMonth('deworming_date', $r->month);

        $sheet->setCellValue('Q43', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R43', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S43', (clone $qry)->where('age_group', 'C')->count());
        
        $qry = (clone $base_qry)
        ->whereNotNull('delivery_date')
        ->whereYear('delivery_date', $r->year)
        ->whereMonth('delivery_date', $r->month)
        ->where('trans_remarks', 'A');

        $sheet->setCellValue('B47', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C47', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D47', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->where('trans_remarks', 'B')
        ->whereNull('visit8')
        ->whereNotNull('transout_date')
        ->whereYear('transout_date', $r->year)
        ->whereMonth('transout_date', $r->month);

        $sheet->setCellValue('B48', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C48', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D48', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('visit1')
        ->whereYear('visit1', $r->year)
        ->whereMonth('visit1', $r->month);

        $sheet->setCellValue('B49', (clone $qry)->where('nutritional_assessment', 'N')->where('age_group', 'A')->count());
        $sheet->setCellValue('C49', (clone $qry)->where('nutritional_assessment', 'N')->where('age_group', 'B')->count());
        $sheet->setCellValue('D49', (clone $qry)->where('nutritional_assessment', 'N')->where('age_group', 'C')->count());

        $sheet->setCellValue('B50', (clone $qry)->where('nutritional_assessment', 'L')->where('age_group', 'A')->count());
        $sheet->setCellValue('C50', (clone $qry)->where('nutritional_assessment', 'L')->where('age_group', 'B')->count());
        $sheet->setCellValue('D50', (clone $qry)->where('nutritional_assessment', 'L')->where('age_group', 'C')->count());

        $sheet->setCellValue('B51', (clone $qry)->where('nutritional_assessment', 'H')->where('age_group', 'A')->count());
        $sheet->setCellValue('C51', (clone $qry)->where('nutritional_assessment', 'H')->where('age_group', 'B')->count());
        $sheet->setCellValue('D51', (clone $qry)->where('nutritional_assessment', 'H')->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->where('gravida', 1)
        ->where('td_lastdose_count', '>=', 2)
        ->whereYear('td_lastdose_date', $r->year)
        ->whereMonth('td_lastdose_date', $r->month);

        $sheet->setCellValue('B52', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C52', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D52', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('hb_date')
        ->whereYear('hb_date', $r->year)
        ->whereMonth('hb_date', $r->month);

        $sheet->setCellValue('Q46', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R46', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S46', (clone $qry)->where('age_group', 'C')->count());

        $sheet->setCellValue('Q47', (clone $qry)->where('hb_result', 1)->where('age_group', 'A')->count());
        $sheet->setCellValue('R47', (clone $qry)->where('hb_result', 1)->where('age_group', 'B')->count());
        $sheet->setCellValue('S47', (clone $qry)->where('hb_result', 1)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('cbc_date')
        ->whereYear('cbc_date', $r->year)
        ->whereMonth('cbc_date', $r->month);

        $sheet->setCellValue('Q48', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R48', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S48', (clone $qry)->where('age_group', 'C')->count());

        $sheet->setCellValue('Q49', (clone $qry)->where('cbc_result', 1)->where('age_group', 'A')->count());
        $sheet->setCellValue('R49', (clone $qry)->where('cbc_result', 1)->where('age_group', 'B')->count());
        $sheet->setCellValue('S49', (clone $qry)->where('cbc_result', 1)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('diabetes_date')
        ->whereYear('diabetes_date', $r->year)
        ->whereMonth('diabetes_date', $r->month);

        $sheet->setCellValue('Q50', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R50', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S50', (clone $qry)->where('age_group', 'C')->count());

        $sheet->setCellValue('Q51', (clone $qry)->where('diabetes_result', 1)->where('age_group', 'A')->count());
        $sheet->setCellValue('R51', (clone $qry)->where('diabetes_result', 1)->where('age_group', 'B')->count());
        $sheet->setCellValue('S51', (clone $qry)->where('diabetes_result', 1)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('outcome')
        ->whereNotNull('delivery_date')
        ->whereYear('delivery_date', $r->year)
        ->whereMonth('delivery_date', $r->month);

        $sheet->setCellValue('B56', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C56', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D56', (clone $qry)->where('age_group', 'C')->count());

        $sheet->setCellValue('B57', (clone $qry)->where('age_group', 'A')->sum('number_livebirths'));
        $sheet->setCellValue('C57', (clone $qry)->where('age_group', 'B')->sum('number_livebirths'));
        $sheet->setCellValue('D57', (clone $qry)->where('age_group', 'C')->sum('number_livebirths'));

        $sheet->setCellValue('B58', (clone $qry)->where('weight_status', 'N')->where('age_group', 'A')->count());
        $sheet->setCellValue('C58', (clone $qry)->where('weight_status', 'N')->where('age_group', 'B')->count());
        $sheet->setCellValue('D58', (clone $qry)->where('weight_status', 'N')->where('age_group', 'C')->count());

        $sheet->setCellValue('B59', (clone $qry)->where('weight_status', 'L')->where('age_group', 'A')->count());
        $sheet->setCellValue('C59', (clone $qry)->where('weight_status', 'L')->where('age_group', 'B')->count());
        $sheet->setCellValue('D59', (clone $qry)->where('weight_status', 'L')->where('age_group', 'C')->count());

        $sheet->setCellValue('B60', (clone $qry)->where('weight_status', 'U')->where('age_group', 'A')->count());
        $sheet->setCellValue('C60', (clone $qry)->where('weight_status', 'U')->where('age_group', 'B')->count());
        $sheet->setCellValue('D60', (clone $qry)->where('weight_status', 'U')->where('age_group', 'C')->count());

        $sheet->setCellValue('B61', (clone $qry)->where('attendant', '!=', 'O')->where('age_group', 'A')->count());
        $sheet->setCellValue('C61', (clone $qry)->where('attendant', '!=', 'O')->where('age_group', 'B')->count());
        $sheet->setCellValue('D61', (clone $qry)->where('attendant', '!=', 'O')->where('age_group', 'C')->count());

        $sheet->setCellValue('B63', (clone $qry)->where('attendant', 'MD')->where('age_group', 'A')->count());
        $sheet->setCellValue('C63', (clone $qry)->where('attendant', 'MD')->where('age_group', 'B')->count());
        $sheet->setCellValue('D63', (clone $qry)->where('attendant', 'MD')->where('age_group', 'C')->count());

        $sheet->setCellValue('B64', (clone $qry)->where('attendant', 'RN')->where('age_group', 'A')->count());
        $sheet->setCellValue('C64', (clone $qry)->where('attendant', 'RN')->where('age_group', 'B')->count());
        $sheet->setCellValue('D64', (clone $qry)->where('attendant', 'RN')->where('age_group', 'C')->count());

        $sheet->setCellValue('B65', (clone $qry)->where('attendant', 'MW')->where('age_group', 'A')->count());
        $sheet->setCellValue('C65', (clone $qry)->where('attendant', 'MW')->where('age_group', 'B')->count());
        $sheet->setCellValue('D65', (clone $qry)->where('attendant', 'MW')->where('age_group', 'C')->count());

        $sheet->setCellValue('Q57', (clone $qry)->where('facility_type', 'PUBLIC')->where('age_group', 'A')->count());
        $sheet->setCellValue('R57', (clone $qry)->where('facility_type', 'PUBLIC')->where('age_group', 'B')->count());
        $sheet->setCellValue('S57', (clone $qry)->where('facility_type', 'PUBLIC')->where('age_group', 'C')->count());

        $sheet->setCellValue('Q58', (clone $qry)->where('facility_type', 'PRIVATE')->where('age_group', 'A')->count());
        $sheet->setCellValue('R58', (clone $qry)->where('facility_type', 'PRIVATE')->where('age_group', 'B')->count());
        $sheet->setCellValue('S58', (clone $qry)->where('facility_type', 'PRIVATE')->where('age_group', 'C')->count());

        $sheet->setCellValue('Q59', (clone $qry)->where('delivery_type', 'VD')->where('age_group', 'A')->count());
        $sheet->setCellValue('R59', (clone $qry)->where('delivery_type', 'VD')->where('age_group', 'B')->count());
        $sheet->setCellValue('S59', (clone $qry)->where('delivery_type', 'VD')->where('age_group', 'C')->count());

        $sheet->setCellValue('Q60', (clone $qry)->where('delivery_type', 'CS')->where('age_group', 'A')->count());
        $sheet->setCellValue('R60', (clone $qry)->where('delivery_type', 'CS')->where('age_group', 'B')->count());
        $sheet->setCellValue('S60', (clone $qry)->where('delivery_type', 'CS')->where('age_group', 'C')->count());

        $sheet->setCellValue('Q61', (clone $qry)->where('delivery_type', 'CVCD')->where('age_group', 'A')->count());
        $sheet->setCellValue('R61', (clone $qry)->where('delivery_type', 'CVCD')->where('age_group', 'B')->count());
        $sheet->setCellValue('S61', (clone $qry)->where('delivery_type', 'CVCD')->where('age_group', 'C')->count());

        $sheet->setCellValue('Q62', (clone $qry)->where('outcome', 'FT')->where('age_group', 'A')->count());
        $sheet->setCellValue('R62', (clone $qry)->where('outcome', 'FT')->where('age_group', 'B')->count());
        $sheet->setCellValue('S62', (clone $qry)->where('outcome', 'FT')->where('age_group', 'C')->count());

        $sheet->setCellValue('Q63', (clone $qry)->where('outcome', 'PT')->where('age_group', 'A')->count());
        $sheet->setCellValue('R63', (clone $qry)->where('outcome', 'PT')->where('age_group', 'B')->count());
        $sheet->setCellValue('S63', (clone $qry)->where('outcome', 'PT')->where('age_group', 'C')->count());

        $sheet->setCellValue('Q64', (clone $qry)->where('outcome', 'FD')->where('age_group', 'A')->count());
        $sheet->setCellValue('R64', (clone $qry)->where('outcome', 'FD')->where('age_group', 'B')->count());
        $sheet->setCellValue('S64', (clone $qry)->where('outcome', 'FD')->where('age_group', 'C')->count());

        $sheet->setCellValue('Q65', (clone $qry)->where('outcome', 'AB')->where('age_group', 'A')->count());
        $sheet->setCellValue('R65', (clone $qry)->where('outcome', 'AB')->where('age_group', 'B')->count());
        $sheet->setCellValue('S65', (clone $qry)->where('outcome', 'AB')->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('outcome')
        ->whereNotNull('pnc2')
        ->whereYear('pnc2', $r->year)
        ->whereMonth('pnc2', $r->month);

        $sheet->setCellValue('B69', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C69', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D69', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('outcome')
        ->whereNull('pp_remarks')
        ->whereYear('pnc4', $r->year)
        ->whereMonth('pnc4', $r->month);

        $sheet->setCellValue('B71', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C71', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D71', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('outcome')
        ->whereNull('pp_remarks')
        ->whereYear('pnc4', $r->year)
        ->whereMonth('pnc4', $r->month);

        $sheet->setCellValue('B72', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C72', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D72', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('outcome')
        ->whereNotNull('pp_td3')
        ->whereYear('pp_td3', $r->year)
        ->whereMonth('pp_td3', $r->month);

        $sheet->setCellValue('Q69', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R69', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S69', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('outcome')
        ->whereNotNull('vita')
        ->whereYear('vita', $r->year)
        ->whereMonth('vita', $r->month);

        $sheet->setCellValue('Q70', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('R70', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('S70', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('outcome')
        ->where('pp_remarks', 'A')
        ->whereYear('registration_date', $r->year)
        ->whereMonth('registration_date', $r->month);

        $sheet->setCellValue('B80', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C80', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D80', (clone $qry)->where('age_group', 'C')->count());

        $qry = (clone $base_qry)
        ->whereNotNull('outcome')
        ->where('pp_remarks', 'B')
        ->whereYear('pp_transout_date', $r->year)
        ->whereMonth('pp_transout_date', $r->month);

        $sheet->setCellValue('B81', (clone $qry)->where('age_group', 'A')->count());
        $sheet->setCellValue('C81', (clone $qry)->where('age_group', 'B')->count());
        $sheet->setCellValue('D81', (clone $qry)->where('age_group', 'C')->count());

        //CHILD CARE TCL
        $qry = (clone $cc_base_qry)
        ->whereIn('cpab', ['1', '2'])
        ->whereYear('registration_date', $r->year)
        ->whereMonth('registration_date', $r->month);

        $sheet->setCellValue('B87', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })->count());
        $sheet->setCellValue('C87', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('bcg1')
        ->whereYear('bcg1', $r->year)
        ->whereMonth('bcg1', $r->month)
        ->where('bcg1_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('B88', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })->count());
        $sheet->setCellValue('C88', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('bcg2')
        ->whereYear('bcg2', $r->year)
        ->whereMonth('bcg2', $r->month)
        ->where('bcg2_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('B89', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })->count());
        $sheet->setCellValue('C89', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('hepab1')
        ->whereYear('hepab1', $r->year)
        ->whereMonth('hepab1', $r->month)
        ->where('hepab1_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('Q87', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })->count());
        $sheet->setCellValue('R87', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('hepab2')
        ->whereYear('hepab2', $r->year)
        ->whereMonth('hepab2', $r->month)
        ->where('hepab2_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('Q88', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })->count());
        $sheet->setCellValue('R88', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('dpt1')
        ->whereYear('dpt1', $r->year)
        ->whereMonth('dpt1', $r->month)
        ->where('dpt1_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('B91', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('dpt1_months', '<=', 12)
        ->count());
        $sheet->setCellValue('C91', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('dpt1_months', '<=', 12)
        ->count());

        $sheet->setCellValue('B99', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('dpt1_months', '>=', 13)
        ->count());
        $sheet->setCellValue('C99', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('dpt1_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('dpt2')
        ->whereYear('dpt2', $r->year)
        ->whereMonth('dpt2', $r->month)
        ->where('dpt2_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('B92', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('dpt2_months', '<=', 12)
        ->count());
        $sheet->setCellValue('C92', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('dpt2_months', '<=', 12)
        ->count());

        $sheet->setCellValue('B100', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('dpt2_months', '>=', 13)
        ->count());
        $sheet->setCellValue('C100', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('dpt2_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('dpt3')
        ->whereYear('dpt3', $r->year)
        ->whereMonth('dpt3', $r->month)
        ->where('dpt3_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('B93', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('dpt3_months', '<=', 12)
        ->count());
        $sheet->setCellValue('C93', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('dpt3_months', '<=', 12)
        ->count());

        $sheet->setCellValue('B101', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('dpt3_months', '>=', 13)
        ->count());
        $sheet->setCellValue('C101', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('dpt3_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('opv1')
        ->whereYear('opv1', $r->year)
        ->whereMonth('opv1', $r->month)
        ->where('opv1_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('B94', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('opv1_months', '<=', 12)
        ->count());
        $sheet->setCellValue('C94', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('opv1_months', '<=', 12)
        ->count());

        $sheet->setCellValue('B102', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('opv1_months', '>=', 13)
        ->count());
        $sheet->setCellValue('C102', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('opv1_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('opv2')
        ->whereYear('opv2', $r->year)
        ->whereMonth('opv2', $r->month)
        ->where('opv2_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('B95', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('opv2_months', '<=', 12)
        ->count());
        $sheet->setCellValue('C95', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('opv2_months', '<=', 12)
        ->count());

        $sheet->setCellValue('B103', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('opv2_months', '>=', 13)
        ->count());
        $sheet->setCellValue('C103', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('opv2_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('opv3')
        ->whereYear('opv3', $r->year)
        ->whereMonth('opv3', $r->month)
        ->where('opv3_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('B96', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('opv3_months', '<=', 12)
        ->count());
        $sheet->setCellValue('C96', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('opv3_months', '<=', 12)
        ->count());

        $sheet->setCellValue('B104', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('opv3_months', '>=', 13)
        ->count());
        $sheet->setCellValue('C104', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('opv3_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('ipv1')
        ->whereYear('ipv1', $r->year)
        ->whereMonth('ipv1', $r->month)
        ->where('ipv1_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('B97', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('ipv1_months', '<=', 12)
        ->count());
        $sheet->setCellValue('C97', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('ipv1_months', '<=', 12)
        ->count());

        $sheet->setCellValue('B105', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('ipv1_months', '>=', 13)
        ->count());
        $sheet->setCellValue('C105', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('ipv1_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('ipv2')
        ->whereYear('ipv2', $r->year)
        ->whereMonth('ipv2', $r->month)
        ->where('ipv2_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('Q91', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('ipv2_months', '<=', 12)
        ->count());
        $sheet->setCellValue('R91', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('ipv2_months', '<=', 12)
        ->count());

        $sheet->setCellValue('Q99', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('ipv2_months', '>=', 13)
        ->count());
        $sheet->setCellValue('R99', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('ipv2_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('pcv1')
        ->whereYear('pcv1', $r->year)
        ->whereMonth('pcv1', $r->month)
        ->where('pcv1_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('Q92', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('pcv1_months', '<=', 12)
        ->count());
        $sheet->setCellValue('R92', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('pcv1_months', '<=', 12)
        ->count());

        $sheet->setCellValue('Q100', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('pcv1_months', '>=', 13)
        ->count());
        $sheet->setCellValue('R100', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('pcv1_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('pcv2')
        ->whereYear('pcv2', $r->year)
        ->whereMonth('pcv2', $r->month)
        ->where('pcv2_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('Q93', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('pcv2_months', '<=', 12)
        ->count());
        $sheet->setCellValue('R93', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('pcv2_months', '<=', 12)
        ->count());

        $sheet->setCellValue('Q101', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('pcv2_months', '>=', 13)
        ->count());
        $sheet->setCellValue('R101', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('pcv2_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('pcv3')
        ->whereYear('pcv3', $r->year)
        ->whereMonth('pcv3', $r->month)
        ->where('pcv3_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('Q94', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('pcv3_months', '<=', 12)
        ->count());
        $sheet->setCellValue('R94', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('pcv3_months', '<=', 12)
        ->count());

        $sheet->setCellValue('Q102', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('pcv3_months', '>=', 13)
        ->count());
        $sheet->setCellValue('R102', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('pcv3_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('mmr1')
        ->whereYear('mmr1', $r->year)
        ->whereMonth('mmr1', $r->month)
        ->where('mmr1_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('Q95', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('mmr1_months', '<=', 12)
        ->count());
        $sheet->setCellValue('R95', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('mmr1_months', '<=', 12)
        ->count());

        $sheet->setCellValue('Q103', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('mmr1_months', '>=', 13)
        ->count());
        $sheet->setCellValue('R103', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('mmr1_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('mmr2')
        ->whereYear('mmr2', $r->year)
        ->whereMonth('mmr2', $r->month)
        ->where('mmr2_type', '!=', 'OTHER RHU/BHS');

        $sheet->setCellValue('Q96', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('mmr2_months', '<=', 12)
        ->count());
        $sheet->setCellValue('R96', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('mmr2_months', '<=', 12)
        ->count());

        $sheet->setCellValue('Q104', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })
        ->where('mmr2_months', '>=', 13)
        ->count());
        $sheet->setCellValue('R104', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })
        ->where('mmr2_months', '>=', 13)
        ->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('fic')
        ->whereYear('fic', $r->year)
        ->whereMonth('fic', $r->month);

        $sheet->setCellValue('Q97', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })->count());
        $sheet->setCellValue('R97', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })->count());

        $qry = (clone $cc_base_qry)
        ->whereNotNull('cic')
        ->whereYear('cic', $r->year)
        ->whereMonth('cic', $r->month);

        $sheet->setCellValue('Q105', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'MALE');
        })->count());
        $sheet->setCellValue('R105', (clone $qry)->whereHas('patient', function ($q) {
            $q->where('gender', 'FEMALE');
        })->count());

        $fileName = "FHSIS_M1_".$r->year."_".$r->month."_".time().".xlsx";
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    public function generateTcl(Request $r) {
        $start_date = Carbon::parse($r->start_date);
        $end_date = Carbon::parse($r->end_date);

        if($r->etcl_type == 'child_care') {
            $spreadsheet = ExcelFactory::load(storage_path('etcl_child_care.xlsx'));
            $sheet = $spreadsheet->getActiveSheet();

            $row = 9;

            $base_qry = InhouseChildCare::whereBetween('registration_date', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->where('enabled', 'Y');

            if(auth()->user()->isMasterAdminEtcl()) {
                if($r->filter_type == 'BHS') {
                    $qry = (clone $base_qry)
                    ->where('facility_id', $r->selected_bhs_id)
                    ->get();
                }
                else {
                    $qry = (clone $base_qry)
                    ->whereHas('facility.brgy', function($q) use ($r) {
                        $q->where('id', $r->selected_brgy_id);
                    })->get();
                }
            }
            else {
                $qry = (clone $base_qry)
                ->where('facility_id', auth()->user()->etcl_bhs_id)
                ->get();
            }

            foreach($qry as $ind => $d) {
                $sheet->setCellValue('A'.$row, $ind + 1);
                $sheet->setCellValue('B'.$row, Carbon::parse($d->registration_date)->format('m/d/Y'));
                $sheet->setCellValue('C'.$row, '');
                $sheet->setCellValue('D'.$row, $d->patient->getName());
                $sheet->setCellValue('E'.$row, substr($d->patient->gender,0,1));
                $sheet->setCellValue('F'.$row, Carbon::parse($d->patient->bdate)->format('m/d/Y'));

                if($d->mother_type == 'Y') {
                    $sheet->setCellValue('G'.$row, $d->maternalcare->patient->getName());
                }
                else {
                    $sheet->setCellValue('G'.$row, $d->mother_name);
                }

                $sheet->setCellValue('H'.$row, $d->patient->getFullAddress());

                $sheet->setCellValue('I'.$row, $d->cpab == 1 ? '✔' : '');
                $sheet->setCellValue('J'.$row, $d->cpab == 2 ? '✔' : '');
                
                $sheet->setCellValue('K'.$row, (!is_null($d->bcg1) ? Carbon::parse($d->bcg1)->format('m/d/Y') : ''));
                $sheet->getStyle('K'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->bcg1_type));
                $sheet->setCellValue('L'.$row, (!is_null($d->bcg2) ? Carbon::parse($d->bcg2)->format('m/d/Y') : ''));
                $sheet->getStyle('L'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->bcg2_type));

                $sheet->setCellValue('M'.$row, (!is_null($d->hepab1) ? Carbon::parse($d->hepab1)->format('m/d/Y') : ''));
                $sheet->getStyle('M'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->hepab1_type));
                $sheet->setCellValue('N'.$row, (!is_null($d->hepab2) ? Carbon::parse($d->hepab2)->format('m/d/Y') : ''));
                $sheet->getStyle('N'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->hepab2_type));

                $sheet->setCellValue('O'.$row, (!is_null($d->dpt1) ? Carbon::parse($d->dpt1)->format('m/d/Y') : ''));
                $sheet->getStyle('O'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->dpt1_type));
                $sheet->setCellValue('P'.$row, (!is_null($d->dpt2) ? Carbon::parse($d->dpt2)->format('m/d/Y') : ''));
                $sheet->getStyle('P'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->dpt2_type));
                $sheet->setCellValue('Q'.$row, (!is_null($d->dpt3) ? Carbon::parse($d->dpt3)->format('m/d/Y') : ''));
                $sheet->getStyle('Q'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->dpt3_type));

                $sheet->setCellValue('R'.$row, (!is_null($d->opv1) ? Carbon::parse($d->opv1)->format('m/d/Y') : ''));
                $sheet->getStyle('R'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->opv1_type));
                $sheet->setCellValue('S'.$row, (!is_null($d->opv2) ? Carbon::parse($d->opv2)->format('m/d/Y') : ''));
                $sheet->getStyle('S'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->opv2_type));
                $sheet->setCellValue('T'.$row, (!is_null($d->opv3) ? Carbon::parse($d->opv3)->format('m/d/Y') : ''));
                $sheet->getStyle('T'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->opv3_type));

                $sheet->setCellValue('U'.$row, (!is_null($d->ipv1) ? Carbon::parse($d->ipv1)->format('m/d/Y') : ''));
                $sheet->getStyle('U'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->ipv1_type));
                $sheet->setCellValue('V'.$row, (!is_null($d->ipv2) ? Carbon::parse($d->ipv2)->format('m/d/Y') : ''));
                $sheet->getStyle('V'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->ipv2_type));

                $sheet->setCellValue('W'.$row, (!is_null($d->pcv1) ? Carbon::parse($d->pcv1)->format('m/d/Y') : ''));
                $sheet->getStyle('W'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->pcv1_type));
                $sheet->setCellValue('X'.$row, (!is_null($d->pcv2) ? Carbon::parse($d->pcv2)->format('m/d/Y') : ''));
                $sheet->getStyle('X'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->pcv2_type));
                $sheet->setCellValue('Y'.$row, (!is_null($d->pcv3) ? Carbon::parse($d->pcv3)->format('m/d/Y') : ''));
                $sheet->getStyle('Y'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->pcv3_type));

                $sheet->setCellValue('Z'.$row, (!is_null($d->mmr1) ? Carbon::parse($d->mmr1)->format('m/d/Y') : ''));
                $sheet->getStyle('Z'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->mmr1_type));
                $sheet->setCellValue('AA'.$row, (!is_null($d->mmr2) ? Carbon::parse($d->mmr2)->format('m/d/Y') : ''));
                $sheet->getStyle('AA'.$row)->getFont()->getColor()
                ->setARGB($d->colorFromType($d->mmr2_type));

                $sheet->setCellValue('AB'.$row,  $d->isFic() ? '✔' : '');
                $sheet->setCellValue('AC'.$row,  $d->isCic() ? '✔' : '');

                $sheet->setCellValue('AD'.$row,  $d->system_remarks);

                $row++;
            }
        }
        else if($r->etcl_type == 'maternal_care') {
            $spreadsheet = ExcelFactory::load(storage_path('etcl_maternal_care.xlsx'));
            $sheet = $spreadsheet->getActiveSheet();

            $row = 6;

            $base_qry = InhouseMaternalCare::whereBetween('registration_date', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->where('enabled', 'Y');

            if(auth()->user()->isMasterAdminEtcl()) {
                if($r->filter_type == 'BHS') {
                    $qry = (clone $base_qry)
                    ->where('facility_id', $r->selected_bhs_id)
                    ->get();
                }
                else {
                    $qry = (clone $base_qry)
                    ->whereHas('facility.brgy', function($q) use ($r) {
                        $q->where('id', $r->selected_brgy_id);
                    })->get();
                }
            }
            else {
                $qry = (clone $base_qry)
                ->where('facility_id', auth()->user()->etcl_bhs_id)
                ->get();
            }

            foreach($qry as $ind => $d) {
                $sheet->setCellValue('A'.$row, $ind + 1);
                $sheet->setCellValue('B'.$row, Carbon::parse($d->registration_date)->format('m/d/Y'));
                $sheet->setCellValue('C'.$row, ($d->patient->inhouseFamilySerials) ? $d->patient->inhouseFamilySerials->inhouse_familyserialno : 'N/A');
                $sheet->setCellValue('D'.$row, $d->patient->getName());
                $sheet->setCellValue('E'.$row, $d->patient->getFullAddress());
                $sheet->setCellValue('F'.$row, $d->age_years);
                $sheet->setCellValue('G'.$row, $d->age_group);
                $sheet->setCellValue('H'.$row, 'LMP: '.Carbon::parse($d->lmp)->format('m/d/Y'));
                $sheet->setCellValue('H'.($row+1), 'G: '.$d->gravida.' P: '.$d->parity);
                $sheet->setCellValue('I'.$row, Carbon::parse($d->edc)->format('m/d/Y'));
                $sheet->setCellValue('J'.$row, (!is_null($d->visit1)) ? Carbon::parse($d->visit1)->format('m/d/Y') : '');
                $sheet->setCellValue('K'.$row, (!is_null($d->visit2)) ? Carbon::parse($d->visit2)->format('m/d/Y') : '');
                $sheet->setCellValue('L'.$row, (!is_null($d->visit3)) ? Carbon::parse($d->visit3)->format('m/d/Y') : '');
                $sheet->setCellValue('M'.$row, (!is_null($d->visit4)) ? Carbon::parse($d->visit4)->format('m/d/Y') : '');
                $sheet->setCellValue('N'.$row, (!is_null($d->visit5)) ? Carbon::parse($d->visit5)->format('m/d/Y') : '');
                $sheet->setCellValue('O'.$row, (!is_null($d->visit6)) ? Carbon::parse($d->visit6)->format('m/d/Y') : '');
                $sheet->setCellValue('P'.$row, (!is_null($d->visit7)) ? Carbon::parse($d->visit7)->format('m/d/Y') : '');
                $sheet->setCellValue('Q'.$row, (!is_null($d->visit8)) ? Carbon::parse($d->visit8)->format('m/d/Y') : '');

                $sheet->getStyle('J'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->visit1_type));
                $sheet->getStyle('K'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->visit2_type));
                $sheet->getStyle('L'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->visit3_type));
                $sheet->getStyle('M'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->visit4_type));
                $sheet->getStyle('N'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->visit5_type));
                $sheet->getStyle('O'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->visit6_type));
                $sheet->getStyle('P'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->visit7_type));
                $sheet->getStyle('Q'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->visit8_type));

                $sheet->setCellValue('R'.$row, $d->completed_8anc == 'Y' ? '1' : '0');
                
                if($d->nutritional_assessment == 'L') {
                    $sheet->setCellValue('S'.$row, $d->bmi);
                }
                else if($d->nutritional_assessment == 'N') {
                    $sheet->setCellValue('T'.$row, $d->bmi);
                }
                else if($d->nutritional_assessment == 'H') {
                    $sheet->setCellValue('U'.$row, $d->bmi);
                }

                $sheet->setCellValue('V'.$row, $d->trans_remarks);

                $sheet->setCellValue('W'.$row, (!is_null($d->td1)) ? Carbon::parse($d->td1)->format('m/d/Y') : '');
                $sheet->setCellValue('X'.$row, (!is_null($d->td2)) ? Carbon::parse($d->td2)->format('m/d/Y') : '');
                $sheet->setCellValue('Y'.$row, (!is_null($d->td3)) ? Carbon::parse($d->td3)->format('m/d/Y') : '');
                $sheet->setCellValue('Z'.$row, (!is_null($d->td4)) ? Carbon::parse($d->td4)->format('m/d/Y') : '');
                $sheet->setCellValue('AA'.$row, (!is_null($d->td5)) ? Carbon::parse($d->td5)->format('m/d/Y') : '');

                $sheet->getStyle('W'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->td1_type));
                $sheet->getStyle('X'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->td2_type));
                $sheet->getStyle('Y'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->td3_type));
                $sheet->getStyle('Z'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->td4_type));
                $sheet->getStyle('AA'.$row)->getFont()->getColor()->setARGB($d->colorFromType($d->td5_type));

                $sheet->setCellValue('AB'.$row, $d->fim_status == 'Y' ? '✔' : 'X');

                $sheet->setCellValue('AC'.$row, !is_null($d->deworming_date) ? 'Y' : 'N');
                $sheet->setCellValue('AC'.($row+1), !is_null($d->deworming_date) ? Carbon::parse($d->deworming_date)->format('m/d/Y') : 'N/A');

                $sheet->setCellValue('AD'.$row, !is_null($d->ifa1_date) ? '#: '.$d->ifa1_dosage : '#:');
                $sheet->setCellValue('AD'.($row+1), !is_null($d->ifa1_date) ? 'd: '.Carbon::parse($d->ifa1_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AD'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->ifa1_type));
                $sheet->setCellValue('AE'.$row, !is_null($d->ifa2_date) ? '#: '.$d->ifa2_dosage : '#:');
                $sheet->setCellValue('AE'.($row+1), !is_null($d->ifa2_date) ? 'd: '.Carbon::parse($d->ifa2_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AE'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->ifa2_type));
                $sheet->setCellValue('AF'.$row, !is_null($d->ifa3_date) ? '#: '.$d->ifa3_dosage : '#:');
                $sheet->setCellValue('AF'.($row+1), !is_null($d->ifa3_date) ? 'd: '.Carbon::parse($d->ifa3_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AF'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->ifa3_type));
                $sheet->setCellValue('AG'.$row, !is_null($d->ifa4_date) ? '#: '.$d->ifa4_dosage : '#:');
                $sheet->setCellValue('AG'.($row+1), !is_null($d->ifa4_date) ? 'd: '.Carbon::parse($d->ifa4_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AG'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->ifa4_type));
                $sheet->setCellValue('AH'.$row, !is_null($d->ifa5_date) ? '#: '.$d->ifa5_dosage : '#:');
                $sheet->setCellValue('AH'.($row+1), !is_null($d->ifa5_date) ? 'd: '.Carbon::parse($d->ifa5_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AH'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->ifa5_type));
                $sheet->setCellValue('AI'.$row, !is_null($d->ifa6_date) ? '#: '.$d->ifa6_dosage : '#:');
                $sheet->setCellValue('AI'.($row+1), !is_null($d->ifa6_date) ? 'd: '.Carbon::parse($d->ifa6_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AI'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->ifa6_type));
                $sheet->setCellValue('AJ'.$row, $d->completed_ifa == 'Y' ? '1' : '0');
                $sheet->setCellValue('AJ'.($row+1), $d->completed_ifa == 'Y' ? 'd: '.Carbon::parse($d->ifa6_date)->format('m/d/Y') : 'd:');
                
                $sheet->setCellValue('AK'.$row, !is_null($d->mms1_date) ? '#: '.$d->mms1_dosage : '#:');
                $sheet->setCellValue('AK'.($row+1), !is_null($d->mms1_date) ? 'd: '.Carbon::parse($d->mms1_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AK'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->mms1_type));
                $sheet->setCellValue('AL'.$row, !is_null($d->mms2_date) ? '#: '.$d->mms2_dosage : '#:');
                $sheet->setCellValue('AL'.($row+1), !is_null($d->mms2_date) ? 'd: '.Carbon::parse($d->mms2_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AL'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->mms2_type));
                $sheet->setCellValue('AM'.$row, !is_null($d->mms3_date) ? '#: '.$d->mms3_dosage : '#:');
                $sheet->setCellValue('AM'.($row+1), !is_null($d->mms3_date) ? 'd: '.Carbon::parse($d->mms3_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AM'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->mms3_type));
                $sheet->setCellValue('AN'.$row, !is_null($d->mms4_date) ? '#: '.$d->mms4_dosage : '#:');
                $sheet->setCellValue('AN'.($row+1), !is_null($d->mms4_date) ? 'd: '.Carbon::parse($d->mms4_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AN'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->mms4_type));
                $sheet->setCellValue('AO'.$row, !is_null($d->mms5_date) ? '#: '.$d->mms5_dosage : '#:');
                $sheet->setCellValue('AO'.($row+1), !is_null($d->mms5_date) ? 'd: '.Carbon::parse($d->mms5_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AO'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->mms5_type));
                $sheet->setCellValue('AP'.$row, !is_null($d->mms6_date) ? '#: '.$d->mms6_dosage : '#:');
                $sheet->setCellValue('AP'.($row+1), !is_null($d->mms6_date) ? 'd: '.Carbon::parse($d->mms6_date)->format('m/d/Y') : 'd:');
                $sheet->getStyle('AP'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->mms6_type));
                $sheet->setCellValue('AQ'.$row, $d->completed_mms == 'Y' ? '1' : '0');
                $sheet->setCellValue('AQ'.($row+1), $d->completed_mms == 'Y' ? 'd: '.Carbon::parse($d->mms6_date)->format('m/d/Y') : 'd:');

                if($d->highrisk == 'Y') {
                    $sheet->setCellValue('AR'.$row, !is_null($d->calcium1_date) ? '#: '.$d->calcium1_dosage : '#:');
                    $sheet->setCellValue('AR'.($row+1), !is_null($d->calcium1_date) ? 'd: '.Carbon::parse($d->calcium1_date)->format('m/d/Y') : 'd:');
                    $sheet->getStyle('AR'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->calcium1_type));
                    $sheet->setCellValue('AS'.$row, !is_null($d->calcium2_date) ? '#: '.$d->calcium2_dosage : '#:');
                    $sheet->setCellValue('AS'.($row+1), !is_null($d->calcium2_date) ? 'd: '.Carbon::parse($d->calcium2_date)->format('m/d/Y') : 'd:');
                    $sheet->getStyle('AS'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->calcium2_type));
                    $sheet->setCellValue('AT'.$row, !is_null($d->calcium3_date) ? '#: '.$d->calcium3_dosage : '#:');
                    $sheet->setCellValue('AT'.($row+1), !is_null($d->calcium3_date) ? 'd: '.Carbon::parse($d->calcium3_date)->format('m/d/Y') : 'd:');
                    $sheet->getStyle('AT'.($row+1))->getFont()->getColor()->setARGB($d->colorFromType($d->calcium3_type));

                    $sheet->setCellValue('AU'.$row, $d->completed_calcium == 'Y' ? '1' : '0');
                    $sheet->setCellValue('AU'.($row+1), $d->completed_calcium == 'Y' ? 'd: '.Carbon::parse($d->calcium3_date)->format('m/d/Y') : 'd:');
                }

                $sheet->setCellValue('AV'.$row, !is_null($d->syphilis_date) ? Carbon::parse($d->syphilis_date)->format('m/d/Y') : '');
                $sheet->setCellValue('AW'.$row, !is_null($d->syphilis_date) ? $d->syphilis_result : '');
                $sheet->setCellValue('AX'.$row, !is_null($d->hiv_date) ? Carbon::parse($d->hiv_date)->format('m/d/Y') : '');
                $sheet->setCellValue('AY'.$row, !is_null($d->hiv_date) ? $d->hiv_result : '');
                $sheet->setCellValue('AZ'.$row, !is_null($d->hb_date) ? Carbon::parse($d->hb_date)->format('m/d/Y') : '');
                $sheet->setCellValue('BA'.$row, !is_null($d->hb_date) ? $d->hb_result : '');
                $sheet->setCellValue('BB'.$row, !is_null($d->cbc_date) ? Carbon::parse($d->cbc_date)->format('m/d/Y') : '');
                $sheet->setCellValue('BC'.$row, !is_null($d->cbc_date) ? $d->cbc_result : '');
                $sheet->setCellValue('BD'.$row, !is_null($d->diabetes_date) ? Carbon::parse($d->diabetes_date)->format('m/d/Y') : '');
                $sheet->setCellValue('BE'.$row, !is_null($d->diabetes_date) ? $d->diabetes_result : '');

                //Outcome
                $sheet->setCellValue('BF'.$row, !is_null($d->delivery_date) ? Carbon::parse($d->delivery_date)->format('m/d/Y') : '');
                $sheet->setCellValue('BG'.$row, $d->outcome);
                $sheet->setCellValue('BH'.$row, $d->delivery_type);

                $sheet->setCellValue('BI'.$row, $d->birth_weight);
                $sheet->setCellValue('BJ'.$row, $d->weight_status);

                $sheet->setCellValue('BK'.$row, ($d->facility_type != 'NON/HEALTH FACILITY') ? $d->place_of_delivery : '');
                $sheet->setCellValue('BL'.$row, $d->bcemoncapable == 'Y' ? '✔' : '');

                $sheet->setCellValue('BM'.$row, $d->facility_type);
                $sheet->setCellValue('BN'.$row, $d->facility_type == 'NON/HEALTH FACILITY' ? $d->nonhealth_type : '');
                $sheet->setCellValue('BO'.$row, $d->attendant != 'O' ? $d->attendant : 'O - '.$d->attendant_others);

                $sheet->setCellValue('BP'.$row, !is_null($d->delivery_date) ? Carbon::parse($d->delivery_date)->format('m/d/Y') : '');
                $sheet->setCellValue('BO'.$row, !is_null($d->delivery_date) ? Carbon::parse($d->delivery_date)->format('h:i A') : '');

                $sheet->setCellValue('BR'.$row, !is_null($d->pnc1) ? Carbon::parse($d->pnc1)->format('m/d/Y') : '');
                $sheet->setCellValue('BS'.$row, !is_null($d->pnc2) ? Carbon::parse($d->pnc2)->format('m/d/Y') : '');
                $sheet->setCellValue('BT'.$row, !is_null($d->pnc3) ? Carbon::parse($d->pnc3)->format('m/d/Y') : '');
                $sheet->setCellValue('BU'.$row, !is_null($d->pnc4) ? Carbon::parse($d->pnc4)->format('m/d/Y') : '');
                $sheet->setCellValue('BV'.$row, $d->completed_4pnc == 'Y' ? '1' : '0');

                $sheet->setCellValue('BW'.$row, !is_null($d->pp_td1) ? '#: '.$d->pp_td1_dosage : '#:');
                $sheet->setCellValue('BW'.($row+1), !is_null($d->pp_td1) ? 'd: '.Carbon::parse($d->pp_td1)->format('m/d/Y') : 'd:');
                $sheet->setCellValue('BX'.$row, !is_null($d->pp_td2) ? '#: '.$d->pp_td2_dosage : '#:');
                $sheet->setCellValue('BX'.($row+1), !is_null($d->pp_td2) ? 'd: '.Carbon::parse($d->pp_td2)->format('m/d/Y') : 'd:');
                $sheet->setCellValue('BY'.$row, !is_null($d->pp_td3) ? '#: '.$d->pp_td3_dosage : '#:');
                $sheet->setCellValue('BY'.($row+1), !is_null($d->pp_td3) ? 'd: '.Carbon::parse($d->pp_td3)->format('m/d/Y') : 'd:');
                $sheet->setCellValue('BZ'.$row, $d->completed_pp_ifa == 'Y' ? '1' : '0');
                $sheet->setCellValue('BZ'.($row+1), $d->completed_pp_ifa == 'Y' ? 'd: '.Carbon::parse($d->pp_td3)->format('m/d/Y') : 'd:');
                $sheet->setCellValue('CA'.$row, !is_null($d->vita) ? '1' : '0');
                $sheet->setCellValue('CA'.($row+1), !is_null($d->vita) ? 'd: '.Carbon::parse($d->vita)->format('m/d/Y') : 'd:');

                $sheet->setCellValue('CB'.$row, $d->pp_remarks);

                //Child Nutrition
                

                $row = $row + 2;
            }
        }

        $fileName = "FHSIS_TCL_{$r->etcl_type}_".time().".xlsx";
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }
}
