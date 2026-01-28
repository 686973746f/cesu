<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\InhouseChildCare;
use App\Models\SyndromicPatient;
use App\Models\InhouseMaternalCare;
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ElectronicTclController extends Controller
{
    public static function viewListOfeTclBackend() {
        $list = [
            ['value' => 'maternal_care', 'text' => 'Maternal Care', 'enabled' => true],
            ['value' => 'child_care', 'text' => 'Child Care', 'enabled' => true],
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
        else {
            return abort(404);
        }

        if(request()->input('year')) {
            $records = (clone $records)->whereYear('registration_date', request()->input('year'))->orderBy('registration_date', 'DESC')->get();
        }
        else {
            $records = (clone $records)->whereYear('registration_date', date('Y'))->orderBy('registration_date', 'DESC')->get();
        }

        return view('efhsis.etcl.etcl_home', compact('type', 'records'));
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

        return view('efhsis.etcl.childcare_encode', compact('d'));
    }

    public function storeChildCare(Request $r, $patient_id) {
        $d = SyndromicPatient::findOrFail($patient_id);

        $c = InhouseChildCare::create([

        ]);
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
            }
            else {
                $base_qry = InhouseMaternalCare::where('enabled', 'Y')
                ->whereHas('facility.brgy', function($q) use ($r) {
                    $q->where('id', $r->selected_brgy_id);
                });
            }
        }
        else {
            $base_qry = InhouseMaternalCare::where('enabled', 'Y')
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

        $sheet->setCellValue('B72', (clone $qry)->where('pp_remarks', 'A')->where('age_group', 'A')->count());
        $sheet->setCellValue('C72', (clone $qry)->where('pp_remarks', 'A')->where('age_group', 'B')->count());
        $sheet->setCellValue('D72', (clone $qry)->where('pp_remarks', 'A')->where('age_group', 'C')->count());

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

        $fileName = "FHSIS_M1_".$r->year."_".$r->month.".xlsx";
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }
}
