<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Forms;
use App\Models\Records;
use App\Models\Companies;
use Illuminate\Http\Request;
use App\Models\PaSwabDetails;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RecordValidationRequest;

class RecordsController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		if(request()->input('q')) {
			if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
				if(!is_null(auth()->user()->brgy_id)) {
					$records = Records::with('user')
					->where(function ($q) {
						$q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
						->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
						->orWhere('id', request()->input('q'));
					})
					->where(function($sq) {
						$sq->whereHas('user', function($q) {
							$q->where('brgy_id', auth()->user()->brgy_id)
							->orWhere('sharedOnId', 'LIKE', '%'.auth()->user()->id);
						})
						->orWhere(function ($q) {
							$q->where('address_province', auth()->user()->brgy->city->province->provinceName)
							->where('address_city', auth()->user()->brgy->city->cityName)
							->where('address_brgy', auth()->user()->brgy->brgyName);
						});
					})
					->orderByRaw('lname ASC, fname ASC, mname ASC')->paginate(10);
				}
				else {
					$records = Records::with('user')
					->where(function ($q) {
						$q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
						->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
						->orWhere('id', request()->input('q'));
					})->whereHas('user', function ($query) {
						$query->where('company_id', auth()->user()->company_id)
						->orWhere('sharedOnId', 'LIKE', '%'.auth()->user()->id);
					})
					->orderByRaw('lname ASC, fname ASC, mname ASC')->paginate(10);
				}
			}
			else {
				$records = Records::where(function ($q) {
					$q->where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
					->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',mb_strtoupper(request()->input('q')))."%")
					->orWhere('id', request()->input('q'));
				})
				->orderByRaw('lname ASC, fname ASC, mname ASC')
				->paginate(10);
			}
		}
		else {
			if(!is_null(auth()->user()->brgy_id) || !is_null(auth()->user()->company_id)) {
				if(!is_null(auth()->user()->brgy_id)) {
					$records = Records::with('user')
					->where(function($sq) {
						$sq->whereHas('user', function($q) {
							$q->where('brgy_id', auth()->user()->brgy_id)
							->orWhere('sharedOnId', 'LIKE', '%'.auth()->user()->id);
						})
						->orWhere(function ($q) {
							$q->where('address_province', auth()->user()->brgy->city->province->provinceName)
							->where('address_city', auth()->user()->brgy->city->cityName)
							->where('address_brgy', auth()->user()->brgy->brgyName);
						});
					})
					->orderByRaw('lname ASC, fname ASC, mname ASC')->paginate(10);
				}
				else {
					$records = Records::with('user')
					->whereHas('user', function($q) {
						$q->where('company_id', auth()->user()->company_id)
						->orWhere('sharedOnId', 'LIKE', '%'.auth()->user()->id);
					})
					->orderByRaw('lname ASC, fname ASC, mname ASC')->paginate(10);
				}
			}
			else {
				$records = Records::orderByRaw('lname ASC, fname ASC, mname ASC')->paginate(10);
			}	
		}

        return view ('records', ['records' => $records]);
    }

	public function check(Request $request) {
		$request->validate([
			'lname' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50|not_in:NA,NONE,TEST',
    		'fname' => 'required|regex:/^[\pL\s\-]+$/u|min:2|max:50|not_in:NA,NONE,TEST',
    		'mname' => 'nullable|regex:/^[\pL\s\-]+$/u|min:2|max:50|not_in:NA,NONE,TEST',
			'bdate' => "required|date|before:tomorrow",
		]);

		$check1 = Records::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);
		$check2 = PaSwabDetails::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);

		if(!is_null($check1)) {
			$param1 = 1;
			$where = '(Existing in the Records Page)';
		}
		else {
			$param1 = 0;
		}

		if(!is_null($check2)) {
			$param2 = 1;
			$where = '(Existing in Pa-Swab Page, waiting for Approval)';
		}
		else {
			$param2 = 0;
		}

		if($param1 == 1 || $param2 == 1) {
			if($param1 == 1) {
				//get latest form
				$check3 = Forms::where('records_id', $check1->id)->orderBy('created_at', 'DESC')->first();

				if($check3) {
					//kung may existing CIF na
					return back()
					->withInput()
					->with('type', 'recordExisting')
					->with('status', 'Error: Record of '.$check1->getName().' (#'.$check1->id.') already exists in the Database.')
					->with('eligibleToEdit', Records::eligibleToUpdate($check1->id))
					->with('statustype', 'danger')
					->with('link', route('records.edit', ['record' => $check1->id]))
					->with('ciflink', route('forms.edit', ['form' => $check3->id]))
					->with('cifdetails', $check3);
				}
				else {
					return back()
					->withInput()
					->with('type', 'recordExisting')
					->with('status', 'Error: Record of '.$check1->getName().' (#'.$check1->id.') already exists in the Database.')
					->with('eligibleToEdit', Records::eligibleToUpdate($check1->id))
					->with('statustype', 'danger')
					->with('link', route('records.edit', ['record' => $check1->id]));
				}
			}
			else if($param2 == 1) {
				//Paswab Eligible to Edit Checking
				if(auth()->user()->isCesuAccount()) {
					$eligibleToEdit = true;
				}
				else {
					$eligibleToEdit = false;
				}

				return back()
				->withInput()
				->with('type', 'recordExisting')
				->with('status', 'Error: Record of '.$check2->getName().' (#'.$check2->id.') already exists in Pa-swab list.')
				->with('eligibleToEdit', $eligibleToEdit)
				->with('statustype', 'danger')
				->with('link', route('paswab.viewspecific', ['id' => $check2->id]));
			}
		}
		else {
			return redirect()->route('records.create', [
				'lname' => mb_strtoupper($request->lname),
				'fname' => mb_strtoupper($request->fname),
				'mname' => (!is_null($request->mname)) ? mb_strtoupper($request->mname) : NULL,
				'gender' => $request->gender,
				'bdate' => $request->bdate,
			]);
		}
	}

	public function duplicateCheckerDashboard() {
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		//Kailangan manggaling sa check function para gumana
		if(request()->input('lname') && request()->input('fname') && request()->input('bdate')) {
			$list = Companies::find(auth()->user()->company_id);
			
			return view ('addrecord', [
				'list' => $list,
				'lname' => mb_strtoupper(request()->input('lname')),
				'fname' => mb_strtoupper(request()->input('fname')),
				'mname' => (!is_null(request()->input('mname'))) ? mb_strtoupper(request()->input('mname')) : NULL,
				'bdate' => (request()->input('bdate')),
			]);
		}
		else {
			return redirect()->route('records.index')->with('status', 'You are not allowed to do that.')->with('statustype', 'warning');
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

		$check1 = Records::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);

		/*
		Checking Double Entry (Old Method)
		$check1 = Records::where('lname', mb_strtoupper($request->lname))
		->where('fname', mb_strtoupper($request->fname))
		->where(function ($query) use ($request) {
			$query->where('mname', mb_strtoupper($request->mname))
			->orWhereNull('mname');
		})->first();
		*/

		if(!is_null($check1)) {
			$param1 = 1;
			$where = '(Existing in the Records Page)';
		}
		else {
			$param1 = 0;
		}

		$check2 = PaSwabDetails::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);

		/*
		Checking Double Entry sa Paswab (Old Method)
		$check2 = PaSwabDetails::where('lname', mb_strtoupper($request->lname))
		->where('fname', mb_strtoupper($request->fname))
		->where(function ($query) use ($request) {
			$query->where('mname', mb_strtoupper($request->mname))
			->orWhereNull('mname');
		})->where('status', 'pending')
		->first();
		*/

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
			->with('msg', $msg)
			->with('where', $where);
		}
		else {
			if(auth()->user()->isCompanyAccount()) {
				$list = Companies::find(auth()->user()->company_id);

				$hasOccupation = 1;
				$occupation_lotbldg = $list->loc_lotbldg;
				$occupation_street = $list->loc_street;
				$occupation_brgy = $list->loc_brgy;
				$occupation_city = $list->loc_city;
				$occupation_cityjson = $list->loc_cityjson;
				$occupation_province = $list->loc_province;
				$occupation_provincejson = $list->loc_provincejson;
				$occupation_name = $list->companyName;
				$occupation_mobile = $list->contactNumber;
				$occupation_email = $list->email;
			}
			else {
				$hasOccupation = $request->hasoccupation;
				$occupation_lotbldg = ($request->filled('occupation_lotbldg') && $request->hasoccupation == 1) ? strtoupper($request->occupation_lotbldg) : NULL;
				$occupation_street = ($request->filled('occupation_street') && $request->hasoccupation == 1) ? strtoupper($request->occupation_street) : NULL;
				$occupation_brgy = ($request->filled('occupation_brgy') && $request->hasoccupation == 1) ? strtoupper($request->occupation_brgy) : NULL;
				$occupation_city = ($request->filled('occupation_city') && $request->hasoccupation == 1) ? strtoupper($request->occupation_city) : NULL;
				$occupation_cityjson = ($request->hasoccupation == 1) ? $request->occupation_cityjson : NULL;
				$occupation_province = ($request->filled('occupation_province') && $request->hasoccupation == 1) ? strtoupper($request->occupation_province) : NULL;
				$occupation_provincejson = ($request->hasoccupation == 1) ? $request->occupation_provincejson : NULL;
				$occupation_name = ($request->filled('occupation_name') && $request->hasoccupation == 1) ? strtoupper($request->occupation_name) : NULL;
				$occupation_mobile = ($request->hasoccupation == 1) ? $request->occupation_mobile : NULL;
				$occupation_email = ($request->hasoccupation == 1) ? $request->occupation_email : NULL;
			}

			$data = $request->user()->records()->create([
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
				'philhealth' => $request->philhealth,
				'address_houseno' => mb_strtoupper($request->address_houseno),
				'address_street' => mb_strtoupper($request->address_street),
				'address_brgy' => mb_strtoupper($request->address_brgy),
				'address_city' => mb_strtoupper($request->address_city),
				'address_cityjson' => $request->address_cityjson,
				'address_province' => mb_strtoupper($request->address_province),
				'address_provincejson' => $request->address_provincejson,
	
				'permaaddressDifferent' => $request->paddressdifferent,
				'permaaddress_houseno' => mb_strtoupper($paddress_houseno),
				'permaaddress_street' => mb_strtoupper($paddress_street),
				'permaaddress_brgy' => mb_strtoupper($paddress_brgy),
				'permaaddress_city' => mb_strtoupper($paddress_city),
				'permaaddress_cityjson' => $paddress_cityjson,
				'permaaddress_province' => mb_strtoupper($paddress_province),
				'permaaddress_provincejson' => $paddress_provincejson,
				'permamobile' => $pmobile,
				'permaphoneno' => $pphoneno,
				'permaemail' => $pemail,
	
				'hasOccupation' => $hasOccupation,
				'occupation' => ($request->filled('occupation') && $request->hasoccupation == 1) ? strtoupper($request->occupation) : NULL,
				'worksInClosedSetting' => ($request->filled('occupation') && $request->hasoccupation == 1) ? $request->worksInClosedSetting : 'NO',
				'occupation_lotbldg' => $occupation_lotbldg,
				'occupation_street' => $occupation_street,
				'occupation_brgy' => $occupation_brgy,
				'occupation_city' => $occupation_city,
				'occupation_cityjson' => $occupation_cityjson,
				'occupation_province' => $occupation_province,
				'occupation_provincejson' => $occupation_provincejson,
				'occupation_name' => $occupation_name,
				'occupation_mobile' => $occupation_mobile,
				'occupation_email' => $occupation_email,

				'natureOfWork' => ($request->hasoccupation == 1) ? mb_strtoupper($request->natureOfWork) : NULL,
				'natureOfWorkIfOthers' => ($request->hasoccupation == 1 && $request->natureOfWork == 'OTHERS') ? mb_strtoupper($request->natureOfWorkIfOthers) : NULL,

				'vaccinationDate1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? $request->vaccinationDate1 : NULL,
				'haveAdverseEvents1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? $request->haveAdverseEvents1 : NULL,
				'vaccinationName1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? $request->vaccineName : NULL,
				'vaccinationNoOfDose1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? 1 : NULL,
				'vaccinationFacility1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationFacility1) : NULL,
				'vaccinationRegion1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationRegion1) : NULL,

				'vaccinationDate2' => ($request->howManyDoseVaccine == 2) ? $request->vaccinationDate2 : NULL,
				'haveAdverseEvents2' => ($request->howManyDoseVaccine == 2) ? $request->haveAdverseEvents2 : NULL,
				'vaccinationName2' => ($request->howManyDoseVaccine == 2) ? $request->vaccineName : NULL,
				'vaccinationNoOfDose2' => ($request->howManyDoseVaccine == 2) ? 2 : NULL,
				'vaccinationFacility2' => ($request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationFacility2) : NULL,
				'vaccinationRegion2' => ($request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationRegion2) : NULL,
			]);
			
			if(auth()->user()->option_enableAutoRedirectToCif == 1) {
				return redirect()->route('forms.new', ['id' => $data->id]);
			}
			else {
				return redirect()->action([RecordsController::class, 'index'])
				->with('status', 'User information has been added successfully.')
				->with('type', 'createRecord')
				->with('newid', $data->id)
				->with('statustype', 'success');
			}
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
		if(Records::eligibleToUpdate($id)) {
			$record = Records::findOrFail($id);
			$cifcheck = Forms::where('records_id', $record->id)->orderBy('created_at', 'DESC')->first();

			//Vaccination Details
            if(!is_null($record->vaccinationDate2) || !is_null($record->vaccinationDate1)) {
                if(!is_null($record->vaccinationDate2)) {
                    $vaccineDose = 2;
                }
                else {
                    $vaccineDose = 1;
                }
            }
            else {
                $vaccineDose = NULL;
            }

			$sharedAccessList = User::where('enabled', 1)
			->where('isAdmin', 0)
			->where('id', '!=', auth()->user()->id)
			->get();

			return view('recordsedit', [
				'record' => $record,
				'cifcheck' =>$cifcheck,
				'vaccineDose' => $vaccineDose,
				'sharedAccessList' => $sharedAccessList,
			]);
		}
		else {
			return redirect()->route('records.index')->with('status', 'You are not allowed to do that.')->with('statustype', 'warning');
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
		if(Records::eligibleToUpdate($id)) {
			$current = Records::findOrFail($id);

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
	
			/*
			//auto dashes in philhealth
			if($request->filled('philhealth')) {
				if(strpos($request->philhealth, '-') !== false && substr($request->philhealth, -2, 1) == "-" && substr($request->philhealth, -12, 1) == "-") {
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
	
			$old_philhealth = Records::where('id', $id)->pluck('philhealth')->first();

			//para sa auto time kapag late nang nilagyan ng philhealth ang pasyente
			if(is_null($old_philhealth) && $request->filled('philhealth')) {
				$form = Forms::where('records_id', $id)->first();

				if($form) {
					if(!is_null($form->testType2)) {
						if($form->testType2 == "OPS" || $form->testType2 == "NPS" || $request->testType2 == "OPS AND NPS") {
							if(is_null($form->oniTimeCollected2)) {
								$trigger = 0;
								$addMinutes = 0;

								while ($trigger != 1) {
									$oniStartTime = date('H:i:s', strtotime('14:00:00 + '. $addMinutes .' minutes'));

									$query = Forms::with('records')
									->where('testDateCollected2', $form->testDateCollected2)
									->whereIn('testType2', ['OPS', 'NPS', 'OPS AND NPS'])
									->whereHas('records', function ($q) {
										$q->whereNotNull('philhealth');
									})
									->where('oniTimeCollected2', $oniStartTime)->get();

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

								$update = Forms::where('id', $form->id)->update([
									'oniTimeCollected2' => $oniTimeFinal,
								]);
							}
						}
					}

					if($form->testType1 == "OPS" || $form->testType1 == "NPS" || $request->testType1 == "OPS AND NPS") {
						if(is_null($form->oniTimeCollected1)) {
							$trigger = 0;
							$addMinutes = 0;

							while ($trigger != 1) {
								$oniStartTime = date('H:i:s', strtotime('14:00:00 + '. $addMinutes .' minutes'));

								$query = Forms::with('records')
								->where('testDateCollected1', $form->testDateCollected1)
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

							$update = Forms::where('id', $form->id)->update([
								'oniTimeCollected1' => $oniTimeFinal,
							]);
						}	
					}
				}
			}

			$current->lname = mb_strtoupper($request->lname);
			$current->fname = mb_strtoupper($request->fname);
			$current->mname = (!is_null($request->mname)) ? mb_strtoupper($request->mname) : NULL;

			if($current->isDirty('lname') || $current->isDirty('fname') || $current->isDirty('mname')) {
				$check1 = Records::detectChangeName($request->lname, $request->fname, $request->mname, $request->bdate, $id);
				$check2 = PaSwabDetails::ifDuplicateFound($request->lname, $request->fname, $request->mname, $request->bdate);

				if(!is_null($check1)) {
					$param1 = 1;
					$where = '(Existing in the Records Page)';
				}
				else {
					$param1 = 0;
				}
				
				if(!is_null($check2)) {
					$param2 = 1;
					$where = '(Existing in Pa-Swab Page, waiting for Approval)';
				}
				else {
					$param2 = 0;
				}
			}
			else {
				$param1 = 0;
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
				->with('msg', $msg)
				->with('where', $where);
			}
			else {
				if(auth()->user()->isCompanyAccount()) {
					$list = Companies::find(auth()->user()->company_id);
	
					$hasOccupation = 1;
					$occupation_lotbldg = $list->loc_lotbldg;
					$occupation_street = $list->loc_street;
					$occupation_brgy = $list->loc_brgy;
					$occupation_city = $list->loc_city;
					$occupation_cityjson = $list->loc_cityjson;
					$occupation_province = $list->loc_province;
					$occupation_provincejson = $list->loc_provincejson;
					$occupation_name = $list->companyName;
					$occupation_mobile = $list->contactNumber;
					$occupation_email = $list->email;
				}
				else {
					$hasOccupation = $request->hasoccupation;
					$occupation_lotbldg = ($request->filled('occupation_lotbldg') && $request->hasoccupation == 1) ? strtoupper($request->occupation_lotbldg) : NULL;
					$occupation_street = ($request->filled('occupation_street') && $request->hasoccupation == 1) ? strtoupper($request->occupation_street) : NULL;
					$occupation_brgy = ($request->filled('occupation_brgy') && $request->hasoccupation == 1) ? strtoupper($request->occupation_brgy) : NULL;
					$occupation_city = ($request->filled('occupation_city') && $request->hasoccupation == 1) ? strtoupper($request->occupation_city) : NULL;
					$occupation_cityjson = ($request->hasoccupation == 1) ? $request->occupation_cityjson : NULL;
					$occupation_province = ($request->filled('occupation_province') && $request->hasoccupation == 1) ? strtoupper($request->occupation_province) : NULL;
					$occupation_provincejson = ($request->hasoccupation == 1) ? $request->occupation_provincejson : NULL;
					$occupation_name = ($request->filled('occupation_name') && $request->hasoccupation == 1) ? strtoupper($request->occupation_name) : NULL;
					$occupation_mobile = ($request->hasoccupation == 1) ? $request->occupation_mobile : NULL;
					$occupation_email = ($request->hasoccupation == 1) ? $request->occupation_email : NULL;
				}
				
				$record = Records::where('id', $id)->update([
					'updated_by' => auth()->user()->id,
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
	
					'hasOccupation' => $hasOccupation,
					'occupation' => ($request->filled('occupation') && $request->hasoccupation == 1) ? strtoupper($request->occupation) : NULL,
					'worksInClosedSetting' => ($request->filled('occupation') && $request->hasoccupation == 1) ? $request->worksInClosedSetting : 'NO',
					'occupation_lotbldg' => $occupation_lotbldg,
					'occupation_street' => $occupation_street,
					'occupation_brgy' => $occupation_brgy,
					'occupation_city' => $occupation_city,
					'occupation_cityjson' => $occupation_cityjson,
					'occupation_province' => $occupation_province,
					'occupation_provincejson' => $occupation_provincejson,
					'occupation_name' => $occupation_name,
					'occupation_mobile' => $occupation_mobile,
					'occupation_email' => $occupation_email,
	
					'natureOfWork' => ($request->hasoccupation == 1) ? mb_strtoupper($request->natureOfWork) : NULL,
					'natureOfWorkIfOthers' => ($request->hasoccupation == 1 && $request->natureOfWork == 'OTHERS') ? mb_strtoupper($request->natureOfWorkIfOthers) : NULL,
	
					'vaccinationDate1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? $request->vaccinationDate1 : NULL,
					'haveAdverseEvents1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? $request->haveAdverseEvents1 : NULL,
					'vaccinationName1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? $request->vaccineName : NULL,
					'vaccinationNoOfDose1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? 1 : NULL,
					'vaccinationFacility1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationFacility1) : NULL,
					'vaccinationRegion1' => ($request->howManyDoseVaccine == 1 || $request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationRegion1) : NULL,
	
					'vaccinationDate2' => ($request->howManyDoseVaccine == 2) ? $request->vaccinationDate2 : NULL,
					'haveAdverseEvents2' => ($request->howManyDoseVaccine == 2) ? $request->haveAdverseEvents2 : NULL,
					'vaccinationName2' => ($request->howManyDoseVaccine == 2) ? $request->vaccineName : NULL,
					'vaccinationNoOfDose2' => ($request->howManyDoseVaccine == 2) ? 2 : NULL,
					'vaccinationFacility2' => ($request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationFacility2) : NULL,
					'vaccinationRegion2' => ($request->howManyDoseVaccine == 2) ? mb_strtoupper($request->vaccinationRegion2) : NULL,
	
					'sharedOnId' => (!is_null($request->sharedOnId)) ? implode(",", $request->sharedOnId) : NULL,
				]);
	
				$record = Records::findOrFail($id);
	
				if(request()->input('fromFormsPage') == 'true') {
					$update = Forms::where('records_id', $record->id)->update([
						'isExported' => 0,
						'exportedDate' => NULL,
					]);
	
					return redirect()->action([FormsController::class, 'index'])->with('status', 'CIF for '.$record->getName()." has been updated successfully.")->with('statustype', 'success');
				}
				else {
					return redirect()->action([RecordsController::class, 'index'])->with('status', 'Patient details of '.$record->getName().' has been updated successfully.')->with('statustype', 'success');
				}
			}
		}
		else {
			return redirect()->route('records.index')->with('status', 'You are not allowed to do that.')->with('statustype', 'warning');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Records $record)
    {
		if(auth()->user()->isAdmin == 1) {
			$record->delete();

			return redirect()->action([RecordsController::class, 'index'])->with('status', 'Patient details of '.$record->getName().' has been deleted successfully.')->with('statustype', 'success');
		}
		else {
			return back()
			->withInput()
			->with('msg', 'You are not allowed to do that.');
		}
    }
}
