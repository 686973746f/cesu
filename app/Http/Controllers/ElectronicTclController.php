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

        if(!is_null($r->outcome)) {
            $table_params = $table_params + [
                'outcome' => $r->outcome,
                'delivery_date' => $r->delivery_date,
                'delivery_type' => $r->delivery_type,

                'birth_weight' => $r->birth_weight,

                'facility_type' => $r->facility_type,
                'place_of_delivery' => ($r->facility_type == 'PUBLIC' || $r->facility_type == 'PRIVATE') ? $r->place_of_delivery : null,
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
            ];
        }

        $c = InhouseMaternalCare::create($table_params);

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
            return redirect()->
            back()
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

        if(!is_null($r->outcome)) {
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

        if(!is_null($r->outcome)) {
            $table_params = $table_params + [
                'outcome' => $r->outcome,
                'delivery_date' => $r->delivery_date,
                'delivery_type' => $r->delivery_type,

                'birth_weight' => $r->birth_weight,

                'facility_type' => $r->facility_type,
                'place_of_delivery' => ($r->facility_type == 'PUBLIC' || $r->facility_type == 'PRIVATE') ? $r->place_of_delivery : null,
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
            ];
        }

        $d->update($table_params);

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

        $sheet->setCellValue('D1', "FHSIS REPORT for the Month {$monthText}, Year [{$r->year}]");

        //$sheet->setCellValue('A1', );

        $fileName = 'M1_TEST.xlsx';
        ob_clean();
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }
}
