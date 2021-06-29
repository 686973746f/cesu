<?php

namespace App\Http\Controllers;

use App\Models\Records;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
use App\Models\Forms;
use App\Http\Requests\PaSwabValidationRequest;
use IlluminateAgnostic\Collection\Support\Str;

class PaSwabController extends Controller
{
    public function index() {
        return view('paswab_index');
    }

    public function store(PaSwabValidationRequest $request) {
        $request->validated();

        if(Records::where('lname', mb_strtoupper($request->lname))
		->where('fname', mb_strtoupper($request->fname))
		->where(function ($query) use ($request) {
			$query->where('mname', mb_strtoupper($request->mname))
			->orWhereNull('mname');
		})
		->where('bdate', $request->bdate)
		->where('gender', strtoupper($request->gender))
		->exists()) {
			$param1 = 1;
		}
		else {
			$param1 = 0;
		}

        if(PaSwabDetails::where('lname', mb_strtoupper($request->lname))
		->where('fname', mb_strtoupper($request->fname))
		->where(function ($query) use ($request) {
			$query->where('mname', mb_strtoupper($request->mname))
			->orWhereNull('mname');
		})
		->where('bdate', $request->bdate)
		->where('gender', strtoupper($request->gender))
        ->whereIn('status', ['approved', 'pending'])
		->exists()) {
			$param2 = 1;
		}
		else {
			$param2 = 0;
		}

        if($param1 == 1 || $param2 == 1) {
            return back()
			->withInput()
			->with('msg', 'Double entry error.')
            ->with('msgtype', 'danger');
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

            $data = PaSwabDetails::create([
                'majikCode' => $majik,
                'pType' => $request->pType,
                'isForHospitalization' => $request->isForHospitalization,
                'interviewDate' => $request->interviewDate,
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
                'philhealth' => $philhealth_organized,
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

                'senderIP' => request()->ip(),
            ]);

            return redirect()->action([PaSwabController::class, 'complete'])->with('majik', $majik)->with('statustype', 'success');
        }
    }

    public function complete() {
        return view('paswab_complete');
    }

    public function check(Request $request) {
        $request->validate([
            'scode' => 'required',
        ]);

        $check = PaSwabDetails::where('majikCode', strtoupper($request->scode))->first();

        if($check->count()) {
            return view('paswab_check', [
                'data' => $check
            ]);
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
        $list = PaSwabDetails::where('status', 'pending')->paginate(10);

        return view('paswab_view', ['list' => $list]);
    }

    public function viewspecific($id) {
        $data = PaSwabDetails::findOrFail($id);

        $interviewers = Interviewers::orderBy('lname', 'asc')->get();

        return view('paswab_view_specific', ['data' => $data, 'interviewers' => $interviewers]);
    }

    public function approve($id, Request $request) {
        $data = PaSwabDetails::findOrFail($id);

        $request->validate([
            'interviewerName' => 'required',
            'testDateCollected1' => 'required|date',
            'testType1' => 'required',
            'testTypeOtherRemarks1' => ($request->testType1 == "ANTIGEN" || $request->testType1 == "OTHERS") ? 'required' : 'nullable',
        ]);

        //create record data first
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
            'worksInClosedSetting' => 'NO',
            'occupation_lotbldg' => NULL,
            'occupation_street' => NULL,
            'occupation_brgy' => NULL,
            'occupation_city' => NULL,
            'occupation_cityjson' => NULL,
            'occupation_province' => NULL,
            'occupation_provincejson' => NULL,
            'occupation_name' => NULL,
            'occupation_mobile' => NULL,
            'occupation_email' => NULL,

            'natureOfWork' => ($data->hasoccupation == 1) ? mb_strtoupper($data->natureOfWork) : NULL,
            'natureOfWorkIfOthers' => ($data->hasoccupation == 1 && $request->natureOfWork == 'OTHERS') ? mb_strtoupper($request->natureOfWorkIfOthers) : NULL,
        ]);

        if(!is_null($rec->philhealth)) {
            if($request->testType1 == "OPS" || $request->testType1 == "NPS" || $request->testType1 == "OPS AND NPS") {
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

        $request->user()->form()->create([
            'status' => 'approved',
            'isPresentOnSwabDay' => NULL,
            'records_id' => $rec->id,
            'drunit' => 'CHO GENERAL TRIAS',
            'drregion' => '4A CAVITE',
            'interviewerName' => $request->interviewerName,
            'interviewerMobile' => '09190664324',
            'interviewDate' => $data->interviewDate,
            'informantName' => NULL,
            'informantRelationship' => NULL,
            'informantMobile' => NULL,
            'existingCaseList' => '1',
            'ecOthersRemarks' => NULL,
            'pType' => $data->pType,
            'isForHospitalization' => $data->isForHospitalization,
            'testingCat' => 'C',
            'havePreviousCovidConsultation' => '0',
            'dateOfFirstConsult' => NULL,
            'facilityNameOfFirstConsult' => NULL,

            'dispoType' => NULL,
            'dispoName' => NULL,
            'dispoDate' => NULL,
            'healthStatus' => 'Asymptomatic',
            'caseClassification' => 'Suspect',
            'healthCareCompanyName' => NULL,
            'healthCareCompanyLocation' => NULL,
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
            'PregnantLMP' => $data->PregnantLMP,
            'PregnantHighRisk' => ($data->isPregnant == 1) ? '1' : '0',
            'diagWithSARI' => '0',
            'imagingDoneDate' => NULL,
            'imagingDone' => 'None',
            'imagingResult' => NULL,
            'imagingOtherFindings' => NULL,

            'testedPositiveUsingRTPCRBefore' => '0',
            'testedPositiveNumOfSwab' => '0',
            'testedPositiveLab' => NULL,
            'testedPositiveSpecCollectedDate' => NULL,

            'testDateCollected1' => $request->testDateCollected1,
            'oniTimeCollected1' => $oniTimeFinal,
            'testDateReleased1' => NULL,
            'testLaboratory1' => NULL,
            'testType1' => $request->testType1,
            'testTypeAntigenRemarks1' => ($request->testType1 == "ANTIGEN") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
            'testTypeOtherRemarks1' => ($request->testType1 == "OTHERS") ? mb_strtoupper($request->testTypeOtherRemarks1) : NULL,
            'testResult1' => 'PENDING',
            'testResultOtherRemarks1' => NULL,

            'testDateCollected2' => NULL,
            'oniTimeCollected2' => NULL,
            'testDateReleased2' => NULL,
            'testLaboratory2' => NULL,
            'testType2' => NULL,
            'testTypeAntigenRemarks2' => NULL,
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

            'remarks' => NULL,
        ]);

        $upd = PaSwabDetails::where('id', $id)->update([
            'status' => 'approved'
        ]);

        return redirect()->action([PaSwabController::class, 'view'])
        ->with('msg', 'Swab Schedule for '.$rec->getName()." has been approved successfully.")
        ->with('msgtype', 'success');
    }

    public function reject($id, Request $request) {

    }
}
