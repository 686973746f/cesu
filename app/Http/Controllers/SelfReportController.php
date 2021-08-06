<?php

namespace App\Http\Controllers;

use App\Models\SelfReports;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;
use App\Http\Requests\SelfReportValidationRequest;

class SelfReportController extends Controller
{
    public function index() {
        $countries = new Countries();
        $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
        $all = $countries->all()->pluck('name.common')->toArray();

        return view('selfreport_index', ['countries' => $all]);
    }

    public function view() {
        $list = SelfReports::all();

        return view('selfreport_view', ['list' => $list]);
    }

    public function store(SelfReportValidationRequest $request) {
        $request->validated();

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

        $newFileName1 = time() . ' - ' . $request->req_file->getClientOriginalName();
        $newFileName2 = time() . ' - ' . $request->result_file->getClientOriginalName();

        $upload1 = $request->req_file->move(public_path('assets/self_reports'), $newFileName1);
        $upload2 = $request->result_file->move(public_path('assets/self_reports'), $newFileName2);

        $new = SelfReports::create([
            'isNewRecord' => 0,
            'records_id' => NULL,
            'patientmsg' => $request->patientmsg,
            'lname' => mb_strtoupper($request->lname),
            'fname' => mb_strtoupper($request->fname),
            'mname' => ($request->filled('mname') && mb_strtoupper($request->mname) != "N/A") ? mb_strtoupper($request->mname) : null,
            'gender' => strtoupper($request->gender),
            'bdate' => $request->bdate,
            'cs' => strtoupper($request->cs),
            'nationality' => strtoupper($request->nationality),
            'mobile' => $request->mobile,
            'phoneno' => ($request->filled('phoneno')) ? $request->phoneno : NULL,
            'email' => $request->email,
            'philhealth' => $philhealth_organized,
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
            'vaccinationFacility1'=> ($request->vaccineq1 == 1) ? $request->vaccinationFacility1 : NULL,
            'vaccinationRegion1'=> ($request->vaccineq1 == 1) ? $request->vaccinationRegion1 : NULL,
            'haveAdverseEvents1'=> ($request->vaccineq1 == 1) ? $request->haveAdverseEvents1 : NULL,
            'vaccinationDate2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->vaccinationDate2 : NULL,
            'vaccinationName2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->nameOfVaccine : NULL,
            'vaccinationNoOfDose2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? 2 : NULL,
            'vaccinationFacility2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->vaccinationFacility2 : NULL,
            'vaccinationRegion2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->vaccinationRegion2 : NULL,
            'haveAdverseEvents2' => ($request->vaccineq1 == 1 && $request->howManyDose == 2) ? $request->haveAdverseEvents2 : NULL,
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
            'req_file' => $newFileName1,
            'result_file' => $newFileName2,
        ]);
    }
}