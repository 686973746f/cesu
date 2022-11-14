<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forms;
use App\Models\Records;
use App\Models\PaSwabLinks;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\MonitoringSheetMaster;
use App\Http\Requests\PaSwabValidationRequest;
use IlluminateAgnostic\Collection\Support\Str;

class PaSwabController extends Controller
{
    public function index($locale) {
        if (! in_array($locale, ['en', 'fil'])) {
            abort(404);
        }

        App::setLocale($locale);
        
        if(request()->input('rlink') && request()->input('s')) {
            $checkcode = PaSwabLinks::where('code', mb_strtoupper(request()->input('rlink')))
            ->where('secondary_code', mb_strtoupper(request()->input('s')))
            ->first();

            if($checkcode) {
                if($checkcode->active != 1) {
                    return view('paswab_index', [
                        'proceed' => 0,
                        'msg' => 'The Pa-swab URL you are using is currently DISABLED. Please double check the link and coordinate with CESU Staff for the correct Pa-swab URL.',
                        'msgtype' => 'warning',
                    ]);
                }

                if($checkcode->interviewer->enabled != 1) {
                    return view('paswab_index', [
                        'proceed' => 0,
                        'msg' => 'Interviewer '.$checkcode->interviewer->getCifName().' has been DISABLED. Please double check the link and coordinate with CESU Staff for the correct Pa-swab URL.',
                        'msgtype' => 'warning',
                    ]);
                }

                return view('paswab_index', [
                    'proceed' => 1,
                    'interviewerName' => $checkcode->interviewer->getCifName(),
                    'enableLockAddress' => $checkcode->enableLockAddress,
                    'lock_brgy' => $checkcode->lock_brgy,
                    'lock_city' => $checkcode->lock_city,
                    'lock_city_text' => $checkcode->lock_city_text,
                    'lock_province' => $checkcode->lock_province,
                    'lock_province_text' => $checkcode->lock_province_text,
                    'lock_subd_array' => $checkcode->lock_subd_array,
                ]);
            }
            else {
                return view('paswab_index', [
                    'proceed' => 0,
                    'msg' => 'Pa-swab URL does not exist. Please double check the link and coordinate with CESU Staff for the correct Pa-swab URL.',
                    'msgtype' => 'danger',
                ]);
            }
        }
        else {
            return view('paswab_index', ['proceed' => 0]);
        }
    }

    public function selectLanguage() {
        return view('paswab_language');
    }

    public function options(Request $request) {
        if($request->submit == 'bulkApprove') {
            $request->validate([
                'testDateCollected1' => 'required|date|after_or_equal:today',
                'testType1' => 'required|in:OPS,NPS,OPS AND NPS,ANTIGEN,ANTIBODY,OTHERS',
                'testTypeOtherRemarks1' => ($request->testType1 == "ANTIGEN" || $request->testType1 == "OTHERS") ? 'required' : 'nullable',
                'antigenKit1' => ($request->testType1 == "ANTIGEN") ? 'required' : 'nullable',
            ]);

            $list = PaSwabDetails::whereIn('id', $request->bulkIDList)->where('status', 'pending')->get();

            foreach($list as $data) {
                if(!($data->checkPaswabBrgyData())) {
                    return abort(401);
                }
                
                //Test Type final validator forAntigen
                if($data->forAntigen == 1) {
                    $ttype = 'ANTIGEN';
                    //dapat baguhin 'to ayon sa system settings
                    $tOtherRemarks = 'CONFIRMATORY';
                    $tAntigenKit = 'ABBOTT';
                }
                else {
                    $ttype = $request->testType1;
                    $tOtherRemarks = $request->testTypeOtherRemarks1;
                    $tAntigenKit = $request->antigenKit1;
                }

                if($data->status == 'pending') {
                    //create record data first
                    if($data->isNewRecord == 1) {
                        $rec = $request->user()->records()->create([
                            'status' => 'approved',
                            'lname' => mb_strtoupper($data->lname),
                            'fname' => mb_strtoupper($data->fname),
                            'mname' => (!is_null($data->mname)) ? mb_strtoupper($data->mname) : null,
                            'gender' => strtoupper($data->gender),
                            'isPregnant' => $data->isPregnant,
                            'cs' => strtoupper($data->cs),
                            'nationality' => strtoupper($data->nationality),
                            'bdate' => $data->bdate,
                            'mobile' => $data->mobile,
                            'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                            'email' => $data->email,
                            'philhealth' => $data->philhealth,
                            'address_houseno' => strtoupper($data->address_houseno),
                            'address_street' => strtoupper($data->address_street),
                            'address_brgy' => strtoupper($data->address_brgy),
                            'address_city' => strtoupper($data->address_city),
                            'address_cityjson' => $data->address_cityjson,
                            'address_province' => strtoupper($data->address_province),
                            'address_provincejson' => $data->address_provincejson,
            
                            'permaaddressDifferent' => 0,
                            'permaaddress_houseno' => strtoupper($data->address_houseno),
                            'permaaddress_street' => strtoupper($data->address_street),
                            'permaaddress_brgy' => strtoupper($data->address_brgy),
                            'permaaddress_city' => strtoupper($data->address_city),
                            'permaaddress_cityjson' => $data->address_cityjson,
                            'permaaddress_province' => strtoupper($data->address_province),
                            'permaaddress_provincejson' => $data->address_provincejson,
                            'permamobile' => $data->mobile,
                            'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                            'permaemail' => $data->email,
            
                            'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                            'occupation' => $data->occupation,
                            'worksInClosedSetting' => $data->worksInClosedSetting,
                            'occupation_lotbldg' => $data->occupation_lotbldg,
                            'occupation_street' => $data->occupation_street,
                            'occupation_brgy' => $data->occupation_brgy,
                            'occupation_city' => $data->occupation_city,
                            'occupation_cityjson' => $data->occupation_cityjson,
                            'occupation_province' => $data->occupation_province,
                            'occupation_provincejson' => $data->occupation_provincejson,
                            'occupation_name' => $data->occupation_name,
                            'occupation_mobile' => $data->occupation_mobile,
                            'occupation_email' => $data->occupation_email,
            
                            'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                            'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,

                            'vaccinationDate1' => $data->vaccinationDate1,
                            'vaccinationName1' => $data->vaccinationName1,
                            'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                            'vaccinationFacility1' => $data->vaccinationFacility1,
                            'vaccinationRegion1' => $data->vaccinationRegion1,
                            'haveAdverseEvents1' => $data->haveAdverseEvents1,

                            'vaccinationDate2' => $data->vaccinationDate2,
                            'vaccinationName2' => $data->vaccinationName2,
                            'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                            'vaccinationFacility2' => $data->vaccinationFacility2,
                            'vaccinationRegion2' => $data->vaccinationRegion2,
                            'haveAdverseEvents2' => $data->haveAdverseEvents2,

                            'vaccinationDate3' => $data->vaccinationDate3,
                            'vaccinationName3' => $data->vaccinationName3,
                            'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                            'vaccinationFacility3' => $data->vaccinationFacility3,
                            'vaccinationRegion3' => $data->vaccinationRegion3,
                            'haveAdverseEvents3' => $data->haveAdverseEvents3,

                            'vaccinationDate4' => $data->vaccinationDate4,
                            'vaccinationName4' => $data->vaccinationName4,
                            'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                            'vaccinationFacility4' => $data->vaccinationFacility4,
                            'vaccinationRegion4' => $data->vaccinationRegion4,
                            'haveAdverseEvents4' => $data->haveAdverseEvents4,
                        ]);
                    }
                    else {
                        $rec = Records::findOrFail($data->records_id);

                        $rec->update([
                            'status' => 'approved',
                            'isPregnant' => $data->isPregnant,
                            'cs' => strtoupper($data->cs),
                            'nationality' => strtoupper($data->nationality),
                            'mobile' => $data->mobile,
                            'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                            'email' => $data->email,
                            'philhealth' => $data->philhealth,
                            'address_houseno' => strtoupper($data->address_houseno),
                            'address_street' => strtoupper($data->address_street),
                            'address_brgy' => strtoupper($data->address_brgy),
                            'address_city' => strtoupper($data->address_city),
                            'address_cityjson' => $data->address_cityjson,
                            'address_province' => strtoupper($data->address_province),
                            'address_provincejson' => $data->address_provincejson,
            
                            'permaaddressDifferent' => 0,
                            'permaaddress_houseno' => strtoupper($data->address_houseno),
                            'permaaddress_street' => strtoupper($data->address_street),
                            'permaaddress_brgy' => strtoupper($data->address_brgy),
                            'permaaddress_city' => strtoupper($data->address_city),
                            'permaaddress_cityjson' => $data->address_cityjson,
                            'permaaddress_province' => strtoupper($data->address_province),
                            'permaaddress_provincejson' => $data->address_provincejson,
                            'permamobile' => $data->mobile,
                            'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                            'permaemail' => $data->email,
            
                            'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                            'occupation' => $data->occupation,
                            'worksInClosedSetting' => $data->worksInClosedSetting,
                            'occupation_lotbldg' => $data->occupation_lotbldg,
                            'occupation_street' => $data->occupation_street,
                            'occupation_brgy' => $data->occupation_brgy,
                            'occupation_city' => $data->occupation_city,
                            'occupation_cityjson' => $data->occupation_cityjson,
                            'occupation_province' => $data->occupation_province,
                            'occupation_provincejson' => $data->occupation_provincejson,
                            'occupation_name' => $data->occupation_name,
                            'occupation_mobile' => $data->occupation_mobile,
                            'occupation_email' => $data->occupation_email,
            
                            'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                            'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,

                            'vaccinationDate1' => $data->vaccinationDate1,
                            'vaccinationName1' => $data->vaccinationName1,
                            'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                            'vaccinationFacility1' => $data->vaccinationFacility1,
                            'vaccinationRegion1' => $data->vaccinationRegion1,
                            'haveAdverseEvents1' => $data->haveAdverseEvents1,

                            'vaccinationDate2' => $data->vaccinationDate2,
                            'vaccinationName2' => $data->vaccinationName2,
                            'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                            'vaccinationFacility2' => $data->vaccinationFacility2,
                            'vaccinationRegion2' => $data->vaccinationRegion2,
                            'haveAdverseEvents2' => $data->haveAdverseEvents2,

                            'vaccinationDate3' => $data->vaccinationDate3,
                            'vaccinationName3' => $data->vaccinationName3,
                            'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                            'vaccinationFacility3' => $data->vaccinationFacility3,
                            'vaccinationRegion3' => $data->vaccinationRegion3,
                            'haveAdverseEvents3' => $data->haveAdverseEvents3,

                            'vaccinationDate4' => $data->vaccinationDate4,
                            'vaccinationName4' => $data->vaccinationName4,
                            'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                            'vaccinationFacility4' => $data->vaccinationFacility4,
                            'vaccinationRegion4' => $data->vaccinationRegion4,
                            'haveAdverseEvents4' => $data->haveAdverseEvents4,
                        ]);

                        $oldform = Forms::where('records_id', $rec->id)->orderBy('created_at', 'DESC')->first();
                    }

                    if(!is_null($rec->philhealth)) {
                        if($ttype == "OPS" || $ttype == "NPS" || $ttype == "OPS AND NPS") {
                            $trigger = 0;
                            $addMinutes = 0;

                            while ($trigger != 1) {
                                $oniStartTime = date('H:i:s', strtotime('14:00:00 + '. $addMinutes .' minutes'));

                                $query = Forms::with('records')
                                ->where('testDateCollected1', $request->testDateCollected1)
                                ->whereIn('testType1', ['OPS', 'NPS', 'OPS AND NPS'])
                                ->whereHas('records', function ($q) {
                                    $q->whereNotNull('philhealth');
                                })
                                ->where('oniTimeCollected1', $oniStartTime)->get();

                                if($query->count()) {
                                    if($query->count() < 5) {
                                        $oniTimeFinal = $oniStartTime;
                                        $trigger = 1;
                                    }
                                    else {
                                        $addMinutes = $addMinutes + 5;
                                    }
                                }
                                else {
                                    $oniTimeFinal = $oniStartTime;
                                    $trigger = 1;
                                }
                            }
                        }
                        else {
                            $oniTimeFinal = NULL;
                        }
                    }
                    else {
                        $oniTimeFinal = NULL;
                    }

                    $comcheck = explode(',', $data->COMO);

                    //Auto Change Testing Category/Subgroup Base on the patient data
                    /*
                    if($rec->isForHospitalization == 1) {
                        array_push($testCat, "F.3");
                    }
                    */

                    $testCat = '';
                    $custom_dispo = NULL;

                    if(!is_null($data->SAS)) {
                        if($data->getAgeInt() >= 60) {
                            $testCat = 'A2';
                        }
                        else {
                            $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
                        }
                    }
                    else if($data->getAgeInt() >= 60) {
                        $testCat = 'A2';
                    }
                    else if($data->pType == 'CLOSE CONTACT') {
                        $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
                    }
                    else if($data->isPregnant == 1) {
                        $testCat = 'A3';
                        //$custom_dispo = 'FOR DELIVERY';
                    }
                    else if(in_array('Dialysis', $comcheck)) {
                        $testCat = 'A3';
                        $custom_dispo = 'FOR DIALYSIS';
                    }
                    else if(in_array('Cancer', $comcheck)) {
                        $testCat = 'A3';
                        $custom_dispo = 'CANCER PATIENT';
                    }
                    else if(in_array('Operation', $comcheck)) {
                        $testCat = 'A3';
                        $custom_dispo = 'FOR OPERATION';
                    }
                    else if(in_array('Transplant', $comcheck)) {
                        $testCat = 'A3';
                        $custom_dispo = 'TRANSPLANT';
                    }
                    else if($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                        $testCat = 'A1';
                    }
                    
                    /*
                    $testCat = [];
                    $custom_dispo = NULL;
                    if(!is_null($data->SAS)) {
                        if($data->getAgeInt() >= 60) {
                            array_push($testCat, "B");
                        }
                        else {
                            array_push($testCat, "C");
                        }
                    }
                    else if($data->getAgeInt() >= 60) {
                        array_push($testCat, "B");
                    }
                    else if(!in_array("D.1", $testCat) && $data->pType == 'CLOSE CONTACT') {
                        array_push($testCat, "D.1");
                    }
                    else if($data->isPregnant == 1) {
                        array_push($testCat, "F.1");
                    }
                    else if(in_array('Dialysis', $comcheck)) {
                        array_push($testCat, "F.2");
                        $custom_dispo = 'DIALYSIS PATIENT';
                    }
                    else if(in_array('Cancer', $comcheck)) {
                        array_push($testCat, "F.4");
                        $custom_dispo = 'CANCER PATIENT';
                    }
                    else if(in_array('Operation', $comcheck)) {
                        array_push($testCat, "F.5");
                    }
                    else if(in_array('Transplant', $comcheck)) {
                        array_push($testCat, "F.6");
                        $custom_dispo = 'FOR TRANSPLANT';
                    }
                    else if($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                        array_push($testCat, "D.2");
                    }
                    else if($data->natureOfWork == 'MANUFACTURING') {
                        array_push($testCat, "I");
                    }
                    else if($data->natureOfWork == 'GOVERNMENT UNITS/ORGANIZATIONS') {
                        array_push($testCat, "E2.3");
                    }
                    else if($data->natureOfWork == 'TRANSPORTATION' || $data->natureOfWork == 'MANNING/SHIPPING AGENCY' || $data->natureOfWork == 'STORAGE') {
                        if(!in_array('J1.1', $testCat)) {
                            array_push($testCat, "J1.1");
                        }
                    }
                    else if($data->natureOfWork == 'EDUCATION') {
                        array_push($testCat, "J1.3");
                    }
                    else if($data->natureOfWork == 'CONSTRUCTION' || $data->natureOfWork == 'ELECTRICITY') {
                        if(!in_array('J1.8', $testCat)) {
                            array_push($testCat, "J1.8");
                        }  
                    }
                    else if($data->natureOfWork == 'HOTEL AND RESTAURANT' || $data->natureOfWork == 'WHOLESALE AND RETAIL TRADE') {
                        if(!in_array('J1.2', $testCat)) {
                            array_push($testCat, "J1.2");
                        }
                    }
                    else if($data->natureOfWork == 'FINANCIAL') {
                        array_push($testCat, "J1.4");
                    }
                    else if($data->natureOfWork == 'SERVICES') {
                        array_push($testCat, "J1.6");
                    }
                    else if($data->natureOfWork == 'MASS MEDIA') {
                        array_push($testCat, "J1.11");
                    }
                    else {
                        array_push($testCat, "G");
                    }

                    $testCat = implode(',', $testCat);
                    */

                    //Auto Change Case Classification to Probable Based on Symptoms
                    if(!is_null($data->SAS)) {
                        if(in_array('Anosmia (Loss of Smell)', explode(",", $data->SAS)) || in_array('Ageusia (Loss of Taste)', explode(",", $data->SAS))) {
                            $caseClassi = 'Probable';
                        }
                        else {
                            $caseClassi = 'Suspect';
                        }
                    }
                    else {
                        $caseClassi = 'Suspect';
                    }

                    //Antigen QR
                    if($data->forAntigen == 1) {
                        $foundunique = false;
                        while(!$foundunique) {
                            $majik = Str::random(10);
                            
                            $qr_search = Forms::where('antigenqr', $majik);
                            if($qr_search->count() == 0) {
                                $foundunique = true;
                            }
                        }
    
                        $antigenqr = $majik;
                    }
                    else {
                        $antigenqr = NULL;
                    }

                    $createform = $request->user()->form()->create([
                        'morbidityMonth' => date('Y-m-d'),
                        'dateReported' => date('Y-m-d'),
                        'majikCode' => $data->majikCode,
                        'status' => 'approved',
                        'isPresentOnSwabDay' => NULL,
                        'records_id' => $rec->id,
                        'drunit' => 'CHO GENERAL TRIAS',
                        'drregion' => '4A',
                        'drprovince' => 'CAVITE',
                        'interviewerName' => $data->getDefaultInterviewerName(),
                        'interviewerMobile' => '09190664324',
                        'interviewDate' => $data->interviewDate,
                        'informantName' => NULL,
                        'informantRelationship' => NULL,
                        'informantMobile' => NULL,
                        'existingCaseList' => '1',
                        'ecOthersRemarks' => NULL,
                        'pType' => ($data->pType == 'FOR TRAVEL') ? 'TESTING' : $data->pType,
                        'isForHospitalization' => $data->isForHospitalization,
                        'testingCat' => $testCat,
                        'havePreviousCovidConsultation' => ($data->isNewRecord == 0) ? '1' : '0',
                        'dateOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->interviewDate : NULL : NULL,
                        'facilityNameOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->drunit : NULL : NULL,

                        'dispoType' => (!is_null($custom_dispo)) ? 5 : 3,
                        'dispoName' => $custom_dispo,
                        'dispoDate' => date('Y-m-d 08:00:00', strtotime($data->interviewDate)),
                        'healthStatus' => (!is_null($data->SAS)) ? 'Mild' : 'Asymptomatic',
                        'caseClassification' => $caseClassi,
                        'isHealthCareWorker' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? '1' : '0',
                        'healthCareCompanyName' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_name : NULL,
                        'healthCareCompanyLocation' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_city.', '.$data->occupation_province : NULL,
                        'isOFW' => '0',
                        'OFWCountyOfOrigin' => NULL,
                        'ofwType' => NULL,
                        'isFNT' => '0',
                        'lsiType' => NULL,
                        'FNTCountryOfOrigin' => NULL,
                        'isLSI' => '0',
                        'LSICity' => NULL,
                        'LSIProvince' => NULL,
                        'isLivesOnClosedSettings' => '0',
                        'institutionType' => NULL,
                        'institutionName' => NULL,
                        'indgSpecify' => NULL,
                        'dateOnsetOfIllness' => $data->dateOnsetOfIllness,
                        'SAS' => $data->SAS,
                        'SASFeverDeg' => $data->SASFeverDeg,
                        'SASOtherRemarks' => $data->SASOtherRemarks,
                        'COMO' => $data->COMO,
                        'COMOOtherRemarks' => $data->COMOOtherRemarks,
                        'PregnantLMP' => $data->ifPregnantLMP,
                        'PregnantHighRisk' => ($data->isPregnant == 1) ? '1' : '0',
                        'diagWithSARI' => '0',
                        'imagingDoneDate' => $data->imagingDoneDate,
                        'imagingDone' => $data->imagingDone,
                        'imagingResult' => $data->imagingResult,
                        'imagingOtherFindings' => $data->imagingOtherFindings,

                        'testedPositiveUsingRTPCRBefore' => '0',
                        'testedPositiveNumOfSwab' => '0',
                        'testedPositiveLab' => NULL,
                        'testedPositiveSpecCollectedDate' => NULL,

                        'testDateCollected1' => $request->testDateCollected1,
                        'oniTimeCollected1' => $oniTimeFinal,
                        'testDateReleased1' => NULL,
                        'testLaboratory1' => NULL,
                        'testType1' => $ttype,
                        'testTypeAntigenRemarks1' => ($ttype == "ANTIGEN") ? mb_strtoupper($tOtherRemarks) : NULL,
                        'antigenKit1' => ($ttype == "ANTIGEN") ? mb_strtoupper($tAntigenKit) : NULL,
                        'testTypeOtherRemarks1' => ($ttype == "OTHERS") ? mb_strtoupper($tOtherRemarks) : NULL,
                        'testResult1' => 'PENDING',
                        'testResultOtherRemarks1' => NULL,

                        'testDateCollected2' => NULL,
                        'oniTimeCollected2' => NULL,
                        'testDateReleased2' => NULL,
                        'testLaboratory2' => NULL,
                        'testType2' => NULL,
                        'testTypeAntigenRemarks2' => NULL,
                        'antigenKit2' => NULL,
                        'testTypeOtherRemarks2' => NULL,
                        'testResult2' => NULL,
                        'testResultOtherRemarks2' => NULL,

                        'outcomeCondition' => 'Active',
                        'outcomeRecovDate' => NULL,
                        'outcomeDeathDate' => NULL,
                        'deathImmeCause' => NULL,
                        'deathAnteCause' => NULL,
                        'deathUndeCause' => NULL,
                        'contriCondi' => NULL,

                        'expoitem1' => $data->expoitem1,
                        'expoDateLastCont' => $data->expoDateLastCont,

                        'expoitem2' => '0',
                        'intCountry' => NULL,
                        'intDateFrom' => NULL,
                        'intDateTo' => NULL,
                        'intWithOngoingCovid' => 'N/A',
                        'intVessel' => NULL,
                        'intVesselNo' => NULL,
                        'intDateDepart' => NULL,
                        'intDateArrive' => NULL,

                        'placevisited' => NULL,

                        'locName1' => NULL,
                        'locAddress1' => NULL,
                        'locDateFrom1' => NULL,
                        'locDateTo1' => NULL,
                        'locWithOngoingCovid1' => 'N/A',

                        'locName2' => NULL,
                        'locAddress2' => NULL,
                        'locDateFrom2' => NULL,
                        'locDateTo2' => NULL,
                        'locWithOngoingCovid2' => 'N/A',
                        
                        'locName3' => NULL,
                        'locAddress3' => NULL,
                        'locDateFrom3' => NULL,
                        'locDateTo3' => NULL,
                        'locWithOngoingCovid3' => 'N/A',
                        
                        'locName4' => NULL,
                        'locAddress4' => NULL,
                        'locDateFrom4' => NULL,
                        'locDateTo4' => NULL,
                        'locWithOngoingCovid4' => 'N/A',

                        'locName5' => NULL,
                        'locAddress5' => NULL,
                        'locDateFrom5' => NULL,
                        'locDateTo5' => NULL,
                        'locWithOngoingCovid5' => 'N/A',

                        'locName6' => NULL,
                        'locAddress6' => NULL,
                        'locDateFrom6' => NULL,
                        'locDateTo6' => NULL,
                        'locWithOngoingCovid6' => 'N/A',

                        'locName7' => NULL,
                        'locAddress7' => NULL,
                        'locDateFrom7' => NULL,
                        'locDateTo7' => NULL,
                        'locWithOngoingCovid7' => 'N/A',

                        'localVessel1' => NULL,
                        'localVesselNo1' => NULL,
                        'localOrigin1' => NULL,
                        'localDateDepart1' => NULL,
                        'localDest1' => NULL,
                        'localDateArrive1' => NULL,

                        'localVessel2' => NULL,
                        'localVesselNo2' => NULL,
                        'localOrigin2' => NULL,
                        'localDateDepart2' => NULL,
                        'localDest2' => NULL,
                        'localDateArrive2' => NULL,

                        'contact1Name' => $data->contact1Name,
                        'contact1No' => $data->contact1No,
                        'contact2Name' => $data->contact2Name,
                        'contact2No' => $data->contact2No,
                        'contact3Name' => $data->contact3Name,
                        'contact3No' => $data->contact3No,
                        'contact4Name' => $data->contact4Name,
                        'contact4No' => $data->contact4No,

                        'remarks' => $data->patientmsg,
                        'antigenqr' => $antigenqr,
                    ]);

                    //Create Monitoring Sheet
                    $msheet_search = MonitoringSheetMaster::where('forms_id', $createform->id)->first();
                    if(!$msheet_search) {
                        $foundunique = false;
                        while(!$foundunique) {
                            $majik = Str::random(30);
                            
                            $search = MonitoringSheetMaster::where('magicURL', $majik);
                            if($search->count() == 0) {
                                $foundunique = true;
                            }
                        }

                        $newmsheet = new MonitoringSheetMaster;

                        $newmsheet->forms_id = $createform->id;
                        $newmsheet->region = '4A';
                        $newmsheet->date_lastexposure = (!is_null($createform->expoDateLastCont)) ? $createform->expoDateLastCont : $createform->interviewDate;
                        $newmsheet->date_startquarantine = $createform->interviewDate;
                        $newmsheet->date_endquarantine = Carbon::parse($createform->interviewDate)->addDays(13)->format('Y-m-d');
                        $newmsheet->magicURL = $majik;

                        $newmsheet->save();
                    }
                    
                    $upd = PaSwabDetails::where('id', $data->id)->update([
                        'status' => 'approved'
                    ]);

                    if(isset($oldform) && !is_null($oldform)) {
                        if($data->isNewRecord == 0 && $oldform->caseClassification != 'Confirmed' && $oldform->caseClassification != 'Non-COVID-19 Case') {
                            $fcheck = Forms::where('id', $oldform->id)->delete();
                        }
                    }
                }
                else {
                    //error code here, dapat hindi gagamit ng 'return' para di huminto yung loop
                }
            }

            return redirect()->action([PaSwabController::class, 'view'])
            ->with('msg', 'Bulk Approval of the selected data has been proccessed successfully.')
            ->with('msgtype', 'success');
        }
        else if($request->submit == 'bulkReject') {
            $request->validate([
                'rejectReason' => 'required',
            ]);

            $list = PaSwabDetails::whereIn('id', $request->bulkIDList)->where('status', 'pending')->get();

            foreach($list as $data) {
                if($data->status == 'pending') {
                    //Test Type final validator forAntigen
                    if($data->forAntigen == 1) {
                        $ttype = 'ANTIGEN';
                        //dapat baguhin 'to ayon sa system settings
                        $tOtherRemarks = 'FOR ANTIGEN REJECTED IN PASWAB';
                        $tAntigenKit = 'FOR ANTIGEN REJECTED IN PASWAB';
                    }
                    else {
                        $ttype = 'REJECTED IN PASWAB';
                        $tOtherRemarks = NULL;
                        $tAntigenKit = NULL;
                    }

                    if($data->isNewRecord == 1) {
                        $rec = $request->user()->records()->create([
                            'status' => 'approved',
                            'lname' => mb_strtoupper($data->lname),
                            'fname' => mb_strtoupper($data->fname),
                            'mname' => (!is_null($data->mname)) ? mb_strtoupper($data->mname) : null,
                            'gender' => strtoupper($data->gender),
                            'isPregnant' => $data->isPregnant,
                            'cs' => strtoupper($data->cs),
                            'nationality' => strtoupper($data->nationality),
                            'bdate' => $data->bdate,
                            'mobile' => $data->mobile,
                            'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                            'email' => $data->email,
                            'philhealth' => $data->philhealth,
                            'address_houseno' => strtoupper($data->address_houseno),
                            'address_street' => strtoupper($data->address_street),
                            'address_brgy' => strtoupper($data->address_brgy),
                            'address_city' => strtoupper($data->address_city),
                            'address_cityjson' => $data->address_cityjson,
                            'address_province' => strtoupper($data->address_province),
                            'address_provincejson' => $data->address_provincejson,
            
                            'permaaddressDifferent' => 0,
                            'permaaddress_houseno' => strtoupper($data->address_houseno),
                            'permaaddress_street' => strtoupper($data->address_street),
                            'permaaddress_brgy' => strtoupper($data->address_brgy),
                            'permaaddress_city' => strtoupper($data->address_city),
                            'permaaddress_cityjson' => $data->address_cityjson,
                            'permaaddress_province' => strtoupper($data->address_province),
                            'permaaddress_provincejson' => $data->address_provincejson,
                            'permamobile' => $data->mobile,
                            'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                            'permaemail' => $data->email,
            
                            'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                            'occupation' => $data->occupation,
                            'worksInClosedSetting' => $data->worksInClosedSetting,
                            'occupation_lotbldg' => $data->occupation_lotbldg,
                            'occupation_street' => $data->occupation_street,
                            'occupation_brgy' => $data->occupation_brgy,
                            'occupation_city' => $data->occupation_city,
                            'occupation_cityjson' => $data->occupation_cityjson,
                            'occupation_province' => $data->occupation_province,
                            'occupation_provincejson' => $data->occupation_provincejson,
                            'occupation_name' => $data->occupation_name,
                            'occupation_mobile' => $data->occupation_mobile,
                            'occupation_email' => $data->occupation_email,
            
                            'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                            'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,

                            'vaccinationDate1' => $data->vaccinationDate1,
                            'vaccinationName1' => $data->vaccinationName1,
                            'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                            'vaccinationFacility1' => $data->vaccinationFacility1,
                            'vaccinationRegion1' => $data->vaccinationRegion1,
                            'haveAdverseEvents1' => $data->haveAdverseEvents1,

                            'vaccinationDate2' => $data->vaccinationDate2,
                            'vaccinationName2' => $data->vaccinationName2,
                            'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                            'vaccinationFacility2' => $data->vaccinationFacility2,
                            'vaccinationRegion2' => $data->vaccinationRegion2,
                            'haveAdverseEvents2' => $data->haveAdverseEvents2,

                            'vaccinationDate3' => $data->vaccinationDate3,
                            'vaccinationName3' => $data->vaccinationName3,
                            'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                            'vaccinationFacility3' => $data->vaccinationFacility3,
                            'vaccinationRegion3' => $data->vaccinationRegion3,
                            'haveAdverseEvents3' => $data->haveAdverseEvents3,
                            
                            'vaccinationDate4' => $data->vaccinationDate4,
                            'vaccinationName4' => $data->vaccinationName4,
                            'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                            'vaccinationFacility4' => $data->vaccinationFacility4,
                            'vaccinationRegion4' => $data->vaccinationRegion4,
                            'haveAdverseEvents4' => $data->haveAdverseEvents4,
                        ]);
                    }
                    else {
                        $rec = Records::findOrFail($data->records_id);

                        $rec->update([
                            'status' => 'approved',
                            'isPregnant' => $data->isPregnant,
                            'cs' => strtoupper($data->cs),
                            'nationality' => strtoupper($data->nationality),
                            'mobile' => $data->mobile,
                            'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                            'email' => $data->email,
                            'philhealth' => $data->philhealth,
                            'address_houseno' => strtoupper($data->address_houseno),
                            'address_street' => strtoupper($data->address_street),
                            'address_brgy' => strtoupper($data->address_brgy),
                            'address_city' => strtoupper($data->address_city),
                            'address_cityjson' => $data->address_cityjson,
                            'address_province' => strtoupper($data->address_province),
                            'address_provincejson' => $data->address_provincejson,
            
                            'permaaddressDifferent' => 0,
                            'permaaddress_houseno' => strtoupper($data->address_houseno),
                            'permaaddress_street' => strtoupper($data->address_street),
                            'permaaddress_brgy' => strtoupper($data->address_brgy),
                            'permaaddress_city' => strtoupper($data->address_city),
                            'permaaddress_cityjson' => $data->address_cityjson,
                            'permaaddress_province' => strtoupper($data->address_province),
                            'permaaddress_provincejson' => $data->address_provincejson,
                            'permamobile' => $data->mobile,
                            'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                            'permaemail' => $data->email,
            
                            'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                            'occupation' => $data->occupation,
                            'worksInClosedSetting' => $data->worksInClosedSetting,
                            'occupation_lotbldg' => $data->occupation_lotbldg,
                            'occupation_street' => $data->occupation_street,
                            'occupation_brgy' => $data->occupation_brgy,
                            'occupation_city' => $data->occupation_city,
                            'occupation_cityjson' => $data->occupation_cityjson,
                            'occupation_province' => $data->occupation_province,
                            'occupation_provincejson' => $data->occupation_provincejson,
                            'occupation_name' => $data->occupation_name,
                            'occupation_mobile' => $data->occupation_mobile,
                            'occupation_email' => $data->occupation_email,
            
                            'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                            'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,

                            'vaccinationDate1' => $data->vaccinationDate1,
                            'vaccinationName1' => $data->vaccinationName1,
                            'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                            'vaccinationFacility1' => $data->vaccinationFacility1,
                            'vaccinationRegion1' => $data->vaccinationRegion1,
                            'haveAdverseEvents1' => $data->haveAdverseEvents1,

                            'vaccinationDate2' => $data->vaccinationDate2,
                            'vaccinationName2' => $data->vaccinationName2,
                            'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                            'vaccinationFacility2' => $data->vaccinationFacility2,
                            'vaccinationRegion2' => $data->vaccinationRegion2,
                            'haveAdverseEvents2' => $data->haveAdverseEvents2,

                            'vaccinationDate3' => $data->vaccinationDate3,
                            'vaccinationName3' => $data->vaccinationName3,
                            'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                            'vaccinationFacility3' => $data->vaccinationFacility3,
                            'vaccinationRegion3' => $data->vaccinationRegion3,
                            'haveAdverseEvents3' => $data->haveAdverseEvents3,

                            'vaccinationDate4' => $data->vaccinationDate4,
                            'vaccinationName4' => $data->vaccinationName4,
                            'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                            'vaccinationFacility4' => $data->vaccinationFacility4,
                            'vaccinationRegion4' => $data->vaccinationRegion4,
                            'haveAdverseEvents4' => $data->haveAdverseEvents4,
                        ]);

                        $oldform = Forms::where('records_id', $rec->id)->orderBy('created_at', 'DESC')->first();
                    }
                    
                    $comcheck = explode(',', $data->COMO);

                    //Auto Change Testing Category/Subgroup Base on the patient data
                    /*
                    if($rec->isForHospitalization == 1) {
                        array_push($testCat, "F.3");
                    }
                    */

                    $testCat = '';
                    $custom_dispo = NULL;

                    if(!is_null($data->SAS)) {
                        if($data->getAgeInt() >= 60) {
                            $testCat = 'A2';
                        }
                        else {
                            $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
                        }
                    }
                    else if($data->getAgeInt() >= 60) {
                        $testCat = 'A2';
                    }
                    else if($data->pType == 'CLOSE CONTACT') {
                        $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
                    }
                    else if($data->isPregnant == 1) {
                        $testCat = 'A3';
                        $custom_dispo = 'FOR DELIVERY';
                    }
                    else if(in_array('Dialysis', $comcheck)) {
                        $testCat = 'A3';
                        $custom_dispo = 'FOR DIALYSIS';
                    }
                    else if(in_array('Cancer', $comcheck)) {
                        $testCat = 'A3';
                        $custom_dispo = 'CANCER PATIENT';
                    }
                    else if(in_array('Operation', $comcheck)) {
                        $testCat = 'A3';
                        $custom_dispo = 'FOR OPERATION';
                    }
                    else if(in_array('Transplant', $comcheck)) {
                        $testCat = 'A3';
                        $custom_dispo = 'TRANSPLANT';
                    }
                    else if($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                        $testCat = 'A1';
                    }

                    /*
                    $testCat = [];
                    $custom_dispo = NULL;
                    if(!is_null($data->SAS)) {
                        if($data->getAgeInt() >= 60) {
                            array_push($testCat, "B");
                        }
                        else {
                            array_push($testCat, "C");
                        }
                    }
                    else if($data->getAgeInt() >= 60) {
                        array_push($testCat, "B");
                    }
                    else if(!in_array("D.1", $testCat) && $data->pType == 'CLOSE CONTACT') {
                        array_push($testCat, "D.1");
                    }
                    else if($data->isPregnant == 1) {
                        array_push($testCat, "F.1");
                    }
                    else if(in_array('Dialysis', $comcheck)) {
                        array_push($testCat, "F.2");
                        $custom_dispo = 'DIALYSIS PATIENT';
                    }
                    else if(in_array('Cancer', $comcheck)) {
                        array_push($testCat, "F.4");
                        $custom_dispo = 'CANCER PATIENT';
                    }
                    else if(in_array('Operation', $comcheck)) {
                        array_push($testCat, "F.5");
                    }
                    else if(in_array('Transplant', $comcheck)) {
                        array_push($testCat, "F.6");
                        $custom_dispo = 'FOR TRANSPLANT';
                    }
                    else if($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                        array_push($testCat, "D.2");
                    }
                    else if($data->natureOfWork == 'MANUFACTURING') {
                        array_push($testCat, "I");
                    }
                    else if($data->natureOfWork == 'GOVERNMENT UNITS/ORGANIZATIONS') {
                        array_push($testCat, "E2.3");
                    }
                    else if($data->natureOfWork == 'TRANSPORTATION' || $data->natureOfWork == 'MANNING/SHIPPING AGENCY' || $data->natureOfWork == 'STORAGE') {
                        if(!in_array('J1.1', $testCat)) {
                            array_push($testCat, "J1.1");
                        }
                    }
                    else if($data->natureOfWork == 'EDUCATION') {
                        array_push($testCat, "J1.3");
                    }
                    else if($data->natureOfWork == 'CONSTRUCTION' || $data->natureOfWork == 'ELECTRICITY') {
                        if(!in_array('J1.8', $testCat)) {
                            array_push($testCat, "J1.8");
                        }  
                    }
                    else if($data->natureOfWork == 'HOTEL AND RESTAURANT' || $data->natureOfWork == 'WHOLESALE AND RETAIL TRADE') {
                        if(!in_array('J1.2', $testCat)) {
                            array_push($testCat, "J1.2");
                        }
                    }
                    else if($data->natureOfWork == 'FINANCIAL') {
                        array_push($testCat, "J1.4");
                    }
                    else if($data->natureOfWork == 'SERVICES') {
                        array_push($testCat, "J1.6");
                    }
                    else if($data->natureOfWork == 'MASS MEDIA') {
                        array_push($testCat, "J1.11");
                    }
                    else {
                        array_push($testCat, "G");
                    }

                    $testCat = implode(',', $testCat);
                    */

                    //Auto Change Case Classification to Probable Based on Symptoms
                    if(!is_null($data->SAS)) {
                        if(in_array('Anosmia (Loss of Smell)', explode(",", $data->SAS)) || in_array('Ageusia (Loss of Taste)', explode(",", $data->SAS))) {
                            $caseClassi = 'Probable';
                        }
                        else {
                            $caseClassi = 'Suspect';
                        }
                    }
                    else {
                        $caseClassi = 'Suspect';
                    }

                    //Antigen QR
                    $antigenqr = NULL;

                    $request->user()->form()->create([
                        'morbidityMonth' => date('Y-m-d'),
                        'dateReported' => date('Y-m-d'),
                        'majikCode' => $data->majikCode,
                        'status' => 'paswab_rejected',
                        'isPresentOnSwabDay' => NULL,
                        'records_id' => $rec->id,
                        'drunit' => 'CHO GENERAL TRIAS',
                        'drregion' => '4A',
                        'drprovince' => 'CAVITE',
                        'interviewerName' => $data->getDefaultInterviewerName(),
                        'interviewerMobile' => '09190664324',
                        'interviewDate' => $data->interviewDate,
                        'informantName' => NULL,
                        'informantRelationship' => NULL,
                        'informantMobile' => NULL,
                        'existingCaseList' => '1',
                        'ecOthersRemarks' => NULL,
                        'pType' => ($data->pType == 'FOR TRAVEL') ? 'TESTING' : $data->pType,
                        'isForHospitalization' => $data->isForHospitalization,
                        'testingCat' => $testCat,
                        'havePreviousCovidConsultation' => ($data->isNewRecord == 0) ? '1' : '0',
                        'dateOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->interviewDate : NULL : NULL,
                        'facilityNameOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->drunit : NULL : NULL,

                        'dispoType' => (!is_null($custom_dispo)) ? 5 : 3,
                        'dispoName' => $custom_dispo,
                        'dispoDate' => date('Y-m-d 08:00:00', strtotime($data->interviewDate)),
                        'healthStatus' => (!is_null($data->SAS)) ? 'Mild' : 'Asymptomatic',
                        'caseClassification' => $caseClassi,
                        'isHealthCareWorker' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? '1' : '0',
                        'healthCareCompanyName' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_name : NULL,
                        'healthCareCompanyLocation' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_city.', '.$data->occupation_province : NULL,
                        'isOFW' => '0',
                        'OFWCountyOfOrigin' => NULL,
                        'ofwType' => NULL,
                        'isFNT' => '0',
                        'lsiType' => NULL,
                        'FNTCountryOfOrigin' => NULL,
                        'isLSI' => '0',
                        'LSICity' => NULL,
                        'LSIProvince' => NULL,
                        'isLivesOnClosedSettings' => '0',
                        'institutionType' => NULL,
                        'institutionName' => NULL,
                        'indgSpecify' => NULL,
                        'dateOnsetOfIllness' => $data->dateOnsetOfIllness,
                        'SAS' => $data->SAS,
                        'SASFeverDeg' => $data->SASFeverDeg,
                        'SASOtherRemarks' => $data->SASOtherRemarks,
                        'COMO' => $data->COMO,
                        'COMOOtherRemarks' => $data->COMOOtherRemarks,
                        'PregnantLMP' => $data->ifPregnantLMP,
                        'PregnantHighRisk' => ($data->isPregnant == 1) ? '1' : '0',
                        'diagWithSARI' => '0',
                        'imagingDoneDate' => $data->imagingDoneDate,
                        'imagingDone' => $data->imagingDone,
                        'imagingResult' => $data->imagingResult,
                        'imagingOtherFindings' => $data->imagingOtherFindings,

                        'testedPositiveUsingRTPCRBefore' => '0',
                        'testedPositiveNumOfSwab' => '0',
                        'testedPositiveLab' => NULL,
                        'testedPositiveSpecCollectedDate' => NULL,

                        'testDateCollected1' => '2121-01-01',
                        'oniTimeCollected1' => NULL,
                        'testDateReleased1' => NULL,
                        'testLaboratory1' => NULL,
                        'testType1' => $ttype,
                        'testTypeAntigenRemarks1' => ($ttype == "ANTIGEN") ? mb_strtoupper($tOtherRemarks) : NULL,
                        'antigenKit1' => ($ttype == "ANTIGEN") ? mb_strtoupper($tAntigenKit) : NULL,
                        'testTypeOtherRemarks1' => ($ttype == "OTHERS") ? mb_strtoupper($tOtherRemarks) : NULL,
                        'testResult1' => 'PENDING',
                        'testResultOtherRemarks1' => NULL,

                        'testDateCollected2' => NULL,
                        'oniTimeCollected2' => NULL,
                        'testDateReleased2' => NULL,
                        'testLaboratory2' => NULL,
                        'testType2' => NULL,
                        'testTypeAntigenRemarks2' => NULL,
                        'antigenKit2' => NULL,
                        'testTypeOtherRemarks2' => NULL,
                        'testResult2' => NULL,
                        'testResultOtherRemarks2' => NULL,

                        'outcomeCondition' => 'Active',
                        'outcomeRecovDate' => NULL,
                        'outcomeDeathDate' => NULL,
                        'deathImmeCause' => NULL,
                        'deathAnteCause' => NULL,
                        'deathUndeCause' => NULL,
                        'contriCondi' => NULL,

                        'expoitem1' => $data->expoitem1,
                        'expoDateLastCont' => $data->expoDateLastCont,

                        'expoitem2' => '0',
                        'intCountry' => NULL,
                        'intDateFrom' => NULL,
                        'intDateTo' => NULL,
                        'intWithOngoingCovid' => 'N/A',
                        'intVessel' => NULL,
                        'intVesselNo' => NULL,
                        'intDateDepart' => NULL,
                        'intDateArrive' => NULL,

                        'placevisited' => NULL,

                        'locName1' => NULL,
                        'locAddress1' => NULL,
                        'locDateFrom1' => NULL,
                        'locDateTo1' => NULL,
                        'locWithOngoingCovid1' => 'N/A',

                        'locName2' => NULL,
                        'locAddress2' => NULL,
                        'locDateFrom2' => NULL,
                        'locDateTo2' => NULL,
                        'locWithOngoingCovid2' => 'N/A',
                        
                        'locName3' => NULL,
                        'locAddress3' => NULL,
                        'locDateFrom3' => NULL,
                        'locDateTo3' => NULL,
                        'locWithOngoingCovid3' => 'N/A',
                        
                        'locName4' => NULL,
                        'locAddress4' => NULL,
                        'locDateFrom4' => NULL,
                        'locDateTo4' => NULL,
                        'locWithOngoingCovid4' => 'N/A',

                        'locName5' => NULL,
                        'locAddress5' => NULL,
                        'locDateFrom5' => NULL,
                        'locDateTo5' => NULL,
                        'locWithOngoingCovid5' => 'N/A',

                        'locName6' => NULL,
                        'locAddress6' => NULL,
                        'locDateFrom6' => NULL,
                        'locDateTo6' => NULL,
                        'locWithOngoingCovid6' => 'N/A',

                        'locName7' => NULL,
                        'locAddress7' => NULL,
                        'locDateFrom7' => NULL,
                        'locDateTo7' => NULL,
                        'locWithOngoingCovid7' => 'N/A',

                        'localVessel1' => NULL,
                        'localVesselNo1' => NULL,
                        'localOrigin1' => NULL,
                        'localDateDepart1' => NULL,
                        'localDest1' => NULL,
                        'localDateArrive1' => NULL,

                        'localVessel2' => NULL,
                        'localVesselNo2' => NULL,
                        'localOrigin2' => NULL,
                        'localDateDepart2' => NULL,
                        'localDest2' => NULL,
                        'localDateArrive2' => NULL,

                        'contact1Name' => $data->contact1Name,
                        'contact1No' => $data->contact1No,
                        'contact2Name' => $data->contact2Name,
                        'contact2No' => $data->contact2No,
                        'contact3Name' => $data->contact3Name,
                        'contact3No' => $data->contact3No,
                        'contact4Name' => $data->contact4Name,
                        'contact4No' => $data->contact4No,

                        'remarks' => $data->patientmsg,
                        'antigenqr' => $antigenqr,
                    ]);
                    
                    //UPDATE PARAMETERS
                    $upd = PaSwabDetails::find($data->id);

                    $upd->status = 'rejected';
                    $upd->remarks = $request->rejectReason;
                    $upd->processedAt = date('Y-m-d');

                    $upd->save();

                    if($data->isNewRecord == 0) {
                        if($oldform) {
                            if($oldform->caseClassification != 'Confirmed' && $oldform->caseClassification != 'Non-COVID-19 Case'){
                                //hayaan munang naka-disable para di mag-overlap sa existing data
                        
                                //$fcheck = Forms::where('id', $oldform->id)->delete();
                            }
                        }
                    }
                }
            }

            return redirect()->action([PaSwabController::class, 'view'])
            ->with('msg', 'Bulk Rejection of the selected data has been proccessed successfully.')
            ->with('msgtype', 'success');
        }
    }

    public function store(PaSwabValidationRequest $request, $locale) {
        //Block Record if No Symptoms or Not for Hospitalization
        if($request->pType != 'CLOSE CONTACT') {
            if($request->forAntigen != 1) {
                if(is_null($request->sasCheck)) {
                    if($request->isForHospitalization != 1) {
                        return back()
                        ->withInput()
                        ->with('msg', 'Based on your swab schedule application, you have NO SYMPTOMS or NOT FOR HOSPITALIZATION. Therefore, your swab schedule application is REJECTED.')
                        ->with('msgtype', 'danger')
                        ->with('skipmodal', true);
                    }
                }
            }
        }
        
        $request->address_street = strtolower($request->address_street);
		$request->address_houseno = strtolower($request->address_houseno);

        $request->validated();

        $request->validate([
            'address_street' => [
                'required',
                'different:address_brgy',
                'different:address_houseno',
                'min:3',
                'regex:/(^[a-zA-Z0-9 ]+$)+/',
                Rule::notIn(array_map('strtolower', ['NEAR BRGY HALL', 'NEAR BARANGAY HALL', '000', 'NONE', 'NOT APPLICABLE'])),
            ],
            'address_houseno' => [
                'required',
                'different:address_brgy',
                'different:address_street',
                'min:3',
                'regex:/(^[a-zA-Z0-9 ]+$)+/',
                Rule::notIn(array_map('strtolower', ['NEAR BRGY HALL', 'NEAR BARANGAY HALL', '000', 'NONE', 'NOT APPLICABLE'])),
            ],
        ]);

        if(mb_strtoupper($request->address_street) == '1' || mb_strtoupper($request->address_street) == '0' || mb_strtoupper($request->address_street) == 'BARANGAY HALL' || mb_strtoupper($request->address_street) == 'BRGY. HALL' || mb_strtoupper($request->address_street) == 'BRGY HALL' || mb_strtoupper($request->address_street) == 'NEAR BRGY HALL' || mb_strtoupper($request->address_street) == 'NEAR BRGY. HALL' || mb_strtoupper($request->address_street) == 'NEAR BARANGAY HALL' || mb_strtoupper($request->address_street) == 'NA' || mb_strtoupper($request->address_street) == 'N/A' || mb_strtoupper($request->address_street) == 'NONE' || mb_strtoupper($request->address_street) == $request->address_brgy || mb_strtoupper($request->address_street) == 'PROPER') {
			return back()
			->withInput()
			->with('msg', 'Encoding Error: The Address Street is Invalid.')
			->with('msgtype', 'danger')
            ->with('skipmodal', true);
		}

		if(mb_strtoupper($request->address_houseno) == '1' || mb_strtoupper($request->address_houseno) == '0' || mb_strtoupper($request->address_houseno) == 'BARANGAY HALL' || mb_strtoupper($request->address_houseno) == 'BRGY. HALL' || mb_strtoupper($request->address_houseno) == 'BRGY HALL' || mb_strtoupper($request->address_houseno) == 'NEAR BRGY HALL' || mb_strtoupper($request->address_houseno) == 'NEAR BRGY. HALL' || mb_strtoupper($request->address_houseno) == 'NEAR BARANGAY HALL' || mb_strtoupper($request->address_houseno) == 'NA' || mb_strtoupper($request->address_houseno) == 'N/A' || mb_strtoupper($request->address_houseno) == 'NONE' || mb_strtoupper($request->address_houseno) == $request->address_brgy || mb_strtoupper($request->address_houseno) == 'PROPER') {
			return back()
			->withInput()
			->with('msg', 'Encoding Error: The Address House No. is Invalid.')
			->with('msgtype', 'danger')
            ->with('skipmodal', true);
		}

        /*
        
        Old Pa-swab duplicate Entry checker

        if(PaSwabDetails::where('lname', mb_strtoupper($request->lname))
		->where('fname', mb_strtoupper($request->fname))
		->where(function ($query) use ($request) {
			$query->where('mname', mb_strtoupper($request->mname))
			->orWhereNull('mname');
		})->where('status', 'pending')
		->exists()) {
			$param2 = 1;
		}
		else {
			$param2 = 0;
		}
        */
        $c1 = PaSwabDetails::ifEntryPending($request->lname, $request->fname, $request->mname);

        if(!is_null($c1)) {
            return back()
			->withInput()
			->with('msg', 'Your pa-swab request is still pending. Please wait for the approval from CESU Staff/Encoders before sending another pa-swab request.')
            ->with('msgtype', 'warning')
            ->with('skipmodal', true);
        }
        else {
            /*

            Old Pa-swab duplicate Entry checker

            if(PaSwabDetails::where('lname', mb_strtoupper($request->lname))
            ->where('fname', mb_strtoupper($request->fname))
            ->where(function ($query) use ($request) {
                $query->where('mname', mb_strtoupper($request->mname))
                ->orWhereNull('mname');
            })->whereIn('status', ['pending', 'approved'])
            ->whereDate('created_at', date('Y-m-d'))
            ->exists()) {
                $param3 = 1;
            }
            else {
                $param3 = 0;
            }
            */

            $c2 = PaSwabDetails::ifHaveEntryToday($request->lname, $request->fname, $request->mname);

            if(!is_null($c2)) {
                return back()
                ->withInput()
                ->with('msg', 'You can only send one pa-swab request per day. Please wait for your request to be approved by CESU Staff/Encoders.')
                ->with('msgtype', 'warning')
                ->with('skipmodal', true);
            }
            else {
                $foundunique = false;

                while(!$foundunique) {
                    $majik = strtoupper(Str::random(6));
                    
                    $search = PaSwabDetails::where('majikCode', $majik);
                    if($search->count() == 0) {
                        $foundunique = true;
                    }
                }

                /*
                if($request->filled('philhealth')) {
                    if (strpos($request->philhealth, '-') !== false && substr($request->philhealth, -2, 1) == "-" && substr($request->philhealth, -12, 1) == "-") {
                        $philhealth_organized = $request->philhealth;
                    }
                    else {
                        $philhealth_organized = str_replace('-','', $request->philhealth);
                        $philhealth_organized = substr($philhealth_organized, 0, 2)."-".substr($philhealth_organized,2,9)."-".substr($philhealth_organized,11,1);
                    }
                }
                else {
                    $philhealth_organized = null;
                }
                */

                $check = PaSwabLinks::where('code', mb_strtoupper($request->linkcode))
                ->where('secondary_code', mb_strtoupper($request->linkcode2nd))
                ->where('active', 1)
                ->first();

                if($check) {
                    $finalproceed = 1;
                }
                else {
                    $finalproceed = 0;
                }

                if($finalproceed == 1) {
                    if(!is_null($check->lock_subd_array)) {
                        $sarray = explode(',', $check->lock_subd_array);
                        
                        if(!in_array(mb_strtoupper($request->address_street), $sarray)) {
                            return back()
                            ->withInput()
                            ->with('msg', 'Invalid Subdivision Selection. Please try again.')
                            ->with('msgtype', 'danger')
                            ->with('skipmodal', true);
                        }
                    }

                    $checku = Records::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);
                    /*
                    Old Pa-swab duplicate Entry checker

                    $checku = Records::where('lname', mb_strtoupper($request->lname))
                    ->where('fname', mb_strtoupper($request->fname))
                    ->where(function ($query) use ($request) {
                        $query->where('mname', mb_strtoupper($request->mname))
                        ->orWhereNull('mname');
                    })->first();
                    */

                    if(!is_null($checku)) {
                        $ifScheduledToday = Forms::where('records_id', $checku->id)
                        ->where(function ($query) {
                            $query->whereDate('testDateCollected1', '>=', date('Y-m-d'))
                            ->orWhere('testDateCollected2', '>=', date('Y-m-d'));
                        })->first();
                    }
                    else {
                        $ifScheduledToday = false;
                    }

                    if($ifScheduledToday) {
                        return back()
                        ->withInput()
                        ->with('msg', 'Error: You are not allowed to submit another pa-swab request because you currently have an Approved Schedule. Please see and use your Schedule Code for more details.')
                        ->with('msgtype', 'danger')
                        ->with('skipmodal', true);
                    }
                    else {
                        if($check->enableLockAddress == 1) {
                            $brgy = mb_strtoupper($check->lock_brgy);
                            $city = mb_strtoupper($check->lock_city_text);
                            $city_json = $check->lock_city;
                            $province = mb_strtoupper($check->lock_province_text);
                            $province_json = $check->lock_province;
                        }
                        else {
                            $brgy = mb_strtoupper($request->address_brgy);
                            $city = mb_strtoupper($request->address_city);
                            $city_json = $request->saddress_city;
                            $province = mb_strtoupper($request->address_province);
                            $province_json = $request->saddress_province;
                        }

                        $data = PaSwabDetails::create([
                            'isNewRecord' => (!is_null($checku)) ? 0 : 1,
                            'records_id' => (!is_null($checku)) ? $checku->id : NULL,
                            'majikCode' => $majik,
                            'pType' => $request->pType,
                            'linkCode' => $request->linkcode,
                            'isForHospitalization' => $request->isForHospitalization,
                            'interviewDate' => $request->interviewDate,
                            'forAntigen' => $request->forAntigen,
                            'lname' => mb_strtoupper($request->lname),
                            'fname' => mb_strtoupper($request->fname),
                            'mname' => ($request->filled('mname') && mb_strtoupper($request->mname) != "N/A") ? mb_strtoupper($request->mname) : null,
                            'bdate' => $request->bdate,
                            'gender' => strtoupper($request->gender),
                            'isPregnant' => ($request->gender == 'FEMALE') ? $request->isPregnant : 0,
                            'ifPregnantLMP' => ($request->gender == 'FEMALE' && $request->isPregnant == 1) ? $request->lmp : NULL,
                            'cs' => strtoupper($request->cs),
                            'nationality' => strtoupper($request->nationality),
                            'mobile' => $request->mobile,
                            'phoneno' => ($request->filled('phoneno')) ? $request->phoneno : NULL,
                            'email' => $request->email,
                            'philhealth' => $request->philhealth,
                            'address_houseno' => strtoupper($request->address_houseno),
                            'address_street' => strtoupper($request->address_street),

                            'address_brgy' => $brgy,
                            'address_city' => $city,
                            'address_cityjson' => $city_json,
                            'address_province' => $province,
                            'address_provincejson' => $province_json,
    
                            'vaccinationDate1' => ($request->vaccineq1 == 1) ? $request->vaccinationDate1 : NULL,
                            'vaccinationName1'=> ($request->vaccineq1 == 1) ? $request->nameOfVaccine : NULL,
                            'vaccinationNoOfDose1'=> ($request->vaccineq1 == 1) ? 1 : NULL,
                            'vaccinationFacility1'=> ($request->vaccineq1 == 1 && $request->filled('vaccinationFacility1')) ? mb_strtoupper($request->vaccinationFacility1) : NULL,
                            'vaccinationRegion1'=> ($request->vaccineq1 == 1 && $request->filled('vaccinationRegion1')) ? mb_strtoupper($request->vaccinationRegion1) : NULL,
                            'haveAdverseEvents1'=> ($request->vaccineq1 == 1) ? $request->haveAdverseEvents1 : NULL,
    
                            'vaccinationDate2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->vaccinationDate2 : NULL,
                            'vaccinationName2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->nameOfVaccine : NULL,
                            'vaccinationNoOfDose2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? 2 : NULL,
                            'vaccinationFacility2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2 && $request->filled('vaccinationFacility2')) ? mb_strtoupper($request->vaccinationFacility2) : NULL,
                            'vaccinationRegion2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2 && $request->filled('vaccinationRegion2')) ? mb_strtoupper($request->vaccinationRegion2) : NULL,
                            'haveAdverseEvents2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->haveAdverseEvents2 : NULL,

                            'vaccinationDate3' => ($request->haveBooster == 1) ? $request->vaccinationDate3 : NULL,
                            'haveAdverseEvents3' => ($request->haveBooster == 1) ? $request->haveAdverseEvents3 : NULL,
                            'vaccinationName3' =>  ($request->haveBooster == 1) ? $request->vaccinationName3 : NULL,
                            'vaccinationNoOfDose3' =>  ($request->haveBooster == 1) ? 3 : NULL,
                            'vaccinationFacility3' =>  ($request->haveBooster == 1 && $request->filled('vaccinationFacility3')) ? mb_strtoupper($request->vaccinationFacility3) : NULL,
                            'vaccinationRegion3' =>  ($request->haveBooster == 1 && $request->filled('vaccinationRegion3')) ? mb_strtoupper($request->vaccinationRegion3) : NULL,

                            'vaccinationDate4' => ($request->haveBooster2 == 1) ? $request->vaccinationDate4 : NULL,
                            'haveAdverseEvents4' => ($request->haveBooster2 == 1) ? $request->haveAdverseEvents4 : NULL,
                            'vaccinationName4' => ($request->haveBooster2 == 1) ? $request->vaccinationName4 : NULL,
                            'vaccinationNoOfDose4' => ($request->haveBooster2 == 1) ? 4 : NULL,
                            'vaccinationFacility4' => ($request->haveBooster2 == 1 && $request->filled('vaccinationFacility4')) ? mb_strtoupper($request->vaccinationFacility4) : NULL,
                            'vaccinationRegion4' => ($request->haveBooster2 == 1 && $request->filled('vaccinationRegion4')) ? mb_strtoupper($request->vaccinationRegion4) : NULL,
            
                            'occupation' => ($request->haveOccupation == 1) ? mb_strtoupper($request->occupation) : NULL,
                            'occupation_name' => ($request->filled('occupation_name')) ? mb_strtoupper($request->occupation_name) : NULL,
                            'natureOfWork' => ($request->haveOccupation == 1) ? mb_strtoupper($request->natureOfWork) : NULL,
                            'natureOfWorkIfOthers' => ($request->haveOccupation == 1 && $request->natureOfWork == "OTHERS") ? mb_strtoupper($request->natureOfWorkIfOthers) : NULL,
                            'worksInClosedSetting' => ($request->haveOccupation == 1) ? $request->worksInClosedSetting : 'UNKNOWN',

                            'occupation_lotbldg' => ($request->haveOccupation == 1) ? mb_strtoupper($request->occupation_lotbldg) : NULL,
                            'occupation_street' => ($request->haveOccupation == 1) ? mb_strtoupper($request->occupation_street) : NULL,
                            'occupation_brgy' => ($request->haveOccupation == 1) ? mb_strtoupper($request->occupation_brgy) : NULL,
                            'occupation_city' => ($request->haveOccupation == 1) ? mb_strtoupper($request->occupation_city) : NULL,
                            'occupation_cityjson' => ($request->haveOccupation == 1) ? mb_strtoupper($request->soccupation_city) : NULL,
                            'occupation_province' => ($request->haveOccupation == 1) ? mb_strtoupper($request->occupation_province) : NULL,
                            'occupation_provincejson' => ($request->haveOccupation == 1) ? mb_strtoupper($request->soccupation_province) : NULL,
                            'occupation_mobile' => $request->occupation_mobile,
                            'occupation_email' => $request->occupation_email,
            
                            'dateOnsetOfIllness' => ($request->haveSymptoms == 1) ? $request->dateOnsetOfIllness : NULL,
                            'SAS' => ($request->haveSymptoms == 1 && !is_null($request->sasCheck)) ? implode(",", $request->sasCheck) : NULL,
                            'SASFeverDeg' => ($request->haveSymptoms == 1) ? $request->SASFeverDeg : NULL,
                            'SASOtherRemarks' => $request->SASOtherRemarks,
            
                            'COMO' => implode(",", $request->comCheck),
                            'COMOOtherRemarks' => $request->COMOOtherRemarks,
            
                            'imagingDoneDate' => $request->imagingDoneDate,
                            'imagingDone' => $request->imagingDone,
                            'imagingResult' => $request->imagingResult,
                            'imagingOtherFindings' => $request->imagingOtherFindings,
            
                            'expoitem1' => $request->expoitem1,
                            'expoDateLastCont' => $request->expoDateLastCont,
            
                            'contact1Name' => ($request->filled('contact1Name')) ? mb_strtoupper($request->contact1Name) : NULL,
                            'contact1No' => $request->contact1No,
                            'contact2Name' => ($request->filled('contact2Name')) ? mb_strtoupper($request->contact2Name) : NULL,
                            'contact2No' => $request->contact2No,
                            'contact3Name' => ($request->filled('contact3Name')) ? mb_strtoupper($request->contact3Name) : NULL,
                            'contact3No' => $request->contact3No,
                            'contact4Name' => ($request->filled('contact4Name')) ? mb_strtoupper($request->contact4Name) : NULL,
                            'contact4No' => $request->contact4No,
        
                            'patientmsg' => $request->patientmsg,
            
                            'senderIP' => request()->ip(),
                        ]);

                        $auto_switch = 1;

                        if($auto_switch == 1) {
                            if($data->forAntigen == 1) {
                                $d_type = 'ANTIGEN';
                            }
                            else {
                                $d_type = 'NPS';
                            }

                            /*
                            NOTE :
                            0 => Sunday
                            1 => Monday
                            2 => Tuesday
                            3 => Wednesday
                            4 => Thursday
                            5 => Friday
                            6 => Saturday
                            */

                            if(time() >= strtotime('00:00') && time() <= ('13:00')) {
                                $weekday = date('w');

                                if($weekday == 3) {
                                    $d_date = date('Y-m-d', strtotime('+1 Day'));
                                }
                                else if($weekday == 6) {
                                    $d_date = date('Y-m-d', strtotime('+2 Days'));
                                }
                                else if($weekday == 0) {
                                    $d_date = date('Y-m-d', strtotime('+1 Day'));
                                }
                                else {
                                    $d_date = date('Y-m-d');
                                }
                            }
                            else {
                                $weekday = date('w', strtotime('+1 Day'));

                                if($weekday == 3) {
                                    $d_date = date('Y-m-d', strtotime('+2 Days'));
                                }
                                else if($weekday == 6) {
                                    $d_date = date('Y-m-d', strtotime('+3 Days'));
                                }
                                else if($weekday == 0) {
                                    $d_date = date('Y-m-d', strtotime('+2 Days'));
                                }
                                else {
                                    $d_date = date('Y-m-d', strtotime('+1 Day'));
                                }
                            }

                            //Auto Accept
                            //create record data first
                            if($data->isNewRecord == 1) {
                                $rec = Records::create([
                                    'user_id' => 42, //CESU Bot
                                    'status' => 'approved',
                                    'lname' => mb_strtoupper($data->lname),
                                    'fname' => mb_strtoupper($data->fname),
                                    'mname' => (!is_null($data->mname)) ? mb_strtoupper($data->mname) : null,
                                    'gender' => strtoupper($data->gender),
                                    'isPregnant' => $data->isPregnant,
                                    'cs' => strtoupper($data->cs),
                                    'nationality' => strtoupper($data->nationality),
                                    'bdate' => $data->bdate,
                                    'mobile' => $data->mobile,
                                    'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                                    'email' => $data->email,
                                    'philhealth' => $data->philhealth,
                                    'address_houseno' => strtoupper($data->address_houseno),
                                    'address_street' => strtoupper($data->address_street),
                                    'address_brgy' => strtoupper($data->address_brgy),
                                    'address_city' => strtoupper($data->address_city),
                                    'address_cityjson' => $data->address_cityjson,
                                    'address_province' => strtoupper($data->address_province),
                                    'address_provincejson' => $data->address_provincejson,
                    
                                    'permaaddressDifferent' => 0,
                                    'permaaddress_houseno' => strtoupper($data->address_houseno),
                                    'permaaddress_street' => strtoupper($data->address_street),
                                    'permaaddress_brgy' => strtoupper($data->address_brgy),
                                    'permaaddress_city' => strtoupper($data->address_city),
                                    'permaaddress_cityjson' => $data->address_cityjson,
                                    'permaaddress_province' => strtoupper($data->address_province),
                                    'permaaddress_provincejson' => $data->address_provincejson,
                                    'permamobile' => $data->mobile,
                                    'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                                    'permaemail' => $data->email,
                    
                                    'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                                    'occupation' => $data->occupation,
                                    'worksInClosedSetting' => $data->worksInClosedSetting,
                                    'occupation_lotbldg' => $data->occupation_lotbldg,
                                    'occupation_street' => $data->occupation_street,
                                    'occupation_brgy' => $data->occupation_brgy,
                                    'occupation_city' => $data->occupation_city,
                                    'occupation_cityjson' => $data->occupation_cityjson,
                                    'occupation_province' => $data->occupation_province,
                                    'occupation_provincejson' => $data->occupation_provincejson,
                                    'occupation_name' => $data->occupation_name,
                                    'occupation_mobile' => $data->occupation_mobile,
                                    'occupation_email' => $data->occupation_email,
                    
                                    'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                                    'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,
                
                                    'vaccinationDate1' => $data->vaccinationDate1,
                                    'vaccinationName1' => $data->vaccinationName1,
                                    'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                                    'vaccinationFacility1' => $data->vaccinationFacility1,
                                    'vaccinationRegion1' => $data->vaccinationRegion1,
                                    'haveAdverseEvents1' => $data->haveAdverseEvents1,
                
                                    'vaccinationDate2' => $data->vaccinationDate2,
                                    'vaccinationName2' => $data->vaccinationName2,
                                    'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                                    'vaccinationFacility2' => $data->vaccinationFacility2,
                                    'vaccinationRegion2' => $data->vaccinationRegion2,
                                    'haveAdverseEvents2' => $data->haveAdverseEvents2,
                
                                    'vaccinationDate3' => $data->vaccinationDate3,
                                    'vaccinationName3' => $data->vaccinationName3,
                                    'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                                    'vaccinationFacility3' => $data->vaccinationFacility3,
                                    'vaccinationRegion3' => $data->vaccinationRegion3,
                                    'haveAdverseEvents3' => $data->haveAdverseEvents3,
                
                                    'vaccinationDate4' => $data->vaccinationDate4,
                                    'vaccinationName4' => $data->vaccinationName4,
                                    'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                                    'vaccinationFacility4' => $data->vaccinationFacility4,
                                    'vaccinationRegion4' => $data->vaccinationRegion4,
                                    'haveAdverseEvents4' => $data->haveAdverseEvents4,
                                ]);
                            }
                            else {
                                $rec = Records::findOrFail($data->records_id);
                
                                $rec->update([
                                    'status' => 'approved',
                                    'isPregnant' => $data->isPregnant,
                                    'cs' => strtoupper($data->cs),
                                    'nationality' => strtoupper($data->nationality),
                                    'mobile' => $data->mobile,
                                    'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                                    'email' => $data->email,
                                    'philhealth' => $data->philhealth,
                                    'address_houseno' => strtoupper($data->address_houseno),
                                    'address_street' => strtoupper($data->address_street),
                                    'address_brgy' => strtoupper($data->address_brgy),
                                    'address_city' => strtoupper($data->address_city),
                                    'address_cityjson' => $data->address_cityjson,
                                    'address_province' => strtoupper($data->address_province),
                                    'address_provincejson' => $data->address_provincejson,
                    
                                    'permaaddressDifferent' => 0,
                                    'permaaddress_houseno' => strtoupper($data->address_houseno),
                                    'permaaddress_street' => strtoupper($data->address_street),
                                    'permaaddress_brgy' => strtoupper($data->address_brgy),
                                    'permaaddress_city' => strtoupper($data->address_city),
                                    'permaaddress_cityjson' => $data->address_cityjson,
                                    'permaaddress_province' => strtoupper($data->address_province),
                                    'permaaddress_provincejson' => $data->address_provincejson,
                                    'permamobile' => $data->mobile,
                                    'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                                    'permaemail' => $data->email,
                    
                                    'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                                    'occupation' => $data->occupation,
                                    'worksInClosedSetting' => $data->worksInClosedSetting,
                                    'occupation_lotbldg' => $data->occupation_lotbldg,
                                    'occupation_street' => $data->occupation_street,
                                    'occupation_brgy' => $data->occupation_brgy,
                                    'occupation_city' => $data->occupation_city,
                                    'occupation_cityjson' => $data->occupation_cityjson,
                                    'occupation_province' => $data->occupation_province,
                                    'occupation_provincejson' => $data->occupation_provincejson,
                                    'occupation_name' => $data->occupation_name,
                                    'occupation_mobile' => $data->occupation_mobile,
                                    'occupation_email' => $data->occupation_email,
                    
                                    'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                                    'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,
                
                                    'vaccinationDate1' => $data->vaccinationDate1,
                                    'vaccinationName1' => $data->vaccinationName1,
                                    'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                                    'vaccinationFacility1' => $data->vaccinationFacility1,
                                    'vaccinationRegion1' => $data->vaccinationRegion1,
                                    'haveAdverseEvents1' => $data->haveAdverseEvents1,
                
                                    'vaccinationDate2' => $data->vaccinationDate2,
                                    'vaccinationName2' => $data->vaccinationName2,
                                    'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                                    'vaccinationFacility2' => $data->vaccinationFacility2,
                                    'vaccinationRegion2' => $data->vaccinationRegion2,
                                    'haveAdverseEvents2' => $data->haveAdverseEvents2,
                
                                    'vaccinationDate3' => $data->vaccinationDate3,
                                    'vaccinationName3' => $data->vaccinationName3,
                                    'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                                    'vaccinationFacility3' => $data->vaccinationFacility3,
                                    'vaccinationRegion3' => $data->vaccinationRegion3,
                                    'haveAdverseEvents3' => $data->haveAdverseEvents3,
                
                                    'vaccinationDate4' => $data->vaccinationDate4,
                                    'vaccinationName4' => $data->vaccinationName4,
                                    'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                                    'vaccinationFacility4' => $data->vaccinationFacility4,
                                    'vaccinationRegion4' => $data->vaccinationRegion4,
                                    'haveAdverseEvents4' => $data->haveAdverseEvents4,

                                    'updated_by' => 42, //CESU Bot
                                ]);
                
                                $oldform = Forms::where('records_id', $rec->id)->first();
                            }
                
                            if(!is_null($rec->philhealth)) {
                                if($d_type == "OPS" || $d_type == "NPS" || $d_type == "OPS AND NPS") {
                                    $trigger = 0;
                                    $addMinutes = 0;
                
                                    while ($trigger != 1) {
                                        $oniStartTime = date('H:i:s', strtotime('14:00:00 + '. $addMinutes .' minutes'));
                
                                        $query = Forms::with('records')
                                        ->where('testDateCollected1', $d_date)
                                        ->whereIn('testType1', ['OPS', 'NPS', 'OPS AND NPS'])
                                        ->whereHas('records', function ($q) {
                                            $q->whereNotNull('philhealth');
                                        })
                                        ->where('oniTimeCollected1', $oniStartTime)->get();
                
                                        if($query->count()) {
                                            if($query->count() < 5) {
                                                $oniTimeFinal = $oniStartTime;
                                                $trigger = 1;
                                            }
                                            else {
                                                $addMinutes = $addMinutes + 5;
                                            }
                                        }
                                        else {
                                            $oniTimeFinal = $oniStartTime;
                                            $trigger = 1;
                                        }
                                    }
                                }
                                else {
                                    $oniTimeFinal = NULL;
                                }
                            }
                            else {
                                $oniTimeFinal = NULL;
                            }
                
                            $comcheck = explode(',', $data->COMO);
                
                            //Auto Change Testing Category/Subgroup Base on the patient data
                            /*
                            if($rec->isForHospitalization == 1) {
                                array_push($testCat, "F.3");
                            }
                            */
                
                            $testCat = '';
                            $custom_dispo = NULL;
                
                            if(!is_null($data->SAS)) {
                                if($data->getAgeInt() >= 60) {
                                    $testCat = 'A2';
                                }
                                else {
                                    $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
                                }
                            }
                            else if($data->getAgeInt() >= 60) {
                                $testCat = 'A2';
                            }
                            else if($data->pType == 'CLOSE CONTACT') {
                                $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
                            }
                            else if($data->isPregnant == 1) {
                                $testCat = 'A3';
                                $custom_dispo = 'FOR DELIVERY';
                            }
                            else if(in_array('Dialysis', $comcheck)) {
                                $testCat = 'A3';
                                $custom_dispo = 'FOR DIALYSIS';
                            }
                            else if(in_array('Cancer', $comcheck)) {
                                $testCat = 'A3';
                                $custom_dispo = 'CANCER PATIENT';
                            }
                            else if(in_array('Operation', $comcheck)) {
                                $testCat = 'A3';
                                $custom_dispo = 'FOR OPERATION';
                            }
                            else if(in_array('Transplant', $comcheck)) {
                                $testCat = 'A3';
                                $custom_dispo = 'TRANSPLANT';
                            }
                            else if($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                                $testCat = 'A1';
                            }
                
                            //Auto Change Case Classification to Probable Based on Symptoms
                            if(!is_null($data->SAS)) {
                                if(in_array('Anosmia (Loss of Smell)', explode(",", $data->SAS)) || in_array('Ageusia (Loss of Taste)', explode(",", $data->SAS))) {
                                    $caseClassi = 'Probable';
                                }
                                else {
                                    $caseClassi = 'Suspect';
                                }
                            }
                            else {
                                $caseClassi = 'Suspect';
                            }
                
                            //Antigen QR
                            if($data->forAntigen == 1) {
                                $foundunique = false;
                                while(!$foundunique) {
                                    $majik = Str::random(10);
                                    
                                    $qr_search = Forms::where('antigenqr', $majik);
                                    if($qr_search->count() == 0) {
                                        $foundunique = true;
                                    }
                                }
                
                                $antigenqr = $majik;
                            }
                            else {
                                $antigenqr = NULL;
                            }
                
                            $createform = Forms::create([
                                'user_id' => 42,
                                'morbidityMonth' => date('Y-m-d'),
                                'dateReported' => date('Y-m-d'),
                                'majikCode' => $data->majikCode,
                                'status' => 'approved',
                                'isPresentOnSwabDay' => NULL,
                                'records_id' => $rec->id,
                                'drunit' => 'CHO GENERAL TRIAS',
                                'drregion' => '4A',
                                'drprovince' => 'CAVITE',
                                'interviewerName' => $data->getDefaultInterviewerName(),
                                'interviewerMobile' => '09190664324',
                                'interviewDate' => $data->interviewDate,
                                'informantName' => NULL,
                                'informantRelationship' => NULL,
                                'informantMobile' => NULL,
                                'existingCaseList' => '1',
                                'ecOthersRemarks' => NULL,
                                'pType' => ($data->pType == 'FOR TRAVEL') ? 'TESTING' : $data->pType,
                                'isForHospitalization' => $data->isForHospitalization,
                                'testingCat' => $testCat,
                                'havePreviousCovidConsultation' => ($data->isNewRecord == 0) ? '1' : '0',
                                'dateOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->interviewDate : NULL : NULL,
                                'facilityNameOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->drunit : NULL : NULL,
                
                                'dispoType' => (!is_null($custom_dispo)) ? 5 : 3,
                                'dispoName' => $custom_dispo,
                                'dispoDate' => date('Y-m-d 08:00:00', strtotime($data->interviewDate)),
                                'healthStatus' => (!is_null($data->SAS)) ? 'Mild' : 'Asymptomatic',
                                'caseClassification' => $caseClassi,
                                'isHealthCareWorker' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? '1' : '0',
                                'healthCareCompanyName' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_name : NULL,
                                'healthCareCompanyLocation' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_city.', '.$data->occupation_province : NULL,
                                'isOFW' => '0',
                                'OFWCountyOfOrigin' => NULL,
                                'ofwType' => NULL,
                                'isFNT' => '0',
                                'lsiType' => NULL,
                                'FNTCountryOfOrigin' => NULL,
                                'isLSI' => '0',
                                'LSICity' => NULL,
                                'LSIProvince' => NULL,
                                'isLivesOnClosedSettings' => '0',
                                'institutionType' => NULL,
                                'institutionName' => NULL,
                                'indgSpecify' => NULL,
                                'dateOnsetOfIllness' => $data->dateOnsetOfIllness,
                                'SAS' => $data->SAS,
                                'SASFeverDeg' => $data->SASFeverDeg,
                                'SASOtherRemarks' => $data->SASOtherRemarks,
                                'COMO' => $data->COMO,
                                'COMOOtherRemarks' => $data->COMOOtherRemarks,
                                'PregnantLMP' => $data->ifPregnantLMP,
                                'PregnantHighRisk' => ($data->isPregnant == 1) ? '1' : '0',
                                'diagWithSARI' => '0',
                                'imagingDoneDate' => $data->imagingDoneDate,
                                'imagingDone' => $data->imagingDone,
                                'imagingResult' => $data->imagingResult,
                                'imagingOtherFindings' => $data->imagingOtherFindings,
                
                                'testedPositiveUsingRTPCRBefore' => '0',
                                'testedPositiveNumOfSwab' => '0',
                                'testedPositiveLab' => NULL,
                                'testedPositiveSpecCollectedDate' => NULL,
                
                                'testDateCollected1' => $d_date,
                                'oniTimeCollected1' => $oniTimeFinal,
                                'testDateReleased1' => NULL,
                                'testLaboratory1' => NULL,
                                'testType1' => $d_type,
                                'testTypeAntigenRemarks1' => ($d_type == "ANTIGEN") ? 'CONFIRMATORY' : NULL,
                                'antigenKit1' => ($d_type == "ANTIGEN") ? 'WONDFO' : NULL,
                                'testTypeOtherRemarks1' => ($d_type == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                                'testResult1' => 'PENDING',
                                'testResultOtherRemarks1' => NULL,
                
                                'testDateCollected2' => NULL,
                                'oniTimeCollected2' => NULL,
                                'testDateReleased2' => NULL,
                                'testLaboratory2' => NULL,
                                'testType2' => NULL,
                                'testTypeAntigenRemarks2' => NULL,
                                'antigenKit2' => NULL,
                                'testTypeOtherRemarks2' => NULL,
                                'testResult2' => NULL,
                                'testResultOtherRemarks2' => NULL,
                
                                'outcomeCondition' => 'Active',
                                'outcomeRecovDate' => NULL,
                                'outcomeDeathDate' => NULL,
                                'deathImmeCause' => NULL,
                                'deathAnteCause' => NULL,
                                'deathUndeCause' => NULL,
                                'contriCondi' => NULL,
                
                                'expoitem1' => $data->expoitem1,
                                'expoDateLastCont' => $data->expoDateLastCont,
                
                                'expoitem2' => '0',
                                'intCountry' => NULL,
                                'intDateFrom' => NULL,
                                'intDateTo' => NULL,
                                'intWithOngoingCovid' => 'N/A',
                                'intVessel' => NULL,
                                'intVesselNo' => NULL,
                                'intDateDepart' => NULL,
                                'intDateArrive' => NULL,
                
                                'placevisited' => NULL,
                
                                'locName1' => NULL,
                                'locAddress1' => NULL,
                                'locDateFrom1' => NULL,
                                'locDateTo1' => NULL,
                                'locWithOngoingCovid1' => 'N/A',
                
                                'locName2' => NULL,
                                'locAddress2' => NULL,
                                'locDateFrom2' => NULL,
                                'locDateTo2' => NULL,
                                'locWithOngoingCovid2' => 'N/A',
                                
                                'locName3' => NULL,
                                'locAddress3' => NULL,
                                'locDateFrom3' => NULL,
                                'locDateTo3' => NULL,
                                'locWithOngoingCovid3' => 'N/A',
                                
                                'locName4' => NULL,
                                'locAddress4' => NULL,
                                'locDateFrom4' => NULL,
                                'locDateTo4' => NULL,
                                'locWithOngoingCovid4' => 'N/A',
                
                                'locName5' => NULL,
                                'locAddress5' => NULL,
                                'locDateFrom5' => NULL,
                                'locDateTo5' => NULL,
                                'locWithOngoingCovid5' => 'N/A',
                
                                'locName6' => NULL,
                                'locAddress6' => NULL,
                                'locDateFrom6' => NULL,
                                'locDateTo6' => NULL,
                                'locWithOngoingCovid6' => 'N/A',
                
                                'locName7' => NULL,
                                'locAddress7' => NULL,
                                'locDateFrom7' => NULL,
                                'locDateTo7' => NULL,
                                'locWithOngoingCovid7' => 'N/A',
                
                                'localVessel1' => NULL,
                                'localVesselNo1' => NULL,
                                'localOrigin1' => NULL,
                                'localDateDepart1' => NULL,
                                'localDest1' => NULL,
                                'localDateArrive1' => NULL,
                
                                'localVessel2' => NULL,
                                'localVesselNo2' => NULL,
                                'localOrigin2' => NULL,
                                'localDateDepart2' => NULL,
                                'localDest2' => NULL,
                                'localDateArrive2' => NULL,
                
                                'contact1Name' => $data->contact1Name,
                                'contact1No' => $data->contact1No,
                                'contact2Name' => $data->contact2Name,
                                'contact2No' => $data->contact2No,
                                'contact3Name' => $data->contact3Name,
                                'contact3No' => $data->contact3No,
                                'contact4Name' => $data->contact4Name,
                                'contact4No' => $data->contact4No,
                
                                'remarks' => $data->patientmsg,
                                'antigenqr' => $antigenqr,
                            ]);
                
                            //Create Monitoring Sheet
                            /*
                            $msheet_search = MonitoringSheetMaster::where('forms_id', $createform->id)->first();
                            if(!$msheet_search) {
                                $foundunique = false;
                                while(!$foundunique) {
                                    $majik = Str::random(30);
                                    
                                    $search = MonitoringSheetMaster::where('magicURL', $majik);
                                    if($search->count() == 0) {
                                        $foundunique = true;
                                    }
                                }
                
                                $newmsheet = new MonitoringSheetMaster;
                
                                $newmsheet->forms_id = $createform->id;
                                $newmsheet->region = '4A';
                                $newmsheet->date_lastexposure = (!is_null($createform->expoDateLastCont)) ? $createform->expoDateLastCont : $createform->interviewDate;
                                $newmsheet->date_startquarantine = $createform->interviewDate;
                                $newmsheet->date_endquarantine = Carbon::parse($createform->interviewDate)->addDays(13)->format('Y-m-d');
                                $newmsheet->magicURL = $majik;
                
                                $newmsheet->save();
                            }
                            */
                            
                            $upd = PaSwabDetails::where('id', $data->id)->update([
                                'status' => 'approved',
                            ]);
                
                            if($data->isNewRecord == 0) {
                                if($oldform) {
                                    if($oldform->caseClassification != 'Confirmed' && $oldform->caseClassification != 'Non-COVID-19 Case') {
                                        $fcheck = Forms::where('id', $oldform->id)->delete();
                                    }
                                }
                            }
                        }
                        
                        return redirect()->route('paswab.complete', ['locale' => $locale])
                        ->with('majik', $majik)
                        ->with('statustype', 'success')
                        ->with('fcode', mb_strtoupper($request->linkcode))
                        ->with('scode', mb_strtoupper($request->linkcode2nd));
                    }
                }
                else {
                    return back()
                    ->withInput()
                    ->with('msg', 'Error: Pa-swab Referal Link Code is invalid or currently disabled. Please use proper Pa-Swab URL and then try again.')
                    ->with('msgtype', 'danger')
                    ->with('skipmodal', true);
                }
            }
        }
    }

    public function complete($locale) {
        if (! in_array($locale, ['en', 'fil'])) {
            abort(404);
        }

        App::setLocale($locale);

        return view('paswab_complete');
    }

    public function check (Request $request, $locale) {
        $request->validate([
            'scode' => 'required',
        ]);

        $check = PaSwabDetails::where('majikCode', strtoupper($request->scode))->first();

        if($check) {
            if($check->status == 'approved') {
                $form = Forms::where('majikCode', $check->majikCode)->first();

                return view('paswab_check', [
                    'data' => $check,
                    'form' => $form,
                ]);
            }
            else {
                return view('paswab_check', [
                    'data' => $check
                ]);
            }
        }
        else {
            return back()
			->withInput()
            ->with('openform', 'patient')
			->with('msg', 'Schedule Code does not exist. Please try again.')
            ->with('msgtype', 'danger');
        }
    }

    public function view() {
        if(request()->input('q')) {
            $list = PaSwabDetails::where(function ($query) {
                $query->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
                ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
                ->orWhere('majikCode', 'LIKE', "%".mb_strtoupper(request()->input('q'))."%")
                ->orWhere('linkCode', 'LIKE', "%".mb_strtoupper(request()->input('q'))."%");
            })->where('status', 'pending');
		}
        else {
            $list = PaSwabDetails::where('status', 'pending');
        }

        if(auth()->user()->isBrgyAccount()) {
            $list = $list->where('address_province', auth()->user()->brgy->city->province->provinceName)
            ->where('address_city', auth()->user()->brgy->city->cityName)
            ->where('address_brgy', auth()->user()->brgy->brgyName);
        }

        $list = $list
        ->orderBy('created_at', 'asc')
        ->paginate(10);
        
        return view('paswab_view', ['list' => $list]);
    }

    public function viewspecific($id) {
        $data = PaSwabDetails::findOrFail($id);

        $interviewers = Interviewers::orderBy('lname', 'asc')->get();

        if($data->checkPaswabBrgyData()) {
            return view('paswab_view_specific', ['data' => $data, 'interviewers' => $interviewers]);
        }
        else {
            return abort(401);
        }
    }

    public function approve($id, Request $request) {
        $data = PaSwabDetails::findOrFail($id);

        if(!($data->checkPaswabBrgyData())) {
            return abort(401);
        }

        //Test Type final validator forAntigen
        if($data->forAntigen == 1) {
            $ttype = 'ANTIGEN';
        }
        else {
            $ttype = $request->testType1;
        }

        $request->validate([
            'interviewerName' => 'required',
            'testDateCollected1' => 'required|date|after_or_equal:today',
            'testType1' => 'required|in:OPS,NPS,OPS AND NPS,ANTIGEN,ANTIBODY,OTHERS',
            'testTypeOtherRemarks1' => ($ttype == "ANTIGEN" || $ttype == "OTHERS") ? 'required' : 'nullable',
            'antigenKit1' => ($ttype == "ANTIGEN") ? 'required' : 'nullable',
        ]);

        if($data->status == 'pending') {
            //create record data first
            if($data->isNewRecord == 1) {
                $rec = $request->user()->records()->create([
                    'status' => 'approved',
                    'lname' => mb_strtoupper($data->lname),
                    'fname' => mb_strtoupper($data->fname),
                    'mname' => (!is_null($data->mname)) ? mb_strtoupper($data->mname) : null,
                    'gender' => strtoupper($data->gender),
                    'isPregnant' => $data->isPregnant,
                    'cs' => strtoupper($data->cs),
                    'nationality' => strtoupper($data->nationality),
                    'bdate' => $data->bdate,
                    'mobile' => $data->mobile,
                    'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                    'email' => $data->email,
                    'philhealth' => $data->philhealth,
                    'address_houseno' => strtoupper($data->address_houseno),
                    'address_street' => strtoupper($data->address_street),
                    'address_brgy' => strtoupper($data->address_brgy),
                    'address_city' => strtoupper($data->address_city),
                    'address_cityjson' => $data->address_cityjson,
                    'address_province' => strtoupper($data->address_province),
                    'address_provincejson' => $data->address_provincejson,
    
                    'permaaddressDifferent' => 0,
                    'permaaddress_houseno' => strtoupper($data->address_houseno),
                    'permaaddress_street' => strtoupper($data->address_street),
                    'permaaddress_brgy' => strtoupper($data->address_brgy),
                    'permaaddress_city' => strtoupper($data->address_city),
                    'permaaddress_cityjson' => $data->address_cityjson,
                    'permaaddress_province' => strtoupper($data->address_province),
                    'permaaddress_provincejson' => $data->address_provincejson,
                    'permamobile' => $data->mobile,
                    'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                    'permaemail' => $data->email,
    
                    'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                    'occupation' => $data->occupation,
                    'worksInClosedSetting' => $data->worksInClosedSetting,
                    'occupation_lotbldg' => $data->occupation_lotbldg,
                    'occupation_street' => $data->occupation_street,
                    'occupation_brgy' => $data->occupation_brgy,
                    'occupation_city' => $data->occupation_city,
                    'occupation_cityjson' => $data->occupation_cityjson,
                    'occupation_province' => $data->occupation_province,
                    'occupation_provincejson' => $data->occupation_provincejson,
                    'occupation_name' => $data->occupation_name,
                    'occupation_mobile' => $data->occupation_mobile,
                    'occupation_email' => $data->occupation_email,
    
                    'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                    'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,

                    'vaccinationDate1' => $data->vaccinationDate1,
                    'vaccinationName1' => $data->vaccinationName1,
                    'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                    'vaccinationFacility1' => $data->vaccinationFacility1,
                    'vaccinationRegion1' => $data->vaccinationRegion1,
                    'haveAdverseEvents1' => $data->haveAdverseEvents1,

                    'vaccinationDate2' => $data->vaccinationDate2,
                    'vaccinationName2' => $data->vaccinationName2,
                    'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                    'vaccinationFacility2' => $data->vaccinationFacility2,
                    'vaccinationRegion2' => $data->vaccinationRegion2,
                    'haveAdverseEvents2' => $data->haveAdverseEvents2,

                    'vaccinationDate3' => $data->vaccinationDate3,
                    'vaccinationName3' => $data->vaccinationName3,
                    'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                    'vaccinationFacility3' => $data->vaccinationFacility3,
                    'vaccinationRegion3' => $data->vaccinationRegion3,
                    'haveAdverseEvents3' => $data->haveAdverseEvents3,

                    'vaccinationDate4' => $data->vaccinationDate4,
                    'vaccinationName4' => $data->vaccinationName4,
                    'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                    'vaccinationFacility4' => $data->vaccinationFacility4,
                    'vaccinationRegion4' => $data->vaccinationRegion4,
                    'haveAdverseEvents4' => $data->haveAdverseEvents4,
                ]);
            }
            else {
                $rec = Records::findOrFail($data->records_id);

                $rec->update([
                    'status' => 'approved',
                    'isPregnant' => $data->isPregnant,
                    'cs' => strtoupper($data->cs),
                    'nationality' => strtoupper($data->nationality),
                    'mobile' => $data->mobile,
                    'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                    'email' => $data->email,
                    'philhealth' => $data->philhealth,
                    'address_houseno' => strtoupper($data->address_houseno),
                    'address_street' => strtoupper($data->address_street),
                    'address_brgy' => strtoupper($data->address_brgy),
                    'address_city' => strtoupper($data->address_city),
                    'address_cityjson' => $data->address_cityjson,
                    'address_province' => strtoupper($data->address_province),
                    'address_provincejson' => $data->address_provincejson,
    
                    'permaaddressDifferent' => 0,
                    'permaaddress_houseno' => strtoupper($data->address_houseno),
                    'permaaddress_street' => strtoupper($data->address_street),
                    'permaaddress_brgy' => strtoupper($data->address_brgy),
                    'permaaddress_city' => strtoupper($data->address_city),
                    'permaaddress_cityjson' => $data->address_cityjson,
                    'permaaddress_province' => strtoupper($data->address_province),
                    'permaaddress_provincejson' => $data->address_provincejson,
                    'permamobile' => $data->mobile,
                    'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                    'permaemail' => $data->email,
    
                    'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                    'occupation' => $data->occupation,
                    'worksInClosedSetting' => $data->worksInClosedSetting,
                    'occupation_lotbldg' => $data->occupation_lotbldg,
                    'occupation_street' => $data->occupation_street,
                    'occupation_brgy' => $data->occupation_brgy,
                    'occupation_city' => $data->occupation_city,
                    'occupation_cityjson' => $data->occupation_cityjson,
                    'occupation_province' => $data->occupation_province,
                    'occupation_provincejson' => $data->occupation_provincejson,
                    'occupation_name' => $data->occupation_name,
                    'occupation_mobile' => $data->occupation_mobile,
                    'occupation_email' => $data->occupation_email,
    
                    'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                    'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,

                    'vaccinationDate1' => $data->vaccinationDate1,
                    'vaccinationName1' => $data->vaccinationName1,
                    'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                    'vaccinationFacility1' => $data->vaccinationFacility1,
                    'vaccinationRegion1' => $data->vaccinationRegion1,
                    'haveAdverseEvents1' => $data->haveAdverseEvents1,

                    'vaccinationDate2' => $data->vaccinationDate2,
                    'vaccinationName2' => $data->vaccinationName2,
                    'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                    'vaccinationFacility2' => $data->vaccinationFacility2,
                    'vaccinationRegion2' => $data->vaccinationRegion2,
                    'haveAdverseEvents2' => $data->haveAdverseEvents2,

                    'vaccinationDate3' => $data->vaccinationDate3,
                    'vaccinationName3' => $data->vaccinationName3,
                    'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                    'vaccinationFacility3' => $data->vaccinationFacility3,
                    'vaccinationRegion3' => $data->vaccinationRegion3,
                    'haveAdverseEvents3' => $data->haveAdverseEvents3,

                    'vaccinationDate4' => $data->vaccinationDate4,
                    'vaccinationName4' => $data->vaccinationName4,
                    'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                    'vaccinationFacility4' => $data->vaccinationFacility4,
                    'vaccinationRegion4' => $data->vaccinationRegion4,
                    'haveAdverseEvents4' => $data->haveAdverseEvents4,
                ]);

                $oldform = Forms::where('records_id', $rec->id)->first();
            }

            if(!is_null($rec->philhealth)) {
                if($ttype == "OPS" || $ttype == "NPS" || $ttype == "OPS AND NPS") {
                    $trigger = 0;
                    $addMinutes = 0;

                    while ($trigger != 1) {
                        $oniStartTime = date('H:i:s', strtotime('14:00:00 + '. $addMinutes .' minutes'));

                        $query = Forms::with('records')
                        ->where('testDateCollected1', $request->testDateCollected1)
                        ->whereIn('testType1', ['OPS', 'NPS', 'OPS AND NPS'])
                        ->whereHas('records', function ($q) {
                            $q->whereNotNull('philhealth');
                        })
                        ->where('oniTimeCollected1', $oniStartTime)->get();

                        if($query->count()) {
                            if($query->count() < 5) {
                                $oniTimeFinal = $oniStartTime;
                                $trigger = 1;
                            }
                            else {
                                $addMinutes = $addMinutes + 5;
                            }
                        }
                        else {
                            $oniTimeFinal = $oniStartTime;
                            $trigger = 1;
                        }
                    }
                }
                else {
                    $oniTimeFinal = NULL;
                }
            }
            else {
                $oniTimeFinal = NULL;
            }

            $comcheck = explode(',', $data->COMO);

            //Auto Change Testing Category/Subgroup Base on the patient data
            /*
            if($rec->isForHospitalization == 1) {
                array_push($testCat, "F.3");
            }
            */

            $testCat = '';
            $custom_dispo = NULL;

            if(!is_null($data->SAS)) {
                if($data->getAgeInt() >= 60) {
                    $testCat = 'A2';
                }
                else {
                    $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
                }
            }
            else if($data->getAgeInt() >= 60) {
                $testCat = 'A2';
            }
            else if($data->pType == 'CLOSE CONTACT') {
                $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
            }
            else if($data->isPregnant == 1) {
                $testCat = 'A3';
                $custom_dispo = 'FOR DELIVERY';
            }
            else if(in_array('Dialysis', $comcheck)) {
                $testCat = 'A3';
                $custom_dispo = 'FOR DIALYSIS';
            }
            else if(in_array('Cancer', $comcheck)) {
                $testCat = 'A3';
                $custom_dispo = 'CANCER PATIENT';
            }
            else if(in_array('Operation', $comcheck)) {
                $testCat = 'A3';
                $custom_dispo = 'FOR OPERATION';
            }
            else if(in_array('Transplant', $comcheck)) {
                $testCat = 'A3';
                $custom_dispo = 'TRANSPLANT';
            }
            else if($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                $testCat = 'A1';
            }

            /*
            $testCat = [];
            $custom_dispo = NULL;
            if(!is_null($data->SAS)) {
                if($data->getAgeInt() >= 60) {
                    array_push($testCat, "B");
                }
                else {
                    array_push($testCat, "C");
                }
            }
            else if($data->getAgeInt() >= 60) {
                array_push($testCat, "B");
            }
            else if(!in_array("D.1", $testCat) && $data->pType == 'CLOSE CONTACT') {
                array_push($testCat, "D.1");
            }
            else if($data->isPregnant == 1) {
                array_push($testCat, "F.1");
            }
            else if(in_array('Dialysis', $comcheck)) {
                array_push($testCat, "F.2");
                $custom_dispo = 'DIALYSIS PATIENT';
            }
            else if(in_array('Cancer', $comcheck)) {
                array_push($testCat, "F.4");
                $custom_dispo = 'CANCER PATIENT';
            }
            else if(in_array('Operation', $comcheck)) {
                array_push($testCat, "F.5");
            }
            else if(in_array('Transplant', $comcheck)) {
                array_push($testCat, "F.6");
                $custom_dispo = 'FOR TRANSPLANT';
            }
            else if($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                array_push($testCat, "D.2");
            }
            else if($data->natureOfWork == 'MANUFACTURING') {
                array_push($testCat, "I");
            }
            else if($data->natureOfWork == 'GOVERNMENT UNITS/ORGANIZATIONS') {
                array_push($testCat, "E2.3");
            }
            else if($data->natureOfWork == 'TRANSPORTATION' || $data->natureOfWork == 'MANNING/SHIPPING AGENCY' || $data->natureOfWork == 'STORAGE') {
                if(!in_array('J1.1', $testCat)) {
                    array_push($testCat, "J1.1");
                }
            }
            else if($data->natureOfWork == 'EDUCATION') {
                array_push($testCat, "J1.3");
            }
            else if($data->natureOfWork == 'CONSTRUCTION' || $data->natureOfWork == 'ELECTRICITY') {
                if(!in_array('J1.8', $testCat)) {
                    array_push($testCat, "J1.8");
                }
            }
            else if($data->natureOfWork == 'HOTEL AND RESTAURANT' || $data->natureOfWork == 'WHOLESALE AND RETAIL TRADE') {
                if(!in_array('J1.2', $testCat)) {
                    array_push($testCat, "J1.2");
                }
            }
            else if($data->natureOfWork == 'FINANCIAL') {
                array_push($testCat, "J1.4");
            }
            else if($data->natureOfWork == 'SERVICES') {
                array_push($testCat, "J1.6");
            }
            else if($data->natureOfWork == 'MASS MEDIA') {
                array_push($testCat, "J1.11");
            }
            else {
                array_push($testCat, "G");
            }

            $testCat = implode(',', $testCat);
            */

            //Auto Change Case Classification to Probable Based on Symptoms
            if(!is_null($data->SAS)) {
                if(in_array('Anosmia (Loss of Smell)', explode(",", $data->SAS)) || in_array('Ageusia (Loss of Taste)', explode(",", $data->SAS))) {
                    $caseClassi = 'Probable';
                }
                else {
                    $caseClassi = 'Suspect';
                }
            }
            else {
                $caseClassi = 'Suspect';
            }

            //Antigen QR
            if($data->forAntigen == 1) {
                $foundunique = false;
                while(!$foundunique) {
                    $majik = Str::random(10);
                    
                    $qr_search = Forms::where('antigenqr', $majik);
                    if($qr_search->count() == 0) {
                        $foundunique = true;
                    }
                }

                $antigenqr = $majik;
            }
            else {
                $antigenqr = NULL;
            }

            $createform = $request->user()->form()->create([
                'morbidityMonth' => date('Y-m-d'),
                'dateReported' => date('Y-m-d'),
                'majikCode' => $data->majikCode,
                'status' => 'approved',
                'isPresentOnSwabDay' => NULL,
                'records_id' => $rec->id,
                'drunit' => 'CHO GENERAL TRIAS',
                'drregion' => '4A',
                'drprovince' => 'CAVITE',
                'interviewerName' => $request->interviewerName,
                'interviewerMobile' => '09190664324',
                'interviewDate' => $data->interviewDate,
                'informantName' => NULL,
                'informantRelationship' => NULL,
                'informantMobile' => NULL,
                'existingCaseList' => '1',
                'ecOthersRemarks' => NULL,
                'pType' => ($data->pType == 'FOR TRAVEL') ? 'TESTING' : $data->pType,
                'isForHospitalization' => $data->isForHospitalization,
                'testingCat' => $testCat,
                'havePreviousCovidConsultation' => ($data->isNewRecord == 0) ? '1' : '0',
                'dateOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->interviewDate : NULL : NULL,
                'facilityNameOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->drunit : NULL : NULL,

                'dispoType' => (!is_null($custom_dispo)) ? 5 : 3,
                'dispoName' => $custom_dispo,
                'dispoDate' => date('Y-m-d 08:00:00', strtotime($data->interviewDate)),
                'healthStatus' => (!is_null($data->SAS)) ? 'Mild' : 'Asymptomatic',
                'caseClassification' => $caseClassi,
                'isHealthCareWorker' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? '1' : '0',
                'healthCareCompanyName' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_name : NULL,
                'healthCareCompanyLocation' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_city.', '.$data->occupation_province : NULL,
                'isOFW' => '0',
                'OFWCountyOfOrigin' => NULL,
                'ofwType' => NULL,
                'isFNT' => '0',
                'lsiType' => NULL,
                'FNTCountryOfOrigin' => NULL,
                'isLSI' => '0',
                'LSICity' => NULL,
                'LSIProvince' => NULL,
                'isLivesOnClosedSettings' => '0',
                'institutionType' => NULL,
                'institutionName' => NULL,
                'indgSpecify' => NULL,
                'dateOnsetOfIllness' => $data->dateOnsetOfIllness,
                'SAS' => $data->SAS,
                'SASFeverDeg' => $data->SASFeverDeg,
                'SASOtherRemarks' => $data->SASOtherRemarks,
                'COMO' => $data->COMO,
                'COMOOtherRemarks' => $data->COMOOtherRemarks,
                'PregnantLMP' => $data->ifPregnantLMP,
                'PregnantHighRisk' => ($data->isPregnant == 1) ? '1' : '0',
                'diagWithSARI' => '0',
                'imagingDoneDate' => $data->imagingDoneDate,
                'imagingDone' => $data->imagingDone,
                'imagingResult' => $data->imagingResult,
                'imagingOtherFindings' => $data->imagingOtherFindings,

                'testedPositiveUsingRTPCRBefore' => '0',
                'testedPositiveNumOfSwab' => '0',
                'testedPositiveLab' => NULL,
                'testedPositiveSpecCollectedDate' => NULL,

                'testDateCollected1' => $request->testDateCollected1,
                'oniTimeCollected1' => $oniTimeFinal,
                'testDateReleased1' => NULL,
                'testLaboratory1' => NULL,
                'testType1' => $ttype,
                'testTypeAntigenRemarks1' => ($ttype == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                'antigenKit1' => ($ttype == "ANTIGEN") ? mb_strtoupper($request->antigenKit1) : NULL,
                'testTypeOtherRemarks1' => ($ttype == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                'testResult1' => 'PENDING',
                'testResultOtherRemarks1' => NULL,

                'testDateCollected2' => NULL,
                'oniTimeCollected2' => NULL,
                'testDateReleased2' => NULL,
                'testLaboratory2' => NULL,
                'testType2' => NULL,
                'testTypeAntigenRemarks2' => NULL,
                'antigenKit2' => NULL,
                'testTypeOtherRemarks2' => NULL,
                'testResult2' => NULL,
                'testResultOtherRemarks2' => NULL,

                'outcomeCondition' => 'Active',
                'outcomeRecovDate' => NULL,
                'outcomeDeathDate' => NULL,
                'deathImmeCause' => NULL,
                'deathAnteCause' => NULL,
                'deathUndeCause' => NULL,
                'contriCondi' => NULL,

                'expoitem1' => $data->expoitem1,
                'expoDateLastCont' => $data->expoDateLastCont,

                'expoitem2' => '0',
                'intCountry' => NULL,
                'intDateFrom' => NULL,
                'intDateTo' => NULL,
                'intWithOngoingCovid' => 'N/A',
                'intVessel' => NULL,
                'intVesselNo' => NULL,
                'intDateDepart' => NULL,
                'intDateArrive' => NULL,

                'placevisited' => NULL,

                'locName1' => NULL,
                'locAddress1' => NULL,
                'locDateFrom1' => NULL,
                'locDateTo1' => NULL,
                'locWithOngoingCovid1' => 'N/A',

                'locName2' => NULL,
                'locAddress2' => NULL,
                'locDateFrom2' => NULL,
                'locDateTo2' => NULL,
                'locWithOngoingCovid2' => 'N/A',
                
                'locName3' => NULL,
                'locAddress3' => NULL,
                'locDateFrom3' => NULL,
                'locDateTo3' => NULL,
                'locWithOngoingCovid3' => 'N/A',
                
                'locName4' => NULL,
                'locAddress4' => NULL,
                'locDateFrom4' => NULL,
                'locDateTo4' => NULL,
                'locWithOngoingCovid4' => 'N/A',

                'locName5' => NULL,
                'locAddress5' => NULL,
                'locDateFrom5' => NULL,
                'locDateTo5' => NULL,
                'locWithOngoingCovid5' => 'N/A',

                'locName6' => NULL,
                'locAddress6' => NULL,
                'locDateFrom6' => NULL,
                'locDateTo6' => NULL,
                'locWithOngoingCovid6' => 'N/A',

                'locName7' => NULL,
                'locAddress7' => NULL,
                'locDateFrom7' => NULL,
                'locDateTo7' => NULL,
                'locWithOngoingCovid7' => 'N/A',

                'localVessel1' => NULL,
                'localVesselNo1' => NULL,
                'localOrigin1' => NULL,
                'localDateDepart1' => NULL,
                'localDest1' => NULL,
                'localDateArrive1' => NULL,

                'localVessel2' => NULL,
                'localVesselNo2' => NULL,
                'localOrigin2' => NULL,
                'localDateDepart2' => NULL,
                'localDest2' => NULL,
                'localDateArrive2' => NULL,

                'contact1Name' => $data->contact1Name,
                'contact1No' => $data->contact1No,
                'contact2Name' => $data->contact2Name,
                'contact2No' => $data->contact2No,
                'contact3Name' => $data->contact3Name,
                'contact3No' => $data->contact3No,
                'contact4Name' => $data->contact4Name,
                'contact4No' => $data->contact4No,

                'remarks' => $data->patientmsg,
                'antigenqr' => $antigenqr,
            ]);

            //Create Monitoring Sheet
            $msheet_search = MonitoringSheetMaster::where('forms_id', $createform->id)->first();
            if(!$msheet_search) {
                $foundunique = false;
                while(!$foundunique) {
                    $majik = Str::random(30);
                    
                    $search = MonitoringSheetMaster::where('magicURL', $majik);
                    if($search->count() == 0) {
                        $foundunique = true;
                    }
                }

                $newmsheet = new MonitoringSheetMaster;

                $newmsheet->forms_id = $createform->id;
                $newmsheet->region = '4A';
                $newmsheet->date_lastexposure = (!is_null($createform->expoDateLastCont)) ? $createform->expoDateLastCont : $createform->interviewDate;
                $newmsheet->date_startquarantine = $createform->interviewDate;
                $newmsheet->date_endquarantine = Carbon::parse($createform->interviewDate)->addDays(13)->format('Y-m-d');
                $newmsheet->magicURL = $majik;

                $newmsheet->save();
            }
            
            $upd = PaSwabDetails::where('id', $id)->update([
                'status' => 'approved'
            ]);

            if($data->isNewRecord == 0) {
                if($oldform) {
                    if($oldform->caseClassification != 'Confirmed' && $oldform->caseClassification != 'Non-COVID-19 Case') {
                        $fcheck = Forms::where('id', $oldform->id)->delete();
                    }
                }
            }
            
            return redirect()->action([PaSwabController::class, 'view'])
            ->with('msg', 'Swab Schedule for '.$data->getName()." has been approved successfully.")
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->action([PaSwabController::class, 'view'])
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }
    }

    public function reject($id, Request $request) {
        /*
        $data = PaSwabDetails::findOrFail($id);

        $request->validate([
            'reason' => 'required',
        ]);

        if($data->status == 'pending') {
            $upd = PaSwabDetails::where('id', $id)->update([
                'status' => 'rejected',
                'remarks' => $request->reason,
                'processedAt' => date('Y-m-d'),
            ]);

            return redirect()->action([PaSwabController::class, 'view'])
            ->with('msg', 'Swab Schedule for '.$data->getName()." has been rejected successfully.")
            ->with('msgtype', 'success');
        }
        else {
            return redirect()->action([PaSwabController::class, 'view'])
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }
        */
        $request->validate([
            'rejectReason' => 'required',
        ]);

        $data = PaSwabDetails::where('id', $id)->where('status', 'pending')->get();

        if(!($data->checkPaswabBrgyData())) {
            return abort(401);
        }

        if($data->status == 'pending') {
            //Test Type final validator forAntigen
            if($data->forAntigen == 1) {
                $ttype = 'ANTIGEN';
                //dapat baguhin 'to ayon sa system settings
                $tOtherRemarks = 'FOR ANTIGEN REJECTED IN PASWAB';
                $tAntigenKit = 'FOR ANTIGEN REJECTED IN PASWAB';
            }
            else {
                $ttype = 'REJECTED IN PASWAB';
                $tOtherRemarks = NULL;
                $tAntigenKit = NULL;
            }

            if($data->isNewRecord == 1) {
                $rec = $request->user()->records()->create([
                    'status' => 'approved',
                    'lname' => mb_strtoupper($data->lname),
                    'fname' => mb_strtoupper($data->fname),
                    'mname' => (!is_null($data->mname)) ? mb_strtoupper($data->mname) : null,
                    'gender' => strtoupper($data->gender),
                    'isPregnant' => $data->isPregnant,
                    'cs' => strtoupper($data->cs),
                    'nationality' => strtoupper($data->nationality),
                    'bdate' => $data->bdate,
                    'mobile' => $data->mobile,
                    'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                    'email' => $data->email,
                    'philhealth' => $data->philhealth,
                    'address_houseno' => strtoupper($data->address_houseno),
                    'address_street' => strtoupper($data->address_street),
                    'address_brgy' => strtoupper($data->address_brgy),
                    'address_city' => strtoupper($data->address_city),
                    'address_cityjson' => $data->address_cityjson,
                    'address_province' => strtoupper($data->address_province),
                    'address_provincejson' => $data->address_provincejson,
    
                    'permaaddressDifferent' => 0,
                    'permaaddress_houseno' => strtoupper($data->address_houseno),
                    'permaaddress_street' => strtoupper($data->address_street),
                    'permaaddress_brgy' => strtoupper($data->address_brgy),
                    'permaaddress_city' => strtoupper($data->address_city),
                    'permaaddress_cityjson' => $data->address_cityjson,
                    'permaaddress_province' => strtoupper($data->address_province),
                    'permaaddress_provincejson' => $data->address_provincejson,
                    'permamobile' => $data->mobile,
                    'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                    'permaemail' => $data->email,
    
                    'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                    'occupation' => $data->occupation,
                    'worksInClosedSetting' => $data->worksInClosedSetting,
                    'occupation_lotbldg' => $data->occupation_lotbldg,
                    'occupation_street' => $data->occupation_street,
                    'occupation_brgy' => $data->occupation_brgy,
                    'occupation_city' => $data->occupation_city,
                    'occupation_cityjson' => $data->occupation_cityjson,
                    'occupation_province' => $data->occupation_province,
                    'occupation_provincejson' => $data->occupation_provincejson,
                    'occupation_name' => $data->occupation_name,
                    'occupation_mobile' => $data->occupation_mobile,
                    'occupation_email' => $data->occupation_email,
    
                    'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                    'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,

                    'vaccinationDate1' => $data->vaccinationDate1,
                    'vaccinationName1' => $data->vaccinationName1,
                    'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                    'vaccinationFacility1' => $data->vaccinationFacility1,
                    'vaccinationRegion1' => $data->vaccinationRegion1,
                    'haveAdverseEvents1' => $data->haveAdverseEvents1,

                    'vaccinationDate2' => $data->vaccinationDate2,
                    'vaccinationName2' => $data->vaccinationName2,
                    'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                    'vaccinationFacility2' => $data->vaccinationFacility2,
                    'vaccinationRegion2' => $data->vaccinationRegion2,
                    'haveAdverseEvents2' => $data->haveAdverseEvents2,

                    'vaccinationDate3' => $data->vaccinationDate3,
                    'vaccinationName3' => $data->vaccinationName3,
                    'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                    'vaccinationFacility3' => $data->vaccinationFacility3,
                    'vaccinationRegion3' => $data->vaccinationRegion3,
                    'haveAdverseEvents3' => $data->haveAdverseEvents3,

                    'vaccinationDate4' => $data->vaccinationDate4,
                    'vaccinationName4' => $data->vaccinationName4,
                    'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                    'vaccinationFacility4' => $data->vaccinationFacility4,
                    'vaccinationRegion4' => $data->vaccinationRegion4,
                    'haveAdverseEvents4' => $data->haveAdverseEvents4,
                ]);
            }
            else {
                $rec = Records::findOrFail($data->records_id);

                $rec->update([
                    'status' => 'approved',
                    'isPregnant' => $data->isPregnant,
                    'cs' => strtoupper($data->cs),
                    'nationality' => strtoupper($data->nationality),
                    'mobile' => $data->mobile,
                    'phoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                    'email' => $data->email,
                    'philhealth' => $data->philhealth,
                    'address_houseno' => strtoupper($data->address_houseno),
                    'address_street' => strtoupper($data->address_street),
                    'address_brgy' => strtoupper($data->address_brgy),
                    'address_city' => strtoupper($data->address_city),
                    'address_cityjson' => $data->address_cityjson,
                    'address_province' => strtoupper($data->address_province),
                    'address_provincejson' => $data->address_provincejson,
    
                    'permaaddressDifferent' => 0,
                    'permaaddress_houseno' => strtoupper($data->address_houseno),
                    'permaaddress_street' => strtoupper($data->address_street),
                    'permaaddress_brgy' => strtoupper($data->address_brgy),
                    'permaaddress_city' => strtoupper($data->address_city),
                    'permaaddress_cityjson' => $data->address_cityjson,
                    'permaaddress_province' => strtoupper($data->address_province),
                    'permaaddress_provincejson' => $data->address_provincejson,
                    'permamobile' => $data->mobile,
                    'permaphoneno' => (!is_null($data->phoneno)) ? $data->phoneno : NULL,
                    'permaemail' => $data->email,
    
                    'hasOccupation' => (!is_null($data->occupation)) ? 1 : 0,
                    'occupation' => $data->occupation,
                    'worksInClosedSetting' => $data->worksInClosedSetting,
                    'occupation_lotbldg' => $data->occupation_lotbldg,
                    'occupation_street' => $data->occupation_street,
                    'occupation_brgy' => $data->occupation_brgy,
                    'occupation_city' => $data->occupation_city,
                    'occupation_cityjson' => $data->occupation_cityjson,
                    'occupation_province' => $data->occupation_province,
                    'occupation_provincejson' => $data->occupation_provincejson,
                    'occupation_name' => $data->occupation_name,
                    'occupation_mobile' => $data->occupation_mobile,
                    'occupation_email' => $data->occupation_email,
    
                    'natureOfWork' => (!is_null($data->occupation)) ? mb_strtoupper($data->natureOfWork) : NULL,
                    'natureOfWorkIfOthers' => (!is_null($data->occupation) && $data->natureOfWork == 'OTHERS') ? mb_strtoupper($data->natureOfWorkIfOthers) : NULL,

                    'vaccinationDate1' => $data->vaccinationDate1,
                    'vaccinationName1' => $data->vaccinationName1,
                    'vaccinationNoOfDose1' => $data->vaccinationNoOfDose1,
                    'vaccinationFacility1' => $data->vaccinationFacility1,
                    'vaccinationRegion1' => $data->vaccinationRegion1,
                    'haveAdverseEvents1' => $data->haveAdverseEvents1,

                    'vaccinationDate2' => $data->vaccinationDate2,
                    'vaccinationName2' => $data->vaccinationName2,
                    'vaccinationNoOfDose2' => $data->vaccinationNoOfDose2,
                    'vaccinationFacility2' => $data->vaccinationFacility2,
                    'vaccinationRegion2' => $data->vaccinationRegion2,
                    'haveAdverseEvents2' => $data->haveAdverseEvents2,

                    'vaccinationDate3' => $data->vaccinationDate3,
                    'vaccinationName3' => $data->vaccinationName3,
                    'vaccinationNoOfDose3' => $data->vaccinationNoOfDose3,
                    'vaccinationFacility3' => $data->vaccinationFacility3,
                    'vaccinationRegion3' => $data->vaccinationRegion3,
                    'haveAdverseEvents3' => $data->haveAdverseEvents3,

                    'vaccinationDate4' => $data->vaccinationDate4,
                    'vaccinationName4' => $data->vaccinationName4,
                    'vaccinationNoOfDose4' => $data->vaccinationNoOfDose4,
                    'vaccinationFacility4' => $data->vaccinationFacility4,
                    'vaccinationRegion4' => $data->vaccinationRegion4,
                    'haveAdverseEvents4' => $data->haveAdverseEvents4,
                ]);

                $oldform = Forms::where('records_id', $rec->id)->orderBy('created_at', 'DESC')->first();
            }

            $comcheck = explode(',', $data->COMO);

            //Auto Change Testing Category/Subgroup Base on the patient data
            /*
            if($rec->isForHospitalization == 1) {
                array_push($testCat, "F.3");
            }
            */

            $testCat = '';
            $custom_dispo = NULL;

            if(!is_null($data->SAS)) {
                if($data->getAgeInt() >= 60) {
                    $testCat = 'A2';
                }
                else {
                    $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
                }
            }
            else if($data->getAgeInt() >= 60) {
                $testCat = 'A2';
            }
            else if($data->pType == 'CLOSE CONTACT') {
                $testCat = 'ALL (Except A1, A2 and A3) with Symptoms of COVID-19';
            }
            else if($data->isPregnant == 1) {
                $testCat = 'A3';
                $custom_dispo = 'FOR DELIVERY';
            }
            else if(in_array('Dialysis', $comcheck)) {
                $testCat = 'A3';
                $custom_dispo = 'FOR DIALYSIS';
            }
            else if(in_array('Cancer', $comcheck)) {
                $testCat = 'A3';
                $custom_dispo = 'CANCER PATIENT';
            }
            else if(in_array('Operation', $comcheck)) {
                $testCat = 'A3';
                $custom_dispo = 'FOR OPERATION';
            }
            else if(in_array('Transplant', $comcheck)) {
                $testCat = 'A3';
                $custom_dispo = 'TRANSPLANT';
            }
            else if($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                $testCat = 'A1';
            }

            /*
            $testCat = [];
            $custom_dispo = NULL;
            if(!is_null($data->SAS)) {
                if($data->getAgeInt() >= 60) {
                    array_push($testCat, "B");
                }
                else {
                    array_push($testCat, "C");
                }
            }
            else if($data->getAgeInt() >= 60) {
                array_push($testCat, "B");
            }
            else if(!in_array("D.1", $testCat) && $data->pType == 'CLOSE CONTACT') {
                array_push($testCat, "D.1");
            }
            else if($data->isPregnant == 1) {
                array_push($testCat, "F.1");
            }
            else if(in_array('Dialysis', $comcheck)) {
                array_push($testCat, "F.2");
            }
            else if(in_array('Cancer', $comcheck)) {
                array_push($testCat, "F.4");
            }
            else if(in_array('Operation', $comcheck)) {
                array_push($testCat, "F.5");
            }
            else if(in_array('Transplant', $comcheck)) {
                array_push($testCat, "F.6");
            }
            else if($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') {
                array_push($testCat, "D.2");
            }
            else if($data->natureOfWork == 'MANUFACTURING') {
                array_push($testCat, "I");
            }
            else if($data->natureOfWork == 'GOVERNMENT UNITS/ORGANIZATIONS') {
                array_push($testCat, "E2.3");
            }
            else if($data->natureOfWork == 'TRANSPORTATION' || $data->natureOfWork == 'MANNING/SHIPPING AGENCY' || $data->natureOfWork == 'STORAGE') {
                if(!in_array('J1.1', $testCat)) {
                    array_push($testCat, "J1.1");
                }
            }
            else if($data->natureOfWork == 'EDUCATION') {
                array_push($testCat, "J1.3");
            }
            else if($data->natureOfWork == 'CONSTRUCTION' || $data->natureOfWork == 'ELECTRICITY') {
                if(!in_array('J1.8', $testCat)) {
                    array_push($testCat, "J1.8");
                }  
            }
            else if($data->natureOfWork == 'HOTEL AND RESTAURANT' || $data->natureOfWork == 'WHOLESALE AND RETAIL TRADE') {
                if(!in_array('J1.2', $testCat)) {
                    array_push($testCat, "J1.2");
                }
            }
            else if($data->natureOfWork == 'FINANCIAL') {
                array_push($testCat, "J1.4");
            }
            else if($data->natureOfWork == 'SERVICES') {
                array_push($testCat, "J1.6");
            }
            else if($data->natureOfWork == 'MASS MEDIA') {
                array_push($testCat, "J1.11");
            }
            else {
                array_push($testCat, "G");
            }

            $testCat = implode(',', $testCat);
            */

            //Auto Change Case Classification to Probable Based on Symptoms
            if(!is_null($data->SAS)) {
                if(in_array('Anosmia (Loss of Smell)', explode(",", $data->SAS)) || in_array('Ageusia (Loss of Taste)', explode(",", $data->SAS))) {
                    $caseClassi = 'Probable';
                }
                else {
                    $caseClassi = 'Suspect';
                }
            }
            else {
                $caseClassi = 'Suspect';
            }

            //Antigen QR
            $antigenqr = NULL;

            $request->user()->form()->create([
                'morbidityMonth' => date('Y-m-d'),
                'dateReported' => date('Y-m-d'),
                'majikCode' => $data->majikCode,
                'status' => 'paswab_rejected',
                'isPresentOnSwabDay' => NULL,
                'records_id' => $rec->id,
                'drunit' => 'CHO GENERAL TRIAS',
                'drregion' => '4A',
                'drprovince' => 'CAVITE',
                'interviewerName' => $data->getDefaultInterviewerName(),
                'interviewerMobile' => '09190664324',
                'interviewDate' => $data->interviewDate,
                'informantName' => NULL,
                'informantRelationship' => NULL,
                'informantMobile' => NULL,
                'existingCaseList' => '1',
                'ecOthersRemarks' => NULL,
                'pType' => ($data->pType == 'FOR TRAVEL') ? 'TESTING' : $data->pType,
                'isForHospitalization' => $data->isForHospitalization,
                'testingCat' => $testCat,
                'havePreviousCovidConsultation' => ($data->isNewRecord == 0) ? '1' : '0',
                'dateOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->interviewDate : NULL : NULL,
                'facilityNameOfFirstConsult' => ($data->isNewRecord == 0) ? ($oldform) ? $oldform->drunit : NULL : NULL,

                'dispoType' => (!is_null($custom_dispo)) ? 5 : 3,
                'dispoName' => $custom_dispo,
                'dispoDate' => date('Y-m-d 08:00:00', strtotime($data->interviewDate)),
                'healthStatus' => (!is_null($data->SAS)) ? 'Mild' : 'Asymptomatic',
                'caseClassification' => $caseClassi,
                'isHealthCareWorker' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? '1' : '0',
                'healthCareCompanyName' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_name : NULL,
                'healthCareCompanyLocation' => ($data->natureOfWork == 'MEDICAL AND HEALTH SERVICES') ? $data->occupation_city.', '.$data->occupation_province : NULL,
                'isOFW' => '0',
                'OFWCountyOfOrigin' => NULL,
                'ofwType' => NULL,
                'isFNT' => '0',
                'lsiType' => NULL,
                'FNTCountryOfOrigin' => NULL,
                'isLSI' => '0',
                'LSICity' => NULL,
                'LSIProvince' => NULL,
                'isLivesOnClosedSettings' => '0',
                'institutionType' => NULL,
                'institutionName' => NULL,
                'indgSpecify' => NULL,
                'dateOnsetOfIllness' => $data->dateOnsetOfIllness,
                'SAS' => $data->SAS,
                'SASFeverDeg' => $data->SASFeverDeg,
                'SASOtherRemarks' => $data->SASOtherRemarks,
                'COMO' => $data->COMO,
                'COMOOtherRemarks' => $data->COMOOtherRemarks,
                'PregnantLMP' => $data->ifPregnantLMP,
                'PregnantHighRisk' => ($data->isPregnant == 1) ? '1' : '0',
                'diagWithSARI' => '0',
                'imagingDoneDate' => $data->imagingDoneDate,
                'imagingDone' => $data->imagingDone,
                'imagingResult' => $data->imagingResult,
                'imagingOtherFindings' => $data->imagingOtherFindings,

                'testedPositiveUsingRTPCRBefore' => '0',
                'testedPositiveNumOfSwab' => '0',
                'testedPositiveLab' => NULL,
                'testedPositiveSpecCollectedDate' => NULL,

                'testDateCollected1' => '2121-01-01',
                'oniTimeCollected1' => NULL,
                'testDateReleased1' => NULL,
                'testLaboratory1' => NULL,
                'testType1' => $ttype,
                'testTypeAntigenRemarks1' => ($ttype == "ANTIGEN") ? mb_strtoupper($tOtherRemarks) : NULL,
                'antigenKit1' => ($ttype == "ANTIGEN") ? mb_strtoupper($tAntigenKit) : NULL,
                'testTypeOtherRemarks1' => ($ttype == "OTHERS") ? mb_strtoupper($tOtherRemarks) : NULL,
                'testResult1' => 'PENDING',
                'testResultOtherRemarks1' => NULL,

                'testDateCollected2' => NULL,
                'oniTimeCollected2' => NULL,
                'testDateReleased2' => NULL,
                'testLaboratory2' => NULL,
                'testType2' => NULL,
                'testTypeAntigenRemarks2' => NULL,
                'antigenKit2' => NULL,
                'testTypeOtherRemarks2' => NULL,
                'testResult2' => NULL,
                'testResultOtherRemarks2' => NULL,

                'outcomeCondition' => 'Active',
                'outcomeRecovDate' => NULL,
                'outcomeDeathDate' => NULL,
                'deathImmeCause' => NULL,
                'deathAnteCause' => NULL,
                'deathUndeCause' => NULL,
                'contriCondi' => NULL,

                'expoitem1' => $data->expoitem1,
                'expoDateLastCont' => $data->expoDateLastCont,

                'expoitem2' => '0',
                'intCountry' => NULL,
                'intDateFrom' => NULL,
                'intDateTo' => NULL,
                'intWithOngoingCovid' => 'N/A',
                'intVessel' => NULL,
                'intVesselNo' => NULL,
                'intDateDepart' => NULL,
                'intDateArrive' => NULL,

                'placevisited' => NULL,

                'locName1' => NULL,
                'locAddress1' => NULL,
                'locDateFrom1' => NULL,
                'locDateTo1' => NULL,
                'locWithOngoingCovid1' => 'N/A',

                'locName2' => NULL,
                'locAddress2' => NULL,
                'locDateFrom2' => NULL,
                'locDateTo2' => NULL,
                'locWithOngoingCovid2' => 'N/A',
                
                'locName3' => NULL,
                'locAddress3' => NULL,
                'locDateFrom3' => NULL,
                'locDateTo3' => NULL,
                'locWithOngoingCovid3' => 'N/A',
                
                'locName4' => NULL,
                'locAddress4' => NULL,
                'locDateFrom4' => NULL,
                'locDateTo4' => NULL,
                'locWithOngoingCovid4' => 'N/A',

                'locName5' => NULL,
                'locAddress5' => NULL,
                'locDateFrom5' => NULL,
                'locDateTo5' => NULL,
                'locWithOngoingCovid5' => 'N/A',

                'locName6' => NULL,
                'locAddress6' => NULL,
                'locDateFrom6' => NULL,
                'locDateTo6' => NULL,
                'locWithOngoingCovid6' => 'N/A',

                'locName7' => NULL,
                'locAddress7' => NULL,
                'locDateFrom7' => NULL,
                'locDateTo7' => NULL,
                'locWithOngoingCovid7' => 'N/A',

                'localVessel1' => NULL,
                'localVesselNo1' => NULL,
                'localOrigin1' => NULL,
                'localDateDepart1' => NULL,
                'localDest1' => NULL,
                'localDateArrive1' => NULL,

                'localVessel2' => NULL,
                'localVesselNo2' => NULL,
                'localOrigin2' => NULL,
                'localDateDepart2' => NULL,
                'localDest2' => NULL,
                'localDateArrive2' => NULL,

                'contact1Name' => $data->contact1Name,
                'contact1No' => $data->contact1No,
                'contact2Name' => $data->contact2Name,
                'contact2No' => $data->contact2No,
                'contact3Name' => $data->contact3Name,
                'contact3No' => $data->contact3No,
                'contact4Name' => $data->contact4Name,
                'contact4No' => $data->contact4No,

                'remarks' => $data->patientmsg,
                'antigenqr' => $antigenqr,
            ]);
            
            //UPDATE PARAMETERS
            $upd = PaSwabDetails::find($data->id);

            $upd->status = 'rejected';
            $upd->remarks = $request->rejectReason;
            $upd->processedAt = date('Y-m-d');

            $upd->save();

            if($data->isNewRecord == 0 && $oldform) {
                //hayaan munang naka-disable para di mag-overlap sa existing data
                
                //$fcheck = Forms::where('id', $oldform->id)->delete();
            }
        }

        return redirect()->action([PaSwabController::class, 'view'])
        ->with('msg', 'Bulk Rejection of the selected data has been proccessed successfully.')
        ->with('msgtype', 'success');
    }
}
