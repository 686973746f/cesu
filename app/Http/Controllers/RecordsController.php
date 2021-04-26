<?php

namespace App\Http\Controllers;

use App\Models\Records;
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

		$records = Records::orderBy('lname','asc')->get();
		
        return view ('records', ['records'=>$records]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('addrecord');
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
			$pphoneno = $request->phoneno;
			$pemail = $request->email;
		}

		if($request->gender == 'Male') {
			$isPregnant = 0;
		}
		else {
			$isPregnant = $request->pregnant;
		}

		$request->user()->records()->create([
			'lname' => strtoupper($request->lname),
			'fname' => strtoupper($request->fname),
			'mname' => strtoupper($request->mname),
			'gender' => strtoupper($request->gender),
			'isPregnant' => $isPregnant,
			'cs' => strtoupper($request->cs),
			'nationality' => strtoupper($request->nationality),
			'bdate' => $request->bdate,
			'mobile' => $request->mobile,
			'phoneno' => $request->phoneno,
			'email' => $request->email,
			'philhealth' => $request->philhealth,
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

		return redirect()->action([RecordsController::class, 'index'])->with('status', 'User information has been created successfully.')->with('statustype', 'success');
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
        $record = Records::findOrFail($id);

		return view('recordsedit', ['record' => $record]);
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
			$pphoneno = $request->phoneno;
			$pemail = $request->email;
		}

		if($request->gender == 'Male') {
			$isPregnant = 0;
		}
		else {
			$isPregnant = $request->pregnant;
		}

        $record = Records::where('id', $id)->update([
			'lname' => strtoupper($request->lname),
			'fname' => strtoupper($request->fname),
			'mname' => $request->filled('mname') ? strtoupper($request->mname) : NULL,
			'gender' => strtoupper($request->gender),
			'isPregnant' => $isPregnant,
			'cs' => strtoupper($request->cs),
			'nationality' => strtoupper($request->nationality),
			'bdate' => $request->bdate,
			'mobile' => $request->mobile,
			'phoneno' => $request->phoneno,
			'email' => $request->email,
			'philhealth' => $request->philhealth,
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
