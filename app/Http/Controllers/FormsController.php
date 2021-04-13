<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use App\Models\Records;
use App\Exports\FormsExport;
use App\Http\Requests\FormValidationRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PragmaRX\Countries\Package\Countries;

class FormsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        if(request()->input('q')) {
            $forms = Forms::whereHas('records', function ($query) {
                $query->where('lname', 'LIKE', '%'.request()->input('q').'%')
                ->orWhere('fname', 'LIKE', '%'.request()->input('q').'%')
                ->orWhere('mname', 'LIKE', '%'.request()->input('q').'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }
        else {
            $forms = Forms::orderBy('created_at', 'desc')->paginate(10);
        }
        */

        if(request()->input('view')) {
            if(request()->input('view') == 1) {
                $forms = Forms::orderBy('created_at', 'desc')->get();
            }
            else if(request()->input('view') == 2) {
                $forms = Forms::whereDate('expoDateLastCont', '<=', date('Y-m-d', strtotime("-5 Days")))->orderBy('created_at', 'desc')->get();
            }
            else if(request()->input('view') == 3) {
                $forms = Forms::where('isExported', '0')->orderBy('created_at', 'desc')->get();
            }
        }
        else {
            $forms = Forms::orderBy('created_at', 'desc')->get();
        }
        

        $records = Records::all();
        $records = $records->count();

        return view('forms', ['forms' => $forms, 'records' => $records]);
    }

    public function export(Request $request)
    {
        //Forms::whereIn('id',[implode(",", $request->listToPrint)])->update(['isExported'=>1]);

        $models = Forms::findMany([implode(",", $request->listToPrint)]);

        $models->each(function ($item){
            $item->update(['isExported'=>'1']);
        });
        
        return Excel::download(new FormsExport($request->listToPrint), 'CIF_'.date("m_d_Y_H_i_s").'.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $records = Records::all()->sortBy('lname', SORT_NATURAL);
        $countries = new Countries();
        $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
        $all = $countries->all()->pluck('name.common')->toArray();
        return view('formscreate', ['countries' => $all, 'records' => $records]);
    }

    public function ajaxGetUserRecord ($id) {
        $srec = Records::where('id',$id)->get();

        $sdata['data'] = $srec;
        echo json_encode($sdata);
        exit;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(FormValidationRequest $request)
    {
        $rec = Records::findOrFail($request->records_id);

        if($rec->isPregnant == 0) {
            $hrp = 0;
        }
        else {
            $hrp = $request->highRiskPregnancy;
        }

        $request->validated();
        
        $request->user()->form()->create([
            'records_id' => $request->records_id,
            'drunit' => $request->drunit,
            'drregion' => $request->drregion,
            'interviewerName' => $request->interviewerName,
            'interviewerMobile' => $request->interviewerMobile,
            'interviewDate' => $request->interviewDate,
            'informantName' => $request->informantName,
            'informantRelationship' => $request->informantRelationship,
            'informantMobile' => $request->informantMobile,
            'pType' => $request->pType,
            'testingCat' => implode(",",$request->testingCat),
            'havePreviousCovidConsultation' => $request->havePreviousCovidConsultation,
            'dateOfFirstConsult' => $request->dateOfFirstConsult,
            'facilityNameOfFirstConsult' => $request->facilityNameOfFirstConsult,
            'admittedInHealthFacility' => $request->admittedInHealthFacility,
            'dateOfAdmissionInHealthFacility' => $request->dateOfAdmissionInHealthFacility,
            'admittedInMultipleHealthFacility' => $request->admittedInMultipleHealthFacility,
            'facilitynameOfFirstAdmitted' => $request->facilitynameOfFirstAdmitted,
            'fRegion' => $request->facilityregion,
            'fCity' => $request->facilityprovince,
            'dispoType' => $request->dispositionType,
            'dispoName' => $request->dispositionName,
            'dispoDate' => $request->dispositionDate,
            'healthStatus' => $request->healthStatus,
            'caseClassification' => $request->caseClassification,
            'isHealthCareWorker' => $request->isHealthCareWorker,
            'healthCareCompanyName' => $request->healthCareCompanyName,
            'healthCareCompanyLocation' => $request->healthCareCompanyLocation,
            'isOFW' => $request->isOFW,
            'OFWCountyOfOrigin' => $request->OFWCountyOfOrigin,
            'isFNT' => $request->isFNT,
            'FNTCountryOfOrigin' => $request->FNTCountryOfOrigin,
            'isLSI' => $request->isLSI,
            'LSICity' => $request->LSICity,
            'LSIProvince' => $request->LSIProvince,
            'isLivesOnClosedSettings' => $request->isLivesOnClosedSettings,
            'institutionType' => $request->institutionType,
            'institutionName' => $request->institutionName,
            'oaddresslotbldg' => $request->oaddresslotbldg,
            'oaddressstreet' => $request->oaddressstreet,
            'oaddressscity' => $request->oaddressscity,
            'oaddresssprovince' => $request->oaddresssprovince,
            'oaddressscountry' => $request->OFWCountyOfOrigin,
            'placeofwork' => $request->placeofwork,
            'employername' => $request->employername,
            'employercontactnumber' => $request->employercontactnumber,
            'dateOnsetOfIllness' => $request->dateOnsetOfIllness,
            'SAS' => (!is_null($request->sasCheck)) ? implode(",", $request->sasCheck) : NULL,
            'SASFeverDeg' => $request->SASFeverDeg,
            'SASOtherRemarks' => $request->SASOtherRemarks,
            'COMO' => implode(",", $request->comCheck),
            'COMOOtherRemarks' => $request->COMOOtherRemarks,
            'PregnantLMP' => $request->PregnantLMP,
            'PregnantHighRisk' => $hrp,
            'diagWithSARI' => $request->diagWithSARI,
            'ImagingDone' => implode(",", $request->imaCheck),
            'chestRDResult' => $request->chestRDResult,
            'chestRDOtherFindings' => $request->chestRDOtherFindings,
            'chestCTResult' => $request->chestCTResult,
            'chestCTOtherFindings' => $request->chestCTOtherFindings,
            'lungUSResult' => $request->lungUSResult,
            'lungUSOtherFindings' => $request->lungUSOtherFindings,
            'testsDoneList' => (!is_null($request->labCheck)) ? implode(",", $request->labCheck) : NULL,
            'rtpcr_ops_date_collected' => $request->rtpcr_ops_date_collected,
            'rtpcr_ops_laboratory' => $request->rtpcr_ops_laboratory,
            'rtpcr_ops_results' => $request->rtpcr_ops_results,
            'rtpcr_ops_date_released' => $request->rtpcr_ops_date_released,
            'rtpcr_nps_date_collected' => $request->rtpcr_nps_date_collected,
            'rtpcr_nps_laboratory' => $request->rtpcr_nps_laboratory,
            'rtpcr_nps_results' => $request->rtpcr_nps_results,
            'rtpcr_nps_date_released' => $request->rtpcr_nps_date_released,
            'rtpcr_both_date_collected' => $request->rtpcr_both_date_collected,
            'rtpcr_both_laboratory' => $request->rtpcr_both_laboratory,
            'rtpcr_both_results' => $request->rtpcr_both_results,
            'rtpcr_both_date_released' => $request->rtpcr_both_date_released,
            'rtpcr_spec_type' => $request->rtpcr_spec_type,
            'rtpcr_spec_date_collected' => $request->rtpcr_spec_date_collected,
            'rtpcr_spec_laboratory' => $request->rtpcr_spec_laboratory,
            'rtpcr_spec_results' => $request->rtpcr_spec_results,
            'rtpcr_spec_date_released' => $request->rtpcr_spec_date_released,
            'antigen_date_collected' => $request->antigen_date_collected,
            'antigen_laboratory' => $request->antigen_laboratory,
            'antigen_results' => $request->antigen_results,
            'antigen_date_released' => $request->antigen_date_released,
            'antibody_date_collected' => $request->antibody_date_collected,
            'antibody_laboratory' => $request->antibody_laboratory,
            'antibody_results' => $request->antibody_results,
            'antibody_date_released' => $request->antibody_date_released,
            'others_specify' => $request->others_specify,
            'others_date_collected' => $request->others_date_collected,
            'others_laboratory' => $request->others_laboratory,
            'others_results' => $request->others_results,
            'others_date_released' => $request->others_date_released,
            'testedPositiveUsingRTPCRBefore' => $request->testedPositiveUsingRTPCRBefore,
            'testedPositiveNumOfSwab' => $request->testedPositiveNumOfSwab,
            'testedPositiveLab' => $request->testedPositiveLab,
            'testedPositiveSpecCollectedDate' => $request->testedPositiveSpecCollectedDate,
            'outcomeCondition' => $request->outcomeCondition,
            'outcomeRecovDate' => $request->outcomeRecovDate,
            'outcomeDeathDate' => $request->outcomeDeathDate,
            'deathImmeCause' => $request->deathImmeCause,
            'deathAnteCause' => $request->deathAnteCause,
            'deathUndeCause' => $request->deathUndeCause,
            'expoitem1' => $request->expoitem1,
            'expoDateLastCont' => $request->expoDateLastCont,
            'expoitem2' => $request->expoitem2,
            'placevisited' => (!is_null($request->vOpt)) ? implode(",", $request->vOpt) : NULL,
            'vOpt1_details' => $request->vOpt1_details,
            'vOpt1_date' => $request->vOpt1_date,
            'vOpt2_details' => $request->vOpt2_details,
            'vOpt2_date' => $request->vOpt2_date,
            'vOpt3_details' => $request->vOpt3_details,
            'vOpt3_date' => $request->vOpt3_date,
            'vOpt4_details' => $request->vOpt4_details,
            'vOpt4_date' => $request->vOpt4_date,
            'vOpt5_details' => $request->vOpt5_details,
            'vOpt5_date' => $request->vOpt5_date,
            'vOpt6_details' => $request->vOpt6_details,
            'vOpt6_date' => $request->vOpt6_date,
            'vOpt7_details' => $request->vOpt7_details,
            'vOpt7_date' => $request->vOpt7_date,
            'vOpt8_details' => $request->vOpt8_details,
            'vOpt8_date' => $request->vOpt8_date,
            'vOpt9_details' => $request->vOpt9_details,
            'vOpt9_date' => $request->vOpt9_date,
            'vOpt10_details' => $request->vOpt10_details,
            'vOpt10_date' => $request->vOpt10_date,
            'vOpt11_details' => $request->vOpt11_details,
            'vOpt11_date' => $request->vOpt11_date,
            'hasTravHistOtherCountries' => $request->hasTravHistOtherCountries,
            'historyCountryOfExit' => $request->historyCountryOfExit,
            'country_historyTypeOfTranspo' => $request->country_historyTypeOfTranspo,
            'country_historyTranspoNo' => $request->country_historyTranspoNo,
            'country_historyTranspoDateOfDeparture' => $request->country_historyTranspoDateOfDeparture,
            'country_historyTranspoDateOfArrival' => $request->country_historyTranspoDateOfArrival,
            'hasTravHistLocal' => $request->hasTravHistLocal,
            'historyPlaceOfOrigin' => $request->historyPlaceOfOrigin,
            'local_historyTypeOfTranspo' => $request->local_historyTypeOfTranspo,
            'local_historyTranspoNo' => $request->local_historyTranspoNo,
            'local_historyTranspoDateOfDeparture' => $request->local_historyTranspoDateOfDeparture,
            'local_historyTranspoDateOfArrival' => $request->local_historyTranspoDateOfArrival,
            'contact1Name' => $request->contact1Name,
            'contact1No' => $request->contact1No,
            'contact2Name' => $request->contact2Name,
            'contact2No' => $request->contact2No,
            'contact3Name' => $request->contact3Name,
            'contact3No' => $request->contact3No,
            'contact4Name' => $request->contact4Name,
            'contact4No' => $request->contact4No,
            'addContName' => (count(array_filter($request->addContName)) > 0) ? implode(",", $request->addContName) : NULL,
            'addContNo' => (count(array_filter($request->addContNo)) > 0) ? implode(",", $request->addContNo) : NULL,
            'addContExpSet' => (count(array_filter($request->addContExpSet)) > 0) ? implode(",", $request->addContExpSet) : NULL,
        ]);

        return redirect()->action([FormsController::class, 'index'])->with('status', 'CIF of Patient was created successfully.')->with('statustype', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $records = Forms::findOrFail($id);

        $countries = new Countries();
        $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
        $all = $countries->all()->pluck('name.common')->toArray();
        
        return view('formsedit', ['countries' => $all, 'records' => $records]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormValidationRequest $request, $id)
    {
        $rec = Forms::findOrFail($id);
        $rec = Records::findOrFail($rec->records->id);

        if($rec->isPregnant == 0) {
            $hrp = 0;
        }
        else {
            $hrp = $request->highRiskPregnancy;
        }

        $request->validated();

        $form = Forms::where('id', $id)->update([
            'drunit' => $request->drunit,
            'drregion' => $request->drregion,
            'interviewerName' => $request->interviewerName,
            'interviewerMobile' => $request->interviewerMobile,
            'interviewDate' => $request->interviewDate,
            'informantName' => $request->informantName,
            'informantRelationship' => $request->informantRelationship,
            'informantMobile' => $request->informantMobile,
            'pType' => $request->pType,
            'testingCat' => implode(",",$request->testingCat),
            'havePreviousCovidConsultation' => $request->havePreviousCovidConsultation,
            'dateOfFirstConsult' => $request->dateOfFirstConsult,
            'facilityNameOfFirstConsult' => $request->facilityNameOfFirstConsult,
            'admittedInHealthFacility' => $request->admittedInHealthFacility,
            'dateOfAdmissionInHealthFacility' => $request->dateOfAdmissionInHealthFacility,
            'admittedInMultipleHealthFacility' => $request->admittedInMultipleHealthFacility,
            'facilitynameOfFirstAdmitted' => $request->facilitynameOfFirstAdmitted,
            'fRegion' => $request->facilityregion,
            'fCity' => $request->facilityprovince,
            'dispoType' => $request->dispositionType,
            'dispoName' => $request->dispositionName,
            'dispoDate' => $request->dispositionDate,
            'healthStatus' => $request->healthStatus,
            'caseClassification' => $request->caseClassification,
            'isHealthCareWorker' => $request->isHealthCareWorker,
            'healthCareCompanyName' => $request->healthCareCompanyName,
            'healthCareCompanyLocation' => $request->healthCareCompanyLocation,
            'isOFW' => $request->isOFW,
            'OFWCountyOfOrigin' => $request->OFWCountyOfOrigin,
            'isFNT' => $request->isFNT,
            'FNTCountryOfOrigin' => $request->FNTCountryOfOrigin,
            'isLSI' => $request->isLSI,
            'LSICity' => $request->LSICity,
            'LSIProvince' => $request->LSIProvince,
            'isLivesOnClosedSettings' => $request->isLivesOnClosedSettings,
            'institutionType' => $request->institutionType,
            'institutionName' => $request->institutionName,
            'oaddresslotbldg' => $request->oaddresslotbldg,
            'oaddressstreet' => $request->oaddressstreet,
            'oaddressscity' => $request->oaddressscity,
            'oaddresssprovince' => $request->oaddresssprovince,
            'oaddressscountry' => $request->OFWCountyOfOrigin,
            'placeofwork' => $request->placeofwork,
            'employername' => $request->employername,
            'employercontactnumber' => $request->employercontactnumber,
            'dateOnsetOfIllness' => $request->dateOnsetOfIllness,
            'SAS' => (!is_null($request->sasCheck)) ? implode(",", $request->sasCheck) : NULL,
            'SASFeverDeg' => $request->SASFeverDeg,
            'SASOtherRemarks' => $request->SASOtherRemarks,
            'COMO' => implode(",", $request->comCheck),
            'COMOOtherRemarks' => $request->COMOOtherRemarks,
            'PregnantLMP' => $request->PregnantLMP,
            'PregnantHighRisk' => $hrp,
            'diagWithSARI' => $request->diagWithSARI,
            'ImagingDone' => implode(",", $request->imaCheck),
            'chestRDResult' => $request->chestRDResult,
            'chestRDOtherFindings' => $request->chestRDOtherFindings,
            'chestCTResult' => $request->chestCTResult,
            'chestCTOtherFindings' => $request->chestCTOtherFindings,
            'lungUSResult' => $request->lungUSResult,
            'lungUSOtherFindings' => $request->lungUSOtherFindings,
            'testsDoneList' => (!is_null($request->labCheck)) ? implode(",", $request->labCheck) : NULL,
            'rtpcr_ops_date_collected' => $request->rtpcr_ops_date_collected,
            'rtpcr_ops_laboratory' => $request->rtpcr_ops_laboratory,
            'rtpcr_ops_results' => $request->rtpcr_ops_results,
            'rtpcr_ops_date_released' => $request->rtpcr_ops_date_released,
            'rtpcr_nps_date_collected' => $request->rtpcr_nps_date_collected,
            'rtpcr_nps_laboratory' => $request->rtpcr_nps_laboratory,
            'rtpcr_nps_results' => $request->rtpcr_nps_results,
            'rtpcr_nps_date_released' => $request->rtpcr_nps_date_released,
            'rtpcr_both_date_collected' => $request->rtpcr_both_date_collected,
            'rtpcr_both_laboratory' => $request->rtpcr_both_laboratory,
            'rtpcr_both_results' => $request->rtpcr_both_results,
            'rtpcr_both_date_released' => $request->rtpcr_both_date_released,
            'rtpcr_spec_type' => $request->rtpcr_spec_type,
            'rtpcr_spec_date_collected' => $request->rtpcr_spec_date_collected,
            'rtpcr_spec_laboratory' => $request->rtpcr_spec_laboratory,
            'rtpcr_spec_results' => $request->rtpcr_spec_results,
            'rtpcr_spec_date_released' => $request->rtpcr_spec_date_released,
            'antigen_date_collected' => $request->antigen_date_collected,
            'antigen_laboratory' => $request->antigen_laboratory,
            'antigen_results' => $request->antigen_results,
            'antigen_date_released' => $request->antigen_date_released,
            'antibody_date_collected' => $request->antibody_date_collected,
            'antibody_laboratory' => $request->antibody_laboratory,
            'antibody_results' => $request->antibody_results,
            'antibody_date_released' => $request->antibody_date_released,
            'others_specify' => $request->others_specify,
            'others_date_collected' => $request->others_date_collected,
            'others_laboratory' => $request->others_laboratory,
            'others_results' => $request->others_results,
            'others_date_released' => $request->others_date_released,
            'testedPositiveUsingRTPCRBefore' => $request->testedPositiveUsingRTPCRBefore,
            'testedPositiveNumOfSwab' => $request->testedPositiveNumOfSwab,
            'testedPositiveLab' => $request->testedPositiveLab,
            'testedPositiveSpecCollectedDate' => $request->testedPositiveSpecCollectedDate,
            'outcomeCondition' => $request->outcomeCondition,
            'outcomeRecovDate' => $request->outcomeRecovDate,
            'outcomeDeathDate' => $request->outcomeDeathDate,
            'deathImmeCause' => $request->deathImmeCause,
            'deathAnteCause' => $request->deathAnteCause,
            'deathUndeCause' => $request->deathUndeCause,
            'expoitem1' => $request->expoitem1,
            'expoDateLastCont' => $request->expoDateLastCont,
            'expoitem2' => $request->expoitem2,
            'placevisited' => (!is_null($request->vOpt)) ? implode(",", $request->vOpt) : NULL,
            'vOpt1_details' => $request->vOpt1_details,
            'vOpt1_date' => $request->vOpt1_date,
            'vOpt2_details' => $request->vOpt2_details,
            'vOpt2_date' => $request->vOpt2_date,
            'vOpt3_details' => $request->vOpt3_details,
            'vOpt3_date' => $request->vOpt3_date,
            'vOpt4_details' => $request->vOpt4_details,
            'vOpt4_date' => $request->vOpt4_date,
            'vOpt5_details' => $request->vOpt5_details,
            'vOpt5_date' => $request->vOpt5_date,
            'vOpt6_details' => $request->vOpt6_details,
            'vOpt6_date' => $request->vOpt6_date,
            'vOpt7_details' => $request->vOpt7_details,
            'vOpt7_date' => $request->vOpt7_date,
            'vOpt8_details' => $request->vOpt8_details,
            'vOpt8_date' => $request->vOpt8_date,
            'vOpt9_details' => $request->vOpt9_details,
            'vOpt9_date' => $request->vOpt9_date,
            'vOpt10_details' => $request->vOpt10_details,
            'vOpt10_date' => $request->vOpt10_date,
            'vOpt11_details' => $request->vOpt11_details,
            'vOpt11_date' => $request->vOpt11_date,
            'hasTravHistOtherCountries' => $request->hasTravHistOtherCountries,
            'historyCountryOfExit' => $request->historyCountryOfExit,
            'country_historyTypeOfTranspo' => $request->country_historyTypeOfTranspo,
            'country_historyTranspoNo' => $request->country_historyTranspoNo,
            'country_historyTranspoDateOfDeparture' => $request->country_historyTranspoDateOfDeparture,
            'country_historyTranspoDateOfArrival' => $request->country_historyTranspoDateOfArrival,
            'hasTravHistLocal' => $request->hasTravHistLocal,
            'historyPlaceOfOrigin' => $request->historyPlaceOfOrigin,
            'local_historyTypeOfTranspo' => $request->local_historyTypeOfTranspo,
            'local_historyTranspoNo' => $request->local_historyTranspoNo,
            'local_historyTranspoDateOfDeparture' => $request->local_historyTranspoDateOfDeparture,
            'local_historyTranspoDateOfArrival' => $request->local_historyTranspoDateOfArrival,
            'contact1Name' => $request->contact1Name,
            'contact1No' => $request->contact1No,
            'contact2Name' => $request->contact2Name,
            'contact2No' => $request->contact2No,
            'contact3Name' => $request->contact3Name,
            'contact3No' => $request->contact3No,
            'contact4Name' => $request->contact4Name,
            'contact4No' => $request->contact4No,
            'addContName' => (count(array_filter($request->addContName)) > 0) ? implode(",", $request->addContName) : NULL,
            'addContNo' => (count(array_filter($request->addContNo)) > 0) ? implode(",", $request->addContNo) : NULL,
            'addContExpSet' => (count(array_filter($request->addContExpSet)) > 0) ? implode(",", $request->addContExpSet) : NULL,
			]);

            return redirect()->action([FormsController::class, 'index'])->with('status', 'CIF for '.$rec->lname.", ".$rec->fname." ".$rec->lname." has been updated successfully.")->with('statustype', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
