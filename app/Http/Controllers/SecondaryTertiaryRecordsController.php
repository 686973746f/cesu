<?php

namespace App\Http\Controllers;

use App\Models\Records;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
use App\Models\SecondaryTertiaryRecords;
use App\Http\Requests\SecondaryTertiaryRecordsValidationRequest;

class SecondaryTertiaryRecordsController extends Controller
{
    public function index() {
        $list = SecondaryTertiaryRecords::paginate(10);

        return view('sc_index', [
            'list' => $list,
        ]);
    }

    public function create() {
        return view('sc_create');
    }

    public function store(SecondaryTertiaryRecordsValidationRequest $request) {
        $request->validated();

        $check1 = Records::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);

        if(!is_null($check1)) {
			$param1 = 1;
			$where = '(Existing in the Records Page)';
		}
		else {
			$param1 = 0;
		}

        $check2 = PaSwabDetails::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);

        if(!is_null($check2)) {
			$param2 = 1;
			$where = '(Existing in Pa-Swab Page, waiting for Approval)';
		}
		else {
			$param2 = 0;
		}

        if($param1 == 1 || $param2 == 1) {
			if($param1 == 1 && $check1->user->isCesuAccount() == true && auth()->user()->isCesuAccount() == false) {
				$msg = 'Double Entry Error. Patient Record already exists and it was already created by CESU Staff/Encoders; hence you cannot see the record on your list.';
			}
			else {
				$msg = 'Double Entry Error. Patient Record already exists.';
			}

			return back()
			->withInput()
			->with('msg', $msg.' '.$where)
            ->with('msgtype', 'danger');
		}
        else {
            $create = $request->user()->secondaryTertiaryRecords()->create([
                'morbidityMonth' => $request->morbidityMonth,
                'dateReported' => $request->dateReported,
                'lname' => mb_strtoupper($request->lname),
                'fname' => mb_strtoupper($request->fname),
                'mname' => ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL,
                'gender' => $request->gender,
                'bdate' => $request->bdate,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'address_houseno' => $request->address_houseno,
                'address_street' => $request->address_street,
                'address_brgy' => $request->address_brgy,
                'address_city' => $request->address_city,
                'address_cityjson' => $request->address_cityjson,
                'address_province' => $request->address_province,
                'address_provincejson' => $request->address_provincejson,
                'temperature' => $request->temperature,
            ]);
    
            return redirect()->route('sc_index')->with('msg', 'The record has been successfully added.')->with('msgtype', 'success');
        }
    }

    public function edit($id) {
        $item = SecondaryTertiaryRecords::findOrFail($id);

        return view('sc_edit', [
            'item' => $item,
        ]);
    }

    public function update(SecondaryTertiaryRecordsValidationRequest $request, $id) {
        $data = SecondaryTertiaryRecords::findOrFail($id);

        $request->validated();

        $data->updated_by = auth()->user()->id;
        $data->morbidityMonth = $request->morbidityMonth;
        $data->dateReported = $request->dateReported;
        $data->lname = mb_strtoupper($request->lname);
        $data->fname = mb_strtoupper($request->fname);
        $data->mname = ($request->filled('mname')) ? mb_strtoupper($request->mname) : NULL;
        $data->gender = $request->gender;
        $data->bdate = $request->bdate;
        $data->email = $request->email;
        $data->mobile = $request->mobile;
        $data->address_houseno = $request->address_houseno;
        $data->address_street = $request->address_street;
        $data->address_brgy = $request->address_brgy;
        $data->address_city = $request->address_city;
        $data->address_cityjson = $request->address_cityjson;
        $data->address_province = $request->address_province;
        $data->address_provincejson = $request->address_provincejson;
        $data->temperature = $request->temperature;

        if($data->isDirty()) {
            
        }
    }

    public function delete($id) {

    }

    public function moveToForSwab($id) {

    }
}
