@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('forms.store')}}" method="POST">
            <div class="card">
                <div class="card-body">
                    @csrf
                    <div class="alert alert-info" role="alert">
                        All fields marked with an asterisk (<span class="text-danger font-weight-bold">*</span>) are required.
                    </div>
                    <hr>

                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{$error}}</p>
                            <hr>
                        @endforeach
                    </div>
                    <hr>
                    @endif
    
                    <div class="form-group">
                      <label for="records_id"><span class="text-danger font-weight-bold">*</span>Select Patient</label>
                      <select name="records_id" id="records_id" required>
                          <option value="" selected disabled>Choose...</option>
                        @forelse($records as $record)
                            <option value="{{$record->id}}" {{($record->id == old('records_id')) ? 'selected' : ""}}>{{$record->lname}}, {{$record->fname}} | {{$record->gender}} | {{date("m/d/Y", strtotime($record->bdate))}}</option>
                        @empty
    
                        @endforelse
                      </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="drunit"><span class="text-danger font-weight-bold">*</span>Disease Reporting Unit</label>
                                <select class="form-control" name="drunit" id="drunit" required>
                                    <option value="" disabled {{(empty(old('drunit'))) ? 'selected' : ''}}>Choose...</option>
                                    <option class="CHO GENERAL TRIAS" {{(old('drunit') == "CHO GENERAL TRIAS") ? 'selected' : ''}}>CHO GENERAL TRIAS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="drregion"><span class="text-danger font-weight-bold">*</span>DRU Region and Province</label>
                                <select class="form-control" name="drregion" id="drregion" required>
                                    <option value="" disabled @if(old('drregion') == "4A CAVITE"){{''}}@else{{'selected'}}@endif>Choose...</option>
                                    <option class="4A CAVITE" @if(old('drregion') == "4A CAVITE"){{'selected'}}@endif>4A CAVITE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for=""><span class="text-danger font-weight-bold">*</span>Philhealth No.</label>
                                <input type="text" name="" id="rec_philhealth" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interviewerName"><span class="text-danger font-weight-bold">*</span>Name of Interviewer</label>
                                <input type="text" name="interviewerName" id="interviewerName" class="form-control" value="{{old('interviewerName')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interviewerMobile"><span class="text-danger font-weight-bold">*</span>Contact Number of Interviewer</label>
                                <input type="number" name="interviewerMobile" id="interviewerMobile" class="form-control" value="{{old('interviewerMobile')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interviewDate"><span class="text-danger font-weight-bold">*</span>Date of Interview</label>
                                <input type="date" name="interviewDate" id="interviewDate" class="form-control" value="@if(!is_null(old('interviewDate'))){{old('interviewDate')}}@else{{date('Y-m-d')}}@endif" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="informantName">Name of Informant <small><i>(If patient unavailable)</i></small></label>
                                <input type="text" name="informantName" id="informantName" class="form-control" value="{{old('informantName')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="informantRelationship">Relationship</label>
                                <select class="form-control" name="informantRelationship" id="informantRelationship">
                                <option value="" disabled {{(is_null(old('informantRelationship'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Relative" {{(old('informantRelationship') == "Relative") ? 'selected' : ''}}>Family/Relative</option>
                                <option value="Friend" {{(old('informantRelationship') == "Friend") ? 'selected' : ''}}>Friend</option>
                                <option value="Others" {{(old('informantRelationship') == "Others") ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="informantMobile">Contact Number of Informant</label>
                                <input type="number" name="informantMobile" id="informantMobile" class="form-control" value="{{old('informantMobile')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pType"><span class="text-danger font-weight-bold">*</span>Type of Client</label>
                        <select class="form-control" name="pType" id="pType" required>
                        <option value="" disabled {{(is_null(old('pType'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="1" @if(old('pType') == "1"){{'selected'}}@endif>COVID-19 Case (Suspect, Probable, or Confirmed)</option>
                        <option value="2" @if(old('pType') == "2"){{'selected'}}@endif>For RT-PCR Testing (Not a Case of Close Contact)</option>
                        <option value="3" @if(old('pType') == "3"){{'selected'}}@endif>Close Contact</option>
                        <option value="4" @if(old('pType') == "4"){{'selected'}}@endif>Others, please specify</option>
                        </select>
                    </div>
                    <div><label for=""><span class="text-danger font-weight-bold">*</span>Testing Category/Subgroup <i>(Check all that apply)</i></label></div>
                    <div class="form-check form-check-inline testingCatOptions">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="testingCat[]" id="testingCat_A" value="A" required @if(is_array(old('testingCat')) && in_array("A", old('testingCat'))) checked @endif> A
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input ml-3" type="checkbox" name="testingCat[]" id="testingCat_B" value="B" required @if(is_array(old('testingCat')) && in_array("B", old('testingCat'))) checked @endif> B
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input ml-3" type="checkbox" name="testingCat[]" id="testingCat_C" value="C" required @if(is_array(old('testingCat')) && in_array("C", old('testingCat'))) checked @else checked @endif> C
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input ml-3" type="checkbox" name="testingCat[]" id="testingCat_D" value="D" required @if(is_array(old('testingCat')) && in_array("D", old('testingCat'))) checked @endif> D
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input ml-3" type="checkbox" name="testingCat[]" id="testingCat_E" value="E" required @if(is_array(old('testingCat')) && in_array("E", old('testingCat'))) checked @endif> E
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input ml-3" type="checkbox" name="testingCat[]" id="testingCat_F" value="F" required @if(is_array(old('testingCat')) && in_array("F", old('testingCat'))) checked @endif> F
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input ml-3" type="checkbox" name="testingCat[]" id="testingCat_G" value="G" required @if(is_array(old('testingCat')) && in_array("G", old('testingCat'))) checked @endif> G
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input ml-3" type="checkbox" name="testingCat[]" id="testingCat_H" value="H" required @if(is_array(old('testingCat')) && in_array("H", old('testingCat'))) checked @endif> H
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input ml-3" type="checkbox" name="testingCat[]" id="testingCat_I" value="I" required @if(is_array(old('testingCat')) && in_array("I", old('testingCat'))) checked @endif> I
                        </label>
                        <label class="form-check-label">
                            <input class="form-check-input ml-3" type="checkbox" name="testingCat[]" id="testingCat_J" value="J" required @if(is_array(old('testingCat')) && in_array("J", old('testingCat'))) checked @endif> J
                        </label>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header font-weight-bold">Part 1. Patient Information</div>
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header">2. Patient Profile</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Last Name</label>
                                                <input type="text" class="form-control" name="" id="rec_lname" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">First Name</label>
                                                <input type="text" class="form-control" name="" id="rec_fname" disabled>
                                            </div>
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Middle Name</label>
                                                <input type="text" class="form-control" name="" id="rec_mname" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Birthdate (MM/DD/YYYY)</label>
                                                <input type="text" class="form-control" name="" id="rec_bdate" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Age</label>
                                                <input type="text" class="form-control" name="" id="rec_age" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Gender</label>
                                                <input type="text" class="form-control" name="" id="rec_gender" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Civil Status</label>
                                                <input type="text" class="form-control" name="" id="rec_cs" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Nationality</label>
                                                <input type="text" class="form-control" name="" id="rec_nationality" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">Occupation</label>
                                                <input type="text" class="form-control" name="" id="rec_occupation" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">3. Current Address in the Philippines and Contact Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">House No./Lot/Bldg.</label>
                                                <input type="text" class="form-control" name="" id="rec_lot" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Street/Purok/Sitio</label>
                                                <input type="text" class="form-control" name="" id="rec_street" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Barangay</label>
                                                <input type="text" class="form-control" name="" id="rec_brgy" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Municipality/City</label>
                                                <input type="text" class="form-control" name="" id="rec_city" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Province</label>
                                                <input type="text" class="form-control" name="" id="rec_province" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Home Phone No. (& Area Code)</label>
                                                <input type="text" class="form-control" name="" id="rec_phoneno" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Cellphone No.</label>
                                                <input type="text" class="form-control" name="" id="rec_mobile" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Email Address</label>
                                                <input type="text" class="form-control" name="" id="rec_email" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">4. Current Workplace Address and Contact Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Lot/Bldg.</label>
                                                <input type="text" class="form-control" name="" id="rec_olot" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Street</label>
                                                <input type="text" class="form-control" name="" id="rec_ostreet" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Barangay</label>
                                                <input type="text" class="form-control" name="" id="rec_obrgy" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Municipality/City</label>
                                                <input type="text" class="form-control" name="" id="rec_ocity" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Province</label>
                                                <input type="text" class="form-control" name="" id="rec_oprovince" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Name of Workplace</label>
                                                <input type="text" class="form-control" name="" id="rec_oname" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Phone No./Cellphone No.</label>
                                                <input type="text" class="form-control" name="" id="rec_omobile" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Email Address</label>
                                                <input type="text" class="form-control" name="" id="rec_oemail" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">5. Consultation and Admission Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="havePreviousCovidConsultation"><span class="text-danger font-weight-bold">*</span>Did you have previous COVID-19 related consultation?</label>
                                                <select class="form-control" name="havePreviousCovidConsultation" id="havePreviousCovidConsultation" required>
                                                    <option value="" selected disabled>Choose...</option>
                                                    <option value="1" {{(old('havePreviousCovidConsultation') == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('havePreviousCovidConsultation') == 0) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="divYes1">
                                                <div class="form-group">
                                                    <label for="dateOfFirstConsult"><span class="text-danger font-weight-bold">*</span>Date of First Consult</label>
                                                    <input type="date" class="form-control" name="dateOfFirstConsult" id="dateOfFirstConsult" value="{{old('dateOfFirstConsult')}}" max="{{date('Y-m-d')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divYes2">
                                        <div class="form-group">
                                            <label for="facilityNameOfFirstConsult"><span class="text-danger font-weight-bold">*</span>Name of facility where first consult was done</label>
                                            <input type="text" class="form-control" name="facilityNameOfFirstConsult" id="facilityNameOfFirstConsult" value="{{old('facilityNameOfFirstConsult')}}">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="admittedInHealthFacility"><span class="text-danger font-weight-bold">*</span>Was the case admitted in a health facility?</label>
                                                    <select class="form-control" name="admittedInHealthFacility" id="admittedInHealthFacility">
                                                        <option value="" disabled {{(empty(old('admittedInHealthFacility'))) ? 'selected' : ''}}>Choose...</option>
                                                        <option value="1" {{(old('admittedInHealthFacility') == 1) ? 'selected' : ''}}>Yes</option>
                                                        <option value="0" {{(old('admittedInHealthFacility') == 0) ? 'selected' : ''}}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div id="divYes3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="dateOfAdmissionInHealthFacility"><span class="text-danger font-weight-bold">*</span>Date of Admission <small><i>(Indicate earliest date)</i></small></label>
                                                                <input type="date" class="form-control" name="dateOfAdmissionInHealthFacility" id="dateOfAdmissionInHealthFacility" value="{{old('dateOfAdmissionInHealthFacility')}}" max="{{date('Y-m-d')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="admittedInMultipleHealthFacility"><span class="text-danger font-weight-bold">*</span>Admitted in multiple health facilities?</label>
                                                                <select class="form-control" name="admittedInMultipleHealthFacility" id="admittedInMultipleHealthFacility">
                                                                    <option value="" disabled {{(is_null(old('admittedInMultipleHealthFacility'))) ? 'selected' : ''}}>Choose...</option>
                                                                    <option value="1" {{(old('admittedInMultipleHealthFacility') == 1) ? 'selected' : ''}}>Yes</option>
                                                                    <option value="0" {{(old('admittedInMultipleHealthFacility') == 0) ? 'selected' : ''}}>No</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="divYes4">
                                            <div class="form-group">
                                                <label for="facilitynameOfFirstAdmitted"><span class="text-danger font-weight-bold">*</span>Name of Facility where patient was first admitted</label>
                                                <input type="text" class="form-control" name="facilitynameOfFirstAdmitted" id="facilitynameOfFirstAdmitted" value="{{old('facilitynameOfFirstAdmitted')}}">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="facilityregion"><span class="text-danger font-weight-bold">*</span>Region of Facility</label>
                                                      <div class="form-group">
                                                        <input type="text" class="form-control" name="facilityregion" id="facilityregion" value="{{old('facilityregion')}}">
                                                      </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="facilityregion"><span class="text-danger font-weight-bold">*</span>Province of Facility</label>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="facilityregion" id="facilityprovince" value="{{old('facilityprovince')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">6. Disposition at Time of Report (Provide name of hospital/isolation/quarantine facility)</div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="dispositionType"><span class="text-danger font-weight-bold">*</span>Status</label>
                                        <select class="form-control" name="dispositionType" id="dispositionType">
                                            <option value="" {{(is_null(old('dispositionType'))) ? 'selected' : ''}}>N/A</option>
                                            <option value="1" {{(old('dispositionType') == 1) ? 'selected' : ''}}>Admitted in hospital</option>
                                            <option value="2" {{(old('dispositionType') == 2) ? 'selected' : ''}}>Admitted in isolation/quarantine facility</option>
                                            <option value="3" {{(old('dispositionType') == 3) ? 'selected' : ''}}>In home isolation/quarantine</option>
                                            <option value="4" {{(old('dispositionType') == 4) ? 'selected' : ''}}>Discharged to home</option>
                                            <option value="5" {{(old('dispositionType') == 5) ? 'selected' : ''}}>Others</option>
                                        </select>
                                    </div>
                                    <div id="divYes5">
                                        <div class="form-group">
                                            <label for="dispositionName" id="dispositionlabel"></label>
                                            <input type="text" class="form-control" name="dispositionName" id="dispositionName" value="{{old('dispositionName')}}">
                                        </div>
                                    </div>
                                    <div id="divYes6">
                                        <div class="form-group">
                                            <label for="dispositionDate" id="dispositiondatelabel"></label>
                                            <input type="datetime-local" class="form-control" name="dispositionDate" id="dispositionDate" value="{{old('dispositionDate')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header"><span class="text-danger font-weight-bold">*</span>7. Health Status at Consult</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <select class="form-control" name="healthStatus" id="healthStatus" required>
                                                    <option value="" disabled {{(is_null(old('healthStatus'))) ? 'selected' : ''}}>Choose...</option>
                                                    <option value="Asymptomatic" {{(old('healthStatus') == 'Asymptomatic') ? 'selected' : ''}}>Asymptomatic </option>
                                                    <option value="Mild" {{(old('healthStatus') == 'Mild') ? 'selected' : ''}}>Mild</option>
                                                    <option value="Moderate" {{(old('healthStatus') == 'Moderate') ? 'selected' : ''}}>Moderate</option>
                                                    <option value="Severe" {{(old('healthStatus') == 'Severe') ? 'selected' : ''}}>Severe</option>
                                                    <option value="Critical" {{(old('healthStatus') == 'Critical') ? 'selected' : ''}}>Critical</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header"><span class="text-danger font-weight-bold">*</span>8. Case Classification</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <select class="form-control" name="caseClassification" id="caseClassification" required>
                                                    <option value="" disabled {{(is_null(old('caseClassification'))) ? 'selected' : ''}}>Choose...</option>
                                                    <option value="Suspect" {{(old('caseClassification') == 'Suspect') ? 'selected' : ''}}>Suspect</option>
                                                    <option value="Probable" {{(old('caseClassification') == 'Probable') ? 'selected' : ''}}>Probable</option>
                                                    <option value="Confirmed" {{(old('caseClassification') == 'Confirmed') ? 'selected' : ''}}>Confirmed</option>
                                                    <option value="Non-COVID-19 Case" {{(old('caseClassification') == 'Non-COVID-19 Case') ? 'selected' : ''}}>Non-COVID-19 Case</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header font-weight-bold">Part 2. Case Investigation Details</div>
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header">9. Special Population</div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="isHealthCareWorker"><span class="text-danger font-weight-bold">*</span>Health Care Worker</label>
                                        <select class="form-control" name="isHealthCareWorker" id="isHealthCareWorker" required>
                                            <option value="1" {{(old('isHealthCareWorker') == 1) ? 'selected' : ''}}>Yes</option>
                                            <option value="0" {{(old('isHealthCareWorker') == 0 || is_null(old('isHealthCareWorker'))) ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div id="divisHealthCareWorker">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="healthCareCompanyName"><span class="text-danger font-weight-bold">*</span>Name of Health Facility</label>
                                                    <input type="text" class="form-control" name="healthCareCompanyName" id="healthCareCompanyName" value="{{old('healthCareCompanyName')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="healthCareCompanyLocation"><span class="text-danger font-weight-bold">*</span>Location of Health Facility</label>
                                                    <input type="text" class="form-control" name="healthCareCompanyLocation" id="healthCareCompanyLocation" value="{{old('healthCareCompanyLocation')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="isOFW"><span class="text-danger font-weight-bold">*</span>Returning Overseas Filipino</label>
                                                <select class="form-control" name="isOFW" id="isOFW" required>
                                                    <option value="1" {{(old('isOFW') == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('isOFW') == 0 || is_null(old('isOFW'))) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divisOFW">
                                                <div class="form-group">
                                                    <label for="OFWCountyOfOrigin"><span class="text-danger font-weight-bold">*</span>Country of Origin</label>
                                                    <select class="form-control" name="OFWCountyOfOrigin" id="OFWCountyOfOrigin">
                                                        <option value="" disabled {{(is_null(old('OFWCountyOfOrigin'))) ? 'selected' : ''}}>Choose...</option>
                                                        @foreach ($countries as $country)
                                                            @if($country != 'Philippines')
                                                                <option value="{{$country}}" {{(old('OFWCountyOfOrigin') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="isFNT"><span class="text-danger font-weight-bold">*</span>Foreign National Traveler</label>
                                                <select class="form-control" name="isFNT" id="isFNT" required>
                                                    <option value="1" {{(old('isFNT') == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('isFNT') == 0 || is_null(old('isFNT'))) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divisFNT">
                                                <div class="form-group">
                                                    <label for="FNTCountryOfOrigin"><span class="text-danger font-weight-bold">*</span>Country of Origin</label>
                                                    <select class="form-control" name="FNTCountryOfOrigin" id="FNTCountryOfOrigin">
                                                        <option value="" selected disabled>Choose...</option>
                                                        @foreach ($countries as $country)
                                                            @if($country != 'Philippines')
                                                                <option value="{{$country}}" {{(old('FNTCountryOfOrigin') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>        
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="isLSI"><span class="text-danger font-weight-bold">*</span>Locally Stranded Individual/APOR/Traveler</label>
                                                <select class="form-control" name="isLSI" id="isLSI" required>
                                                    <option value="1" {{(old('isLSI') == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('isLSI') == 0 || is_null(old('isLSI'))) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divisLSI">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label for="LSIProvince"><span class="text-danger font-weight-bold">*</span>Province of Origin</label>
                                                          <select class="form-control" name="LSIProvince" id="LSIProvince">
                                                                <option value="" selected disabled>Choose...</option>
                                                          </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="LSICity"><span class="text-danger font-weight-bold">*</span>City of Origin</label>
                                                            <select class="form-control" name="LSICity" id="LSICity">
                                                                  <option value="" selected disabled>Choose...</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="isLivesOnClosedSettings"><span class="text-danger font-weight-bold">*</span>Lives in Closed Settings</label>
                                                <select class="form-control" name="isLivesOnClosedSettings" id="isLivesOnClosedSettings" required>
                                                    <option value="1" {{(old('isLivesOnClosedSettings') == 1) ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{(old('isLivesOnClosedSettings') == 0 || is_null(old('isLivesOnClosedSettings'))) ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="divisLivesOnClosedSettings">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label for="institutionType"><span class="text-danger font-weight-bold">*</span>Specify Type of Institution</label>
                                                          <input type="text" class="form-control" name="institutionType" id="institutionType" value="{{old('institutionType')}}">
                                                          <small><i>(e.g. prisons, residential facilities, retirement communities, care homes, camps etc.)</i></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="institutionName"><span class="text-danger font-weight-bold">*</span>Name of Institution</label>
                                                            <input type="text" class="form-control" name="institutionName" id="institutionName" value="{{old('institutionName')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">10. Permanent Address and Contact Information (If different from current address)</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">House No./Lot/Bldg.</label>
                                                <input type="text" class="form-control" name="" id="rec_plot" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Street/Purok/Sitio</label>
                                                <input type="text" class="form-control" name="" id="rec_pstreet" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Barangay</label>
                                                <input type="text" class="form-control" name="" id="rec_pbrgy" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Municipality/City</label>
                                                <input type="text" class="form-control" name="" id="rec_pcity" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Province</label>
                                                <input type="text" class="form-control" name="" id="rec_pprovince" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Home Phone No. (& Area Code)</label>
                                                <input type="text" class="form-control" name="" id="rec_pphone" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Cellphone No.</label>
                                                <input type="text" class="form-control" name="" id="rec_pmobile" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Email Address</label>
                                                <input type="text" class="form-control" name="" id="rec_pemail" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">11. Address Outside the Philippines and Contact Information (for Overseas Filipino Workers and Individuals with Residence outside PH)</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="oaddresslotbldg"><span class="text-danger font-weight-bold">*</span>Lot/Bldg.</label>
                                                <input type="text" class="form-control" name="oaddresslotbldg" id="oaddresslotbldg" value="{{old('oaddresslotbldg')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="oaddressstreet"><span class="text-danger font-weight-bold">*</span>Street</label>
                                                <input type="text" class="form-control" name="oaddressstreet" id="oaddressstreet" value="{{old('oaddressstreet')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="oaddressscity"><span class="text-danger font-weight-bold">*</span>Municipality/City</label>
                                                <input type="text" class="form-control" name="oaddressscity" id="oaddressscity" value="{{old('oaddressscity')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="oaddresssprovince"><span class="text-danger font-weight-bold">*</span>Province</label>
                                                <input type="text" class="form-control" name="oaddresssprovince" id="oaddresssprovince" value="{{old('oaddresssprovince')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="oaddressscountry"><span class="text-danger font-weight-bold">*</span>Country</label>
                                                    <input type="text" class="form-control" name="oaddressscountry" id="oaddressscountry" value="{{old('oaddressscountry')}}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="placeofwork"><span class="text-danger font-weight-bold">*</span>Place of Work</label>
                                                <input type="text" class="form-control" name="placeofwork" id="placeofwork" value="{{old('placeofwork')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="employername"><span class="text-danger font-weight-bold">*</span>Employer's Name</label>
                                                <input type="text" class="form-control" name="employername" id="employername" value="{{old('employername')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="employercontactnumber">Employer's/Office Contact No.</label>
                                                <input type="text" class="form-control" name="employercontactnumber" id="employercontactnumber" value="{{old('employercontactnumber')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">12. Clinical Information</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="dateOnsetOfIllness">Date of Onset of Illness</label>
                                              <input type="date" class="form-control" name="dateOnsetOfIllness" id="dateOnsetOfIllness" max="{{date('Y-m-d')}}">
                                            </div>
                                            <div class="card">
                                                <div class="card-header">Signs and Symptoms (Check all that apply if present)</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Asymptomatic"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck1"
                                                                  {{(is_array(old('sasCheck')) && in_array("Asymptomatic", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck1">Asymptomatic</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Fever"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck2"
                                                                  {{(is_array(old('sasCheck')) && in_array("Fever", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck2">Fever</label>
                                                            </div>
                                                            <div id="divFeverChecked">
                                                                <div class="form-group mt-2">
                                                                  <label for="SASFeverDeg">Degrees (in Celcius)</label>
                                                                  <input type="number" class="form-control" name="SASFeverDeg" id="SASFeverDeg" min="1" value="{{old('SASFeverDeg')}}">
                                                                </div>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Cough"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck3"
                                                                  {{(is_array(old('sasCheck')) && in_array("Cough", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck3">Cough</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="General Weakness"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck4"
                                                                  {{(is_array(old('sasCheck')) && in_array("General Weakness", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck4">General Weakness</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Fatigue"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck5"
                                                                  {{(is_array(old('sasCheck')) && in_array("Fatigue", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck5">Fatigue</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Headache"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck6"
                                                                  {{(is_array(old('sasCheck')) && in_array("Headache", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck6">Headache</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Myalgia"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck7"
                                                                  {{(is_array(old('sasCheck')) && in_array("Myalgia", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck7">Myalgia</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Sore throat"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck8"
                                                                  {{(is_array(old('sasCheck')) && in_array("Sore throat", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck8">Sore Throat</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Coryza"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck9"
                                                                  {{(is_array(old('sasCheck')) && in_array("Coryza", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck9">Coryza</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Dyspnea"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck10"
                                                                  {{(is_array(old('sasCheck')) && in_array("Dyspnea", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck10">Dyspnea</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Anorexia"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck11"
                                                                  {{(is_array(old('sasCheck')) && in_array("Anorexia", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck11">Anorexia</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Nausea"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck12"
                                                                  {{(is_array(old('sasCheck')) && in_array("Nausea", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck12">Nausea</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Vomiting"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck13"
                                                                  {{(is_array(old('sasCheck')) && in_array("Vomiting", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck13">Vomiting</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Diarrhea"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck14"
                                                                  {{(is_array(old('sasCheck')) && in_array("Diarrhea", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck14">Diarrhea</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Altered Mental Status"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck15"
                                                                  {{(is_array(old('sasCheck')) && in_array("Altered Mental Status", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck15">Altered Mental Status</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Anosmia (Loss of Smell)"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck16"
                                                                  {{(is_array(old('sasCheck')) && in_array("Anosmia (Loss of Smell)", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck16">Anosmia (Loss of Smell)</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Ageusia (Loss of Taste)"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck17"
                                                                  {{(is_array(old('sasCheck')) && in_array("Ageusia (Loss of Taste)", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck17">Ageusia (Loss of Taste)</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Others"
                                                                  name="sasCheck[]"
                                                                  id="signsCheck18"
                                                                  {{(is_array(old('sasCheck')) && in_array("Others", old('sasCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="signsCheck18">Others</label>
                                                            </div>
                                                            <div id="divSASOtherChecked">
                                                                <div class="form-group mt-2">
                                                                  <label for="SASOtherRemarks">Specify Findings</label>
                                                                  <input type="text" class="form-control" name="SASOtherRemarks" id="SASOtherRemarks" value="{{old('SASOtherRemarks')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header">Comorbidities (Check all that apply if present)</div>
                                                <div class="card-body">
                                                    <div class="row comoOpt">
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="None"
                                                                  name="comCheck[]"
                                                                  id="comCheck1"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("None", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck1">None</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Hypertension"
                                                                  name="comCheck[]"
                                                                  id="comCheck2"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Hypertension", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck2">Hypertension</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Diabetes"
                                                                  name="comCheck[]"
                                                                  id="comCheck3"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Diabetes", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck3">Diabetes</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Heart Disease"
                                                                  name="comCheck[]"
                                                                  id="comCheck4"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Heart Disease", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck4">Heart Disease</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Lung Disease"
                                                                  name="comCheck[]"
                                                                  id="comCheck5"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Lung Disease", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck5">Lung Disease</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Gastrointestinal"
                                                                  name="comCheck[]"
                                                                  id="comCheck6"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Gastrointestinal", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck6">Gastrointestinal</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Genito-urinary"
                                                                  name="comCheck[]"
                                                                  id="comCheck7"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Genito-urinary", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck7">Genito-urinary</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Neurological Disease"
                                                                  name="comCheck[]"
                                                                  id="comCheck8"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Neurological Disease", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck8">Neurological Disease</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Cancer"
                                                                  name="comCheck[]"
                                                                  id="comCheck9"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Cancer", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck9">Cancer</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input
                                                                  class="form-check-input"
                                                                  type="checkbox"
                                                                  value="Others"
                                                                  name="comCheck[]"
                                                                  id="comCheck10"
                                                                  required
                                                                  {{(is_array(old('comCheck')) && in_array("Others", old('comCheck'))) ? 'checked' : ''}}
                                                                />
                                                                <label class="form-check-label" for="comCheck10">Others</label>
                                                            </div>
                                                            <div id="divComOthersChecked">
                                                                <div class="form-group mt-2">
                                                                  <label for="COMOOtherRemarks">Specify Findings</label>
                                                                  <input type="text" class="form-control" name="COMOOtherRemarks" id="COMOOtherRemarks" value="{{old('COMOOtherRemarks')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for=""><span class="text-danger font-weight-bold">*</span>Are you Pregnant?</label>
                                                        <input type="text" class="form-control" name="" id="rec_ispregnant" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="PregnantLMP"><span class="text-danger font-weight-bold">*</span>LMP</label>
                                                        <input type="date" class="form-control" name="PregnantLMP" id="PregnantLMP" value="{{old('PregnantLMP')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                              <label for="highRiskPregnancy"><span class="text-danger font-weight-bold">*</span>High Risk Pregnancy</label>
                                              <select class="form-control" name="highRiskPregnancy" id="highRiskPregnancy">
                                                <option value="0" {{(is_null(old('highRiskPregnancy')) || old('highRiskPregnancy') == 0) ? 'selected' : ''}}>No</option>
                                                <option value="1" {{(old('highRiskPregnancy') == 1) ? 'selected' : ''}}>Yes</option>
                                              </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                      <label for="diagWithSARI"><span class="text-danger font-weight-bold">*</span>Were you diagnosed to have Severe Acute Respiratory Illness? <small><i>(Refer to Appendix 2)</i></small></label>
                                      <select class="form-control" name="diagWithSARI" id="diagWithSARI" required>
                                        <option value="1" {{(old('diagWithSARI') == 1) ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{(is_null(old('diagWithSARI')) || old('diagWithSARI') == 0) ? 'selected' : ''}}>No</option>
                                      </select>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            Chest imaging findings suggestive of COVID-19
                                            <hr>
                                            <span class="text-danger font-weight-bold">*</span>Imaging Done (Check all that apply)
                                        </div>
                                        <div class="card-body imaOptions">
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Chest Radiography"
                                                  name="imaCheck[]"
                                                  id="imaCheck1"
                                                  required
                                                  {{(is_array(old('imaCheck')) && in_array("Chest Radiography", old('imaCheck'))) ? 'checked' : ''}}
                                                />
                                                <label class="form-check-label" for="imaCheck1">Chest Radiography</label>
                                            </div>
                                            <div id="imaCheck1div">
                                                <div class="form-group mt-3">
                                                    <label for="chestRDResult"><span class="text-danger font-weight-bold">*</span>Results</label>
                                                    <select class="form-control" name="chestRDResult" id="chestRDResult">
                                                      <option value="" disabled {{(is_null(old('chestRDResult'))) ? 'selected' : ''}}>Choose...</option>
                                                      <option value="NORMAL" {{(old('chestRDResult') == "NORMAL") ? 'selected' : ''}}>Normal</option>
                                                      <option value="HAZY" {{(old('chestRDResult') == "HAZY") ? 'selected' : ''}}>Hazy opacities, often rounded in morphology, with peripheral and lower lung distribution</option>
                                                      <option value="PENDING" {{(old('chestRDResult') == "PENDING") ? 'selected' : ''}}>Pending</option>
                                                      <option value="OTHERS" {{(old('chestRDResult') == "OTHERS") ? 'selected' : ''}}>Other findings</option>
                                                    </select>
                                                </div>
                                                <div id="imaCheck1Others">
                                                    <div class="form-group">
                                                        <label for="chestRDOtherFindings"><span class="text-danger font-weight-bold">*</span>Specify Findings</label>
                                                        <input type="text" class="form-control" name="chestRDOtherFindings" id="chestRDOtherFindings" value="{{old('chestRDOtherFindings')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Chest CT"
                                                  name="imaCheck[]"
                                                  id="imaCheck2"
                                                  required
                                                  {{(is_array(old('imaCheck')) && in_array("Chest CT", old('imaCheck'))) ? 'checked' : ''}}
                                                />
                                                <label class="form-check-label" for="imaCheck2">Chest CT</label>
                                            </div>
                                            <div id="imaCheck2div">
                                                <div class="form-group mt-3">
                                                    <label for="chestCTResult"><span class="text-danger font-weight-bold">*</span>Results</label>
                                                    <select class="form-control" name="chestCTResult" id="chestCTResult">
                                                      <option value="" disabled {{(is_null(old('chestCTResult'))) ? 'selected' : ''}}>Choose...</option>
                                                      <option value="NORMAL" {{(old('chestCTResult') == "NORMAL") ? 'selected' : ''}}>Normal</option>
                                                      <option value="MULTIPLE" {{(old('chestCTResult') == "MULTIPLE") ? 'selected' : ''}}>Multiple bilateral ground glass opacities, often rounded in morphology, with peripheral and lower lung distribution</option>
                                                      <option value="PENDING" {{(old('chestCTResult') == "PENDING") ? 'selected' : ''}}>Pending</option>
                                                      <option value="OTHERS" {{(old('chestCTResult') == "OTHERS") ? 'selected' : ''}}>Other findings</option>
                                                    </select>
                                                </div>
                                                <div id="imaCheck2Others">
                                                    <div class="form-group">
                                                        <label for="chestCTOtherFindings"><span class="text-danger font-weight-bold">*</span>Specify Findings</label>
                                                        <input type="text" class="form-control" name="chestCTOtherFindings" id="chestCTOtherFindings" value="{{old('chestCTOtherFindings')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Lung Ultrasound"
                                                  name="imaCheck[]"
                                                  id="imaCheck3"
                                                  required
                                                  {{(is_array(old('imaCheck')) && in_array("Lung Ultrasound", old('imaCheck'))) ? 'checked' : ''}}
                                                />
                                                <label class="form-check-label" for="imaCheck3">Lung Ultrasound</label>
                                            </div>
                                            <div id="imaCheck3div">
                                                <div class="form-group mt-3">
                                                    <label for="lungUSResult"><span class="text-danger font-weight-bold">*</span>Results</label>
                                                    <select class="form-control" name="lungUSResult" id="lungUSResult">
                                                      <option value="" disabled {{(is_null(old('lungUSResult'))) ? 'selected' : ''}}>Choose...</option>
                                                      <option value="NORMAL" {{(old('lungUSResult') == "NORMAL") ? 'selected' : ''}}>Normal</option>
                                                      <option value="THICKENED" {{(old('lungUSResult') == "THICKENED") ? 'selected' : ''}}>Thickened pleural lines, B lines (multifocal, discrete, or confluent), consolidative patterns with or without air bronchograms.</option>
                                                      <option value="PENDING" {{(old('lungUSResult') == "PENDING") ? 'selected' : ''}}>Pending</option>
                                                      <option value="OTHERS" {{(old('lungUSResult') == "OTHERS") ? 'selected' : ''}}>Other findings</option>
                                                    </select>
                                                </div>
                                                <div id="imaCheck3Others">
                                                    <div class="form-group">
                                                        <label for="lungUSOtherFindings"><span class="text-danger font-weight-bold">*</span>Specify Findings</label>
                                                        <input type="text" class="form-control" name="lungUSOtherFindings" id="lungUSOtherFindings" value="{{old('lungUSOtherFindings')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="None"
                                                  name="imaCheck[]"
                                                  id="imaCheck4"
                                                  required
                                                  {{(is_array(old('imaCheck')) && in_array("None", old('imaCheck'))) ? 'checked' : ''}}
                                                />
                                                <label class="form-check-label" for="imaCheck4">None</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">13. Laboratory Information</div>
                                <div class="card-body">
                                    <div class="labOptions">
                                        <table class="table table-bordered">
                                            <thead class="text-center">
                                                <tr>
                                                    <th style="vertical-align: middle"><span class="text-danger font-weight-bold">*</span>Test Done (Check all that apply)</th>
                                                    <th style="vertical-align: middle"><span class="text-danger font-weight-bold">*</span>Date Collected</th>
                                                    <th style="vertical-align: middle">Laboratory</th>
                                                    <th style="vertical-align: middle"><span class="text-danger font-weight-bold">*</span>Results</th>
                                                    <th style="vertical-align: middle">Date Released</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="vertical-align: middle">
                                                        <div class="form-check">
                                                            <input
                                                              class="form-check-input"
                                                              type="checkbox"
                                                              value="RT-PCR (OPS)"
                                                              name="labCheck[]"
                                                              id="labCheck1"
                                                              required
                                                              {{(is_array(old('labCheck')) && in_array("RT-PCR (OPS)", old('labCheck'))) ? 'checked' : ''}}
                                                            />
                                                            <label class="form-check-label" for="labCheck1">RT-PCR (OPS)</label>
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="rtpcr_ops_date_collected" id="rtpcr_ops_date_collected" max="{{date('Y-m-d')}}" value="{{old('rtpcr_ops_date_collected')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="text" class="form-control" name="rtpcr_ops_laboratory" id="rtpcr_ops_laboratory" value="{{old('rtpcr_ops_laboratory')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <select class="form-control" name="rtpcr_ops_results" id="rtpcr_ops_results">
                                                            <option value="" disabled {{(is_null(old('rtpcr_ops_results'))) ? 'selected' : ''}}>Choose...</option>
                                                            <option value="PENDING" {{(old('rtpcr_ops_results') == "PENDING") ? 'selected' : ''}}>Pending</option>
                                                            <option value="POSITIVE" {{(old('rtpcr_ops_results') == "POSITIVE") ? 'selected' : ''}}>Positive</option>
                                                            <option value="NEGATIVE" {{(old('rtpcr_ops_results') == "NEGATIVE") ? 'selected' : ''}}>Negative</option>
                                                            <option value="EQUIVOCAL" {{(old('rtpcr_ops_results') == "EQUIVOCAL") ? 'selected' : ''}}>Equivocal</option>
                                                        </select>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="rtpcr_ops_date_released" id="rtpcr_ops_date_released" max="{{date('Y-m-d')}}" value="{{old('rtpcr_ops_date_released')}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: middle">
                                                        <div class="form-check">
                                                            <input
                                                              class="form-check-input"
                                                              type="checkbox"
                                                              value="RT-PCR (NPS)"
                                                              name="labCheck[]"
                                                              id="labCheck2"
                                                              required
                                                              {{(is_array(old('labCheck')) && in_array("RT-PCR (NPS)", old('labCheck'))) ? 'checked' : ''}}
                                                            />
                                                            <label class="form-check-label" for="labCheck2">RT-PCR (NPS)</label>
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="rtpcr_nps_date_collected" id="rtpcr_nps_date_collected" max="{{date('Y-m-d')}}" value="{{old('rtpcr_nps_date_collected')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="text" class="form-control" name="rtpcr_nps_laboratory" id="rtpcr_nps_laboratory" value="{{old('rtpcr_nps_laboratory')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <select class="form-control" name="rtpcr_nps_results" id="rtpcr_nps_results">
                                                            <option value="" disabled {{(is_null(old('rtpcr_nps_results'))) ? 'selected' : ''}}>Choose...</option>
                                                            <option value="PENDING" {{(old('rtpcr_nps_results') == "PENDING") ? 'selected' : ''}}>Pending</option>
                                                            <option value="POSITIVE" {{(old('rtpcr_nps_results') == "POSITIVE") ? 'selected' : ''}}>Positive</option>
                                                            <option value="NEGATIVE" {{(old('rtpcr_nps_results') == "NEGATIVE") ? 'selected' : ''}}>Negative</option>
                                                            <option value="EQUIVOCAL" {{(old('rtpcr_nps_results') == "EQUIVOCAL") ? 'selected' : ''}}>Equivocal</option>
                                                        </select>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="rtpcr_nps_date_released" id="rtpcr_nps_date_released" max="{{date('Y-m-d')}}" value="{{old('rtpcr_nps_date_released')}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: middle">
                                                        <div class="form-check">
                                                            <input
                                                              class="form-check-input"
                                                              type="checkbox"
                                                              value="RT-PCR (OPS and NPS)"
                                                              name="labCheck[]"
                                                              id="labCheck3"
                                                              required
                                                              {{(is_array(old('labCheck')) && in_array("RT-PCR (OPS and NPS)", old('labCheck'))) ? 'checked' : ''}}
                                                            />
                                                            <label class="form-check-label" for="labCheck3">RT-PCR (OPS and NPS)</label>
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="rtpcr_both_date_collected" id="rtpcr_both_date_collected" max="{{date('Y-m-d')}}" value="{{old('rtpcr_both_date_collected')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="text" class="form-control" name="rtpcr_both_laboratory" id="rtpcr_both_laboratory" value="{{old('rtpcr_both_laboratory')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <select class="form-control" name="rtpcr_both_results" id="rtpcr_both_results">
                                                            <option value="" disabled {{(is_null(old('rtpcr_both_results'))) ? 'selected' : ''}}>Choose...</option>
                                                            <option value="PENDING" {{(old('rtpcr_both_results') == "PENDING") ? 'selected' : ''}}>Pending</option>
                                                            <option value="POSITIVE" {{(old('rtpcr_both_results') == "POSITIVE") ? 'selected' : ''}}>Positive</option>
                                                            <option value="NEGATIVE" {{(old('rtpcr_both_results') == "NEGATIVE") ? 'selected' : ''}}>Negative</option>
                                                            <option value="EQUIVOCAL" {{(old('rtpcr_both_results') == "EQUIVOCAL") ? 'selected' : ''}}>Equivocal</option>
                                                        </select>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="rtpcr_both_date_released" id="rtpcr_both_date_released" max="{{date('Y-m-d')}}" value="{{old('rtpcr_both_date_released')}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: middle">
                                                        <div class="form-check">
                                                            <input
                                                              class="form-check-input"
                                                              type="checkbox"
                                                              value="RT-PCR"
                                                              name="labCheck[]"
                                                              id="labCheck4"
                                                              required
                                                              {{(is_array(old('labCheck')) && in_array("RT-PCR", old('labCheck'))) ? 'checked' : ''}}
                                                            />
                                                            <label class="form-check-label" for="labCheck4">RT-PCR</label>
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            <input type="text" class="form-control" name="rtpcr_spec_type" id="rtpcr_spec_type" placeholder="Specimen Type" value="{{old('rtpcr_spec_type')}}">
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="rtpcr_spec_date_collected" id="rtpcr_spec_date_collected" max="{{date('Y-m-d')}}" value="{{old('rtpcr_spec_date_collected')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="text" class="form-control" name="rtpcr_spec_laboratory" id="rtpcr_spec_laboratory" value="{{old('rtpcr_spec_laboratory')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <select class="form-control" name="rtpcr_spec_results" id="rtpcr_spec_results">
                                                            <option value="" disabled {{(is_null(old('rtpcr_spec_results'))) ? 'selected' : ''}}>Choose...</option>
                                                            <option value="PENDING" {{(old('rtpcr_spec_results') == "PENDING") ? 'selected' : ''}}>Pending</option>
                                                            <option value="POSITIVE" {{(old('rtpcr_spec_results') == "POSITIVE") ? 'selected' : ''}}>Positive</option>
                                                            <option value="NEGATIVE" {{(old('rtpcr_spec_results') == "NEGATIVE") ? 'selected' : ''}}>Negative</option>
                                                            <option value="EQUIVOCAL" {{(old('rtpcr_spec_results') == "EQUIVOCAL") ? 'selected' : ''}}>Equivocal</option>
                                                        </select>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="rtpcr_spec_date_released" id="rtpcr_spec_date_released" max="{{date('Y-m-d')}}" value="{{old('rtpcr_spec_date_released')}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: middle">
                                                        <div class="form-check">
                                                            <input
                                                              class="form-check-input"
                                                              type="checkbox"
                                                              value="Antigen Test"
                                                              name="labCheck[]"
                                                              id="labCheck5"
                                                              required
                                                              {{(is_array(old('labCheck')) && in_array("Antigen Test", old('labCheck'))) ? 'checked' : ''}}
                                                            />
                                                            <label class="form-check-label" for="labCheck5">Antigen Test</label>
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="antigen_date_collected" id="antigen_date_collected" max="{{date('Y-m-d')}}" value="{{old('antigen_date_collected')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="text" class="form-control" name="antigen_laboratory" id="antigen_laboratory" value="{{old('antigen_laboratory')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <select class="form-control" name="antigen_results" id="antigen_results">
                                                            <option value="" disabled {{(is_null(old('antigen_results'))) ? 'selected' : ''}}>Choose...</option>
                                                            <option value="PENDING" {{(old('antigen_results') == "PENDING") ? 'selected' : ''}}>Pending</option>
                                                            <option value="POSITIVE" {{(old('antigen_results') == "POSITIVE") ? 'selected' : ''}}>Positive</option>
                                                            <option value="NEGATIVE" {{(old('antigen_results') == "NEGATIVE") ? 'selected' : ''}}>Negative</option>
                                                            <option value="EQUIVOCAL" {{(old('antigen_results') == "EQUIVOCAL") ? 'selected' : ''}}>Equivocal</option>
                                                        </select>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="antigen_date_released" id="antigen_date_released" max="{{date('Y-m-d')}}" value="{{old('rtpcr_spec_date_released')}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: middle">
                                                        <div class="form-check">
                                                            <input
                                                              class="form-check-input"
                                                              type="checkbox"
                                                              value="Antibody Test"
                                                              name="labCheck[]"
                                                              id="labCheck6"
                                                              required
                                                              {{(is_array(old('labCheck')) && in_array("Antibody Test", old('labCheck'))) ? 'checked' : ''}}
                                                            />
                                                            <label class="form-check-label" for="labCheck6">Antibody Test</label>
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="antibody_date_collected" id="antibody_date_collected" max="{{date('Y-m-d')}}" value="{{old('antibody_date_collected')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="text" class="form-control" name="antibody_laboratory" id="antibody_laboratory" value="{{old('antibody_laboratory')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <select class="form-control" name="antibody_results" id="antibody_results">
                                                            <option value="" disabled {{(is_null(old('antibody_results'))) ? 'selected' : ''}}>Choose...</option>
                                                            <option value="M+G-" {{(old('antibody_results') == "M+G-") ? 'selected' : ''}}>IgM (+) IgG (-)</option>
                                                            <option value="G+M-" {{(old('antibody_results') == "G+M-") ? 'selected' : ''}}>IgG (+) IgM (-)</option>
                                                            <option value="M+G+" {{(old('antibody_results') == "M+G+") ? 'selected' : ''}}>IgM (+) IgG (+)</option>
                                                            <option value="M-G-" {{(old('antibody_results') == "M-G-") ? 'selected' : ''}}>IgM (-) IgG (-)</option>
                                                        </select>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="antibody_date_released" id="antibody_date_released" max="{{date('Y-m-d')}}" value="{{old('antibody_date_released')}}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="vertical-align: middle">
                                                        <div class="form-check">
                                                            <input
                                                              class="form-check-input"
                                                              type="checkbox"
                                                              value="Others"
                                                              name="labCheck[]"
                                                              id="labCheck7"
                                                              required
                                                              {{(is_array(old('labCheck')) && in_array("Others", old('labCheck'))) ? 'checked' : ''}}
                                                            />
                                                            <label class="form-check-label" for="labCheck7">Others</label>
                                                        </div>
                                                        <div class="form-group mt-3">
                                                          <input type="text" class="form-control" name="others_specify" id="others_specify" placeholder="Others: Specify" value="{{old('others_specify')}}">
                                                        </div>
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="others_date_collected" id="others_date_collected" max="{{date('Y-m-d')}}" value="{{old('others_date_collected')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="text" class="form-control" name="others_laboratory" id="others_laboratory" value="{{old('others_laboratory')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="text" class="form-control" name="others_results" id="others_results" placeholder="Specify Result" value="{{old('others_results')}}">
                                                    </td>
                                                    <td style="vertical-align: middle">
                                                        <input type="date" class="form-control" name="others_date_released" id="others_date_released" max="{{date('Y-m-d')}}" value="{{old('others_date_released')}}">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="testedPositiveUsingRTPCRBefore"><span class="text-danger font-weight-bold">*</span>Have you ever tested positive using RT-PCR before?</label>
                                                    <select class="form-control" name="testedPositiveUsingRTPCRBefore" id="testedPositiveUsingRTPCRBefore" required>
                                                      <option value="1" {{(old('testedPositiveUsingRTPCRBefore') == 1) ? 'selected' : ''}}>Yes</option>
                                                      <option value="0" {{(is_null(old('testedPositiveUsingRTPCRBefore')) || old('testedPositiveUsingRTPCRBefore') == 0) ? 'selected' : ''}}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="testedPositiveNumOfSwab"><span class="text-danger font-weight-bold">*</span>Number of previous RT-PCR swabs done</label>
                                                    <input type="number" class="form-control" name="testedPositiveNumOfSwab" id="testedPositiveNumOfSwab" min="0" value="{{(is_null(old('testedPositiveNumOfSwab'))) ? '0' : old('testedPositiveNumOfSwab')}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="divIfTestedPositiveUsingRTPCR">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="testedPositiveLab"><span class="text-danger font-weight-bold">*</span>Laboratory</label>
                                                      <input type="text" class="form-control" name="testedPositiveLab" id="testedPositiveLab" value="{{old('testedPositiveLab')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="testedPositiveSpecCollectedDate"><span class="text-danger font-weight-bold">*</span>Date of Specimen Collection</label>
                                                        <input type="date" class="form-control" name="testedPositiveSpecCollectedDate" id="testedPositiveSpecCollectedDate" max="{{date('Y-m-d')}}" value="{{old('testedPositiveSpecCollectedDate')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">14. Outcome/Condition at Time of Report</div>
                                <div class="card-body">
                                    <div class="form-group">
                                      <label for="outcomeCondition"><span class="text-danger font-weight-bold">*</span>Select Condition</label>
                                      <select class="form-control" name="outcomeCondition" id="outcomeCondition">
                                        <option value="" {{(is_null(old('outcomeCondition'))) ? 'selected' : ''}}>N/A</option>
                                        <option value="Active" {{(old('outcomeCondition') == 'Active') ? 'selected' : ''}}>Active (Currently admitted or in isolation/quarantine)</option>
                                        <option value="Recovered" {{(old('outcomeCondition') == 'Recovered') ? 'selected' : ''}}>Recovered</option>
                                        <option value="Died" {{(old('outcomeCondition') == 'Died') ? 'selected' : ''}}>Died</option>
                                      </select>
                                    </div>
                                    <div id="ifOutcomeRecovered">
                                        <div class="form-group">
                                          <label for="outcomeRecovDate"><span class="text-danger font-weight-bold">*</span>Date of Recovery</label>
                                          <input type="date" class="form-control" name="outcomeRecovDate" id="outcomeRecovDate" max="{{date('Y-m-d')}}" value="{{old('outcomeRecovDate')}}">
                                        </div>
                                    </div>
                                    <div id="ifOutcomeDied">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="outcomeDeathDate"><span class="text-danger font-weight-bold">*</span>Date of Death</label>
                                                    <input type="date" class="form-control" name="outcomeDeathDate" id="outcomeDeathDate" max="{{date('Y-m-d')}}" value="{{old('outcomeDeathDate')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathImmeCause"><span class="text-danger font-weight-bold">*</span>Immediate Cause</label>
                                                    <input type="text" class="form-control" name="deathImmeCause" id="deathImmeCause" value="{{old('deathImmeCause')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathAnteCause">Antecedent Cause</label>
                                                    <input type="text" class="form-control" name="deathAnteCause" id="deathAnteCause" value="{{old('deathAnteCause')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathUndeCause">Underlying Cause</label>
                                                    <input type="text" class="form-control" name="deathUndeCause" id="deathUndeCause" value="{{old('deathUndeCause')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header font-weight-bold">Part 3. Contact Tracing</div>
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header">15. Exposure History</div>
                                <div class="card-body">
                                    <div class="form-group">
                                      <label for="expoitem1"><span class="text-danger font-weight-bold">*</span>History of exposure to known probable and/or confirmed COVID-19 case 14 days before the onset of signs and symptoms?  OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                                      <select class="form-control" name="expoitem1" id="expoitem1" required>
                                        <option value="" disabled {{(is_null(old('expoitem1'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="1" {{(old('expoitem1') == 1) ? 'selected' : ''}}>Yes</option>
                                        <option value="2" {{(old('expoitem1') == 2) ? 'selected' : ''}}>No</option>
                                        <option value="3" {{(old('expoitem1') == 3) ? 'selected' : ''}}>Unknown</option>
                                      </select>
                                    </div>
                                    <div id="divExpoitem1">
                                        <div class="form-group">
                                          <label for=""><span class="text-danger font-weight-bold">*</span>Date of Last Contact</label>
                                          <input type="date" class="form-control" name="expoDateLastCont" id="expoDateLastCont" max="{{date('Y-m-d')}}" value="{{old('expoDateLastCont')}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="expoitem2"><span class="text-danger font-weight-bold">*</span>Have you been in a place with a known COVID-19 community transmission 14 days before the onset of signs and symptoms? OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                                        <select class="form-control" name="expoitem2" id="expoitem2" required>
                                          <option value="" disabled {{(is_null(old('expoitem2'))) ? 'selected' : ''}}>Choose...</option>
                                          <option value="1" {{(old('expoitem2') == 1) ? 'selected' : ''}}>Yes</option>
                                          <option value="2" {{(old('expoitem2') == 2) ? 'selected' : ''}}>No</option>
                                          <option value="3" {{(old('expoitem2') == 3) ? 'selected' : ''}}>Unknown</option>
                                        </select>
                                    </div>
                                    <div id="ifVisited">
                                        <div class="card">
                                            <div class="card-header">Specify Place</div>
                                            <div class="card-body">
                                                <div class="alert alert-info" role="alert">
                                                    Check all that apply, provide details such as name of establishment, transport service, venue, location, etc. And date of visit.
                                                </div>
                                                <table class="table table-bordered">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th>Place Visited</th>
                                                            <th>Details</th>
                                                            <th>Date of Visit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="vertical-align: middle">
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="1"
                                                                      name="vOpt[]"
                                                                      id="vOpt1"
                                                                      {{(is_array(old('vOpt')) && in_array(1, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt1">Health Facility</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt1_details" id="vOpt1_details" value="{{old('vOpt1_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt1_date" id="vOpt1_date" max="{{date('Y-m-d')}}" value="{{old('vOpt1_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="2"
                                                                      name="vOpt[]"
                                                                      id="vOpt2"
                                                                      {{(is_array(old('vOpt')) && in_array(2, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt2">Closed Settings (e.g. Jail)</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt2_details" id="vOpt2_details" value="{{old('vOpt2_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt2_date" id="vOpt2_date" max="{{date('Y-m-d')}}" value="{{old('vOpt2_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="3"
                                                                      name="vOpt[]"
                                                                      id="vOpt3"
                                                                      {{(is_array(old('vOpt')) && in_array(3, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt3">Market</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt3_details" id="vOpt3_details" value="{{old('vOpt3_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt3_date" id="vOpt3_date" max="{{date('Y-m-d')}}" value="{{old('vOpt3_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="4"
                                                                      name="vOpt[]"
                                                                      id="vOpt4"
                                                                      {{(is_array(old('vOpt')) && in_array(4, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt4">Home</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt4_details" id="vOpt4_details" value="{{old('vOpt4_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt4_date" id="vOpt4_date" max="{{date('Y-m-d')}}" value="{{old('vOpt4_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="5"
                                                                      name="vOpt[]"
                                                                      id="vOpt5"
                                                                      {{(is_array(old('vOpt')) && in_array(5, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt5">International Travel</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt5_details" id="vOpt5_details" value="{{old('vOpt5_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt5_date" id="vOpt5_date" max="{{date('Y-m-d')}}" value="{{old('vOpt5_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="6"
                                                                      name="vOpt[]"
                                                                      id="vOpt6"
                                                                      {{(is_array(old('vOpt')) && in_array(6, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt6">School</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt6_details" id="vOpt6_details" value="{{old('vOpt6_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt6_date" id="vOpt6_date" max="{{date('Y-m-d')}}" value="{{old('vOpt6_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="7"
                                                                      name="vOpt[]"
                                                                      id="vOpt7"
                                                                      {{(is_array(old('vOpt')) && in_array(7, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt7">Transportation</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt7_details" id="vOpt7_details" value="{{old('vOpt7_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt7_date" id="vOpt7_date" max="{{date('Y-m-d')}}" value="{{old('vOpt7_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="8"
                                                                      name="vOpt[]"
                                                                      id="vOpt8"
                                                                      {{(is_array(old('vOpt')) && in_array(8, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt8">Workplace</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt8_details" id="vOpt8_details" value="{{old('vOpt8_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt8_date" id="vOpt8_date" max="{{date('Y-m-d')}}" value="{{old('vOpt8_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="9"
                                                                      name="vOpt[]"
                                                                      id="vOpt9"
                                                                      {{(is_array(old('vOpt')) && in_array(9, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt9">Local Travel</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt9_details" id="vOpt9_details" value="{{old('vOpt9_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt9_date" id="vOpt9_date" max="{{date('Y-m-d')}}" value="{{old('vOpt9_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="10"
                                                                      name="vOpt[]"
                                                                      id="vOpt10"
                                                                      {{(is_array(old('vOpt')) && in_array(10, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt10">Social Gathering</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt10_details" id="vOpt10_details" value="{{old('vOpt10_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt10_date" id="vOpt10_date" max="{{date('Y-m-d')}}" value="{{old('vOpt10_date')}}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input
                                                                      class="form-check-input"
                                                                      type="checkbox"
                                                                      value="11"
                                                                      name="vOpt[]"
                                                                      id="vOpt11"
                                                                      {{(is_array(old('vOpt')) && in_array(11, old('vOpt'))) ? 'checked' : ''}}
                                                                    />
                                                                    <label class="form-check-label" for="vOpt11">Others</label>
                                                                </div>
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="text" class="form-control" name="vOpt11_details" id="vOpt11_details" value="{{old('vOpt11_details')}}">
                                                            </td>
                                                            <td style="vertical-align: middle">
                                                                <input type="date" class="form-control" name="vOpt11_date" id="vOpt11_date" max="{{date('Y-m-d')}}" value="{{old('vOpt11_date')}}">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">16. Travel History</div>
                                <div class="card-body">
                                    <div class="form-group">
                                      <label for="hasTravHistOtherCountries"><span class="text-danger font-weight-bold">*</span>History of travel/visit/work in other countries with a known COVID-19 transmission 14 days before the onset of signs and symptoms</label>
                                      <select class="form-control" name="hasTravHistOtherCountries" id="hasTravHistOtherCountries" required>
                                        <option value="1" {{(old('hasTravHistOtherCountries') == 1) ? 'selected' : ''}}>Yes</option>
                                        <option value="0" {{(old('hasTravHistOtherCountries') == 0 || is_null(old('hasTravHistOtherCountries'))) ? 'selected' : ''}}>No</option>
                                      </select>
                                    </div>
                                    <div id="div_haveHistoryOfTravelOtherCountries">
                                        <div class="form-group">
                                          <label for="historyCountryOfExit"><span class="text-danger font-weight-bold">*</span>Country of Exit</label>
                                          <select class="form-control" name="historyCountryOfExit" id="historyCountryOfExit">
                                              <option value="" {{(is_null('historyCountryOfExit')) ? 'selected disabled' : ''}}>Choose...</option>
                                                @foreach ($countries as $country)
                                                    @if($country != 'Philippines')
                                                        <option value="{{$country}}" {{(old('historyCountryOfExit') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                                    @endif
                                                @endforeach
                                          </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                  <label for="country_historyTypeOfTranspo"><span class="text-danger font-weight-bold">*</span>Airline/Sea Vessel</label>
                                                  <input type="text" class="form-control" name="country_historyTypeOfTranspo" id="country_historyTypeOfTranspo" value="{{old('country_historyTypeOfTranspo')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="country_historyTranspoNo"><span class="text-danger font-weight-bold">*</span>Flight/Vessel Number</label>
                                                    <input type="text" class="form-control" name="country_historyTranspoNo" id="country_historyTranspoNo" value="{{old('country_historyTranspoNo')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="country_historyTranspoDateOfDeparture"><span class="text-danger font-weight-bold">*</span>Date of Departure</label>
                                                    <input type="date" class="form-control" name="country_historyTranspoDateOfDeparture" id="country_historyTranspoDateOfDeparture" value="{{old('country_historyTranspoDateOfDeparture')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="country_historyTranspoDateOfArrival"><span class="text-danger font-weight-bold">*</span>Date of Arrival in PH</label>
                                                    <input type="date" class="form-control" name="country_historyTranspoDateOfArrival" id="country_historyTranspoDateOfArrival" value="{{old('country_historyTranspoDateOfArrival')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="hasTravHistLocal"><span class="text-danger font-weight-bold">*</span>History of travel/visit/work in other local place with a known COVID-19 transmission 14 days before the onset of signs and symptoms</label>
                                        <select class="form-control" name="hasTravHistLocal" id="hasTravHistLocal">
                                          <option value="1" {{(old('hasTravHistLocal') == 1) ? 'selected' : ''}}>Yes</option>
                                          <option value="0" {{(old('hasTravHistLocal') == 0 || is_null(old('hasTravHistLocal'))) ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div id="div_haveHistoryOfTravelLocal">
                                        <div class="form-group">
                                            <label for="historyPlaceOfOrigin"><span class="text-danger font-weight-bold">*</span>Place of Origin</label>
                                            <input type="text" class="form-control" name="historyPlaceOfOrigin" id="historyPlaceOfOrigin" value="{{old('historyPlaceOfOrigin')}}">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                  <label for="local_historyTypeOfTranspo"><span class="text-danger font-weight-bold">*</span>Airline/Sea vessel/Bus line/Train</label>
                                                  <input type="text" class="form-control" name="local_historyTypeOfTranspo" id="local_historyTypeOfTranspo" value="{{old('local_historyTypeOfTranspo')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="local_historyTranspoNo"><span class="text-danger font-weight-bold">*</span>Flight/Vessel Number/Bus No.</label>
                                                    <input type="text" class="form-control" name="local_historyTranspoNo" id="local_historyTranspoNo" value="{{old('local_historyTranspoNo')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="local_historyTranspoDateOfDeparture"><span class="text-danger font-weight-bold">*</span>Date of Departure</label>
                                                    <input type="date" class="form-control" name="local_historyTranspoDateOfDeparture" id="local_historyTranspoDateOfDeparture" value="{{old('local_historyTranspoDateOfDeparture')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="local_historyTranspoDateOfArrival"><span class="text-danger font-weight-bold">*</span>Date of Arrival in the Current City/Mun</label>
                                                    <input type="date" class="form-control" name="local_historyTranspoDateOfArrival" id="local_historyTranspoDateOfArrival" value="{{old('local_historyTranspoDateOfArrival')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt-3">
                                        <div class="card-header">List the names of persons who were with you two days prior to onset of illness until this date and their contact numbers.</div>
                                        <div class="card-body">
                                            <div class="alert alert-info" role="alert">
                                                If asymptomatic, list the names of persons who were with you on the day you submitted specimen for testing until this date and their contact numbers.
                                            </div>
                                            <table class="table table-bordered">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Contact No.</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            <input type="text" class="form-control" name="contact1Name" id="contact1Name" value="{{old('contact1Name')}}">
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <input type="number" class="form-control" name="contact1No" id="contact1No" value="{{old('contact1No')}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            <input type="text" class="form-control" name="contact2Name" id="contact2Name" value="{{old('contact2Name')}}">
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <input type="number" class="form-control" name="contact2No" id="contact2No" value="{{old('contact2No')}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            <input type="text" class="form-control" name="contact3Name" id="contact3Name" value="{{old('contact3Name')}}">
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <input type="number" class="form-control" name="contact3No" id="contact3No" value="{{old('contact3No')}}">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="vertical-align: middle;">
                                                            <input type="text" class="form-control" name="contact4Name" id="contact4Name" value="{{old('contact4Name')}}">
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            <input type="number" class="form-control" name="contact4No" id="contact4No" value="{{old('contact4No')}}">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">For Additional Close Contact (Include ALL Household Contacts)</div>
                                <div class="card-body">
                                    @for($i=1;$i<=10;$i++)
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                <label for="">{{$i}}. Name</label>
                                                <input type="text" class="form-control" name="addContName[]" id="" value="{{old('addContName.'.$i)}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Contact Number</label>
                                                    <input type="number" class="form-control" name="addContNo[]" id="" value="{{old('addContNo.'.$i)}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="">Exposure Settings</label>
                                                    <input type="text" class="form-control" name="addContExpSet[]" id="" value="{{old('addContExpSet.'.$i)}}">
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" id="formsubmit">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {

            $('#records_id').selectize();

            $('#records_id').change(function (e) { 
                e.preventDefault();
                var uid = $('#records_id').val();
                if ($('#records_id').val().length != 0) {
                    fetchRecords(uid);
                }
            }).trigger('change');

            $('#informantName').keydown(function (e) { 
                if($(this).val().length <= 0 || $(this).val() == "") {
                    $('#informantRelationship').prop({disabled: true, required: false});
                    $('#informantMobile').prop({disabled: true, required: false});
                }
                else {
                    $('#informantRelationship').val("");
                    $('#informantRelationship').prop({disabled: false, required: true});
                    $('#informantMobile').prop({disabled: false, required: true});
                }
            }).trigger('keydown');

            $(function(){
                var requiredCheckboxes = $('.testingCatOptions :checkbox[required]');
                requiredCheckboxes.change(function(){
                    if(requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                }).trigger('change');
            });

            $(function(){
                var requiredCheckboxes = $('.imaOptions :checkbox[required]');
                requiredCheckboxes.change(function(){
                    if(requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                }).trigger('change');;
            });

            $(function(){
                var requiredCheckboxes = $('.comoOpt :checkbox[required]');
                requiredCheckboxes.change(function(){
                    if(requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                }).trigger('change');;
            });

            $(function(){
                var requiredCheckboxes = $('.labOptions :checkbox[required]');
                requiredCheckboxes.change(function(){
                    if(requiredCheckboxes.is(':checked')) {
                        requiredCheckboxes.removeAttr('required');
                    } else {
                        requiredCheckboxes.attr('required', 'required');
                    }
                }).trigger('change');;
            });

            $('#LSICity').prop({'disabled': true, 'required': false});

            $.getJSON("{{asset('json/refregion.json')}}", function(data) {
                var sorted = data.sort(function(a, b) {
                    if (a.regDesc > b.regDesc) {
                    return 1;
                    }
                    if (a.regDesc < b.regDesc) {
                    return -1;
                    }
                    return 0;
                });
                $.each(sorted, function(key, val) {
                    $("#sfacilityregion").append('<option value="'+val.regCode+'">'+val.regDesc+'</option>');
                });
            });

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
                    $("#LSIProvince").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
                });
            });

            $('#sfacilityregion').change(function (e) {
                e.preventDefault();
                $('#facilityprovince').prop({'disabled': false, 'required': true});
                $('#facilityprovince').empty();
                $("#facilityprovince").append('<option value="" selected disabled>Choose...</option>');

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
                        if($('#sfacilityregion').val() == val.regCode) {
                            $("#facilityprovince").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
                        }
                    });
			    });
            });

            $('#LSIProvince').change(function (e) { 
                e.preventDefault();
                $('#LSICity').prop({'disabled': false, 'required': true});
                $('#LSICity').empty();
                $("#LSICity").append('<option value="" selected disabled>Choose...</option>');
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
                        if($('#LSIProvince').val() == val.provCode) {
                            $("#LSICity").append('<option value="'+val.citymunCode+'">'+val.citymunDesc+'</option>');
                        }
                    });
			    });
            });

            //$('#OFWCountyOfOrigin').selectize();
            //$('#FNTCountryOfOrigin').selectize();
        
            $('#divYes1').hide();
            $('#divYes2').hide();
            $('#divYes3').hide();
            $('#divYes4').hide();
            $('#divYes5').hide();
            $('#divYes6').hide();
            
            $('#dispositionDate').prop("type", "datetime-local");

            $('#havePreviousCovidConsultation').change(function (e) { 
                e.preventDefault();
                if($(this).val() == '1') {
                    $('#divYes1').show();
                    $('#divYes2').show();

                    $('#dateOfFirstConsult').prop('required', true);
                    $('#facilityNameOfFirstConsult').prop('required', true);
                }
                else {
                    $('#divYes1').hide();
                    $('#divYes2').hide();

                    $('#dateOfFirstConsult').prop('required', false);
                    $('#facilityNameOfFirstConsult').prop('required', false);
                }
            }).trigger('change');

            $('#admittedInHealthFacility').change(function (e) { 
                e.preventDefault();
                if($(this).val() == '1') {
                    $('#divYes3').show();
                    $('#divYes4').show();
                    $('#dateOfFirstConsult').prop('required', true);
                    $('#admittedInMultipleHealthFacility').prop('required', true);
                    $('#facilitynameOfFirstAdmitted').prop('required', true);
                    $('#facilityregion').prop('required', true);
                    $('#facilityprovince').prop('required', true);
                }
                else {
                    $('#divYes3').hide();
                    $('#divYes4').hide();
                    $('#dateOfFirstConsult').prop('required', false);
                    $('#admittedInMultipleHealthFacility').prop('required', false);
                    $('#facilitynameOfFirstAdmitted').prop('required', false);
                    $('#facilityregion').prop('required', false);
                    $('#facilityprovince').prop('required', false);
                }
            }).trigger('change');

            $('#dispositionType').change(function (e) {
                e.preventDefault();
                $('#dispositionDate').prop("type", "datetime-local");
                
                if($(this).val() == '1' || $(this).val() == '2') {
                    $('#dispositionName').prop('required', true);
                    $('#dispositionDate').prop('required', true);
                }
                else if ($(this).val() == '3' || $(this).val() == '4') {
                    $('#dispositionName').prop('required', false);
                    $('#dispositionDate').prop('required', true);
                }
                else if ($(this).val() == '5') {
                    $('#dispositionName').prop('required', true);
                    $('#dispositionDate').prop('required', false);
                }
                else if($(this).val().length == 0){
                    $('#dispositionName').prop('required', false);
                    $('#dispositionDate').prop('required', false);
                }

                if($(this).val() == '1') {
                    $('#divYes5').show();
                    $('#divYes6').show();

                    $('#dispositionlabel').text("Name of Hospital");
                    $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
                }
                if($(this).val() == '2') {
                    $('#divYes5').show();
                    $('#divYes6').show();

                    $('#dispositionlabel').text("Name of Facility");
                    $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
                }
                if($(this).val() == '3') {
                    $('#divYes5').hide();
                    $('#divYes6').show();

                    $('#dispositiondatelabel').text("Date and Time isolated/quarantined at home");
                }
                if($(this).val() == '4') {
                    $('#divYes5').hide();
                    $('#divYes6').show();

                    $('#dispositionDate').prop("type", "date");

                    $('#dispositiondatelabel').text("Date of Discharge");
                }
                if($(this).val() == '5') {
                    $('#divYes5').show();
                    $('#divYes6').hide();

                    $('#dispositionlabel').text("State Reason");
                }
                else if($(this).val().length == 0){
                    $('#divYes5').hide();
                    $('#divYes6').hide();
                }
            }).trigger('change');

            $('#isHealthCareWorker').change(function (e) { 
                e.preventDefault();
                if($(this).val() == '0') {
                    $('#divisHealthCareWorker').hide();
                    $('#healthCareCompanyName').prop('required', false);
                    $('#healthCareCompanyLocation').prop('required', false);
                }
                else {
                    $('#divisHealthCareWorker').show();
                    $('#healthCareCompanyName').prop('required', true);
                    $('#healthCareCompanyLocation').prop('required', true);
                }
            }).trigger('change');

            $('#isOFW').change(function (e) {
                if($(this).val() == '0') {
                    $('#divisOFW').hide();
                    $('#OFWCountyOfOrigin').prop('required', false);

                    $('#oaddresslotbldg').prop({'required': false, 'disabled': true});
                    $('#oaddressstreet').prop({'required': false, 'disabled': true});
                    $('#oaddressscity').prop({'required': false, 'disabled': true});
                    $('#oaddresssprovince').prop({'required': false, 'disabled': true});
                    $('#oaddressscountry').prop({'required': false, 'disabled': true});
                    $('#placeofwork').prop({'required': false, 'disabled': true});
                    $('#employername').prop({'required': false, 'disabled': true});
                    $('#employercontactnumber').prop({'required': false, 'disabled': true});

                    $('#oaddresslotbldg').val('N/A');
                    $('#oaddressstreet').val('N/A');
                    $('#oaddressscity').val('N/A');
                    $('#oaddresssprovince').val('N/A');
                    $('#oaddressscountry').val('N/A');
                    $('#placeofwork').val('N/A');
                    $('#employername').val('N/A');
                    $('#employercontactnumber').val('N/A');
                }
                else {
                    $('#divisOFW').show();
                    var control = $('#OFWCountyOfOrigin')[0].selectize;
                    control.clear();
                    $('#oaddressscountry').val('N/A');
                    $('#OFWCountyOfOrigin').prop('required', true);

                    $('#oaddresslotbldg').prop({required: true, disabled: false});
                    $('#oaddressstreet').prop({required: true, disabled: false});
                    $('#oaddressscity').prop({required: true, disabled: false});
                    $('#oaddresssprovince').prop({required: true, disabled: false});
                    $('#oaddressscountry').prop({required: true, disabled: false});
                    $('#placeofwork').prop({required: true, disabled: false});
                    $('#employername').prop({required: true, disabled: false});
                    $('#employercontactnumber').prop({required: true, disabled: false});

                    $('#oaddresslotbldg').val('');
                    $('#oaddressstreet').val('');
                    $('#oaddressscity').val('');
                    $('#oaddresssprovince').val('');
                    $('#placeofwork').val('');
                    $('#employername').val('');
                    $('#employercontactnumber').val('');
                }
            }).trigger('change');

            $('#OFWCountyOfOrigin').change(function (e) { 
                e.preventDefault();
                $('#oaddressscountry').val($(this).val());
            });

            $('#isFNT').change(function (e) {
                if($(this).val() == '0') {
                    $('#divisFNT').hide();
                    $('#FNTCountryOfOrigin').prop('required', false);
                }
                else {
                    $('#divisFNT').show();
                    $('#FNTCountryOfOrigin').prop('required', true);
                }
            }).trigger('change');

            $('#isLSI').change(function (e) {
                if($(this).val() == '0') {
                    $('#divisLSI').hide();
                    $('#LSIProvince').prop('required', false);
                    $('#LSICity').prop('required', false);
                }
                else {
                    $('#divisLSI').show();
                    $('#LSIProvince').prop('required', true);
                    $('#LSICity').prop('required', true);
                }
            }).trigger('change');

            $('#isLivesOnClosedSettings').change(function (e) {
                if($(this).val() == '0') {
                    $('#divisLivesOnClosedSettings').hide();
                    $('#institutionType').prop('required', false);
                    $('#institutionName').prop('required', false);
                }
                else {
                    $('#divisLivesOnClosedSettings').show();
                    $('#institutionType').prop('required', true);
                    $('#institutionName').prop('required', true);
                }
            }).trigger('change');

            $('#signsCheck2').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divFeverChecked').show();
                    $('#SASFeverDeg').prop('required', true);
                }
                else {
                    $('#divFeverChecked').hide();
                    $('#SASFeverDeg').prop('required', false);
                }
            }).trigger('change');

            $('#signsCheck18').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divSASOtherChecked').show();
                    $('#SASOtherRemarks').prop('required', true);
                }
                else {
                    $('#divSASOtherChecked').hide();
                    $('#SASOtherRemarks').prop('required', false);
                }
            }).trigger('change');

            $('#comCheck10').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#divComOthersChecked').show();
                    $('#COMOOtherRemarks').prop('required', true);
                }
                else {
                    $('#divComOthersChecked').hide();
                    $('#COMOOtherRemarks').prop('required', false);
                }
            }).trigger('change');
            
            $('#comCheck1').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#comCheck2').prop({'disabled': true, 'checked': false});
                    $('#comCheck3').prop({'disabled': true, 'checked': false});
                    $('#comCheck4').prop({'disabled': true, 'checked': false});
                    $('#comCheck5').prop({'disabled': true, 'checked': false});
                    $('#comCheck6').prop({'disabled': true, 'checked': false});
                    $('#comCheck7').prop({'disabled': true, 'checked': false});
                    $('#comCheck8').prop({'disabled': true, 'checked': false});
                    $('#comCheck9').prop({'disabled': true, 'checked': false});
                    $('#comCheck10').prop({'disabled': true, 'checked': false});
                }
                else {
                    $('#comCheck2').prop({'disabled': false, 'checked': false});
                    $('#comCheck3').prop({'disabled': false, 'checked': false});
                    $('#comCheck4').prop({'disabled': false, 'checked': false});
                    $('#comCheck5').prop({'disabled': false, 'checked': false});
                    $('#comCheck6').prop({'disabled': false, 'checked': false});
                    $('#comCheck7').prop({'disabled': false, 'checked': false});
                    $('#comCheck8').prop({'disabled': false, 'checked': false});
                    $('#comCheck9').prop({'disabled': false, 'checked': false});
                    $('#comCheck10').prop({'disabled': false, 'checked': false});
                }
            });

            @if(is_null(old('comCheck')))
                $('#comCheck1').prop('checked', true);
            @endif

            $('#rec_ispregnant').val("N/A");
            $('#PregnantLMP').prop({disabled: true, required: false});
            $('#highRiskPregnancy').prop({disabled: true, required: false});

            $('#imaCheck1').change(function (e) {
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#imaCheck1div').show();
                    $('#chestRDResult').prop('required', true);
                }  
                else {
                    $('#imaCheck1div').hide();
                    $('#chestRDResult').prop('required', false);
                }
            }).trigger('change');

            $('#chestRDResult').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "4") {
                    $('#imaCheck1Others').show();
                    $('#chestRDOtherFindings').prop('required', true);
                }
                else {
                    $('#imaCheck1Others').hide();
                    $('#chestRDOtherFindings').prop('required', false);
                }
            }).trigger('change');

            $('#imaCheck2').change(function (e) {
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#imaCheck2div').show();
                    $('#chestCTResult').prop('required', true);
                }  
                else {
                    $('#imaCheck2div').hide();
                    $('#chestCTResult').prop('required', false);
                }
            }).trigger('change');

            $('#chestCTResult').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "4") {
                    $('#imaCheck2Others').show();
                    $('#chestCTOtherFindings').prop('required', true);
                }
                else {
                    $('#imaCheck2Others').hide();
                    $('#chestCTOtherFindings').prop('required', false);
                }
            }).trigger('change');

            $('#imaCheck3').change(function (e) {
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#imaCheck3div').show();
                    $('#lungUSResult').prop('required', true);
                }  
                else {
                    $('#imaCheck3div').hide();
                    $('#lungUSResult').prop('required', false);
                }
            }).trigger('change');

            $('#lungUSResult').change(function (e) {
                e.preventDefault();
                if($(this).val() == "4") {
                    $('#imaCheck3Others').show();
                    $('#lungUSOtherFindings').prop('required', true);
                }
                else {
                    $('#imaCheck3Others').hide();
                    $('#lungUSOtherFindings').prop('required', false);
                }
            }).trigger('change');

            //If Imaging Done list clicked 'None'
            $('#imaCheck4').change(function (e) {
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#imaCheck1').prop({disabled: true, checked: false});
                    $('#imaCheck1').trigger('change');
                    $('#imaCheck2').prop({disabled: true, checked: false});
                    $('#imaCheck2').trigger('change');
                    $('#imaCheck3').prop({disabled: true, checked: false});
                    $('#imaCheck3').trigger('change');
                }
                else {
                    $('#imaCheck1').prop({disabled: false, checked: false});
                    $('#imaCheck1').trigger('change');
                    $('#imaCheck2').prop({disabled: false, checked: false});
                    $('#imaCheck2').trigger('change');
                    $('#imaCheck3').prop({disabled: false, checked: false});
                    $('#imaCheck3').trigger('change');
                }
            });

            @if(is_null(old('imaCheck')))
                $('#imaCheck4').prop('checked', true);
            @endif
        
            $('#labCheck1').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#rtpcr_ops_date_collected').prop({disabled: false, required: true});
                    $('#rtpcr_ops_laboratory').prop({disabled: false, required: false});
                    $('#rtpcr_ops_results').prop({disabled: false, required: true});
                    $('#rtpcr_ops_date_released').prop({disabled: false, required: false});
                }
                else {
                    $('#rtpcr_ops_date_collected').prop({disabled: true, required: false});
                    $('#rtpcr_ops_laboratory').prop({disabled: true, required: false});
                    $('#rtpcr_ops_results').prop({disabled: true, required: false});
                    $('#rtpcr_ops_date_released').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#labCheck2').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#rtpcr_nps_date_collected').prop({disabled: false, required: true});
                    $('#rtpcr_nps_laboratory').prop({disabled: false, required: false});
                    $('#rtpcr_nps_results').prop({disabled: false, required: true});
                    $('#rtpcr_nps_date_released').prop({disabled: false, required: false});
                }
                else {
                    $('#rtpcr_nps_date_collected').prop({disabled: true, required: false});
                    $('#rtpcr_nps_laboratory').prop({disabled: true, required: false});
                    $('#rtpcr_nps_results').prop({disabled: true, required: false});
                    $('#rtpcr_nps_date_released').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#labCheck3').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#rtpcr_both_date_collected').prop({disabled: false, required: true});
                    $('#rtpcr_both_laboratory').prop({disabled: false, required: false});
                    $('#rtpcr_both_results').prop({disabled: false, required: true});
                    $('#rtpcr_both_date_released').prop({disabled: false, required: false});
                }
                else {
                    $('#rtpcr_both_date_collected').prop({disabled: true, required: false});
                    $('#rtpcr_both_laboratory').prop({disabled: true, required: false});
                    $('#rtpcr_both_results').prop({disabled: true, required: false});
                    $('#rtpcr_both_date_released').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#labCheck4').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#rtpcr_spec_type').prop({disabled: false, required: true});
                    $('#rtpcr_spec_date_collected').prop({disabled: false, required: true});
                    $('#rtpcr_spec_laboratory').prop({disabled: false, required: false});
                    $('#rtpcr_spec_results').prop({disabled: false, required: true});
                    $('#rtpcr_spec_date_released').prop({disabled: false, required: false});
                }
                else {
                    $('#rtpcr_spec_type').prop({disabled: true, required: false});
                    $('#rtpcr_spec_date_collected').prop({disabled: true, required: false});
                    $('#rtpcr_spec_laboratory').prop({disabled: true, required: false});
                    $('#rtpcr_spec_results').prop({disabled: true, required: false});
                    $('#rtpcr_spec_date_released').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#labCheck5').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#antigen_date_collected').prop({disabled: false, required: true});
                    $('#antigen_laboratory').prop({disabled: false, required: false});
                    $('#antigen_results').prop({disabled: false, required: true});
                    $('#antigen_date_released').prop({disabled: false, required: false});
                }
                else {
                    $('#antigen_date_collected').prop({disabled: true, required: false});
                    $('#antigen_laboratory').prop({disabled: true, required: false});
                    $('#antigen_results').prop({disabled: true, required: false});
                    $('#antigen_date_released').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#labCheck6').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#antibody_date_collected').prop({disabled: false, required: true});
                    $('#antibody_laboratory').prop({disabled: false, required: false});
                    $('#antibody_results').prop({disabled: false, required: true});
                    $('#antigen_date_released').prop({disabled: false, required: false});
                }
                else {
                    $('#antibody_date_collected').prop({disabled: true, required: false});
                    $('#antibody_laboratory').prop({disabled: true, required: false});
                    $('#antibody_results').prop({disabled: true, required: false});
                    $('#antibody_date_released').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#labCheck7').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#others_date_collected').prop({disabled: false, required: true});
                    $('#others_laboratory').prop({disabled: false, required: false});
                    $('#others_results').prop({disabled: false, required: true});
                    $('#others_date_released').prop({disabled: false, required: false});
                    $('#others_specify').prop({disabled: false, required: true});
                }
                else {
                    $('#others_date_collected').prop({disabled: true, required: false});
                    $('#others_laboratory').prop({disabled: true, required: false});
                    $('#others_results').prop({disabled: true, required: false});
                    $('#others_date_released').prop({disabled: true, required: false});
                    $('#others_specify').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#testedPositiveUsingRTPCRBefore').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "1") {
                    $('#divIfTestedPositiveUsingRTPCR').show();
                    $('#testedPositiveLab').prop('required', true);
                    $('#testedPositiveSpecCollectedDate').prop('required', true);
                }
                else {
                    $('#divIfTestedPositiveUsingRTPCR').hide();
                    $('#testedPositiveLab').prop('required', false);
                    $('#testedPositiveSpecCollectedDate').prop('required', false);
                }
            }).trigger('change');

            $('#outcomeCondition').change(function (e) { 
                e.preventDefault();
                if($(this).val() == 'Recovered') {
                    $('#ifOutcomeRecovered').show();
                    $('#outcomeRecovDate').prop('required', true);
                    $('#ifOutcomeDied').hide();
                    $('#outcomeDeathDate').prop('required', false);
                    $('#deathImmeCause').prop('required', false);
                    $('#deathAnteCause').prop('required', false);
                    $('#deathUndeCause').prop('required', false);
                }
                else if($(this).val() == 'Died') {
                    $('#ifOutcomeRecovered').hide();
                    $('#outcomeRecovDate').prop('required', false);
                    $('#ifOutcomeDied').show();
                    $('#outcomeDeathDate').prop('required', true);
                    $('#deathImmeCause').prop('required', true);
                    $('#deathAnteCause').prop('required', true);
                    $('#deathUndeCause').prop('required', true);
                }
                else {
                    $('#ifOutcomeRecovered').hide();
                    $('#outcomeRecovDate').prop('required', false);
                    $('#ifOutcomeDied').hide();
                    $('#outcomeDeathDate').prop('required', false);
                    $('#deathImmeCause').prop('required', false);
                    $('#deathAnteCause').prop('required', false);
                    $('#deathUndeCause').prop('required', false);
                }
            }).trigger('change');

            $('#expoitem1').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "1") {
                    $('#divExpoitem1').show();
                    $('#expoDateLastCont').prop('required', true);
                }
                else {
                    $('#divExpoitem1').hide();
                    $('#expoDateLastCont').prop('required', false);
                }
            }).trigger('change');

            $('#expoitem2').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "1" || $(this).val() == "3") {
                    $('#ifVisited').show();
                }
                else {
                    $('#ifVisited').hide();
                    $('#vOpt1').prop('checked', false);
                    $('#vOpt1').trigger('change');
                    $('#vOpt2').prop('checked', false);
                    $('#vOpt2').trigger('change');
                    $('#vOpt3').prop('checked', false);
                    $('#vOpt3').trigger('change');
                    $('#vOpt4').prop('checked', false);
                    $('#vOpt4').trigger('change');
                    $('#vOpt5').prop('checked', false);
                    $('#vOpt5').trigger('change');
                    $('#vOpt6').prop('checked', false);
                    $('#vOpt6').trigger('change');
                    $('#vOpt7').prop('checked', false);
                    $('#vOpt7').trigger('change');
                    $('#vOpt8').prop('checked', false);
                    $('#vOpt8').trigger('change');
                    $('#vOpt9').prop('checked', false);
                    $('#vOpt9').trigger('change');
                    $('#vOpt10').prop('checked', false);
                    $('#vOpt10').trigger('change');
                    $('#vOpt11').prop('checked', false);
                    $('#vOpt11').trigger('change');
                }
            }).trigger('change');

            $('#vOpt1').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt1_details').prop({disabled: false, required: true});
                    $('#vOpt1_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt1_details').prop({disabled: true, required: false});
                    $('#vOpt1_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt2').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt2_details').prop({disabled: false, required: true});
                    $('#vOpt2_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt2_details').prop({disabled: true, required: false});
                    $('#vOpt2_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt3').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt3_details').prop({disabled: false, required: true});
                    $('#vOpt3_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt3_details').prop({disabled: true, required: false});
                    $('#vOpt3_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt4').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt4_details').prop({disabled: false, required: true});
                    $('#vOpt4_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt4_details').prop({disabled: true, required: false});
                    $('#vOpt4_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt5').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt5_details').prop({disabled: false, required: true});
                    $('#vOpt5_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt5_details').prop({disabled: true, required: false});
                    $('#vOpt5_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt6').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt6_details').prop({disabled: false, required: true});
                    $('#vOpt6_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt6_details').prop({disabled: true, required: false});
                    $('#vOpt6_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt7').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt7_details').prop({disabled: false, required: true});
                    $('#vOpt7_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt7_details').prop({disabled: true, required: false});
                    $('#vOpt7_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt8').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt8_details').prop({disabled: false, required: true});
                    $('#vOpt8_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt8_details').prop({disabled: true, required: false});
                    $('#vOpt8_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt9').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt9_details').prop({disabled: false, required: true});
                    $('#vOpt9_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt9_details').prop({disabled: true, required: false});
                    $('#vOpt9_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt10').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt10_details').prop({disabled: false, required: true});
                    $('#vOpt10_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt10_details').prop({disabled: true, required: false});
                    $('#vOpt10_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#vOpt11').change(function (e) { 
                e.preventDefault();
                if($(this).prop('checked') == true) {
                    $('#vOpt11_details').prop({disabled: false, required: true});
                    $('#vOpt11_date').prop({disabled: false, required: true});
                }
                else {
                    $('#vOpt11_details').prop({disabled: true, required: false});
                    $('#vOpt11_date').prop({disabled: true, required: false});
                }
            }).trigger('change');

            $('#hasTravHistOtherCountries').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "1") {
                    $('#div_haveHistoryOfTravelOtherCountries').show();
                    $('#historyCountryOfExit').prop('required', true);
                    $('#country_historyTypeOfTranspo').prop('required', true);
                    $('#country_historyTranspoNo').prop('required', true);
                    $('#country_historyTranspoDateOfDeparture').prop('required', true);
                    $('#country_historyTranspoDateOfArrival').prop('required', true);
                }
                else {
                    $('#div_haveHistoryOfTravelOtherCountries').hide();
                    $('#historyCountryOfExit').prop('required', false);
                    $('#country_historyTypeOfTranspo').prop('required', false);
                    $('#country_historyTranspoNo').prop('required', false);
                    $('#country_historyTranspoDateOfDeparture').prop('required', false);
                    $('#country_historyTranspoDateOfArrival').prop('required', false);
                }
            }).trigger('change');

            $('#hasTravHistLocal').change(function (e) { 
                e.preventDefault();
                if($(this).val() == "1") {
                    $('#div_haveHistoryOfTravelLocal').show();
                    $('#historyPlaceOfOrigin').prop('required', true);
                    $('#local_historyTypeOfTranspo').prop('required', true);
                    $('#local_historyTranspoNo').prop('required', true);
                    $('#local_historyTranspoDateOfDeparture').prop('required', true);
                    $('#local_historyTranspoDateOfArrival').prop('required', true);
                }
                else{
                    $('#div_haveHistoryOfTravelLocal').hide();
                    $('#historyPlaceOfOrigin').prop('required', false);
                    $('#local_historyTypeOfTranspo').prop('required', false);
                    $('#local_historyTranspoNo').prop('required', false);
                    $('#local_historyTranspoDateOfDeparture').prop('required', false);
                    $('#local_historyTranspoDateOfArrival').prop('required', false);
                }
            }).trigger('change');
        });

        function fetchRecords(id) {
                $.ajax({
                    url: "/ajaxGetUserRecord/"+id,
                    type: "get",
                    dataType: "json",
                    cache: false,
                    processData: false,
                    error: function(xhr, status, error) {
                        var err = JSON.parse(xhr.responseText);
                        alert(err.Message);
                    },
                    success: function (response) {
                        if(response['data'][0].philhealth == null) {
                            var recph = "N/A"
                        }
                        else {
                            var recph = response['data'][0].philhealth;
                        }

                        $('#rec_philhealth').val(recph);
                        $('#rec_lname').val(response['data'][0].lname);
                        $('#rec_fname').val(response['data'][0].fname);
                        $('#rec_mname').val(response['data'][0].mname);

                        var formattedDate = new Date(response['data'][0].bdate);
                        var d = formattedDate.getDate();
                        var m =  formattedDate.getMonth();
                        m += 1;  // JavaScript months are 0-11
                        var y = formattedDate.getFullYear();

                        if (d < 10) {
                            d = "0" + d;
                        }
                        if (m < 10) {
                            m = "0" + m;
                        }

                        $('#rec_bdate').val(m + "/" + d + "/" + y);
                        
                        dob = new Date(response['data'][0].bdate);
                        var today = new Date();
                        var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
                        $('#rec_age').val(age);
                        $('#rec_gender').val(response['data'][0].gender);
                        $('#rec_cs').val(response['data'][0].cs);
                        $('#rec_nationality').val(response['data'][0].nationality);
                        $('#rec_occupation').val((response['data'][0].occupation == null) ? "N/A" : response['data'][0].occupation);
                        $('#rec_lot').val(response['data'][0].address_houseno);
                        $('#rec_street').val(response['data'][0].address_street);
                        $('#rec_brgy').val(response['data'][0].address_brgy);
                        $('#rec_city').val(response['data'][0].address_city);
                        $('#rec_province').val(response['data'][0].address_province);
                        $('#rec_phoneno').val((response['data'][0].phoneno == null) ? "N/A" : response['data'][0].phoneno);
                        $('#rec_mobile').val(response['data'][0].mobile);
                        $('#rec_email').val((response['data'][0].email == null) ? "N/A" : response['data'][0].email);

                        $('#rec_olot').val((response['data'][0].occupation_lotbldg == null) ? "N/A" : response['data'][0].occupation_lotbldg);
                        $('#rec_ostreet').val((response['data'][0].occupation_street == null) ? "N/A" : response['data'][0].occupation_street);
                        $('#rec_obrgy').val((response['data'][0].occupation_brgy == null) ? "N/A" : response['data'][0].occupation_brgy);
                        $('#rec_ocity').val((response['data'][0].occupation_city == null) ? "N/A" : response['data'][0].occupation_city);
                        $('#rec_oprovince').val((response['data'][0].occupation_province == null) ? "N/A" : response['data'][0].occupation_province);
                        $('#rec_oname').val((response['data'][0].occupation_name == null) ? "N/A" : response['data'][0].occupation_name);
                        $('#rec_omobile').val((response['data'][0].occupation_mobile == null) ? "N/A" : response['data'][0].occupation_mobile);
                        $('#rec_oemail').val((response['data'][0].occupation_email == null) ? "N/A" : response['data'][0].occupation_email);

                        $('#rec_plot').val((response['data'][0].permaaddress_houseno == null) ? "N/A" : response['data'][0].permaaddress_houseno);
                        $('#rec_pstreet').val((response['data'][0].permaaddress_street == null) ? "N/A" : response['data'][0].permaaddress_street);
                        $('#rec_pbrgy').val((response['data'][0].permaaddress_brgy == null) ? "N/A" : response['data'][0].permaaddress_brgy);
                        $('#rec_pcity').val((response['data'][0].permaaddress_city == null) ? "N/A" : response['data'][0].permaaddress_city);
                        $('#rec_pprovince').val((response['data'][0].permaaddress_province == null) ? "N/A" : response['data'][0].permaaddress_province);
                        $('#rec_pphone').val((response['data'][0].permaphoneno == null) ? "N/A" : response['data'][0].permaphoneno);
                        $('#rec_pmobile').val((response['data'][0].permamobile == null) ? "N/A" : response['data'][0].permamobile);
                        $('#rec_pemail').val((response['data'][0].permaemail == null) ? "N/A" : response['data'][0].permaemail);

                        $('#rec_ispregnant').val((response['data'][0].isPregnant == 1) ? "Yes" : "No");

                        if(response['data'][0].isPregnant == 1) {
                            $('#PregnantLMP').prop({disabled: false, required: true});
                            $('#highRiskPregnancy').prop({disabled: false, required: true});
                        }
                        else {
                            $('#PregnantLMP').prop({disabled: true, required: false});
                            $('#highRiskPregnancy').prop({disabled: true, required: false});
                        }
                    }
                });
            }
    </script>
@endsection