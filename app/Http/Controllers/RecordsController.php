<?php

namespace App\Http\Controllers;

use App\Models\Records;
use App\Models\Companies;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\RecordValidationRequest;

class RecordsController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		/*
		if(request()->input('q')) {
			$records = Records::where('lname', 'LIKE', '%'.request()->input('q').'%')
			->orWhere('fname', 'LIKE', '%'.request()->input('q').'%')
			->orWhere('mname', 'LIKE', '%'.request()->input('q').'%')
			->paginate(10);
		}
		else {
			$records = Records::orderBy('lname','asc')->get();
		}
		*/

		if(request()->input('q')) {
			if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
				if(!is_null(auth()->user()->brgy_id)) {
					$records = Records::with('user')
					->where(function ($query) {
						$query->where('lname', 'LIKE', mb_strtoupper(request()->input('q'))."%")
						->orWhere('fname', 'LIKE', mb_strtoupper(request()->input('q'))."%")
						->orWhere('mname', 'LIKE', mb_strtoupper(request()->input('q'))."%");
					})
					->whereHas('user', function ($query) {
						$query->where('brgy_id', auth()->user()->brgy_id);
					})
					->orderBy('lname', 'asc')->paginate(10);
				}
				else {
					$records = Records::with('user')
					->where(function ($query) {
						$query->where('lname', 'LIKE', mb_strtoupper(request()->input('q'))."%")
						->orWhere('fname', 'LIKE', mb_strtoupper(request()->input('q'))."%")
						->orWhere('mname', 'LIKE', mb_strtoupper(request()->input('q'))."%");
					})
					->whereHas('user', function ($query) {
						$query->where('company_id', auth()->user()->company_id);
					})
					->orderBy('lname', 'asc')->paginate(10);
				}
			}
			else {
				$records = Records::where('lname', 'LIKE', mb_strtoupper(request()->input('q'))."%")
				->orWhere('fname', 'LIKE', mb_strtoupper(request()->input('q'))."%")
				->orWhere('mname', 'LIKE', mb_strtoupper(request()->input('q'))."%")
				->orderBy('lname', 'asc')->paginate(10);
			}
		}
		else {
			if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
				if(!is_null(auth()->user()->brgy_id)) {
					$records = Records::with('user')
					->whereHas('user', function($q) {
						$q->where('brgy_id', auth()->user()->brgy_id);
					})
					->orderBy('lname', 'asc')->paginate(10);
				}
				else {
					$records = Records::with('user')
					->whereHas('user', function($q) {
						$q->where('company_id', auth()->user()->company_id);
					})
					->orderBy('lname', 'asc')->paginate(10);
				}
			}
			else {
				$records = Records::orderBy('lname', 'asc')->paginate(10);
			}	
		}

        return view ('records', ['records' => $records]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		if(!is_null(auth()->user()->company_id)) {
			$list = Companies::find(auth()->user()->company_id);
			
			return view ('addrecord_company', ['list' => $list]);
		}
		else {
			return view ('addrecord');
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecordValidationRequest $request) {

		$request->validated();

		if($request->paddressdifferent == 1) {
			$paddress_houseno = $request->permaaddress_houseno;
			$paddress_street = $request->permaaddress_street;
			$paddress_brgy = $request->permaaddress_brgy;
			$paddress_city = $request->permaaddress_city;
			$paddress_cityjson = $request->permaaddress_cityjson;
			$paddress_province = $request->permaaddress_province;
			$paddress_provincejson = $request->permaaddress_provincejson;
			$pmobile = $request->permamobile;
			$pphoneno = $request->permaphoneno;
			$pemail = $request->permaemail;
		}
		else {
			$paddress_houseno = $request->address_houseno;
			$paddress_street = $request->address_street;
			$paddress_brgy = $request->address_brgy;
			$paddress_city = $request->address_city;
			$paddress_cityjson = $request->address_cityjson;
			$paddress_province = $request->address_province;
			$paddress_provincejson = $request->address_provincejson;
			$pmobile = $request->mobile;
			$pphoneno = ($request->filled('phoneno')) ? $request->phoneno : NULL;
			$pemail = $request->email;
		}

		if($request->gender == 'Male') {
			$isPregnant = 0;
		}
		else {
			$isPregnant = $request->pregnant;
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

		if($param1 == 1) {
			return back()
			->withInput()
			->with('msg', 'Double Entry Error. Patient Record already exists.');
		}
		else {
			if(is_null(auth()->user()->company_id)) {
				$request->user()->records()->create([
					'status' => 'approved',
					'lname' => mb_strtoupper($request->lname),
					'fname' => mb_strtoupper($request->fname),
					'mname' => ($request->filled('mname') && mb_strtoupper($request->mname) != "N/A") ? mb_strtoupper($request->mname) : null,
					'gender' => strtoupper($request->gender),
					'isPregnant' => $isPregnant,
					'cs' => strtoupper($request->cs),
					'nationality' => strtoupper($request->nationality),
					'bdate' => $request->bdate,
					'mobile' => $request->mobile,
					'phoneno' => ($request->filled('phoneno')) ? $request->phoneno : NULL,
					'email' => $request->email,
					'philhealth' => $philhealth_organized,
					'address_houseno' => strtoupper($request->address_houseno),
					'address_street' => strtoupper($request->address_street),
					'address_brgy' => strtoupper($request->address_brgy),
					'address_city' => strtoupper($request->address_city),
					'address_cityjson' => $request->address_cityjson,
					'address_province' => strtoupper($request->address_province),
					'address_provincejson' => $request->address_provincejson,
		
					'permaaddressDifferent' => $request->paddressdifferent,
					'permaaddress_houseno' => strtoupper($paddress_houseno),
					'permaaddress_street' => strtoupper($paddress_street),
					'permaaddress_brgy' => strtoupper($paddress_brgy),
					'permaaddress_city' => strtoupper($paddress_city),
					'permaaddress_cityjson' => $paddress_cityjson,
					'permaaddress_province' => strtoupper($paddress_province),
					'permaaddress_provincejson' => $paddress_provincejson,
					'permamobile' => $pmobile,
					'permaphoneno' => $pphoneno,
					'permaemail' => $pemail,
		
					'hasOccupation' => $request->hasoccupation,
					'occupation' => ($request->filled('occupation') && $request->hasoccupation == 1) ? strtoupper($request->occupation) : NULL,
					'worksInClosedSetting' => ($request->filled('occupation') && $request->hasoccupation == 1) ? $request->worksInClosedSetting : 'NO',
					'occupation_lotbldg' => ($request->filled('occupation_lotbldg') && $request->hasoccupation == 1) ? strtoupper($request->occupation_lotbldg) : NULL,
					'occupation_street' => ($request->filled('occupation_street') && $request->hasoccupation == 1) ? strtoupper($request->occupation_street) : NULL,
					'occupation_brgy' => ($request->filled('occupation_brgy') && $request->hasoccupation == 1) ? strtoupper($request->occupation_brgy) : NULL,
					'occupation_city' => ($request->filled('occupation_city') && $request->hasoccupation == 1) ? strtoupper($request->occupation_city) : NULL,
					'occupation_cityjson' => ($request->hasoccupation == 1) ? $request->occupation_cityjson : NULL,
					'occupation_province' => ($request->filled('occupation_province') && $request->hasoccupation == 1) ? strtoupper($request->occupation_province) : NULL,
					'occupation_provincejson' => ($request->hasoccupation == 1) ? $request->occupation_provincejson : NULL,
					'occupation_name' => ($request->filled('occupation_name') && $request->hasoccupation == 1) ? strtoupper($request->occupation_name) : NULL,
					'occupation_mobile' => ($request->hasoccupation == 1) ? $request->occupation_mobile : NULL,
					'occupation_email' => ($request->hasoccupation == 1) ? $request->occupation_email : NULL,
				]);
			}
			else {
				$list = Companies::find(auth()->user()->company_id);

				$request->user()->records()->create([
					'status' => 'approved',
					'lname' => mb_strtoupper($request->lname),
					'fname' => mb_strtoupper($request->fname),
					'mname' => ($request->filled('mname') && mb_strtoupper($request->mname) != "N/A") ? mb_strtoupper($request->mname) : null,
					'gender' => strtoupper($request->gender),
					'isPregnant' => $isPregnant,
					'cs' => strtoupper($request->cs),
					'nationality' => strtoupper($request->nationality),
					'bdate' => $request->bdate,
					'mobile' => $request->mobile,
					'phoneno' => ($request->filled('phoneno')) ? $request->phoneno : NULL,
					'email' => $request->email,
					'philhealth' => $philhealth_organized,
					'address_houseno' => strtoupper($request->address_houseno),
					'address_street' => strtoupper($request->address_street),
					'address_brgy' => strtoupper($request->address_brgy),
					'address_city' => strtoupper($request->address_city),
					'address_cityjson' => $request->address_cityjson,
					'address_province' => strtoupper($request->address_province),
					'address_provincejson' => $request->address_provincejson,
		
					'permaaddressDifferent' => $request->paddressdifferent,
					'permaaddress_houseno' => strtoupper($paddress_houseno),
					'permaaddress_street' => strtoupper($paddress_street),
					'permaaddress_brgy' => strtoupper($paddress_brgy),
					'permaaddress_city' => strtoupper($paddress_city),
					'permaaddress_cityjson' => $paddress_cityjson,
					'permaaddress_province' => strtoupper($paddress_province),
					'permaaddress_provincejson' => $paddress_provincejson,
					'permamobile' => $pmobile,
					'permaphoneno' => $pphoneno,
					'permaemail' => $pemail,
		
					'hasOccupation' => 1,
					'occupation' => strtoupper($request->occupation),
					'worksInClosedSetting' => $request->worksInClosedSetting,
					'occupation_lotbldg' => $list->loc_lotbldg,
					'occupation_street' => $list->loc_street,
					'occupation_brgy' => $list->loc_brgy,
					'occupation_city' => $list->loc_city,
					'occupation_cityjson' => $list->loc_cityjson,
					'occupation_province' => $list->loc_province,
					'occupation_provincejson' => $list->loc_provincejson,
					'occupation_name' => $list->companyName,
					'occupation_mobile' => $list->contactNumber,
					'occupation_email' => $list->email,
				]);
			}
			
	
			return redirect()->action([RecordsController::class, 'index'])->with('status', 'User information has been added successfully.')->with('statustype', 'success');
		}
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
		if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
			if(!is_null(auth()->user()->brgy_id)) {
				$record = Records::with('user')
				->where('id', $id)
				->whereHas('user', function ($query) {
					$query->where('brgy_id', auth()->user()->brgy_id);
				})->first();
			}
			else {
				$record = Records::with('user')
				->where('id', $id)
				->whereHas('user', function ($query) {
					$query->where('company_id', auth()->user()->company_id);
				})->first();
			}
		}
		else {
			$record = Records::findOrFail($id);
		}

		if($record) {
			return view('recordsedit', ['record' => $record]);
		}
		else {
			return redirect()->action([RecordsController::class, 'index'])->with('status', 'You are not allowed to do that.')->with('statustype', 'warning');
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecordValidationRequest $request, $id)
    {
		$request->validated();

		if($request->paddressdifferent == 1) {
			$paddress_houseno = $request->permaaddress_houseno;
			$paddress_street = $request->permaaddress_street;
			$paddress_brgy = $request->permaaddress_brgy;
			$paddress_city = $request->permaaddress_city;
			$paddress_cityjson = $request->permaaddress_cityjson;
			$paddress_province = $request->permaaddress_province;
			$paddress_provincejson = $request->permaaddress_provincejson;
			$pmobile = $request->permamobile;
			$pphoneno = $request->permaphoneno;
			$pemail = $request->permaemail;
		}
		else {
			$paddress_houseno = $request->address_houseno;
			$paddress_street = $request->address_street;
			$paddress_brgy = $request->address_brgy;
			$paddress_city = $request->address_city;
			$paddress_cityjson = $request->address_cityjson;
			$paddress_province = $request->address_province;
			$paddress_provincejson = $request->address_provincejson;
			$pmobile = $request->mobile;
			$pphoneno = ($request->filled('phoneno')) ? $request->phoneno : NULL;
			$pemail = $request->email;
		}

		if($request->gender == 'Male') {
			$isPregnant = 0;
		}
		else {
			$isPregnant = $request->pregnant;
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

        $record = Records::where('id', $id)->update([
			'lname' => mb_strtoupper($request->lname),
			'fname' => mb_strtoupper($request->fname),
			'mname' => $request->filled('mname') ? mb_strtoupper($request->mname) : NULL,
			'gender' => strtoupper($request->gender),
			'isPregnant' => $isPregnant,
			'cs' => strtoupper($request->cs),
			'nationality' => strtoupper($request->nationality),
			'bdate' => $request->bdate,
			'mobile' => $request->mobile,
			'phoneno' => ($request->filled('phoneno')) ? $request->phoneno : NULL,
			'email' => $request->email,
			'philhealth' => $philhealth_organized,
			'address_houseno' => strtoupper($request->address_houseno),
			'address_street' => strtoupper($request->address_street),
			'address_brgy' => strtoupper($request->address_brgy),
			'address_city' => strtoupper($request->address_city),
			'address_cityjson' => $request->address_cityjson,
			'address_province' => strtoupper($request->address_province),
			'address_provincejson' => $request->address_provincejson,

			'permaaddressDifferent' => $request->paddressdifferent,
			'permaaddress_houseno' => strtoupper($paddress_houseno),
			'permaaddress_street' => strtoupper($paddress_street),
			'permaaddress_brgy' => strtoupper($paddress_brgy),
			'permaaddress_city' => strtoupper($paddress_city),
			'permaaddress_cityjson' => $paddress_cityjson,
			'permaaddress_province' => strtoupper($paddress_province),
			'permaaddress_provincejson' => $paddress_provincejson,
			'permamobile' => $pmobile,
			'permaphoneno' => $pphoneno,
			'permaemail' => $pemail,

			'hasOccupation' => $request->hasoccupation,
			'occupation' => ($request->filled('occupation') && $request->hasoccupation == 1) ? strtoupper($request->occupation) : NULL,
			'worksInClosedSetting' => ($request->filled('occupation') && $request->hasoccupation == 1) ? $request->worksInClosedSetting : 'NO',
			'occupation_lotbldg' => ($request->filled('occupation_lotbldg') && $request->hasoccupation == 1) ? strtoupper($request->occupation_lotbldg) : NULL,
			'occupation_street' => ($request->filled('occupation_street') && $request->hasoccupation == 1) ? strtoupper($request->occupation_street) : NULL,
			'occupation_brgy' => ($request->filled('occupation_brgy') && $request->hasoccupation == 1) ? strtoupper($request->occupation_brgy) : NULL,
			'occupation_city' => ($request->filled('occupation_city') && $request->hasoccupation == 1) ? strtoupper($request->occupation_city) : NULL,
			'occupation_cityjson' => ($request->hasoccupation == 1) ? $request->occupation_cityjson : NULL,
			'occupation_province' => ($request->filled('occupation_province') && $request->hasoccupation == 1) ? strtoupper($request->occupation_province) : NULL,
			'occupation_provincejson' => ($request->hasoccupation == 1) ? $request->occupation_provincejson : NULL,
			'occupation_name' => ($request->filled('occupation_name') && $request->hasoccupation == 1) ? strtoupper($request->occupation_name) : NULL,
			'occupation_mobile' => ($request->hasoccupation == 1) ? $request->occupation_mobile : NULL,
			'occupation_email' => ($request->hasoccupation == 1) ? $request->occupation_email : NULL,
			]);

			return redirect()->action([RecordsController::class, 'index'])->with('status', 'User information has been updated successfully.')->with('statustype', 'success');
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
