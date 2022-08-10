<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forms;
use App\Models\Records;
use App\Models\SelfReports;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
use Illuminate\Support\Facades\App;
use PragmaRX\Countries\Package\Countries;
use IlluminateAgnostic\Collection\Support\Str;
use App\Http\Requests\SelfReportValidationRequest;

class SelfReportController extends Controller
{
    public function index($locale) {
        if (! in_array($locale, ['en', 'fil'])) {
            abort(404);
        }

        App::setLocale($locale);

        $countries = new Countries();
        $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
        $all = $countries->all()->pluck('name.common')->toArray();

        return view('selfreport_index', ['countries' => $all]);
    }

    public function selectLanguage() {
        return view('selfreport_language');
    }

    public function view() {
        if(request()->get('viewCompleted') == 'true') {
            $list = SelfReports::where('status', 'completed')->orderBy('created_at', 'DESC')->paginate(10);
            $dom = 'rt';
            $header = 'Self Report Completed List | Total: '.$list->total();
        }
        else {
            $list = SelfReports::where('status', 'pending')->get();
            $dom = 'Qlfrtip';
            $header = 'Self Report Pending List | Total: '.$list->count();
        }
        
        return view('selfreport_view', [
            'list' => $list,
            'dom' => $dom,
            'header' => $header,
        ]);
    }

    public function edit($id) {
        $data = SelfReports::findOrFail($id);

        $countries = new Countries();
        $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
        $all = $countries->all()->pluck('name.common')->toArray();

        return view('selfreport_viewspecific', ['data' => $data, 'countries' => $all, 'editable' => ($data->status == 'pending') ? true : false]);
    }

    public function finishAssessment(Request $request, $id) {
        $data = SelfReports::findOrFail($id);

        if($request->dispositionType == 1 || $request->dispositionType == 2) {
            $dNameVal = 'required';
            $dDateVal = 'required|date';
        }
        else if ($request->dispositionType == 3 || $request->dispositionType == 4){
            $dNameVal = 'nullable';
            $dDateVal = 'required|date';
        }
        else if ($request->dispositionType == 5) {
            $dNameVal = 'required';
            $dDateVal = 'nullable|date';
        }
        else if ($request->dispositionType == 6) {
            $dNameVal = 'nullable';
            $dDateVal = 'required|date';
        }
        else {
            $dNameVal = 'nullable';
            $dDateVal = 'nullable|date';
        }

        $request->validate([
            'dispositionType' => 'nullable',
            'dispositionName' => $dNameVal,
            'dispositionDate' => $dDateVal,
        ]);

        $data->dispoType = $request->dispositionType;
        $data->dispoName = $request->dispositionName;
        $data->dispoDate = $request->dispositionDate;

        $data->expoitem1 = $request->expoitem1;
        $data->expoDateLastCont = $request->expoDateLastCont;

        $data->expoitem2 = $request->expoitem2;
        $data->intCountry = $request->intCountry;
        $data->intDateFrom = $request->intDateFrom;
        $data->intDateTo = $request->intDateTo;
        $data->intWithOngoingCovid = ($request->expoitem2 == 2) ? $request->intWithOngoingCovid : 'N/A';
        $data->intVessel = $request->intVessel;
        $data->intVesselNo = $request->intVesselNo;
        $data->intDateDepart = $request->intDateDepart;
        $data->intDateArrive = $request->intDateArrive;

        $data->placevisited = (!is_null($request->placevisited)) ? implode(",", $request->placevisited) : NULL;

        $data->locName1 = $request->locName1;
        $data->locAddress1 = $request->locAddress1;
        $data->locDateFrom1 = $request->locDateFrom1;
        $data->locDateTo1 = $request->locDateTo1;
        $data->locWithOngoingCovid1 = (!is_null($request->placevisited) && in_array('Health Facility', $request->placevisited)) ? $request->locWithOngoingCovid1 : 'N/A';

        $data->locName2 = $request->locName2;
        $data->locAddress2 = $request->locAddress2;
        $data->locDateFrom2 = $request->locDateFrom2;
        $data->locDateTo2 = $request->locDateTo2;
        $data->locWithOngoingCovid2 = (!is_null($request->placevisited) && in_array('Closed Settings', $request->placevisited)) ? $request->locWithOngoingCovid2 : 'N/A';
        
        $data->locName3 = $request->locName3;
        $data->locAddress3 = $request->locAddress3;
        $data->locDateFrom3 = $request->locDateFrom3;
        $data->locDateTo3 = $request->locDateTo3;
        $data->locWithOngoingCovid3 = (!is_null($request->placevisited) && in_array('School', $request->placevisited)) ? $request->locWithOngoingCovid3 : 'N/A';
        
        $data->locName4 = $request->locName4;
        $data->locAddress4 = $request->locAddress4;
        $data->locDateFrom4 = $request->locDateFrom4;
        $data->locDateTo4 = $request->locDateTo4;
        $data->locWithOngoingCovid4 = (!is_null($request->placevisited) && in_array('Workplace', $request->placevisited)) ? $request->locWithOngoingCovid4 : 'N/A';

        $data->locName5 = $request->locName5;
        $data->locAddress5 = $request->locAddress5;
        $data->locDateFrom5 = $request->locDateFrom5;
        $data->locDateTo5 = $request->locDateTo5;
        $data->locWithOngoingCovid5 = (!is_null($request->placevisited) && in_array('Market', $request->placevisited)) ? $request->locWithOngoingCovid5 : 'N/A';

        $data->locName6 = $request->locName6;
        $data->locAddress6 = $request->locAddress6;
        $data->locDateFrom6 = $request->locDateFrom6;
        $data->locDateTo6 = $request->locDateTo6;
        $data->locWithOngoingCovid6 = (!is_null($request->placevisited) && in_array('Social Gathering', $request->placevisited)) ? $request->locWithOngoingCovid6 : 'N/A';

        $data->locName7 = $request->locName7;
        $data->locAddress7 = $request->locAddress7;
        $data->locDateFrom7 = $request->locDateFrom7;
        $data->locDateTo7 = $request->locDateTo7;
        $data->locWithOngoingCovid7 = (!is_null($request->placevisited) && in_array('Others', $request->placevisited)) ? $request->locWithOngoingCovid7 : 'N/A';

        $data->localVessel1 = $request->localVessel1;
        $data->localVesselNo1 = $request->localVesselNo1;
        $data->localOrigin1 = $request->localOrigin1;
        $data->localDateDepart1 = $request->localDateDepart1;
        $data->localDest1 = $request->localDest1;
        $data->localDateArrive1 = $request->localDateArrive1;

        $data->localVessel2 = $request->localVessel2;
        $data->localVesselNo2 = $request->localVesselNo2;
        $data->localOrigin2 = $request->localOrigin2;
        $data->localDateDepart2 = $request->localDateDepart2;
        $data->localDest2 = $request->localDest2;
        $data->localDateArrive2 = $request->localDateArrive2;

        $data->contact1Name = ($request->filled('contact1Name')) ? mb_strtoupper($request->contact1Name) : NULL;
        $data->contact1No = $request->contact1No;
        $data->contact2Name = ($request->filled('contact2Name')) ? mb_strtoupper($request->contact2Name) : NULL;
        $data->contact2No = $request->contact2No;
        $data->contact3Name = ($request->filled('contact3Name')) ? mb_strtoupper($request->contact3Name) : NULL;
        $data->contact3No = $request->contact3No;
        $data->contact4Name = ($request->filled('contact4Name')) ? mb_strtoupper($request->contact4Name) : NULL;
        $data->contact4No = $request->contact4No;

        $data->status = 'completed';

        if($data->isDirty() && $data->getOriginal('status') == 'pending') {
            $data->save();
        }

        $record_array = array(
            'status' => 'approved',
            'lname' => $data->lname,
            'fname' => $data->fname,
            'mname' => $data->mname,
            'gender' => $data->gender,
            'isPregnant' => $data->isPregnant,
            'cs' => $data->cs,
            'nationality' => $data->nationality,
            'bdate' => $data->bdate,
            'mobile' => $data->mobile,
            'phoneno' => $data->phoneno,
            'email' => $data->email,
            'philhealth' => $data->philhealth,
            'address_houseno' => $data->address_houseno,
            'address_street' => $data->address_street,
            'address_brgy' => $data->address_brgy,
            'address_city' => $data->address_city,
            'address_cityjson' => $data->address_cityjson,
            'address_province' => $data->address_province,
            'address_provincejson' => $data->address_provincejson,

            'permaaddressDifferent' => 0,
            'permaaddress_houseno' => $data->address_houseno,
            'permaaddress_street' => $data->address_street,
            'permaaddress_brgy' => $data->address_brgy,
            'permaaddress_city' => $data->address_city,
            'permaaddress_cityjson' => $data->address_cityjson,
            'permaaddress_province' => $data->address_province,
            'permaaddress_provincejson' => $data->address_provincejson,
            'permamobile' => $data->mobile,
            'permaphoneno' => $data->phoneno,
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

            'natureOfWork' => $data->natureOfWork,
            'natureOfWorkIfOthers' => $data->natureOfWorkIfOthers,

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
        );

        if($data->isNewRecord == 1) {
            $record_create = $request->user()->records()->create($record_array);
            $thisRecordId = $record_create->id;
            
            //Auto Re-infect if Positive + Positive ang Old CIF
            $autoreinfect = 0;
        }
        else {
            $thisRecordId = $data->records_id;

            //Auto Re-infect if Positive + Positive ang Old CIF
            $oldcifcheck = Forms::where('records_id', $thisRecordId)
            ->where('caseClassification', 'Confirmed')
            ->first();

            if($oldcifcheck) {
                $autoreinfect = 1;
            }
            else {
                $autoreinfect = 0;
            }
        }

        //Auto Change Testing Category/Subgroup Base on the patient data
        $testCat = [];
        if(!is_null($data->SAS)) {
            array_push($testCat, "C");
        }
        else {
            array_push($testCat, "D.1");
        }

        if($data->getAgeInt() >= 60) {
            array_push($testCat, "B");
        }
        if(!in_array("D.1", $testCat) && $data->pType == 'CLOSE CONTACT') {
            array_push($testCat, "D.1");
        }
        if($data->isPregnant == 1) {
            array_push($testCat, "F");
        }

        if(time() >= strtotime('16:00:00')) {
            $mm = date('Y-m-d', strtotime('+1 Day'));
        }
        else {
            $mm = date('Y-m-d');
        }

        //Auto Recovered if Lagpas na sa Quarantine Period
        $dateToday = Carbon::parse(date('Y-m-d'));
        if($data->dispoType != 6 && $data->dispoType != 7) {
            $swabDateCollected = $data->testDateCollected1;

            if($data->dispoType == 1) {
                $daysToRecover = 21;
            }
            else {
                if(!is_null($data->vaccinationDate2)) {
                    $date1 = Carbon::parse($data->vaccinationDate2);
                    $days_diff = $date1->diffInDays($dateToday);

                    if($days_diff >= 14) {
                        $daysToRecover = 7;
                    }
                    else {
                        $daysToRecover = 10;
                    }
                }
                else {
                    if($data->vaccinationName1 == 'JANSSEN') {
                        $date1 = Carbon::parse($data->vaccinationDate1);
                        $days_diff = $date1->diffInDays($dateToday);

                        if($days_diff >= 14) {
                            $daysToRecover = 7;
                        }
                        else {
                            $daysToRecover = 10;
                        }
                    }
                    else {
                        $daysToRecover = 10;
                    }
                }
            }

            $startDate = Carbon::parse(date('Y-m-d', strtotime($swabDateCollected)));
            $diff = $startDate->diffInDays($dateToday);
            if($diff >= $daysToRecover) {
                $auto_outcome = 'Recovered';
                $auto_outcome_recovered_date = Carbon::parse($swabDateCollected)->addDays($daysToRecover)->format('Y-m-d');
                //$auto_outcome_recovered_date = date('Y-m-d');
            }
            else {
                $auto_outcome = 'Active';
                $auto_outcome_recovered_date = NULL;
            }
        }
        else {
            $auto_outcome = 'Active';
            $auto_outcome_recovered_date = NULL;
        }

        $form_array = array(
            'reinfected' => ($autoreinfect == 1) ? 1 : 0,
            'morbidityMonth' => $mm,
            'dateReported' => $data->testDateReleased1,
            'status' => 'approved',
            'isPresentOnSwabDay' => 1,
            'records_id' => $thisRecordId,
            'drunit' => $data->drunit,
            'drregion' => $data->drregion,
            'drprovince' => $data->drprovince,
            'interviewerName' => 'BROAS, LUIS',
            'interviewerMobile' => '09190664324',
            'interviewDate' => date('Y-m-d'),
            'informantName' => NULL,
            'informantRelationship' => NULL,
            'informantMobile' => NULL,
            'existingCaseList' => 1,
            'ecOthersRemarks' => NULL,
            'pType' => $data->pType,
            'ccType' => NULL,
            'isForHospitalization' => 0,
            'testingCat' => implode(",", $testCat),
            'havePreviousCovidConsultation' => $data->havePreviousCovidConsultation,
            'dateOfFirstConsult' => $data->dateOfFirstConsult,
            'facilityNameOfFirstConsult' => $data->facilityNameOfFirstConsult,
            
            'dispoType' => $data->dispoType,
            'dispoName' => $data->dispoName,
            'dispoDate' => $data->dispoDate,
            'healthStatus' => (!is_null($data->SAS)) ? 'Mild' : 'Asymptomatic',
            'caseClassification' => 'Confirmed',
            'confirmedVariantName' => NULL,
            'isHealthCareWorker' => $data->isHealthCareWorker,
            'healthCareCompanyName' => $data->healthCareCompanyName,
            'healthCareCompanyLocation' => $data->healthCareCompanyLocation,
            'isOFW' => $data->isOFW,
            'OFWCountyOfOrigin' => $data->OFWCountyOfOrigin,
            'OFWPassportNo' => $data->OFWPassportNo,
            'ofwType' => $data->ofwType,
            'isFNT' => $data->isFNT,
            'FNTCountryOfOrigin' => $data->FNTCountryOfOrigin,
            'FNTPassportNo' => $data->FNTPassportNo,
            'isLSI' => $data->isLSI,
            'lsiType' => $data->lsiType,
            'LSICity' => $data->LSICity,
            'LSIProvince' => $data->LSIProvince,
            'isLivesOnClosedSettings' => $data->isLivesOnClosedSettings,
            'institutionType' => $data->institutionType,
            'institutionName' => $data->institutionName,
            'dateOnsetOfIllness' => $data->dateOnsetOfIllness,
            'SAS' => $data->SAS,
            'SASFeverDeg' => $data->SASFeverDeg,
            'SASOtherRemarks' => $data->SASOtherRemarks,
            'COMO' => $data->COMO,
            'COMOOtherRemarks' => $data->COMOOtherRemarks,
            'PregnantLMP' => $data->ifPregnantLMP,
            'PregnantHighRisk' => ($data->isPregnant == 1) ? 1 : 0,
            'diagWithSARI' => $data->diagWithSARI,
            'imagingDoneDate' => $data->imagingDoneDate,
            'imagingDone' => $data->imagingDone,
            'imagingResult' => $data->imagingResult,
            'imagingOtherFindings' => $data->imagingOtherFindings,

            'testedPositiveUsingRTPCRBefore' => $data->testedPositiveUsingRTPCRBefore,
            'testedPositiveNumOfSwab' => $data->testedPositiveNumOfSwab,
            'testedPositiveLab' => $data->testedPositiveLab,
            'testedPositiveSpecCollectedDate' => $data->testedPositiveSpecCollectedDate,

            'testDateCollected1' => $data->testDateCollected1,
            'oniTimeCollected1' => NULL,
            'testDateReleased1' => $data->testDateReleased1,
            'testLaboratory1' => $data->testLaboratory1,
            'testType1' => $data->testType1,
            'testTypeAntigenRemarks1' => $data->testTypeAntigenRemarks1,
            'antigenKit1' => $data->antigenKit1,
            'testTypeOtherRemarks1' => $data->testTypeOtherRemarks1,
            'testResult1' => 'POSITIVE',
            'testResultOtherRemarks1' => $data->testResultOtherRemarks1,

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

            'outcomeCondition' => $auto_outcome,
            'outcomeRecovDate' => $auto_outcome_recovered_date,
            'outcomeDeathDate' => NULL,
            'deathImmeCause' => NULL,
            'deathAnteCause' => NULL,
            'deathUndeCause' => NULL,
            'contriCondi' => NULL,

            'expoitem1' => $data->expoitem1,
            'expoDateLastCont' => $data->expoDateLastCont,

            'expoitem2' => $data->expoitem2,
            'intCountry' => $data->intCountry,
            'intDateFrom' => $data->intDateFrom,
            'intDateTo' => $data->intDateTo,
            'intWithOngoingCovid' => $data->intWithOngoingCovid,
            'intVessel' => $data->intVessel,
            'intVesselNo' => $data->intVesselNo,
            'intDateDepart' => $data->intDateDepart,
            'intDateArrive' => $data->intDateArrive,

            'placevisited' => $data->placevisited,

            'locName1' => $data->locName1,
            'locAddress1' => $data->locAddress1,
            'locDateFrom1' => $data->locDateFrom1,
            'locDateTo1' => $data->locDateTo1,
            'locWithOngoingCovid1' => $data->locWithOngoingCovid1,

            'locName2' => $data->locName2,
            'locAddress2' => $data->locAddress2,
            'locDateFrom2' => $data->locDateFrom2,
            'locDateTo2' => $data->locDateTo2,
            'locWithOngoingCovid2' => $data->locWithOngoingCovid2,
            
            'locName3' => $data->locName3,
            'locAddress3' => $data->locAddress3,
            'locDateFrom3' => $data->locDateFrom3,
            'locDateTo3' => $data->locDateTo3,
            'locWithOngoingCovid3' => $data->locWithOngoingCovid3,
            
            'locName4' => $data->locName4,
            'locAddress4' => $data->locAddress4,
            'locDateFrom4' => $data->locDateFrom4,
            'locDateTo4' => $data->locDateTo4,
            'locWithOngoingCovid4' => $data->locWithOngoingCovid4,

            'locName5' => $data->locName5,
            'locAddress5' => $data->locAddress5,
            'locDateFrom5' => $data->locDateFrom5,
            'locDateTo5' => $data->locDateTo5,
            'locWithOngoingCovid5' => $data->locWithOngoingCovid5,

            'locName6' => $data->locName6,
            'locAddress6' => $data->locAddress6,
            'locDateFrom6' => $data->locDateFrom6,
            'locDateTo6' => $data->locDateTo6,
            'locWithOngoingCovid6' => $data->locWithOngoingCovid6,

            'locName7' => $data->locName7,
            'locAddress7' => $data->locAddress7,
            'locDateFrom7' => $data->locDateFrom7,
            'locDateTo7' => $data->locDateTo7,
            'locWithOngoingCovid7' => $data->locWithOngoingCovid7,

            'localVessel1' => $data->localVessel1,
            'localVesselNo1' => $data->localVesselNo1,
            'localOrigin1' => $data->localOrigin1,
            'localDateDepart1' => $data->localDateDepart1,
            'localDest1' => $data->localDest1,
            'localDateArrive1' => $data->localDateArrive1,

            'localVessel2' => $data->localVessel2,
            'localVesselNo2' => $data->localVesselNo2,
            'localOrigin2' => $data->localOrigin2,
            'localDateDepart2' => $data->localDateDepart2,
            'localDest2' => $data->localDest2,
            'localDateArrive2' => $data->localDateArrive2,

            'contact1Name' => $data->contact1Name,
            'contact1No' => $data->contact1No,
            'contact2Name' => $data->contact2Name,
            'contact2No' => $data->contact2No,
            'contact3Name' => $data->contact3Name,
            'contact3No' => $data->contact3No,
            'contact4Name' => $data->contact4ame,
            'contact4No' => $data->contact4No,

            'remarks' => $data->remarks,
        );

        //Ipasok na sa Records and Forms
        $form_create = $request->user()->form()->create($form_array);

        return redirect()->route('selfreport.view')->with('msg', 'Patient #'.$data->id.' ('.$data->getName().') has been processed successfully.')->with('msgtype', 'success');
    }
    
    public function store(SelfReportValidationRequest $request) {
        $request->validated();

        //$newFileName1 = time() . ' - ' . $request->req_file->getClientOriginalName();
        $newFileName2 = 'srfile'.time().Str::random(20).'.'.$request->result_file->getClientOriginalExtension();
        //$newFileName2 = time() . ' - ' . $request->result_file->getClientOriginalName();

        //$upload1 = $request->req_file->move(public_path('assets/self_reports'), $newFileName1);
        $upload2 = $request->result_file->move(public_path('assets/self_reports'), $newFileName2);

        $check1 = Records::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);
        $check2 = PaSwabDetails::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);
        
        if(is_null($check2)) {
            $new = SelfReports::create([
                'drunit' => 'CHO GENERAL TRIAS',
                'drregion' => '4A',
                'drprovince' => 'CAVITE',
                'isNewRecord' => (!is_null($check1)) ? 0 : 1, //0 = OLD, 1 = NEW
                'records_id' => (!is_null($check1)) ? $check1->id : NULL,
                'patientmsg' => $request->patientmsg,
                'lname' => mb_strtoupper($request->lname),
                'fname' => mb_strtoupper($request->fname),
                'mname' => ($request->filled('mname') && mb_strtoupper($request->mname) != "N/A") ? mb_strtoupper($request->mname) : NULL,
                'gender' => strtoupper($request->gender),
                'bdate' => $request->bdate,
                'cs' => strtoupper($request->cs),
                'nationality' => strtoupper($request->nationality),
                'mobile' => $request->mobile,
                'phoneno' => ($request->filled('phoneno')) ? $request->phoneno : NULL,
                'email' => $request->email,
                'philhealth' => $request->philhealth,
                'isPregnant' => ($request->gender == 'FEMALE') ? $request->isPregnant : 0,
                'ifPregnantLMP' => ($request->gender == 'FEMALE' && $request->isPregnant == 1) ? $request->lmp : NULL,
                'address_houseno' => strtoupper($request->address_houseno),
                'address_street' => strtoupper($request->address_street),
                'address_brgy' => strtoupper($request->address_brgy),
                'address_city' => strtoupper($request->address_city),
                'address_cityjson' => $request->saddress_city,
                'address_province' => strtoupper($request->address_province),
                'address_provincejson' => $request->saddress_province,

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

                'drunit' => 'CHO GENERAL TRIAS',
                'drregion' => '4A',
                'drprovince' => 'CAVITE',
                
                'pType' => $request->pType,
                'isHealthCareWorker' => $request->isHealthCareWorker,
                'healthCareCompanyName' => $request->healthCareCompanyName,
                'healthCareCompanyLocation' => $request->healthCareCompanyLocation,
                'isOFW' => $request->isOFW,
                'OFWCountyOfOrigin' => ($request->isOFW == 1) ? $request->OFWCountyOfOrigin : NULL,
                'OFWPassportNo' => ($request->isOFW == 1) ? $request->OFWPassportNo : NULL,
                'ofwType' => ($request->isOFW == 1) ? $request->ofwType : NULL,
                'isFNT' => $request->isFNT,
                'FNTCountryOfOrigin' => ($request->isFNT == 1) ? $request->FNTCountryOfOrigin : NULL,
                'FNTPassportNo' => ($request->isFNT == 1) ? $request->FNTPassportNo : NULL,
                'isLSI' => $request->isLSI,
                'lsiType' => $request->lsiType,
                'LSICity' => $request->LSICity,
                'LSIProvince' => $request->LSIProvince,
                'LSICityjson' => NULL,
                'LSIProvincejson' => NULL,
                'isLivesOnClosedSettings' => $request->isLivesOnClosedSettings,
                'institutionType' => $request->institutionType,
                'institutionName' => $request->institutionName,
                'havePreviousCovidConsultation' => $request->havePreviousCovidConsultation,
                'dateOfFirstConsult' => $request->dateOfFirstConsult,
                'facilityNameOfFirstConsult' => $request->facilityNameOfFirstConsult,
                'dispoType' => $request->dispositionType,
                'dispoName' => $request->dispositionName,
                'dispoDate' => $request->dispositionDate,
                'testedPositiveUsingRTPCRBefore' => $request->testedPositiveUsingRTPCRBefore,
                'testedPositiveSpecCollectedDate' => $request->testedPositiveSpecCollectedDate,
                'testedPositiveLab' => $request->testedPositiveLab,
                'testedPositiveNumOfSwab' => $request->testedPositiveNumOfSwab,
                'testDateCollected1' => $request->testDateCollected1,
                'testDateReleased1' => $request->testDateReleased1,
                'testLaboratory1' => $request->testLaboratory1,
                'testType1' => $request->testType1,
                'testTypeAntigenRemarks1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
                'antigenKit1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->antigenKit1) : NULL,
                'testTypeOtherRemarks1' => ($request->testType1 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,

                'vaccinationDate1' => ($request->vaccineq1 == 1) ? $request->vaccinationDate1 : NULL,
                'vaccinationName1'=> ($request->vaccineq1 == 1) ? $request->nameOfVaccine : NULL,
                'vaccinationNoOfDose1'=> ($request->vaccineq1 == 1) ? 1 : NULL,
                'vaccinationFacility1'=> ($request->vaccineq1 == 1 && $request->filled('vaccinationFacility1')) ? $request->vaccinationFacility1 : NULL,
                'vaccinationRegion1'=> ($request->vaccineq1 == 1 && $request->filled('vaccinationRegion1')) ? $request->vaccinationRegion1 : NULL,
                'haveAdverseEvents1'=> ($request->vaccineq1 == 1) ? $request->haveAdverseEvents1 : NULL,
                'vaccinationDate2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->vaccinationDate2 : NULL,
                'vaccinationName2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->nameOfVaccine : NULL,
                'vaccinationNoOfDose2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? 2 : NULL,
                'vaccinationFacility2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2 && $request->filled('vaccinationFacility2')) ? $request->vaccinationFacility2 : NULL,
                'vaccinationRegion2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2 && $request->filled('vaccinationRegion2')) ? $request->vaccinationRegion2 : NULL,
                'haveAdverseEvents2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->haveAdverseEvents2 : NULL,
                'vaccinationDate3' => ($request->haveBooster == 1) ? $request->vaccinationDate3 : NULL,
				'haveAdverseEvents3' => ($request->haveBooster == 1) ? $request->haveAdverseEvents3 : NULL,
				'vaccinationName3' =>  ($request->haveBooster == 1) ? $request->vaccinationName3 : NULL,
				'vaccinationNoOfDose3' =>  ($request->haveBooster == 1) ? 3 : NULL,
				'vaccinationFacility3' =>  ($request->haveBooster == 1 && $request->filled('vaccinationFacility3')) ? mb_strtoupper($request->vaccinationFacility3) : NULL,
				'vaccinationRegion3' =>  ($request->haveBooster == 1 && $request->filled('vaccinationRegion3')) ? mb_strtoupper($request->vaccinationRegion3) : NULL,
                
                'dateOnsetOfIllness' => ($request->haveSymptoms == 1) ? $request->dateOnsetOfIllness : NULL,
                'SAS' => ($request->haveSymptoms == 1 && !is_null($request->sasCheck)) ? implode(",", $request->sasCheck) : NULL,
                'SASFeverDeg' => ($request->haveSymptoms == 1) ? $request->SASFeverDeg : NULL,
                'SASOtherRemarks' => $request->SASOtherRemarks,
    
                'COMO' => implode(",", $request->comCheck),
                'COMOOtherRemarks' => $request->COMOOtherRemarks,
                'diagWithSARI' => $request->diagWithSARI,
                'imagingDoneDate' => $request->imagingDoneDate,
                'imagingDone' => $request->imagingDone,
                'imagingResult' => $request->imagingResult,
                'imagingOtherFindings' => $request->imagingOtherFindings,
                'expoitem1' => $request->expoitem1,
                'expoDateLastCont' => $request->expoDateLastCont,
                'expoitem2' => $request->expoitem2,
                'intCountry' => $request->intCountry,
                'intDateFrom' => $request->intDateFrom,
                'intDateTo' => $request->intDateTo,
                'intWithOngoingCovid' => ($request->expoitem2 == 2) ? $request->intWithOngoingCovid : 'N/A',
                'intVessel' => $request->intVessel,
                'intVesselNo' => $request->intVesselNo,
                'intDateDepart' => $request->intDateDepart,
                'intDateArrive' => $request->intDateArrive,
    
                'placevisited' => (!is_null($request->placevisited)) ? implode(",", $request->placevisited) : NULL,
    
                'locName1' => $request->locName1,
                'locAddress1' => $request->locAddress1,
                'locDateFrom1' => $request->locDateFrom1,
                'locDateTo1' => $request->locDateTo1,
                'locWithOngoingCovid1' => (!is_null($request->placevisited) && in_array('Health Facility', $request->placevisited)) ? $request->locWithOngoingCovid1 : 'N/A', 
    
                'locName2' => $request->locName2,
                'locAddress2' => $request->locAddress2,
                'locDateFrom2' => $request->locDateFrom2,
                'locDateTo2' => $request->locDateTo2,
                'locWithOngoingCovid2' => (!is_null($request->placevisited) && in_array('Closed Settings', $request->placevisited)) ? $request->locWithOngoingCovid2 : 'N/A',
                
                'locName3' => $request->locName3,
                'locAddress3' => $request->locAddress3,
                'locDateFrom3' => $request->locDateFrom3,
                'locDateTo3' => $request->locDateTo3,
                'locWithOngoingCovid3' => (!is_null($request->placevisited) && in_array('School', $request->placevisited)) ? $request->locWithOngoingCovid3 : 'N/A',
                
                'locName4' => $request->locName4,
                'locAddress4' => $request->locAddress4,
                'locDateFrom4' => $request->locDateFrom4,
                'locDateTo4' => $request->locDateTo4,
                'locWithOngoingCovid4' => (!is_null($request->placevisited) && in_array('Workplace', $request->placevisited)) ? $request->locWithOngoingCovid4 : 'N/A',
    
                'locName5' => $request->locName5,
                'locAddress5' => $request->locAddress5,
                'locDateFrom5' => $request->locDateFrom5,
                'locDateTo5' => $request->locDateTo5,
                'locWithOngoingCovid5' => (!is_null($request->placevisited) && in_array('Market', $request->placevisited)) ? $request->locWithOngoingCovid5 : 'N/A',
    
                'locName6' => $request->locName6,
                'locAddress6' => $request->locAddress6,
                'locDateFrom6' => $request->locDateFrom6,
                'locDateTo6' => $request->locDateTo6,
                'locWithOngoingCovid6' => (!is_null($request->placevisited) && in_array('Social Gathering', $request->placevisited)) ? $request->locWithOngoingCovid6 : 'N/A',
    
                'locName7' => $request->locName7,
                'locAddress7' => $request->locAddress7,
                'locDateFrom7' => $request->locDateFrom7,
                'locDateTo7' => $request->locDateTo7,
                'locWithOngoingCovid7' => (!is_null($request->placevisited) && in_array('Others', $request->placevisited)) ? $request->locWithOngoingCovid7 : 'N/A',
    
                'localVessel1' => $request->localVessel1,
                'localVesselNo1' => $request->localVesselNo1,
                'localOrigin1' => $request->localOrigin1,
                'localDateDepart1' => $request->localDateDepart1,
                'localDest1' => $request->localDest1,
                'localDateArrive1' => $request->localDateArrive1,
    
                'localVessel2' => $request->localVessel2,
                'localVesselNo2' => $request->localVesselNo2,
                'localOrigin2' => $request->localOrigin2,
                'localDateDepart2' => $request->localDateDepart2,
                'localDest2' => $request->localDest2,
                'localDateArrive2' => $request->localDateArrive2,
                'contact1Name' => ($request->filled('contact1Name')) ? mb_strtoupper($request->contact1Name) : NULL,
                'contact1No' => $request->contact1No,
                'contact2Name' => ($request->filled('contact2Name')) ? mb_strtoupper($request->contact2Name) : NULL,
                'contact2No' => $request->contact2No,
                'contact3Name' => ($request->filled('contact3Name')) ? mb_strtoupper($request->contact3Name) : NULL,
                'contact3No' => $request->contact3No,
                'contact4Name' => ($request->filled('contact4Name')) ? mb_strtoupper($request->contact4Name) : NULL,
                'contact4No' => $request->contact4No,
                'remarks' => NULL,
                //'req_file' => $newFileName1,
                'result_file' => $newFileName2,
                'senderIP' => request()->ip(),
                'magicURL' => Str::random(10),
            ]);
    
            return redirect()->route('selfreport.storeComplete', ['locale' => app()->getLocale()])->with('completed', true);
        }
        else {
            return back()
			->withInput()
            ->with('msg', 'System detected that you submitted a pa-swab request. Therefore, your request will not be submitted.')
            ->with('msgtype', 'danger');
        }
    }

    public function storeComplete($locale) {
        if (! in_array($locale, ['en', 'fil'])) {
            abort(404);
        }

        App::setLocale($locale);

        if(session('completed')) {
            return view('selfreport_completed');
        }
        else {
            return redirect()->route('main')
            ->with('msg', 'You are not allowed to do that.')
            ->with('msgtype', 'warning');
        }
    }

    public function viewDocument($id) {
        $data = SelfReports::findOrFail($id);

        $file_name = $data->result_file;

        return response()->file(public_path("assets/self_reports/").$file_name);
    }

    public function convertToSuspected(Request $request, $id) {
        $data = SelfReports::findOrFail($id);

        $request->validate([
            'reason' => 'nullable',
            'testDateCollected2' => 'required|date|after_or_equal:today|before_or_equal:'.date('Y-12-31'),
            'testType2' => 'required|in:OPS,NPS,OPS AND NPS,ANTIGEN,ANTIBODY,OTHERS',
            'testTypeOtherRemarks2' => ($request->testType2 == 'ANTIGEN' || $request->testType2 == 'OTHERS') ? 'required' : 'nullable',
            'antigenKit2' => ($request->testType2 == 'ANTIGEN') ? 'required' : 'nullable',
        ]);

        $data->status = 'completed';

        if($data->isDirty() && $data->getOriginal('status') == 'pending') {
            $data->save();
        }

        $record_array = array(
            'status' => 'approved',
            'lname' => $data->lname,
            'fname' => $data->fname,
            'mname' => $data->mname,
            'gender' => $data->gender,
            'isPregnant' => $data->isPregnant,
            'cs' => $data->cs,
            'nationality' => $data->nationality,
            'bdate' => $data->bdate,
            'mobile' => $data->mobile,
            'phoneno' => $data->phoneno,
            'email' => $data->email,
            'philhealth' => $data->philhealth,
            'address_houseno' => $data->address_houseno,
            'address_street' => $data->address_street,
            'address_brgy' => $data->address_brgy,
            'address_city' => $data->address_city,
            'address_cityjson' => $data->address_cityjson,
            'address_province' => $data->address_province,
            'address_provincejson' => $data->address_provincejson,

            'permaaddressDifferent' => 0,
            'permaaddress_houseno' => $data->address_houseno,
            'permaaddress_street' => $data->address_street,
            'permaaddress_brgy' => $data->address_brgy,
            'permaaddress_city' => $data->address_city,
            'permaaddress_cityjson' => $data->address_cityjson,
            'permaaddress_province' => $data->address_province,
            'permaaddress_provincejson' => $data->address_provincejson,
            'permamobile' => $data->mobile,
            'permaphoneno' => $data->phoneno,
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

            'natureOfWork' => $data->natureOfWork,
            'natureOfWorkIfOthers' => $data->natureOfWorkIfOthers,

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
        );

        if($data->isNewRecord == 1) {
            $record_create = $request->user()->records()->create($record_array);
            $thisRecordId = $record_create->id;
            
            //Auto Re-infect if Positive + Positive ang Old CIF
            $autoreinfect = 0;
        }
        else {
            $thisRecordId = $data->records_id;

            //Auto Re-infect if Positive + Positive ang Old CIF
            $oldcifcheck = Forms::where('records_id', $thisRecordId)
            ->where('caseClassification', 'Confirmed')
            ->first();

            if($oldcifcheck) {
                $autoreinfect = 1;
            }
            else {
                $autoreinfect = 0;
            }
        }

        //Auto Change Testing Category/Subgroup Base on the patient data
        $testCat = [];
        if(!is_null($data->SAS)) {
            array_push($testCat, "C");
        }
        else {
            array_push($testCat, "D.1");
        }

        if($data->getAgeInt() >= 60) {
            array_push($testCat, "B");
        }
        if(!in_array("D.1", $testCat) && $data->pType == 'CLOSE CONTACT') {
            array_push($testCat, "D.1");
        }
        if($data->isPregnant == 1) {
            array_push($testCat, "F");
        }

        if(time() >= strtotime('16:00:00')) {
            $mm = date('Y-m-d', strtotime('+1 Day'));
        }
        else {
            $mm = date('Y-m-d');
        }

        $form_array = array(
            'reinfected' => ($autoreinfect == 1) ? 1 : 0,
            'morbidityMonth' => $mm,
            'dateReported' => date('Y-m-d'),
            'status' => 'approved',
            'isPresentOnSwabDay' => 0,
            'records_id' => $thisRecordId,
            'drunit' => 'CHO GENERAL TRIAS',
            'drregion' => '4A',
            'drprovince' => 'CAVITE',
            'interviewerName' => 'BROAS, LUIS',
            'interviewerMobile' => '09190664324',
            'interviewDate' => date('Y-m-d'),
            'informantName' => NULL,
            'informantRelationship' => NULL,
            'informantMobile' => NULL,
            'existingCaseList' => 1,
            'ecOthersRemarks' => NULL,
            'pType' => $data->pType,
            'ccType' => NULL,
            'isForHospitalization' => 0,
            'testingCat' => implode(",", $testCat),
            'havePreviousCovidConsultation' => $data->havePreviousCovidConsultation,
            'dateOfFirstConsult' => $data->dateOfFirstConsult,
            'facilityNameOfFirstConsult' => $data->facilityNameOfFirstConsult,
            
            'dispoType' => $data->dispoType,
            'dispoName' => $data->dispoName,
            'dispoDate' => $data->dispoDate,
            'healthStatus' => (!is_null($data->SAS)) ? 'Mild' : 'Asymptomatic',
            'caseClassification' => (!is_null($data->SAS)) ? 'Probable' : 'Suspect',
            'confirmedVariantName' => NULL,
            'isHealthCareWorker' => $data->isHealthCareWorker,
            'healthCareCompanyName' => $data->healthCareCompanyName,
            'healthCareCompanyLocation' => $data->healthCareCompanyLocation,
            'isOFW' => $data->isOFW,
            'OFWCountyOfOrigin' => $data->OFWCountyOfOrigin,
            'OFWPassportNo' => $data->OFWPassportNo,
            'ofwType' => $data->ofwType,
            'isFNT' => $data->isFNT,
            'FNTCountryOfOrigin' => $data->FNTCountryOfOrigin,
            'FNTPassportNo' => $data->FNTPassportNo,
            'isLSI' => $data->isLSI,
            'lsiType' => $data->lsiType,
            'LSICity' => $data->LSICity,
            'LSIProvince' => $data->LSIProvince,
            'isLivesOnClosedSettings' => $data->isLivesOnClosedSettings,
            'institutionType' => $data->institutionType,
            'institutionName' => $data->institutionName,
            'dateOnsetOfIllness' => $data->dateOnsetOfIllness,
            'SAS' => $data->SAS,
            'SASFeverDeg' => $data->SASFeverDeg,
            'SASOtherRemarks' => $data->SASOtherRemarks,
            'COMO' => $data->COMO,
            'COMOOtherRemarks' => $data->COMOOtherRemarks,
            'PregnantLMP' => $data->ifPregnantLMP,
            'PregnantHighRisk' => ($data->isPregnant == 1) ? 1 : 0,
            'diagWithSARI' => $data->diagWithSARI,
            'imagingDoneDate' => $data->imagingDoneDate,
            'imagingDone' => $data->imagingDone,
            'imagingResult' => $data->imagingResult,
            'imagingOtherFindings' => $data->imagingOtherFindings,

            'testedPositiveUsingRTPCRBefore' => $data->testedPositiveUsingRTPCRBefore,
            'testedPositiveNumOfSwab' => $data->testedPositiveNumOfSwab,
            'testedPositiveLab' => $data->testedPositiveLab,
            'testedPositiveSpecCollectedDate' => $data->testedPositiveSpecCollectedDate,

            'testDateCollected1' => $request->testDateCollected2,
            'oniTimeCollected1' => NULL,
            'testDateReleased1' => NULL,
            'testLaboratory1' => NULL,
            'testType1' => $request->testType2,
            'testTypeAntigenRemarks1' => ($request->testType2 == 'ANTIGEN') ? $request->testTypeOtherRemarks2 : NULL,
            'antigenKit1' => ($request->testType2 == 'ANTIGEN') ? $request->antigenKit2 : NULL,
            'testTypeOtherRemarks1' => ($request->testType2 == 'OTHERS') ? $request->testTypeOtherRemarks2 : NULL,
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

            'expoitem1' => 3,
            'expoDateLastCont' => NULL,

            'expoitem2' => 3,
            /*
            'intCountry' => $data->intCountry,
            'intDateFrom' => $data->intDateFrom,
            'intDateTo' => $data->intDateTo,
            'intWithOngoingCovid' => $data->intWithOngoingCovid,
            'intVessel' => $data->intVessel,
            'intVesselNo' => $data->intVesselNo,
            'intDateDepart' => $data->intDateDepart,
            'intDateArrive' => $data->intDateArrive,

            'placevisited' => $data->placevisited,

            'locName1' => $data->locName1,
            'locAddress1' => $data->locAddress1,
            'locDateFrom1' => $data->locDateFrom1,
            'locDateTo1' => $data->locDateTo1,
            'locWithOngoingCovid1' => $data->locWithOngoingCovid1,

            'locName2' => $data->locName2,
            'locAddress2' => $data->locAddress2,
            'locDateFrom2' => $data->locDateFrom2,
            'locDateTo2' => $data->locDateTo2,
            'locWithOngoingCovid2' => $data->locWithOngoingCovid2,
            
            'locName3' => $data->locName3,
            'locAddress3' => $data->locAddress3,
            'locDateFrom3' => $data->locDateFrom3,
            'locDateTo3' => $data->locDateTo3,
            'locWithOngoingCovid3' => $data->locWithOngoingCovid3,
            
            'locName4' => $data->locName4,
            'locAddress4' => $data->locAddress4,
            'locDateFrom4' => $data->locDateFrom4,
            'locDateTo4' => $data->locDateTo4,
            'locWithOngoingCovid4' => $data->locWithOngoingCovid4,

            'locName5' => $data->locName5,
            'locAddress5' => $data->locAddress5,
            'locDateFrom5' => $data->locDateFrom5,
            'locDateTo5' => $data->locDateTo5,
            'locWithOngoingCovid5' => $data->locWithOngoingCovid5,

            'locName6' => $data->locName6,
            'locAddress6' => $data->locAddress6,
            'locDateFrom6' => $data->locDateFrom6,
            'locDateTo6' => $data->locDateTo6,
            'locWithOngoingCovid6' => $data->locWithOngoingCovid6,

            'locName7' => $data->locName7,
            'locAddress7' => $data->locAddress7,
            'locDateFrom7' => $data->locDateFrom7,
            'locDateTo7' => $data->locDateTo7,
            'locWithOngoingCovid7' => $data->locWithOngoingCovid7,

            'localVessel1' => $data->localVessel1,
            'localVesselNo1' => $data->localVesselNo1,
            'localOrigin1' => $data->localOrigin1,
            'localDateDepart1' => $data->localDateDepart1,
            'localDest1' => $data->localDest1,
            'localDateArrive1' => $data->localDateArrive1,

            'localVessel2' => $data->localVessel2,
            'localVesselNo2' => $data->localVesselNo2,
            'localOrigin2' => $data->localOrigin2,
            'localDateDepart2' => $data->localDateDepart2,
            'localDest2' => $data->localDest2,
            'localDateArrive2' => $data->localDateArrive2,

            'contact1Name' => $data->contact1Name,
            'contact1No' => $data->contact1No,
            'contact2Name' => $data->contact2Name,
            'contact2No' => $data->contact2No,
            'contact3Name' => $data->contact3Name,
            'contact3No' => $data->contact3No,
            'contact4Name' => $data->contact4ame,
            'contact4No' => $data->contact4No,
            */

            'remarks' => $request->reason,
        );

        //Ipasok na sa Records and Forms
        $form_create = $request->user()->form()->create($form_array);

        return redirect()->route('selfreport.view')->with('msg', 'Patient #'.$data->id.' ('.$data->getName().') has been scheduled for swab successfully.')->with('msgtype', 'success');
    }
}