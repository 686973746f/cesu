@extends('layouts.app')

@section('content')
    <div class="container">
        @if(auth()->user()->isAdmin == 1)
        <form action="/records/{{$record->id}}" method="POST">
            @csrf
            @method('delete')
            <div class="text-right mb-3">
                <button type="submit" class="btn btn-danger" onclick="return confirm('This will also delete the CIF associated with this record. You cannot undo once the process is done. Are you sure you want to DELETE? Click OK to Confirm.')"><i class="fa fa-trash mr-2" aria-hidden="true"></i>Delete Patient Record</button>
            </div>
        </form>
        @endif

        <form action="{{route('records.update', $record->id)}}{{(request()->input('fromFormsPage') == 'true') ? '?fromFormsPage=true' : ''}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header font-weight-bold text-info">
                    Edit Patient Information
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <p>{{Str::plural('Error', $errors->count())}} detected in updating the patient record:</p>
                        <hr>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </div>
                    @endif
                    @if(session('msg'))
					<div class="alert alert-danger" role="alert">
						{{session('msg')}}
					</div>
				    @endif
                    @if($de_list->count() != 0)
                    <div class="alert alert-warning" role="alert">
                        <h5><b>Warning: Possible Duplicate Entries Detected</b></h5>
                        <p>Please see and check carefully the patient list below:</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Age/Gender</th>
                                        <th scope="col">Birthdate</th>
                                        <th scope="col">Mobile Number</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Date Encoded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($de_list as $d)
                                    <tr class="">
                                        <td><a href="{{route('records.edit', ['record' => $d->id])}}">#{{$d->id}}</a></td>
                                        <td>{{$d->getName()}}</td>
                                        <td>{{$d->getAge()}}/{{substr($d->gender,0,1)}}</td>
                                        <td>{{date('m/d/Y', strtotime($d->bdate))}}</td>
                                        <td>{{$d->mobile}}</td>
                                        <td><small>{{$d->getAddress()}}</small></td>
                                        <td>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p>If the new patient was one on the list, do not proceed on encoding and click the existing patient name on the list.</p>
                        <p>If NOT, ignore the warning and continue encoding.</p>
                    </div>
                    @endif
                    @if($cifcheck)
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('forms.edit', ['form' => $cifcheck->id])}}" role="button"><i class="fa fa-file mr-2" aria-hidden="true"></i>View Existing CIF of Patient</a>
                    @endif
                    <hr>
                    <h5 class="font-weight-bold">Patient Information</h5>
                    <hr>
                    <div class="alert alert-info" role="alert">
                        Note: All fields marked with an asterisk (<span class="text-danger font-weight-bold">*</span>) are required.
                    </div>
                    <div class="form-group">
                      <label for="">Patient ID</label>
                      <input type="text" class="form-control" value="#{{$record->id}}" readonly>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="bg-light">Encoded By / Date</td>
                                    <td class="text-center">{{$record->user->name}} ({{date("m/d/Y h:i A - l", strtotime($record->created_at))}})</td>
                                </tr>
                                @if(!is_null($record->updated_by))
                                <tr>
                                    <td class="bg-light">Edited By / Date</td>
                                    <td class="text-center">{{$record->getEditedBy()}} ({{date("m/d/Y h:i A - l", strtotime($record->updated_at))}})</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control @error('lname') border-danger @enderror" id="lname" name="lname" value="{{old('lname', $record->lname)}}" style="text-transform: uppercase;" max="50" required>
                                @error('lname')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name (and Suffix)</label>
                                <input type="text" class="form-control @error('fname') border-danger @enderror" id="fname" name="fname" value="{{old('fname', $record->fname)}}" style="text-transform: uppercase;" max="50" required>
                                @error('fname')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mname">Middle Name <small><i>(Leave blank if N/A)</i></small></label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname', $record->mname)}}" style="text-transform: uppercase;" max="50">
                                @error('mname')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $record->bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                @error('bdate')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            @php
                            if(\Carbon\Carbon::parse(old('bdate', $record->bdate))->age > 0) {
                                $getAge = \Carbon\Carbon::parse(old('bdate', $record->bdate))->age.' Y';
                            }
                            else {
                                if (\Carbon\Carbon::parse(old('bdate', $record->bdate))->diff(\Carbon\Carbon::now())->format('%m') == 0) {
                                    $getAge = \Carbon\Carbon::parse(old('bdate', $record->bdate))->diff(\Carbon\Carbon::now())->format('%d D');
                                }
                                else {
                                    $getAge = \Carbon\Carbon::parse(old('bdate', $record->bdate))->diff(\Carbon\Carbon::now())->format('%m M');
                                }
                            }
                            @endphp
                            <div class="form-group">
                                <label><span class="text-danger font-weight-bold">*</span>Age</label>
                                <input type="text" class="form-control" value="{{$getAge}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Gender</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="" disabled {{(is_null(old('gender', $record->gender))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="MALE" {{(old('gender', $record->gender) == 'MALE') ? 'selected' : ''}}>Male</option>
                                    <option value="FEMALE" {{(old('gender', $record->gender) == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                </select>
                                <div id="pdiv" class="d-none">
                                    <div class="form-group">
                                        <label for="pregnant"><span class="text-danger font-weight-bold">*</span>Is the Patient Pregnant?</label>
                                        <select class="form-control" name="pregnant" id="pregnant" required>
                                          <option value="0" {{(old('pregnant', $record->isPregnant) == 0) ? 'selected' : ''}}>No</option>
                                          <option value="1" {{(old('pregnant', $record->isPregnant) == 1) ? 'selected' : ''}}>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cs"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                <select class="form-control" id="cs" name="cs" required>
                                    <option value="" disabled {{(is_null(old('cs', $record->cs))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="SINGLE" {{(old('cs', $record->cs) == 'SINGLE') ? 'selected' : ''}}>Single</option>
                                    <option value="MARRIED" {{(old('cs', $record->cs) == 'MARRIED') ? 'selected'  : ''}}>Married</option>
                                    <option value="WIDOWED" {{(old('cs', $record->cs) == 'WIDOWED') ? 'selected'  : ''}}>Widowed</option>
                                </select>
                                @error('cs')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="nationality"><span class="text-danger font-weight-bold">*</span>Nationality</label>
                                <select class="form-control" id="nationality" name="nationality" required>
                                    <option value="" disabled selected>Choose</option>
                                    <option value="FILIPINO" {{(old('nationality', $record->nationality) == 'FILIPINO') ? 'selected' : ''}}>Filipino</option>
                                    <option value="FOREIGN" {{(old('nationality', $record->nationality) == 'FOREIGN') ? 'selected' : ''}}>Foreign</option>
                                </select>
                                @error('nationality')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="isindg"><span class="text-danger font-weight-bold">*</span>Indigenous Person</label>
                                <select class="form-control" id="isindg" name="isindg" required>
                                    <option value="0" {{(old('isindg', $record->isindg) == '0') ? 'selected' : ''}}>No</option>
                                    <option value="1" {{(old('isindg', $record->isindg) == '1') ? 'selected' : ''}}>Yes</option>
                                </select>
                            </div>
                            <div id="div_indg" class="d-none">
                                <div class="form-group">
                                  <label for="indg_specify"><span class="text-danger font-weight-bold">*</span>Specify Group</label>
                                  <input type="text" class="form-control" name="indg_specify" id="indg_specify" style="text-transform: uppercase;" value="{{old('indg_specify', $record->indg_specify)}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mobile"><span class="text-danger font-weight-bold">*</span>Cellphone No.</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile', $record->mobile)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                @error('mobile')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="phoneno">Home Phone No. (& Area Code)</label>
                                <input type="text" class="form-control" id="phoneno" name="phoneno" value="{{old('phoneno', $record->phoneno)}}">
                                @error('phoneno')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" value="{{old('email', $record->email)}}">
                                @error('email')
                                      <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="philhealth">Philhealth No. <small><i>(Leave blank if N/A)</i></small></label>
                                <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth', $record->philhealth)}}" pattern="[0-9]{12}">
                                <small class="text-muted">Note: Please type the Complete Philhealth # (12 Digits, No Dashes)</small>
                                @error('philhealth')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h5 class="font-weight-bold">Current Address</h5>
                    <hr>
                    <div id="addresstext" class="d-none">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                  <input type="text" class="form-control" name="address_province" id="address_province" value="{{old('address_province', $record->address_province)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address_city" id="address_city" value="{{old('address_city', $record->address_city)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                  <input type="text" class="form-control" name="address_provincejson" id="address_provincejson" value="{{old('address_provincejson', $record->address_provincejson)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address_cityjson" id="address_cityjson" value="{{old('address_cityjson', $record->address_cityjson)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <strong class="text-danger">Note:</strong> Special Characters such as <strong>! @ # _ $ , . ( )</strong> etc. are not allowed to input in the adress bar. 
                    </div>
                    @if(auth()->user()->isBrgyAccount() && auth()->user()->brgy->displayInList == 1)
                    <div class="alert alert-info" role="alert">
                        <strong class="text-danger">Note:</strong> For encoding Patients residing from other Barangay, please transfer it with the respective Barangay properly for proper monitoring.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="saddress_province"><span class="text-danger font-weight-bold">*</span>Province</label>
                                <input type="text" class="form-control" name="saddress_province" id="saddress_province" value="{{($sameaddress) ? auth()->user()->brgy->city->province->provinceName : $record->address_province}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="saddress_city"><span class="text-danger font-weight-bold">*</span>City</label>
                                <input type="text" class="form-control" name="saddress_city" id="saddress_city" value="{{($sameaddress) ? auth()->user()->brgy->city->cityName : $record->address_city}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address_brgy"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                <input type="text" class="form-control" name="address_brgy" id="address_brgy" value="{{($sameaddress) ? auth()->user()->brgy->brgyName : $record->address_brgy}}" readonly>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="saddress_province"><span class="text-danger font-weight-bold">*</span>Province</label>
                                <select class="form-control" name="saddress_province" id="saddress_province" required>
                                  <option value="" selected disabled>Choose...</option>
                                </select>
                                    @error('saddress_province')
                                      <small class="text-danger">{{$message}}</small>
                                  @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="saddress_city"><span class="text-danger font-weight-bold">*</span>City</label>
                              <select class="form-control" name="saddress_city" id="saddress_city" required>
                                <option value="" selected disabled>Choose...</option>
                              </select>
                                @error('saddress_city')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="address_brgy"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                              <select class="form-control" name="address_brgy" id="address_brgy" required>
                                <option value="" selected disabled>Choose...</option>
                              </select>
                                  @error('address_brgy')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_houseno"><span class="text-danger font-weight-bold">*</span>House No./Lot/Building</label>
                                <input type="text" class="form-control" id="address_houseno" name="address_houseno" value="{{old('address_houseno', $record->address_houseno)}}" pattern="(^[a-zA-Z0-9 ]+$)+" style="text-transform: uppercase;" placeholder="ex. S1 B2 L3 PHASE 4 JUAN ST / #0465" required>
                                @error('address_houseno')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_street"><span class="text-danger font-weight-bold">*</span>Street/Subdivision/Purok/Sitio</label>
                                <input type="text" class="form-control" id="address_street" name="address_street" value="{{old('address_street', $record->address_street)}}" pattern="(^[a-zA-Z0-9 ]+$)+" minlength="4" style="text-transform: uppercase;" placeholder="ex. CAMELLA HOMES / PUROK 1 / SITIO LUNA" required>
                                @error('address_street')
                                    <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header"><i class="fas fa-syringe mr-2"></i>COVID-19 Vaccination Information</div>
                        <div class="card-body">
                            <div class="form-group">
                              <label for="howManyDoseVaccine"><span class="text-danger font-weight-bold">*</span>If vaccinated, how many dose?</label>
                              <select class="form-control" name="howManyDoseVaccine" id="howManyDoseVaccine">
                                <option value="" {{(is_null(old('howManyDoseVaccine', $vaccineDose))) ? 'selected' : ''}}>N/A</option>
                                <option value="1" {{(old('howManyDoseVaccine', $vaccineDose) == '1') ? 'selected' : ''}}>1st Dose only</option>
                                <option value="2" id="2ndDoseOption" {{(old('howManyDoseVaccine', $vaccineDose) == '2') ? 'selected' : ''}}>1st and 2nd Dose Completed</option>
                              </select>
                            </div>
                            <div id="ifVaccinated" class="d-none">
                                <div class="form-group">
                                  <label for="vaccineName"><span class="text-danger font-weight-bold">*</span>Name of Vaccine</label>
                                  <select class="form-control" name="vaccineName" id="vaccineName">
                                    <option value="" disabled {{is_null(old('vaccineName', $record->vaccinationName1)) ? 'selected' : ''}}>Choose...</option>
                                    <option value="BHARAT BIOTECH" {{(old('vaccineName', $record->vaccinationName1) == "BHARAT BIOTECH") ? 'selected' : ''}}>Bharat BioTech</option>
                                    <option value="GAMALEYA SPUTNIK V" {{(old('vaccineName', $record->vaccinationName1) == 'GAMALEYA SPUTNIK V') ? 'selected' : ''}}>Gamaleya Sputnik V</option>
                                    <option value="JANSSEN" {{(old('vaccineName', $record->vaccinationName1) == "JANSSEN") ? 'selected' : ''}}>Janssen</option>
                                    <option value="MODERNA" {{(old('vaccineName', $record->vaccinationName1) == 'MODERNA') ? 'selected' : ''}}>Moderna</option>
                                    <option value="NOVARAX" {{(old('vaccineName', $record->vaccinationName1) == 'NOVARAX') ? 'selected' : ''}}>Novarax</option>
                                    <option value="OXFORD ASTRAZENECA" {{(old('vaccineName', $record->vaccinationName1) == 'OXFORD ASTRAZENECA') ? 'selected' : ''}}>Oxford AstraZeneca</option>
                                    <option value="PFIZER BIONTECH" {{(old('vaccineName', $record->vaccinationName1) == 'PFIZER BIONTECH') ? 'selected' : ''}}>Pfizer BioNTech</option>
                                    <option value="SINOPHARM" {{(old('vaccineName', $record->vaccinationName1) == 'SINOPHARM') ? 'selected' : ''}}>Sinopharm</option>
                                    <option value="SINOVAC CORONAVAC" {{(old('vaccineName', $record->vaccinationName1) == 'SINOVAC CORONAVAC') ? 'selected' : ''}}>Sinovac Coronavac</option>
                                  </select>
                                  <small class="text-muted">Vaccine Name not Included in the List? You may contact CESU Staff.</small>
                                </div>
                                <hr>
                                <div id="ifFirstDoseVaccine" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationDate1"><span class="text-danger font-weight-bold">*</span>First (1st) Dose Date</label>
                                                <input type="date" class="form-control" name="vaccinationDate1" id="vaccinationDate1" value="{{old('vaccinationDate1', $record->vaccinationDate1)}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="haveAdverseEvents1"><span class="text-danger font-weight-bold">*</span>First Dose Adverse Event/s</label>
                                                <select class="form-control" name="haveAdverseEvents1" id="haveAdverseEvents1">
                                                    <option value="0" {{(old('haveAdverseEvents1', $record->haveAdverseEvents1) == '0') ? 'selected' : ''}}>No</option>
                                                    <option value="1" {{(old('haveAdverseEvents1', $record->haveAdverseEvents1) == '1') ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationFacility1">First Dose Vaccination Center/Facility <small>(Optional)</small></label>
                                                <input type="text" class="form-control" name="vaccinationFacility1" id="vaccinationFacility1" value="{{old('vaccinationFacility1', $record->vaccinationFacility1)}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationRegion1">First Dose Region of Health Facility <small>(Optional)</small></label>
                                                <input type="text" class="form-control" name="vaccinationRegion1" id="vaccinationRegion1" value="{{old('vaccinationRegion1', $record->vaccinationRegion1)}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="ifSecondDoseVaccine" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationDate2"><span class="text-danger font-weight-bold">*</span>Second (2nd) Dose Date</label>
                                                <input type="date" class="form-control" name="vaccinationDate2" id="vaccinationDate2" value="{{old('vaccinationDate2', $record->vaccinationDate2)}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="haveAdverseEvents2"><span class="text-danger font-weight-bold">*</span>Second Dose Adverse Event/s</label>
                                                <select class="form-control" name="haveAdverseEvents2" id="haveAdverseEvents2">
                                                    <option value="0" {{(old('haveAdverseEvents2', $record->haveAdverseEvents2) == '0') ? 'selected' : ''}}>No</option>
                                                    <option value="1" {{(old('haveAdverseEvents2', $record->haveAdverseEvents2) == '1') ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationFacility2">Second Dose Vaccination Center/Facility <small>(Optional)</small></label>
                                                <input type="text" class="form-control" name="vaccinationFacility2" id="vaccinationFacility2" value="{{old('vaccinationFacility2', $record->vaccinationFacility2)}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationRegion2">Second Dose Region of Health Facility <small>(Optional)</small></label>
                                                <input type="text" class="form-control" name="vaccinationRegion2" id="vaccinationRegion2" value="{{old('vaccinationRegion2', $record->vaccinationRegion2)}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="booster_question" class="d-none">
                                    <hr>
                                    <div class="form-group">
                                      <label for="haveBooster"><span class="text-danger font-weight-bold">*</span>Already Vaccinated with Booster Vaccine?</label>
                                      <select class="form-control" name="haveBooster" id="haveBooster" required>
                                        <option value="0" {{(old('haveBooster', $haveBooster) == '0') ? 'selected' : ''}}>No</option>
                                        <option value="1" {{(old('haveBooster', $haveBooster) == '1') ? 'selected' : ''}}>Yes</option>
                                      </select>
                                    </div>
                                </div>
                                <div id="ifBoosterVaccine" class="d-none">
                                    <div class="form-group">
                                        <label for="vaccinationName3"><span class="text-danger font-weight-bold">*</span>Booster Vaccine Name</label>
                                        <select class="form-control" name="vaccinationName3" id="vaccinationName3">
                                          <option value="" disabled {{is_null(old('vaccinationName3', $record->vaccinationName3)) ? 'selected' : ''}}>Choose...</option>
                                          <option value="BHARAT BIOTECH" {{(old('vaccinationName3', $record->vaccinationName3) == "BHARAT BIOTECH") ? 'selected' : ''}}>Bharat BioTech</option>
                                          <option value="GAMALEYA SPUTNIK V" {{(old('vaccinationName3', $record->vaccinationName3) == 'GAMALEYA SPUTNIK V') ? 'selected' : ''}}>Gamaleya Sputnik V</option>
                                          <option value="JANSSEN" {{(old('vaccinationName3', $record->vaccinationName3) == "JANSSEN") ? 'selected' : ''}}>Janssen</option>
                                          <option value="MODERNA" {{(old('vaccinationName3', $record->vaccinationName3) == 'MODERNA') ? 'selected' : ''}}>Moderna</option>
                                          <option value="NOVARAX" {{(old('vaccinationName3', $record->vaccinationName3) == 'NOVARAX') ? 'selected' : ''}}>Novarax</option>
                                          <option value="OXFORD ASTRAZENECA" {{(old('vaccinationName3', $record->vaccinationName3) == 'OXFORD ASTRAZENECA') ? 'selected' : ''}}>Oxford AstraZeneca</option>
                                          <option value="PFIZER BIONTECH" {{(old('vaccinationName3', $record->vaccinationName3) == 'PFIZER BIONTECH') ? 'selected' : ''}}>Pfizer BioNTech</option>
                                          <option value="SINOPHARM" {{(old('vaccinationName3', $record->vaccinationName3) == 'SINOPHARM') ? 'selected' : ''}}>Sinopharm</option>
                                          <option value="SINOVAC CORONAVAC" {{(old('vaccinationName3', $record->vaccinationName3) == 'SINOVAC CORONAVAC') ? 'selected' : ''}}>Sinovac Coronavac</option>
                                        </select>
                                        <small class="text-muted">Vaccine Name not Included in the List? You may contact CESU Staff.</small>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationDate3"><span class="text-danger font-weight-bold">*</span>Booster Date Vaccinated</label>
                                                <input type="date" class="form-control" name="vaccinationDate3" id="vaccinationDate3" value="{{old('vaccinationDate3', $record->vaccinationDate3)}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="haveAdverseEvents3"><span class="text-danger font-weight-bold">*</span>Booster Adverse Event/s</label>
                                                <select class="form-control" name="haveAdverseEvents3" id="haveAdverseEvents3">
                                                    <option value="0" {{(old('haveAdverseEvents3', $record->haveAdverseEvents3) == '0') ? 'selected' : ''}}>No</option>
                                                    <option value="1" {{(old('haveAdverseEvents3', $record->haveAdverseEvents3) == '1') ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationFacility3">Booster Vaccination Center/Facility <small>(Optional)</small></label>
                                                <input type="text" class="form-control" name="vaccinationFacility3" id="vaccinationFacility3" value="{{old('vaccinationFacility3', $record->vaccinationFacility3)}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationRegion3">Booster Region of Health Facility <small>(Optional)</small></label>
                                                <input type="text" class="form-control" name="vaccinationRegion3" id="vaccinationRegion3" value="{{old('vaccinationRegion3', $record->vaccinationRegion3)}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="haveBooster2"><span class="text-danger font-weight-bold">*</span>Have 2ND Booster Vaccine?</label>
                                        <select class="form-control" name="haveBooster2" id="haveBooster2" required>
                                          <option value="0" {{(old('haveBooster2', $haveBooster) == '0') ? 'selected' : ''}}>No</option>
                                          <option value="1" {{(old('haveBooster2', $haveBooster) == '1') ? 'selected' : ''}}>Yes</option>
                                        </select>
                                    </div>
                                    <div id="ifBoosterVaccine2" class="d-none">
                                        <div class="form-group">
                                            <label for="vaccinationName4"><span class="text-danger font-weight-bold">*</span>2ND Booster Vaccine Name</label>
                                            <select class="form-control" name="vaccinationName4" id="vaccinationName4">
                                              <option value="" disabled {{is_null(old('vaccinationName4', $record->vaccinationName4)) ? 'selected' : ''}}>Choose...</option>
                                              <option value="BHARAT BIOTECH" {{(old('vaccinationName4', $record->vaccinationName4) == "BHARAT BIOTECH") ? 'selected' : ''}}>Bharat BioTech</option>
                                              <option value="GAMALEYA SPUTNIK V" {{(old('vaccinationName4', $record->vaccinationName4) == 'GAMALEYA SPUTNIK V') ? 'selected' : ''}}>Gamaleya Sputnik V</option>
                                              <option value="JANSSEN" {{(old('vaccinationName4', $record->vaccinationName4) == "JANSSEN") ? 'selected' : ''}}>Janssen</option>
                                              <option value="MODERNA" {{(old('vaccinationName4', $record->vaccinationName4) == 'MODERNA') ? 'selected' : ''}}>Moderna</option>
                                              <option value="NOVARAX" {{(old('vaccinationName4', $record->vaccinationName4) == 'NOVARAX') ? 'selected' : ''}}>Novarax</option>
                                              <option value="OXFORD ASTRAZENECA" {{(old('vaccinationName4', $record->vaccinationName4) == 'OXFORD ASTRAZENECA') ? 'selected' : ''}}>Oxford AstraZeneca</option>
                                              <option value="PFIZER BIONTECH" {{(old('vaccinationName4', $record->vaccinationName4) == 'PFIZER BIONTECH') ? 'selected' : ''}}>Pfizer BioNTech</option>
                                              <option value="SINOPHARM" {{(old('vaccinationName4', $record->vaccinationName4) == 'SINOPHARM') ? 'selected' : ''}}>Sinopharm</option>
                                              <option value="SINOVAC CORONAVAC" {{(old('vaccinationName4', $record->vaccinationName4) == 'SINOVAC CORONAVAC') ? 'selected' : ''}}>Sinovac Coronavac</option>
                                            </select>
                                            <small class="text-muted">Vaccine Name not Included in the List? You may contact CESU Staff.</small>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vaccinationDate4"><span class="text-danger font-weight-bold">*</span>2ND Booster Date Vaccinated</label>
                                                    <input type="date" class="form-control" name="vaccinationDate4" id="vaccinationDate4" value="{{old('vaccinationDate4', $record->vaccinationDate4)}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="haveAdverseEvents4"><span class="text-danger font-weight-bold">*</span>2ND Booster Adverse Event/s</label>
                                                    <select class="form-control" name="haveAdverseEvents4" id="haveAdverseEvents4">
                                                        <option value="0" {{(old('haveAdverseEvents4', $record->haveAdverseEvents4) == '0') ? 'selected' : ''}}>No</option>
                                                        <option value="1" {{(old('haveAdverseEvents4', $record->haveAdverseEvents4) == '1') ? 'selected' : ''}}>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vaccinationFacility4">2ND Booster Vaccination Center/Facility <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="vaccinationFacility4" id="vaccinationFacility4" value="{{old('vaccinationFacility4', $record->vaccinationFacility4)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vaccinationRegion4">2ND Booster Region of Health Facility <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="vaccinationRegion4" id="vaccinationRegion4" value="{{old('vaccinationRegion4', $record->vaccinationRegion4)}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="addresscheck">
                        <div class="form-check form-check-inline">
                            <label for="" class="mr-3 mt-1">Current Address is Different from Permanent Address?</label>
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="paddressdifferent" id="paddressdifferent" value="1" {{(old('paddressdifferent', $record->permaaddressDifferent) == 1) ? 'checked' : ''}}> Yes
                            </label>
                            <label class="form-check-label">
                                <input class="form-check-input ml-3" type="radio" name="paddressdifferent" id="paddressdifferent" value="0" {{(old('paddressdifferent', $record->permaaddressDifferent) == 0) ? 'checked' : ''}}> No
                            </label>
                        </div>
                    </div>
                    <div id="permaaddress_div" class="d-none">
                        <hr>
                        <h5 class="font-weight-bold">Permanent Address and Contact Information</h5>
                        <hr>
                        <div id="permaaddresstext" class="d-none">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <input type="text" class="form-control" name="permaaddress_province" id="permaaddress_province" value="{{old('permaaddress_province', $record->permaaddress_province)}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="permaaddress_city" id="permaaddress_city" value="{{old('permaaddress_city', $record->permaaddress_city)}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <input type="text" class="form-control" name="permaaddress_provincejson" id="permaaddress_provincejson" value="{{old('permaaddress_provincejson', $record->permaaddress_provincejson)}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="permaaddress_cityjson" id="permaaddress_cityjson" value="{{old('permaaddress_cityjson', $record->permaaddress_cityjson)}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="spermaaddress_province"><span class="text-danger font-weight-bold">*</span>Province</label>
                                    <select class="form-control" name="spermaaddress_province" id="spermaaddress_province">
                                      <option value="" selected disabled>Choose...</option>
                                    </select>
                                        @error('spermaaddress_province')
                                          <small class="text-danger">{{$message}}</small>
                                      @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="spermaaddress_city"><span class="text-danger font-weight-bold">*</span>City</label>
                                    <select class="form-control" name="spermaaddress_city" id="spermaaddress_city">
                                      <option value="" selected disabled>Choose...</option>
                                    </select>
                                      @error('spermaaddress_city')
                                          <small class="text-danger">{{$message}}</small>
                                      @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="permaaddress_brgy"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                    <select class="form-control" name="permaaddress_brgy" id="permaaddress_brgy">
                                      <option value="" selected disabled>Choose...</option>
                                    </select>
                                        @error('permaaddress_brgy')
                                          <small class="text-danger">{{$message}}</small>
                                      @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="permaaddress_houseno"><span class="text-danger font-weight-bold">*</span>House No./Lot/Building</label>
                                    <input type="text" class="form-control" id="permaaddress_houseno" name="permaaddress_houseno" value="{{(old('paddressdifferent', $record->permaaddressDifferent) == 1) ? old('permaaddress_houseno', $record->permaaddress_houseno) : old('permaaddress_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" style="text-transform: uppercase;">
                                    @error('permaaddress_houseno')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="permaaddress_street"><span class="text-danger font-weight-bold">*</span>Street/Purok/Sitio</label>
                                    <input type="text" class="form-control" id="permaaddress_street" name="permaaddress_street" value="{{(old('paddressdifferent', $record->permaaddressDifferent) == 1) ? old('permaaddress_street', $record->permaaddressDifferent) : old('permaaddress_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" style="text-transform: uppercase;">
                                    @error('permaaddress_street')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="permamobile"><span class="text-danger font-weight-bold">*</span>Cellphone No.</label>
                                    <input type="text" class="form-control" id="permamobile" name="permamobile" value="{{(old('paddressdifferent', $record->permaaddressDifferent) == 1) ? old('permamobile', $record->permamobile) : old('permamobile')}}" pattern="[0-9]{11}" placeholder="0917xxxxxxx">
                                    @error('permamobile')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="permaphoneno">Home Phone No. (& Area Code)</label>
                                    <input type="number" class="form-control" id="permaphoneno" name="permaphoneno" value="{{(old('paddressdifferent', $record->permaaddressDifferent) == 1) ? old('permaphoneno', $record->permaphoneno) : old('permaphoneno')}}">
                                    @error('permaphoneno')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="permaemail" class="form-control" name="permaemail" id="permaemail" value="{{(old('paddressdifferent', $record->permaaddressDifferent) == 1) ? old('permaemail', $record->permaemail) : old('permaemail')}}">
                                    @error('permaemail')
                                          <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @if(auth()->user()->isCompanyAccount())
                    <div id="hasOccSelect">
                        <div class="form-check form-check-inline">
                            <label for="" class="mr-3 mt-1">Patient has Occupation?</label>
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="hasoccupation" id="hasoccupation" value="1" checked> Yes
                            </label>
                        </div>
                    </div>
                    <div id="occupation_div" class="d-none">
                        <hr>
                        <h5 class="font-weight-bold">Current Workplace Information and Address</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="occupation">Occupation/Job Title</label>
                                    <input type="text" class="form-control" name="occupation" id="occupation" value="{{old('occupation', $record->occupation)}}" style="text-transform: uppercase;">
                                    @error('occupation')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="natureOfWork"><span class="text-danger font-weight-bold">*</span>Nature of Work</label>
                                    <select class="form-control" name="natureOfWork" id="natureOfWork">
                                        <option value="" disabled {{(is_null(old('natureOfWork', $record->natureOfWork))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="AGRICULTURE" {{(old('natureOfWork', $record->natureOfWork) == 'AGRICULTURE') ? 'selected' : ''}}>Agriculture</option>
                                        <option value="BPO" {{(old('natureOfWork', $record->natureOfWork) == 'BPO') ? 'selected' : ''}}>BPO (Outsourcing E.G. eTelecare Global Sol. Inc)</option>
                                        <option value="COMMUNICATIONS" {{(old('natureOfWork', $record->natureOfWork) == 'COMMUNICATIONS') ? 'selected' : ''}}>Communications (E.G. PLDT)</option>
                                        <option value="CONSTRUCTION" {{(old('natureOfWork', $record->natureOfWork) == 'CONSTRUCTION') ? 'selected' : ''}}>Construction (E.G. Makati Dev Corp)</option>
                                        <option value="EDUCATION" {{(old('natureOfWork', $record->natureOfWork) == 'EDUCATION') ? 'selected' : ''}}>Education (E.G. DLSU)</option>
                                        <option value="ELECTRICITY" {{(old('natureOfWork', $record->natureOfWork) == 'ELECTRICITY') ? 'selected' : ''}}>Electricity</option>
                                        <option value="FINANCIAL" {{(old('natureOfWork', $record->natureOfWork) == 'FINANCIAL') ? 'selected' : ''}}>Financial (E.G. Banks)</option>
                                        <option value="GOVERNMENT UNITS/ORGANIZATIONS" {{(old('natureOfWork', $record->natureOfWork) == 'GOVERNMENT UNITS/ORGANIZATIONS') ? 'selected' : ''}}>Government Units/Organizations (E.G. GSIS)</option>
                                        <option value="HOTEL AND RESTAURANT" {{(old('natureOfWork', $record->natureOfWork) == 'HOTEL AND RESTAURANT') ? 'selected' : ''}}>Hotel and Restaurant (E.G. Jollibee Foods Corp)</option>
                                        <option value="MANNING/SHIPPING AGENCY" {{(old('natureOfWork', $record->natureOfWork) == 'MANNING/SHIPPING AGENCY') ? 'selected' : ''}}>Manning/Shipping Agency (E.G. Fil Star Maritime)</option>
                                        <option value="MANUFACTURING" {{(old('natureOfWork', $record->natureOfWork) == 'MANUFACTURING') ? 'selected' : ''}}>Manufacturing (E.G. Nestle Phils Inc)</option>
                                        <option value="MASS MEDIA" {{(old('natureOfWork', $record->natureOfWork) == 'MASS MEDIA') ? 'selected' : ''}}>Mass Media (Field Reporters, Photographers, etc.)</option>
                                        <option value="MEDICAL AND HEALTH SERVICES" {{(old('natureOfWork', $record->natureOfWork) == 'MEDICAL AND HEALTH SERVICES') ? 'selected' : ''}}>Medical and Health Services</option>
                                        <option value="MICROFINANCE" {{(old('natureOfWork', $record->natureOfWork) == 'MICROFINANCE') ? 'selected' : ''}}>Microfinance (E.G. Ahon sa Hirap Inc)</option>
                                        <option value="MINING AND QUARRYING" {{(old('natureOfWork', $record->natureOfWork) == 'MINING AND QUARRYING') ? 'selected' : ''}}>Mining and Quarrying (E.G. Philex Mining Corp)</option>
                                        <option value="NON PROFIT ORGANIZATIONS" {{(old('natureOfWork', $record->natureOfWork) == 'NON PROFIT ORGANIZATIONS') ? 'selected' : ''}}>Non Profit Organizations (E.G. Iglesia Ni Cristo)</option>
                                        <option value="REAL ESTATE" {{(old('natureOfWork', $record->natureOfWork) == 'REAL ESTATE') ? 'selected' : ''}}>Real Estate (E.G. Megaworld Corp)</option>
                                        <option value="SERVICES" {{(old('natureOfWork', $record->natureOfWork) == 'SERVICES') ? 'selected' : ''}}>Services (Hairdressers, manicurist, embalmers, security guards, messengers, massage therapists, etc.)</option>
                                        <option value="STORAGE" {{(old('natureOfWork', $record->natureOfWork) == 'STORAGE') ? 'selected' : ''}}>Storage (Include Freight Forwarding E.G. Dhl)</option>
                                        <option value="TRANSPORTATION" {{(old('natureOfWork', $record->natureOfWork) == 'TRANSPORTATION') ? 'selected' : ''}}>Transportation (E.G. Philippine Airlines)</option>
                                        <option value="WHOLESALE AND RETAIL TRADE" {{(old('natureOfWork', $record->natureOfWork) == 'WHOLESALE AND RETAIL TRADE') ? 'selected' : ''}}>Wholesale and Retail Trade (E.G. Mercury Drug)</option>
                                        <option value="OTHERS" {{(old('natureOfWork', $record->natureOfWork) == 'OTHERS') ? 'selected' : ''}}>Others (Specify)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="specifyWorkNatureDiv" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="natureOfWorkIfOthers"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                        <input type="text" class="form-control" name="natureOfWorkIfOthers" id="natureOfWorkIfOthers" value="{{old('natureOfWorkIfOthers', $record->natureOfWorkIfOthers)}}">
                                        @error('natureOfWorkIfOthers')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="occupation_name">Name of Workplace/Company</small></label>
                                    <input type="text" class="form-control" name="occupation_name" id="occupation_name" value="{{$record->occupation_name}}" readonly>
                                    @error('occupation_name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="worksInClosedSetting"><span class="text-danger font-weight-bold">*</span>Works in a closed setting?</label>
                                    <select class="form-control" name="worksInClosedSetting" id="worksInClosedSetting">
                                        <option value="UNKNOWN" {{(old('worksInClosedSetting', $record->worksInClosedSetting) == "UNKNOWN") ? 'selected' : ''}}>Unknown</option>
                                        <option value="YES" {{(old('worksInClosedSetting', $record->worksInClosedSetting) == "YES") ? 'selected' : ''}}>Yes</option>
                                        <option value="NO" {{(old('worksInClosedSetting', $record->worksInClosedSetting) == "NO") ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="occupation_province"><span class="text-danger font-weight-bold">*</span>Workplace Province</label>
                                    <input type="text" class="form-control" name="occupation_province" id="occupation_province" value="{{$record->occupation_province}}" readonly>
                                    @error('occupation_province')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="occupation_city"><span class="text-danger font-weight-bold">*</span>Workplace City</label>
                                    <input type="text" class="form-control" name="occupation_city" id="occupation_city" value="{{$record->occupation_city}}" readonly>
                                    @error('occupation_city')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="occupation_brgy"><span class="text-danger font-weight-bold">*</span>Workplace Barangay</label>
                                    <input type="text" class="form-control" name="occupation_brgy" id="occupation_brgy" value="{{$record->occupation_brgy}}" readonly>
                                    @error('occupation_brgy')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="occupation_lotbldg"><span class="text-danger font-weight-bold">*</span>Workplace Lot/Building</label>
                                    <input type="text" class="form-control" name="occupation_lotbldg" id="occupation_lotbldg" value="{{$record->occupation_lotbldg}}" readonly>
                                    @error('occupation_lotbldg')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="occupation_street"><span class="text-danger font-weight-bold">*</span>Workplace Street</label>
                                    <input type="text" class="form-control" name="occupation_street" id="occupation_street" value="{{$record->occupation_street}}" readonly>
                                    @error('occupation_street')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="occupation_mobile">Workplace Phone/Mobile No. <small>(Optional)</small></label>
                                    <input type="text" class="form-control" name="occupation_mobile" id="occupation_mobile" value="{{$record->occupation_mobile}}" readonly>
                                    @error('occupation_mobile')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="occupation_email">Workplace Email <small>(Optional)</small></label>
                                    <input type="email" class="form-control" name="occupation_email" id="occupation_email" value="{{$record->occupation_email}}" readonly>
                                    @error('occupation_email')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div id="hasOccSelect">
                        <div class="form-check form-check-inline">
                            <label for="" class="mr-3 mt-1">Patient has Occupation?</label>
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="hasoccupation" id="hasoccupation" value="1" {{(old('hasoccupation', $record->hasOccupation) == 1) ? 'checked' : ''}}> Yes
                            </label>
                            <label class="form-check-label">
                                <input class="form-check-input ml-3" type="radio" name="hasoccupation" id="hasoccupation" value="0" {{(old('hasoccupation', $record->hasOccupation) == 0) ? 'checked' : ''}}> No
                            </label>
                        </div>
                    </div>
                    <div id="occupation_div" class="d-none">
                        <hr>
                        <h5 class="font-weight-bold">Current Workplace Information and Address</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="occupation"><span class="text-danger font-weight-bold">*</span>Occupation/Job Title</label>
                                    <input type="text" class="form-control" name="occupation" id="occupation" value="{{(old('hasoccupation', $record->hasOccupation) == 1) ? old('occupation', $record->occupation) : ""}}" style="text-transform: uppercase;">
                                    @error('occupation')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="natureOfWork"><span class="text-danger font-weight-bold">*</span>Nature of Work</label>
                                    <select class="form-control" name="natureOfWork" id="natureOfWork">
                                        <option value="" disabled {{(is_null(old('natureOfWork', $record->natureOfWork))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="AGRICULTURE" {{(old('natureOfWork', $record->natureOfWork) == 'AGRICULTURE') ? 'selected' : ''}}>Agriculture</option>
                                        <option value="BPO" {{(old('natureOfWork', $record->natureOfWork) == 'BPO') ? 'selected' : ''}}>BPO (Outsourcing E.G. eTelecare Global Sol. Inc)</option>
                                        <option value="COMMUNICATIONS" {{(old('natureOfWork', $record->natureOfWork) == 'COMMUNICATIONS') ? 'selected' : ''}}>Communications (E.G. PLDT)</option>
                                        <option value="CONSTRUCTION" {{(old('natureOfWork', $record->natureOfWork) == 'CONSTRUCTION') ? 'selected' : ''}}>Construction (E.G. Makati Dev Corp)</option>
                                        <option value="EDUCATION" {{(old('natureOfWork', $record->natureOfWork) == 'EDUCATION') ? 'selected' : ''}}>Education (E.G. DLSU)</option>
                                        <option value="ELECTRICITY" {{(old('natureOfWork', $record->natureOfWork) == 'ELECTRICITY') ? 'selected' : ''}}>Electricity</option>
                                        <option value="FINANCIAL" {{(old('natureOfWork', $record->natureOfWork) == 'FINANCIAL') ? 'selected' : ''}}>Financial (E.G. Banks)</option>
                                        <option value="GOVERNMENT UNITS/ORGANIZATIONS" {{(old('natureOfWork', $record->natureOfWork) == 'GOVERNMENT UNITS/ORGANIZATIONS') ? 'selected' : ''}}>Government Units/Organizations (E.G. GSIS)</option>
                                        <option value="HOTEL AND RESTAURANT" {{(old('natureOfWork', $record->natureOfWork) == 'HOTEL AND RESTAURANT') ? 'selected' : ''}}>Hotel and Restaurant (E.G. Jollibee Foods Corp)</option>
                                        <option value="MANNING/SHIPPING AGENCY" {{(old('natureOfWork', $record->natureOfWork) == 'MANNING/SHIPPING AGENCY') ? 'selected' : ''}}>Manning/Shipping Agency (E.G. Fil Star Maritime)</option>
                                        <option value="MANUFACTURING" {{(old('natureOfWork', $record->natureOfWork) == 'MANUFACTURING') ? 'selected' : ''}}>Manufacturing (E.G. Nestle Phils Inc)</option>
                                        <option value="MASS MEDIA" {{(old('natureOfWork', $record->natureOfWork) == 'MASS MEDIA') ? 'selected' : ''}}>Mass Media (Field Reporters, Photographers, etc.)</option>
                                        <option value="MEDICAL AND HEALTH SERVICES" {{(old('natureOfWork', $record->natureOfWork) == 'MEDICAL AND HEALTH SERVICES') ? 'selected' : ''}}>Medical and Health Services</option>
                                        <option value="MICROFINANCE" {{(old('natureOfWork', $record->natureOfWork) == 'MICROFINANCE') ? 'selected' : ''}}>Microfinance (E.G. Ahon sa Hirap Inc)</option>
                                        <option value="MINING AND QUARRYING" {{(old('natureOfWork', $record->natureOfWork) == 'MINING AND QUARRYING') ? 'selected' : ''}}>Mining and Quarrying (E.G. Philex Mining Corp)</option>
                                        <option value="NON PROFIT ORGANIZATIONS" {{(old('natureOfWork', $record->natureOfWork) == 'NON PROFIT ORGANIZATIONS') ? 'selected' : ''}}>Non Profit Organizations (E.G. Iglesia Ni Cristo)</option>
                                        <option value="REAL ESTATE" {{(old('natureOfWork', $record->natureOfWork) == 'REAL ESTATE') ? 'selected' : ''}}>Real Estate (E.G. Megaworld Corp)</option>
                                        <option value="SERVICES" {{(old('natureOfWork', $record->natureOfWork) == 'SERVICES') ? 'selected' : ''}}>Services (Hairdressers, manicurist, embalmers, security guards, messengers, massage therapists, etc.)</option>
                                        <option value="STORAGE" {{(old('natureOfWork', $record->natureOfWork) == 'STORAGE') ? 'selected' : ''}}>Storage (Include Freight Forwarding E.G. Dhl)</option>
                                        <option value="TRANSPORTATION" {{(old('natureOfWork', $record->natureOfWork) == 'TRANSPORTATION') ? 'selected' : ''}}>Transportation (E.G. Philippine Airlines)</option>
                                        <option value="WHOLESALE AND RETAIL TRADE" {{(old('natureOfWork', $record->natureOfWork) == 'WHOLESALE AND RETAIL TRADE') ? 'selected' : ''}}>Wholesale and Retail Trade (E.G. Mercury Drug)</option>
                                        <option value="OTHERS" {{(old('natureOfWork', $record->natureOfWork) == 'OTHERS') ? 'selected' : ''}}>Others (Specify)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="specifyWorkNatureDiv" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="natureOfWorkIfOthers"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                        <input type="text" class="form-control" name="natureOfWorkIfOthers" id="natureOfWorkIfOthers" value="{{old('natureOfWorkIfOthers', $record->natureOfWorkIfOthers)}}">
                                        @error('natureOfWorkIfOthers')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="occupation_name"><span class="text-danger font-weight-bold">*</span>Name of Workplace/Company</label>
                                    <input type="text" class="form-control" name="occupation_name" id="occupation_name" value="{{old('occupation_name', $record->occupation_name)}}" style="text-transform: uppercase;">
                                    @error('occupation_name')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="worksInClosedSetting"><span class="text-danger font-weight-bold">*</span>Works in a closed setting?</label>
                                    <select class="form-control" name="worksInClosedSetting" id="worksInClosedSetting">
                                        <option value="UNKNOWN" {{(old('worksInClosedSetting', $record->worksInClosedSetting) == "UNKNOWN") ? 'selected' : ''}}>Unknown</option>
                                        <option value="YES" {{(old('worksInClosedSetting', $record->worksInClosedSetting) == "YES") ? 'selected' : ''}}>Yes</option>
                                        <option value="NO" {{(old('worksInClosedSetting', $record->worksInClosedSetting) == "NO") ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="isHCW"><span class="text-danger font-weight-bold">*</span>is Health Care Worker?</label>
                                    <select class="form-control" name="isHCW" id="isHCW">
                                        <option value="" disabled {{(is_null(old('isHCW', $record->isHCW))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="1" {{(old('isHCW', $record->isHCW) == "1") ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{(old('isHCW', $record->isHCW) == "0") ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="occupationaddresstext" class="d-none">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <input type="text" class="form-control" name="occupation_province" id="occupation_province" value="{{old('occupation_province', $record->occupation_province)}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="occupation_city" id="occupation_city" value="{{old('occupation_city', $record->occupation_city)}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <input type="text" class="form-control" name="occupation_provincejson" id="occupation_provincejson" value="{{old('occupation_provincejson', $record->occupation_provincejson)}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="occupation_cityjson" id="occupation_cityjson" value="{{old('occupation_cityjson', $record->occupation_cityjson)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="soccupation_province"><span class="text-danger font-weight-bold">*</span>Workplace Province</label>
                                    <select class="form-control" name="soccupation_province" id="soccupation_province">
                                      <option value="" selected disabled>Choose...</option>
                                    </select>
                                        @error('soccupation_province')
                                          <small class="text-danger">{{$message}}</small>
                                      @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="soccupation_city"><span class="text-danger font-weight-bold">*</span>Workplace City</label>
                                    <select class="form-control" name="soccupation_city" id="soccupation_city">
                                      <option value="" selected disabled>Choose...</option>
                                    </select>
                                      @error('soccupation_city')
                                          <small class="text-danger">{{$message}}</small>
                                      @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="occupation_brgy"><span class="text-danger font-weight-bold">*</span>Workplace Barangay</label>
                                    <select class="form-control" name="occupation_brgy" id="occupation_brgy">
                                      <option value="" selected disabled>Choose...</option>
                                    </select>
                                        @error('occupation_brgy')
                                          <small class="text-danger">{{$message}}</small>
                                      @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="occupation_lotbldg"><span class="text-danger font-weight-bold">*</span>Workplace Lot/Building</label>
                                    <input type="text" class="form-control" id="occupation_lotbldg" name="occupation_lotbldg" value="{{old('occupation_lotbldg', $record->occupation_lotbldg)}}" pattern="(^[a-zA-Z0-9 ]+$)+" style="text-transform: uppercase;">
                                    @error('occupation_lotbldg')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="occupation_street"><span class="text-danger font-weight-bold">*</span>Workplace Street</label>
                                    <input type="text" class="form-control" id="occupation_street" name="occupation_street" value="{{old('occupation_street', $record->occupation_street)}}" pattern="(^[a-zA-Z0-9 ]+$)+" style="text-transform: uppercase;">
                                    @error('occupation_street')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="occupation_mobile">Workplace Phone/Mobile No. <small>(Optional)</small></label>
                                    <input type="text" class="form-control" id="occupation_mobile" name="occupation_mobile" pattern="[0-9]{11}" placeholder="0917xxxxxxx" value="{{old('occupation_mobile', $record->occupation_mobile)}}">
                                    @error('occupation_mobile')
                                        <small class="text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="occupation_email">Workplace Email <small>(Optional)</small></label>
                                    <input type="email" class="form-control" name="occupation_email" id="occupation_email" value="{{old('occupation_email', $record->occupation_email)}}">
                                    @error('occupation_email')
                                          <small class="text-danger">{{$message}}</small>
                                      @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(auth()->user()->isCesuAccount())
                    <hr>
                    <div class="card">
                        <div class="card-header">Other Settings</div>
                        <div class="card-body">
                            <div class="form-group">
                              <label for="sharedOnId">Share Access To Other Accounts</label>
                              <select class="form-control" name="sharedOnId[]" id="sharedOnId" multiple>
                                  @foreach($sharedAccessList as $i)
                                  <option value="{{$i->id}}" {{(collect(old('sharedOnId', explode(',', $record->sharedOnId)))->contains($i->id)) ? 'selected' : ''}}>{{$i->name}}</option>
                                  @endforeach
                              </select>
                            </div>
                            @if(auth()->user()->isAdmin == 1)
                            <hr>
                            <div class="form-check">
                                <label class="form-check-label">
                                  <input type="checkbox" class="form-check-input" name="is_confidential" id="is_confidential" value="1" {{($record->is_confidential == 1) ? 'checked' : ''}}>
                                  Is the record confidential? <i>(Details can only be seen by Admins)</i>
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary btn-block" id="submitBtn"><i class="fas fa-edit mr-2"></i>Update (CTRL + S)</button>
                </div>
            </div>
        </form>
        
        <div class="card mt-3">
            <div class="card-header"><b><i class="fas fa-file-alt mr-2"></i>Patient Docs</b></div>
            <div class="card-body">
                @if($docs_list->count() != 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">File Type</th>
                                <th scope="col">Filename</th>
                                <th scope="col">Upload Date / By</th>
                                <th scope="col">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($docs_list as $d)
                            <tr class="">
                                <td>{{$loop->iteration}}</td>
                                <td><a href="{{route('forms.edit', ['form' => $d->forms_id])}}">{{$d->forms_id}}</a></td>
                                <td>{{$d->file_type}}</td>
                                <td><a href="/forms/download/{{$d->id}}" class="btn btn-success btn-sm">{{basename($d->filepath)}}</a></td>
                                <td>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</td>
                                <td>{{$d->remarks}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center">No Docs uploaded yet.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        $(document).bind('keydown', function(e) {
            if(e.ctrlKey && (e.which == 83)) {
                e.preventDefault();
                $('#submitBtn').trigger('click');
                $('#submitBtn').prop('disabled', true);
                setTimeout(function() {
				    $('#submitBtn').prop('disabled', false);
			    }, 2000);
                return false;
            }
        });

        $(document).ready(function () {
            @if(!(auth()->user()->isBrgyAccount()) || auth()->user()->brgy->displayInList == 0)
            $('#saddress_province, #saddress_city, #address_brgy').select2({
                theme: "bootstrap",
            });
            @endif
            @if(!(auth()->user()->isCompanyAccount()))
            $('#soccupation_province, #soccupation_city, #occupation_brgy').select2({
                theme: "bootstrap",
            });
            @endif
            $('#natureOfWork, #spermaaddress_province, #spermaaddress_city, #permaaddress_brgy, #sharedOnId').select2({
                theme: "bootstrap",
            });

            $('#saddress_city').prop('disabled', true);
            $('#address_brgy').prop('disabled', true);
            $('#spermaaddress_city').prop('disabled', true);
            $('#permaaddress_brgy').prop('disabled', true);
            $('#soccupation_city').prop('disabled', true);
            $('#occupation_brgy').prop('disabled', true);

            var sadp_default = "{{old('address_provincejson', $record->address_provincejson)}}";
            var sadc_default = "{{old('address_cityjson', $record->address_cityjson)}}";

            var padp_default = "{{old('permaaddress_provincejson', $record->permaaddress_provincejson)}}";
            var padc_default = "{{old('permaaddress_cityjson', $record->permaaddress_cityjson)}}";

            var oadp_default = "{{old('occupation_provincejson', $record->occupation_provincejson)}}";
            var oadc_default = "{{old('occupation_cityjson', $record->occupation_cityjson)}}";
    
            $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
                var sorted = data.sort(function(a, b) {
                    if (a.provDesc > b.provDesc) {
                    return 1;
                    }
                    if (a.provDesc < b.provDesc) {
                    return -1;
                    }
                    return 0;
                });
    
                $.each(sorted, function(key, val) {
                    $('#saddress_province').append($('<option>', {
                        value: val.provCode,
                        text: val.provDesc,
                        selected: (sadp_default == val.provCode) ? true : false,
                    }));
                    $('#spermaaddress_province').append($('<option>', {
                        value: val.provCode,
                        text: val.provDesc,
                        selected: (padp_default == val.provCode) ? true : false,
                    }));
                    $('#soccupation_province').append($('<option>', {
                        value: val.provCode,
                        text: val.provDesc,
                        selected: (oadp_default == val.provCode) ? true : false,
                    }));
                });
            });

            $('#saddress_province').change(function (e) {
                e.preventDefault();
                $('#saddress_city').prop('disabled', false);
                $('#address_brgy').prop('disabled', true);
                $('#saddress_city').empty();
                $("#saddress_city").append('<option value="" selected disabled>Choose...</option>');
                $('#address_brgy').empty();
                $("#address_brgy").append('<option value="" selected disabled>Choose...</option>');
                $("#address_province").val($('#saddress_province option:selected').text());
			    $("#address_provincejson").val($('#saddress_province').val());
                
                $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
                    var sorted = data.sort(function(a, b) {
                        if (a.citymunDesc > b.citymunDesc) {
                        return 1;
                        }
                        if (a.citymunDesc < b.citymunDesc) {
                        return -1;
                        }
                        return 0;
                    });
                    $.each(sorted, function(key, val) {
                        if($('#saddress_province').val() == val.provCode) {
                            $('#saddress_city').append($('<option>', {
                                value: val.citymunCode,
                                text: val.citymunDesc,
                                selected: (sadc_default == val.citymunCode) ? true : false,
                            })); 
                        }
                    });
                });
            }).trigger('change');

            //for edit default values on load
            $("#address_province").val("{{old('address_province', $record->address_province)}}");
            $("#address_provincejson").val("{{old('address_provincejson', $record->address_provincejson)}}");
    
            $('#spermaaddress_province').change(function (e) {
                e.preventDefault();
                $('#spermaaddress_city').prop('disabled', false);
                $('#permaaddress_brgy').prop('disabled', true);
                $('#spermaaddress_city').empty();
                $("#spermaaddress_city").append('<option value="" selected disabled>Choose...</option>');
                $('#permaaddress_brgy').empty();
                $("#permaaddress_brgy").append('<option value="" selected disabled>Choose...</option>');
                $("#permaaddress_province").val($('#spermaaddress_province option:selected').text());
			    $("#permaaddress_provincejson").val($('#spermaaddress_province').val());
                
                $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
                    var sorted = data.sort(function(a, b) {
                        if (a.citymunDesc > b.citymunDesc) {
                        return 1;
                        }
                        if (a.citymunDesc < b.citymunDesc) {
                        return -1;
                        }
                        return 0;
                    });
                    $.each(sorted, function(key, val) {
                        if($('#spermaaddress_province').val() == val.provCode) {
                            $('#spermaaddress_city').append($('<option>', {
                                value: val.citymunCode,
                                text: val.citymunDesc,
                                selected: (padc_default == val.citymunCode) ? true : false,
                            }));
                        }
                    });
                });
            });

            $('#soccupation_province').change(function (e) {
                e.preventDefault();
                $('#soccupation_city').prop('disabled', false);
                $('#occupation_brgy').prop('disabled', true);
                $('#soccupation_city').prop('required', true);
			    $('#occupation_brgy').prop('required', false);
                $('#soccupation_city').empty();
                $("#soccupation_city").append('<option value="" selected disabled>Choose...</option>');
                $('#occupation_brgy').empty();
                $("#occupation_brgy").append('<option value="" selected disabled>Choose...</option>');
                $("#occupation_province").val($('#soccupation_province option:selected').text());
			    $("#occupation_provincejson").val($('#soccupation_province').val());
                
                $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
                    var sorted = data.sort(function(a, b) {
                        if (a.citymunDesc > b.citymunDesc) {
                        return 1;
                        }
                        if (a.citymunDesc < b.citymunDesc) {
                        return -1;
                        }
                        return 0;
                    });
                    $.each(sorted, function(key, val) {
                        if($('#soccupation_province').val() == val.provCode) {
                            $('#soccupation_city').append($('<option>', {
                                value: val.citymunCode,
                                text: val.citymunDesc,
                                selected: (oadc_default == val.citymunCode) ? true : false,
                            }));
                        }
                    });
                });
            });

            if(padp_default.length != 0) {
                $('#spermaaddress_province').trigger('change');

                //for edit default values on load
                $("#permaaddress_province").val("{{old('permaaddress_province', $record->permaaddress_province)}}");
                $("#permaaddress_provincejson").val("{{old('permaaddress_provincejson', $record->permaaddress_provincejson)}}");
            }

            if(oadp_default.length != 0) {
                $('#soccupation_province').trigger('change');

                //for edit default values on load
                $("#occupation_province").val("{{old('occupation_province', $record->occupation_province)}}");
			    $("#occupation_provincejson").val("{{old('occupation_provincejson', $record->occupation_provincejson)}}");
            }
    
            $('#saddress_city').change(function (e) { 
                e.preventDefault();
                $('#address_brgy').prop('disabled', false);
                $('#address_brgy').empty();
                $("#address_brgy").append('<option value="" selected disabled>Choose...</option>');
                $("#address_city").val($('#saddress_city option:selected').text());
                $('#address_cityjson').val($('#saddress_city').val());
    
                $.getJSON("{{asset('json/refbrgy.json')}}", function(data) {
                    var sorted = data.sort(function(a, b) {
                        if (a.brgyDesc > b.brgyDesc) {
                        return 1;
                        }
                        if (a.brgyDesc < b.brgyDesc) {
                        return -1;
                        }
                        return 0;
                    });
                    $.each(sorted, function(key, val) {
                        if($('#saddress_city').val() == val.citymunCode) {
                            $('#address_brgy').append($('<option>', {
                                value: val.brgyDesc.toUpperCase(),
                                text: val.brgyDesc.toUpperCase(),
                                selected: (val.brgyDesc.toUpperCase() == "{{old('address_brgy', $record->address_brgy)}}") ? true : false,
                            }));
                        }
                    });
                });
            }).trigger('change');

            $("#address_city").val("{{old('address_city', $record->address_city)}}");
            $('#address_cityjson').val("{{old('address_cityjson', $record->address_cityjson)}}");
    
            $('#spermaaddress_city').change(function (e) { 
                e.preventDefault();
                $('#permaaddress_brgy').prop('disabled', false);
                $('#permaaddress_brgy').empty();
                $("#permaaddress_brgy").append('<option value="" selected disabled>Choose...</option>');
                $("#permaaddress_city").val($('#spermaaddress_city option:selected').text());
			    $('#permaaddress_cityjson').val($('#spermaaddress_city').val());
    
                $.getJSON("{{asset('json/refbrgy.json')}}", function(data) {
                    var sorted = data.sort(function(a, b) {
                        if (a.brgyDesc > b.brgyDesc) {
                        return 1;
                        }
                        if (a.brgyDesc < b.brgyDesc) {
                        return -1;
                        }
                        return 0;
                    });
                    $.each(sorted, function(key, val) {
                        if($('#spermaaddress_city').val() == val.citymunCode) {
                            $('#permaaddress_brgy').append($('<option>', {
                                value: val.brgyDesc.toUpperCase(),
                                text: val.brgyDesc.toUpperCase(),
                                selected: (val.brgyDesc.toUpperCase() == "{{old('permaaddress_brgy', $record->permaaddress_brgy)}}") ? true : false,
                            }));
                        }
                    });
                });
            });
            
            $('#soccupation_city').change(function (e) { 
                e.preventDefault();
                $('#occupation_brgy').prop('disabled', false);
                $('#occupation_brgy').prop('required', true);
                $('#occupation_brgy').empty();
                $("#occupation_brgy").append('<option value="" selected disabled>Choose...</option>');
                $("#occupation_city").val($('#soccupation_city option:selected').text());
			    $('#occupation_cityjson').val($('#soccupation_city').val());
    
                $.getJSON("{{asset('json/refbrgy.json')}}", function(data) {
                    var sorted = data.sort(function(a, b) {
                        if (a.brgyDesc > b.brgyDesc) {
                        return 1;
                        }
                        if (a.brgyDesc < b.brgyDesc) {
                        return -1;
                        }
                        return 0;
                    });
                    $.each(sorted, function(key, val) {
                        if($('#soccupation_city').val() == val.citymunCode) {
                            $('#occupation_brgy').append($('<option>', {
                                value: val.brgyDesc.toUpperCase(),
                                text: val.brgyDesc.toUpperCase(),
                                selected: (val.brgyDesc.toUpperCase() == "{{old('occupation_brgy', $record->occupation_brgy)}}") ? true : false,
                            }));
                        }
                    });
                });
            });

            if(padc_default.length != 0) {
                $('#spermaaddress_city').trigger('change');

                //for edit default values on load
                $("#permaaddress_city").val("{{old('permaaddress_city', $record->permaaddress_city)}}");
			    $('#permaaddress_cityjson').val("{{old('permaaddress_cityjson', $record->permaaddress_cityjson)}}");
            }

            if(oadc_default.length != 0) {
                $('#soccupation_city').trigger('change');
                
                //for edit default values on load
                $("#occupation_city").val("{{old('occupation_city', $record->occupation_city)}}");
			    $('#occupation_cityjson').val("{{old('occupation_cityjson', $record->occupation_cityjson)}}");
            }
            
            $('#addresscheck').change(function() {
                if($("input[name='paddressdifferent']:checked").val() == 0) {
                    $('#permaaddress_div').addClass('d-none');
    
                    $('#spermaaddress_province').prop('required', false);
                    $('#spermaaddress_city').prop('required', false);
                    $('#permaaddress_brgy').prop('required', false);
                    $('#permaaddress_houseno').prop('required', false);
                    $('#permaaddress_street').prop('required', false);
                    $('#permamobile').prop('required', false);
                }
                else {
                    $('#permaaddress_div').removeClass('d-none');
    
                    $('#spermaaddress_province').prop('required', true);
                    $('#spermaaddress_city').prop('required', true);
                    $('#permaaddress_brgy').prop('required', true);
                    $('#permaaddress_houseno').prop('required', true);
                    $('#permaaddress_street').prop('required', true);
                    $('#permamobile').prop('required', true);
                }
            }).trigger('change');
    
            $('#gender').change(function (e) {
                e.preventDefault();
                if($('#gender').val() == 'FEMALE') {
                    $('#pdiv').removeClass('d-none');
                }
                else {
                    $('#pdiv').addClass('d-none');
                }
            }).trigger('change');

            $('#hasOccSelect').change(function () { 
                if($("input[name='hasoccupation']:checked").val() == 0) {
                    $('#occupation_div').addClass('d-none');

                    $('#occupation_name').prop('required', false);
                    $('#natureOfWork').prop('required', false);
                    $('#occupation').prop('required', false);
                    $('#soccupation_province').prop('required', false);
                    $('#soccupation_city').prop('required', false);
                    $('#occupation_brgy').prop('required', false);
                    $('#occupation_lotbldg').prop('required', false);
                    $('#occupation_street').prop('required', false);
                    $('#worksInClosedSetting').prop('required', false);
                    $('#isHCW').prop('required', false);

                    $('#natureOfWork').val('').change();
                }
                else {
                    $('#occupation_div').removeClass('d-none');

                    $('#occupation_name').prop('required', true);
                    $('#natureOfWork').prop('required', true);
                    $('#occupation').prop('required', true);
                    $('#soccupation_province').prop('required', true);
                    $('#soccupation_city').prop('required', true);
                    $('#occupation_brgy').prop('required', true);
                    $('#occupation_lotbldg').prop('required', true);
                    $('#occupation_street').prop('required', true);
                    $('#worksInClosedSetting').prop('required', true);
                    $('#isHCW').prop('required', true);
                }
            }).trigger('change');

            $('#natureOfWork').change(function (e) { 
                e.preventDefault();
                if($(this).val() == 'OTHERS') {
                    $('#specifyWorkNatureDiv').removeClass('d-none');
                    $('#natureOfWorkIfOthers').prop('required', true);
                }
                else {
                    $('#specifyWorkNatureDiv').addClass('d-none');
                    $('#natureOfWorkIfOthers').prop('required', false);
                }
		    }).trigger('change');

            $('#howManyDoseVaccine').change(function (e) { 
                e.preventDefault();
                if($(this).val() == '') {
                    $('#vaccineName').prop('required', false);

                    $('#ifVaccinated').addClass('d-none');
                    $('#ifFirstDoseVaccine').addClass('d-none');
                    $('#ifSecondDoseVaccine').addClass('d-none');

                    $('#vaccinationDate1').prop('required', false);
                    $('#haveAdverseEvents1').prop('required', false);
                    $('#vaccinationDate2').prop('required', false);
                    $('#haveAdverseEvents2').prop('required', false);

                    $('#booster_question').addClass('d-none');
                    $('#haveBooster').val('0');
                    $('#haveBooster').trigger('change');

                    $('#haveBooster2').val('0');
                    $('#haveBooster2').trigger('change');
                }
                else if($(this).val() == '1') {
                    $('#vaccineName').prop('required', true);

                    $('#ifVaccinated').removeClass('d-none');
                    $('#ifFirstDoseVaccine').removeClass('d-none');
                    $('#ifSecondDoseVaccine').addClass('d-none');

                    $('#vaccinationDate1').prop('required', true);
                    $('#haveAdverseEvents1').prop('required', true);
                    $('#vaccinationDate2').prop('required', false);
                    $('#haveAdverseEvents2').prop('required', false);

                    $('#booster_question').removeClass('d-none');
                    $('#haveBooster').val("{{old('haveBooster', $haveBooster)}}");
                    $('#haveBooster').trigger('change');

                    $('#haveBooster2').val("{{old('haveBooster2', $haveBooster2)}}");
                    $('#haveBooster2').trigger('change');
                }
                else if($(this).val() == '2') {
                    $('#vaccineName').prop('required', true);

                    $('#ifVaccinated').removeClass('d-none');
                    $('#ifFirstDoseVaccine').removeClass('d-none');
                    $('#ifSecondDoseVaccine').removeClass('d-none');

                    $('#vaccinationDate1').prop('required', true);
                    $('#haveAdverseEvents1').prop('required', true);
                    $('#vaccinationDate2').prop('required', true);
                    $('#haveAdverseEvents2').prop('required', true);
                    
                    $('#booster_question').removeClass('d-none');
                    $('#haveBooster').val("{{old('haveBooster', $haveBooster)}}");
                    $('#haveBooster').trigger('change');

                    $('#haveBooster2').val("{{old('haveBooster2', $haveBooster2)}}");
                    $('#haveBooster2').trigger('change');
                }
            }).trigger('change');

            $('#haveBooster').change(function (e) { 
                e.preventDefault();
                if($(this).val() == 1) {
                    $('#ifBoosterVaccine').removeClass('d-none');
                    $('#vaccinationName3').prop('required', true);
                    $('#vaccinationDate3').prop('required', true);
                    $('#haveAdverseEvents3').prop('required', true);
                }
                else {
                    $('#ifBoosterVaccine').addClass('d-none');
                    $('#vaccinationName3').prop('required', false);
                    $('#vaccinationDate3').prop('required', false);
                    $('#haveAdverseEvents3').prop('required', false);
                }
            }).trigger('change');

            $('#haveBooster2').change(function (e) { 
                e.preventDefault();
                if($(this).val() == 1) {
                    $('#ifBoosterVaccine2').removeClass('d-none');
                    $('#vaccinationName4').prop('required', true);
                    $('#vaccinationDate4').prop('required', true);
                    $('#haveAdverseEvents4').prop('required', true);
                }
                else {
                    $('#ifBoosterVaccine2').addClass('d-none');
                    $('#vaccinationName4').prop('required', false);
                    $('#vaccinationDate4').prop('required', false);
                    $('#haveAdverseEvents4').prop('required', false);
                }
            }).trigger('change');

            $('#vaccineName').change(function (e) { 
                e.preventDefault();
                if($(this).val() == 'JANSSEN') {
                    $('#howManyDoseVaccine').val(1).trigger('change');
                    $('#2ndDoseOption').hide();
                    $('#booster_question').removeClass('d-none');
                }
                else {
                    $('#2ndDoseOption').show();
                    if($('#howManyDoseVaccine').val() == 2) {
                        $('#booster_question').removeClass('d-none');
                    }
                    else {
                        $('#booster_question').addClass('d-none');
                    }
                }
            }).trigger('change');
        });

        $('#isindg').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 1) {
                $('#div_indg').removeClass('d-none');
                $('#indg_specify').prop('required', true);
            }
            else {
                $('#div_indg').addClass('d-none');
                $('#indg_specify').prop('required', false);
            }
        }).trigger('change');

        $('#philhealth').change(function (e) { 
            e.preventDefault();
            var value = $(this).val();
            var result = value.replace(/-/g, ''); // Remove dashes using regex
            $(this).val(result);
        }).trigger('change');
    </script>
@endsection