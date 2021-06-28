<?php

namespace App\Http\Controllers;

use App\Models\Records;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
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
		->exists()) {
			$param1 = 1;
		}
		else {
			$param1 = 0;
		}

        if($param1 == 1) {
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
            'natureOfWorkIfOthers' => ($requedatast->hasoccupation == 1 && $request->natureOfWork == 'OTHERS') ? mb_strtoupper($request->natureOfWorkIfOthers) : NULL,
        ]);
    }

    public function reject($id, Request $request) {

    }
}
