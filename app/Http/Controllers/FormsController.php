<?php

namespace App\Http\Controllers;

use App\Models\Forms;
use App\Models\Records;
use App\Models\CifUploads;
use App\Exports\FormsExport;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PragmaRX\Countries\Package\Countries;
use App\Http\Requests\FormValidationRequest;

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
                $forms = Forms::orderBy('testDateCollected1', 'desc')->orderBy('created_at', 'desc')->get();
            }
            else if(request()->input('view') == 2) {
                $forms = Forms::whereDate('expoDateLastCont', '<=', date('Y-m-d', strtotime("-5 Days")))->orderBy('created_at', 'desc')->get();
            }
            else if(request()->input('view') == 3) {
                $forms = Forms::where('isExported', '0')->orderBy('created_at', 'desc')->get();
            }
        }
        else {
            $forms = Forms::where('testDateCollected1', date('Y-m-d'))->orWhere('testDateCollected2', date('Y-m-d'))->orderBy('created_at', 'desc')->get();
        }

        $records = Records::orderBy('lname', 'asc')->get();

        $formsctr = Forms::all();

        return view('forms', ['forms' => $forms, 'records' => $records, 'formsctr' => $formsctr]);
    }

    public function export(Request $request)
    {
        //Forms::whereIn('id',[implode(",", $request->listToPrint)])->update(['isExported'=>1]);

        /*
        $models = Forms::findMany([implode(",", $request->listToPrint)]);
        $models->each(function ($item){
            $item->update(['isExported'=>'1']);
            $item->update(['exportedDate'=>NOW()]);
        });
        */
        
        $list = $request->listToPrint;

        asort($list);

        $models = Forms::whereIn('id', $list)
        ->update(['isExported'=>'1', 'exportedDate'=>NOW()]);
        
        return Excel::download(new FormsExport($request->listToPrint), 'CIF_'.date("m_d_Y").'.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function new($id) {
        $check = Records::findOrFail($id);

        if($check->user->brgy_id == auth()->user()->brgy_id || is_null(auth()->user()->brgy_id)) {
            if(Forms::where('records_id', $id)->exists()) {
                //existing na
                $ex_id = Forms::where('records_id', $id)->first();
                return redirect()->back()
                ->with('modalmsg', 'CIF Records already exists on '.$check->lname.", ".$check->fname." ".$check->mname)
                ->with('exist_id', $ex_id->id)
                ->with('attended', ($ex_id->isPresentOnSwabDay == 1) ? "YES" : "NO")
                ->with('eType', (!is_null($ex_id->testType2)) ? $ex_id->testType2 : $ex_id->testType1)
                ->with('dateCollected', (!is_null($ex_id->testDateCollected2)) ? date('m/d/Y', strtotime($ex_id->testDateCollected2)) : date('m/d/Y', strtotime($ex_id->testDateCollected1)));
            }
            else {
                $interviewers = Interviewers::orderBy('lname', 'asc')->get();
                
                $countries = new Countries();
                $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
                $all = $countries->all()->pluck('name.common')->toArray();
                return view('formscreate', ['countries' => $all, 'records' => $check, 'interviewers' => $interviewers, 'id' => $id]);
            }
        }
        else {
            return redirect()->action([FormsController::class, 'index'])->with('status', 'You are not allowed to do that.')->with('statustype', 'warning');
        }
    }

    /*
    public function create()
    {
        $records = Records::all()->sortBy('lname', SORT_NATURAL);
        $interviewers = Interviewers::orderBy('lname', 'asc')->get();

        $countries = new Countries();
        $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
        $all = $countries->all()->pluck('name.common')->toArray();

        return view('formscreate', ['countries' => $all, 'records' => $records, 'interviewers' => $interviewers]);
    }

    public function ajaxGetUserRecord ($id) {
        $srec = Records::where('id',$id)->get();

        $sdata['data'] = $srec;
        echo json_encode($sdata);
        exit;
    }
    */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(FormValidationRequest $request, $id)
    {
        $rec = Records::findOrFail($id);

        if($rec->isPregnant == 0) {
            $hrp = 0;
        }
        else {
            $hrp = $request->highRiskPregnancy;
        }

        $request->validated();

        if(Forms::where([
            ['records_id', $id],
            ['testDateCollected1', $request->testDateCollected1]
            ])->exists()) {
                return redirect()->action([FormsController::class, 'index'])->with('status', 'Double Entry Detected! Error: CIF Record for '.$rec->lname.", ".$rec->fname." ".$rec->mname." already exists at ".date('m/d/Y'))->with('statustype', 'danger');
            }
            else {
                $request->user()->form()->create([
                    'status' => 'approved',
                    'records_id' => $id,
                    'drunit' => $request->drunit,
                    'drregion' => $request->drregion,
                    'interviewerName' => $request->interviewerName,
                    'interviewerMobile' => $request->interviewerMobile,
                    'interviewDate' => $request->interviewDate,
                    'informantName' => $request->informantName,
                    'informantRelationship' => $request->informantRelationship,
                    'informantMobile' => $request->informantMobile,
                    'existingCaseList' => implode(",", $request->existingCaseList),
                    'ecOthersRemarks' => $request->ecOthersRemarks,
                    'pType' => $request->pType,
                    'testingCat' => implode(",",$request->testingCat),
                    'havePreviousCovidConsultation' => $request->havePreviousCovidConsultation,
                    'dateOfFirstConsult' => $request->dateOfFirstConsult,
                    'facilityNameOfFirstConsult' => $request->facilityNameOfFirstConsult,
                    
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
                    'ofwType' => ($request->isOFW == 1) ? $request->ofwType : NULL,
                    'isFNT' => $request->isFNT,
                    'lsiType' => $request->lsiType,
                    'FNTCountryOfOrigin' => $request->FNTCountryOfOrigin,
                    'isLSI' => $request->isLSI,
                    'LSICity' => $request->LSICity,
                    'LSIProvince' => $request->LSIProvince,
                    'isLivesOnClosedSettings' => $request->isLivesOnClosedSettings,
                    'institutionType' => $request->institutionType,
                    'institutionName' => $request->institutionName,
                    'indgSpecify' => $request->indgSpecify,
                    'dateOnsetOfIllness' => $request->dateOnsetOfIllness,
                    'SAS' => (!is_null($request->sasCheck)) ? implode(",", $request->sasCheck) : NULL,
                    'SASFeverDeg' => $request->SASFeverDeg,
                    'SASOtherRemarks' => $request->SASOtherRemarks,
                    'COMO' => implode(",", $request->comCheck),
                    'COMOOtherRemarks' => $request->COMOOtherRemarks,
                    'PregnantLMP' => $request->PregnantLMP,
                    'PregnantHighRisk' => $hrp,
                    'diagWithSARI' => $request->diagWithSARI,
                    'imagingDoneDate' => $request->imagingDoneDate,
                    'imagingDone' => $request->imagingDone,
                    'imagingResult' => $request->imagingResult,
                    'imagingOtherFindings' => $request->imagingOtherFindings,
        
                    'testedPositiveUsingRTPCRBefore' => $request->testedPositiveUsingRTPCRBefore,
                    'testedPositiveNumOfSwab' => $request->testedPositiveNumOfSwab,
                    'testedPositiveLab' => $request->testedPositiveLab,
                    'testedPositiveSpecCollectedDate' => $request->testedPositiveSpecCollectedDate,
        
                    'testDateCollected1' => $request->testDateCollected1,
                    'oniTimeCollected1' => $request->oniTimeCollected1,
                    'testDateReleased1' => $request->testDateReleased1,
                    'testLaboratory1' => $request->testLaboratory1,
                    'testType1' => $request->testType1,
                    'testTypeOtherRemarks1' => $request->testTypeOtherRemarks1,
                    'testResult1' => $request->testResult1,
                    'testResultOtherRemarks1' => $request->testResultOtherRemarks1,
        
                    'testDateCollected2' => $request->testDateCollected2,
                    'testDateReleased2' => $request->testDateReleased2,
                    'testLaboratory2' => $request->testLaboratory2,
                    'testType2' => (!is_null($request->testType2)) ? $request->testType2 : NULL,
                    'testTypeOtherRemarks2' => $request->testTypeOtherRemarks2,
                    'testResult2' => (!is_null($request->testType2)) ? $request->testResult2 : NULL,
                    'testResultOtherRemarks2' => $request->testResultOtherRemarks2,
        
                    'outcomeCondition' => $request->outcomeCondition,
                    'outcomeRecovDate' => $request->outcomeRecovDate,
                    'outcomeDeathDate' => $request->outcomeDeathDate,
                    'deathImmeCause' => $request->deathImmeCause,
                    'deathAnteCause' => $request->deathAnteCause,
                    'deathUndeCause' => $request->deathUndeCause,
                    'contriCondi' => $request->contriCondi,
        
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
        
                    'contact1Name' => $request->contact1Name,
                    'contact1No' => $request->contact1No,
                    'contact2Name' => $request->contact2Name,
                    'contact2No' => $request->contact2No,
                    'contact3Name' => $request->contact3Name,
                    'contact3No' => $request->contact3No,
                    'contact4Name' => $request->contact4Name,
                    'contact4No' => $request->contact4No,
                ]);
        
                return redirect()->action([FormsController::class, 'index'])->with('status', 'CIF of Patient was created successfully.')->with('statustype', 'success');
            }
    }

    public function upload(Request $request, $id) {

        $request->validate([
            'file_type' => 'required',
            'filepath' => 'required|mimes:jpg,png,jpeg,pdf|max:5048',
            'remarks' => 'nullable',
        ]);

        $newFileName = time() . ' - ' . $request->filepath->getClientOriginalName();

        $upload = $request->filepath->move(asset('assets/cif_docs'), $newFileName);
    
        $request->user()->cifupload()->create([
            'forms_id' => $id,
            'file_type' => $request->file_type,
            'filepath' => $newFileName,
            'remarks' => $request->remarks,
        ]);

        return redirect()->back()
        ->with('msg', 'Document has been uploaded successfully.')
        ->with('msgType', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        $interviewers = Interviewers::orderBy('lname', 'asc')->get();

        $docs = CifUploads::where('forms_id', $id)->get();

        $countries = new Countries();
        $countries = $countries->all()->sortBy('name.common', SORT_NATURAL);
        $all = $countries->all()->pluck('name.common')->toArray();

        if($records->user->brgy_id == auth()->user()->brgy_id || is_null(auth()->user()->brgy_id)) {
            return view('formsedit', ['countries' => $all, 'records' => $records, 'interviewers' => $interviewers, 'docs' => $docs]);
        }
        else {
            return redirect()->action([FormsController::class, 'index'])->with('status', 'You are not allowed to do that.')->with('statustype', 'warning');
        }
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
        
        $olddate = $rec->testDateCollected1;

        $rec = Records::findOrFail($rec->records->id);

        if($rec->isPregnant == 0) {
            $hrp = 0;
        }
        else {
            $hrp = $request->highRiskPregnancy;
        }

        if($request->testResult1 == 'PENDING') {
            $changeCC = $request->caseClassification;
        }
        else if($request->testResult1 == 'POSITIVE') {
            $changeCC = 'Confirmed';
        }
        else if($request->testResult1 == 'NEGATIVE') {
            if($request->pType == 'CLOSE CONTACT') {
                $changeCC = 'Suspect';
            }
            else {
                $changeCC = 'Non-COVID-19 Case';
            }
        }

        $request->validated();

        if($olddate == $request->testDateCollected1) {
            $proceed = 1;
        }
        else {
            if(Forms::where([
                ['records_id', $rec->id],
                ['testDateCollected1', $request->testDateCollected1]
            ])->exists()) {
                $proceed = 0;
            }
            else {
                $proceed = 1;
            }
        }

        if($proceed == 1) {
            $form = Forms::where('id', $id)->update([
                'isExported' => '0',
                'exportedDate' => null,
                'drunit' => $request->drunit,
                'drregion' => $request->drregion,
                'interviewerName' => $request->interviewerName,
                'interviewerMobile' => $request->interviewerMobile,
                'interviewDate' => $request->interviewDate,
                'informantName' => $request->informantName,
                'informantRelationship' => $request->informantRelationship,
                'informantMobile' => $request->informantMobile,
                'existingCaseList' => implode(",", $request->existingCaseList),
                'ecOthersRemarks' => $request->ecOthersRemarks,
                'pType' => $request->pType,
                'testingCat' => implode(",",$request->testingCat),
                'havePreviousCovidConsultation' => $request->havePreviousCovidConsultation,
                'dateOfFirstConsult' => $request->dateOfFirstConsult,
                'facilityNameOfFirstConsult' => $request->facilityNameOfFirstConsult,
                
                'dispoType' => $request->dispositionType,
                'dispoName' => $request->dispositionName,
                'dispoDate' => $request->dispositionDate,
                'healthStatus' => $request->healthStatus,
                'caseClassification' => $changeCC,
                'isHealthCareWorker' => $request->isHealthCareWorker,
                'healthCareCompanyName' => $request->healthCareCompanyName,
                'healthCareCompanyLocation' => $request->healthCareCompanyLocation,
                'isOFW' => $request->isOFW,
                'OFWCountyOfOrigin' => $request->OFWCountyOfOrigin,
                'ofwType' => ($request->isOFW == 1) ? $request->ofwType : NULL,
                'isFNT' => $request->isFNT,
                'lsiType' => $request->lsiType,
                'FNTCountryOfOrigin' => $request->FNTCountryOfOrigin,
                'isLSI' => $request->isLSI,
                'LSICity' => $request->LSICity,
                'LSIProvince' => $request->LSIProvince,
                'isLivesOnClosedSettings' => $request->isLivesOnClosedSettings,
                'institutionType' => $request->institutionType,
                'institutionName' => $request->institutionName,
                'indgSpecify' => $request->indgSpecify,
                'dateOnsetOfIllness' => $request->dateOnsetOfIllness,
                'SAS' => (!is_null($request->sasCheck)) ? implode(",", $request->sasCheck) : NULL,
                'SASFeverDeg' => $request->SASFeverDeg,
                'SASOtherRemarks' => $request->SASOtherRemarks,
                'COMO' => implode(",", $request->comCheck),
                'COMOOtherRemarks' => $request->COMOOtherRemarks,
                'PregnantLMP' => $request->PregnantLMP,
                'PregnantHighRisk' => $hrp,
                'diagWithSARI' => $request->diagWithSARI,
                'imagingDoneDate' => $request->imagingDoneDate,
                'imagingDone' => $request->imagingDone,
                'imagingResult' => $request->imagingResult,
                'imagingOtherFindings' => $request->imagingResult,
    
                'testedPositiveUsingRTPCRBefore' => $request->testedPositiveUsingRTPCRBefore,
                'testedPositiveNumOfSwab' => $request->testedPositiveNumOfSwab,
                'testedPositiveLab' => $request->testedPositiveLab,
                'testedPositiveSpecCollectedDate' => $request->testedPositiveSpecCollectedDate,
    
                'testDateCollected1' => $request->testDateCollected1,
                'oniTimeCollected1' => $request->oniTimeCollected1,
                'testDateReleased1' => $request->testDateReleased1,
                'testLaboratory1' => $request->testLaboratory1,
                'testType1' => $request->testType1,
                'testTypeOtherRemarks1' => $request->testTypeOtherRemarks1,
                'testResult1' => $request->testResult1,
                'testResultOtherRemarks1' => $request->testResultOtherRemarks1,
    
                'testDateCollected2' => $request->testDateCollected2,
                'testDateReleased2' => $request->testDateReleased2,
                'testLaboratory2' => $request->testLaboratory2,
                'testType2' => ($request->testType2 != "N/A") ? $request->testType2 : NULL,
                'testTypeOtherRemarks2' => $request->testTypeOtherRemarks2,
                'testResult2' => ($request->testType2 != "N/A") ? $request->testResult2 : NULL,
                'testResultOtherRemarks2' => $request->testResultOtherRemarks2,
    
                'outcomeCondition' => $request->outcomeCondition,
                'outcomeRecovDate' => $request->outcomeRecovDate,
                'outcomeDeathDate' => $request->outcomeDeathDate,
                'deathImmeCause' => $request->deathImmeCause,
                'deathAnteCause' => $request->deathAnteCause,
                'deathUndeCause' => $request->deathUndeCause,
                'contriCondi' => $request->contriCondi,
    
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
    
                'contact1Name' => $request->contact1Name,
                'contact1No' => $request->contact1No,
                'contact2Name' => $request->contact2Name,
                'contact2No' => $request->contact2No,
                'contact3Name' => $request->contact3Name,
                'contact3No' => $request->contact3No,
                'contact4Name' => $request->contact4Name,
                'contact4No' => $request->contact4No,
                ]);
    
                return redirect()->action([FormsController::class, 'index'])->with('status', 'CIF for '.$rec->lname.", ".$rec->fname." ".$rec->mname." has been updated successfully.")->with('statustype', 'success');
        }
        else {
            return redirect()->action([FormsController::class, 'index'])->with('status', 'Double Entry Detected! Edit Error: CIF Record for '.$rec->lname.", ".$rec->fname." ".$rec->mname." already exists at ".date('m/d/Y', strtotime($request->testDateCollected1)))->with('statustype', 'danger');
        }   
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
