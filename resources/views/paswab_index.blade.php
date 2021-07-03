@extends('layouts.app')

@section('content')
    <form action="{{route('paswab.store')}}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header font-weight-bold text-primary">Schedule for Swab Form</div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{$error}}</p>
                            <hr>
                        @endforeach
                    </div>
                    <hr>
                    @endif
                    <div class="alert alert-info" role="alert">
                        All fields marked with an asterisk (<span class="text-danger font-weight-bold">*</span>) are required.
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">1. Consultation Details</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pType"><span class="text-danger font-weight-bold">*</span>Type of Client</label>
                                        <select class="form-control" name="pType" id="pType" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="PROBABLE" @if(old('pType') == "PROBABLE"){{'selected'}}@endif>COVID-19 Case (Suspect, Probable, or Confirmed)</option>
                                            <option value="CLOSE CONTACT" @if(old('pType') == "CLOSE CONTACT"){{'selected'}}@endif>Close Contact</option>
                                            <option value="TESTING" @if(old('pType') == "TESTING"){{'selected'}}@endif>For RT-PCR Testing (Not a Case of Close Contact)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="isForHospitalization"><span class="text-danger font-weight-bold">*</span>For Hospitalization</label>
                                      <select class="form-control" name="isForHospitalization" id="isForHospitalization" required>
                                          <option value="" disabled {{is_null(old('isForHospitalization')) ? 'selected' : ''}}>Choose...</option>
                                            <option value="1" {{(old('isForHospitalization') == '1') ? 'selected' : ''}}>Yes</option>
                                            <option value="0" {{(old('isForHospitalization') == '0') ? 'selected' : ''}}>No</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="interviewDate"><span class="text-danger font-weight-bold">*</span>Date of Interview</label>
                                        <input type="date" name="interviewDate" id="interviewDate" class="form-control" min="{{date('Y-m-d', strtotime("-14 Days"))}}" max="{{date('Y-m-d')}}" value="{{old('interviewDate')}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">2. Personal Information</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                        <input type="text" class="form-control @error('lname') border-danger @enderror" id="lname" name="lname" value="{{old('lname')}}" max="50" required>
                                        @error('lname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name (and Suffix)</label>
                                        <input type="text" class="form-control @error('fname') border-danger @enderror" id="fname" name="fname" value="{{old('fname')}}" max="50" required>
                                        @error('fname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mname">Middle Name <small><i>(Leave blank if N/A)</i></small></label>
                                        <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" max="50">
                                        @error('mname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                        <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                        @error('bdate')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender"><span class="text-danger font-weight-bold">*</span>Gender</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose</option>
                                            <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                            <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                        </select>
                                        @error('gender')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div id="ifGenderFemale">
                                        <div class="form-group">
                                            <label for="isPregnant"><span class="text-danger font-weight-bold">*</span>Are you Pregnant?</label>
                                            <select class="form-control" name="isPregnant" id="isPregnant">
                                                <option value="" disabled {{(is_null(old('isPregnant'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="0" {{(old('isPregnant') == '0') ? 'selected' : ''}}>No</option>
                                                <option value="1" {{(old('isPregnant') == '1') ? 'selected' : ''}}>Yes</option>
                                            </select>
                                        </div>
                                        <div id="ifPregnant">
                                            <div class="form-group">
                                              <label for="lmp"><span class="text-danger font-weight-bold">*</span>Last Menstrual Period (LMP)</label>
                                              <input type="date" class="form-control" name="lmp" id="lmp" value="{{old('lmp')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cs"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                        <select class="form-control" id="cs" name="cs" required>
                                            <option value="" disabled {{(is_null(old('cs'))) ? 'selected' : ''}}>Choose</option>
                                            <option value="SINGLE" @if(old('cs') == 'SINGLE') {{'selected'}} @endif>Single</option>
                                            <option value="MARRIED" @if(old('cs') == 'MARRIED') {{'selected'}} @endif>Married</option>
                                            <option value="WIDOWED" @if(old('cs') == 'WIDOWED') {{'selected'}} @endif>Widowed</option>
                                            <option value="N/A" @if(old('cs') == 'N/A') {{'selected'}} @endif>N/A</option>
                                        </select>
                                        @error('cs')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nationality"><span class="text-danger font-weight-bold">*</span>Nationality</label>
                                        <select class="form-control" id="nationality" name="nationality" required>
                                            <option value="Filipino" @if(old('nationality') == 'Filipino' || empty(old('nationality'))) {{'selected'}} @endif>Filipino</option>
                                            <option value="Foreign" @if(old('nationality') == 'Foreign') {{'selected'}} @endif>Foreign</option>
                                        </select>
                                        @error('nationality')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile"><span class="text-danger font-weight-bold">*</span>Mobile Number <small>(Use the format: 09*********)</small></label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile')}}" pattern="[0-9]{11}" placeholder="0917xxxxxxx" required>
                                        @error('mobile')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="philhealth">Philhealth Number <small><i>(Leave blank if N/A)</i></small></label>
                                        <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" minlength="12" maxlength="14">
                                        <small class="form-text text-muted">Note: Avoid hassle in your swab schedule by providing your Philhealth Number <i>(There are NO HIDDEN CHARGES applied into your Philhealth Account)</i></small>
                                        @error('philhealth')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phoneno">Telephone Number (& Area Code)</label>
                                        <input type="text" class="form-control" id="phoneno" name="phoneno" value="{{old('phoneno')}}">
                                        @error('phoneno')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}">
                                        @error('email')
                                              <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div id="addresstext">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                          <input type="text" class="form-control" name="address_province" id="address_province" value="{{old('address_province')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address_city" id="address_city" value="{{old('address_city')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                          <input type="text" class="form-control" name="address_provincejson" id="address_provincejson" value="{{old('address_provincejson')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address_cityjson" id="address_cityjson" value="{{old('address_cityjson')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                            </div>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_houseno"><span class="text-danger font-weight-bold">*</span>House No./Lot/Building</label>
                                        <input type="text" class="form-control" id="address_houseno" name="address_houseno" value="{{old('address_houseno')}}" required>
                                        @error('address_houseno')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_street"><span class="text-danger font-weight-bold">*</span>Street/Purok/Sitio</label>
                                        <input type="text" class="form-control" id="address_street" name="address_street" value="{{old('address_street')}}" required>
                                        @error('address_street')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">3. Occupation Details</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="haveOccupation"><span class="text-danger font-weight-bold">*</span>Are you currently employed?</label>
                                <select class="form-control" name="haveOccupation" id="haveOccupation" required>
                                    <option value="" disabled {{(is_null(old('haveOccupation'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="1" {{(old('haveOccupation') == '1') ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('haveOccupation') == '0') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="occupationRow">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="occupation"><span class="text-danger font-weight-bold">*</span>Occupation</label>
                                          <input type="text" class="form-control" name="occupation" id="occupation" value="{{old('occupation')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="occupation_name">Name of Workplace</label>
                                            <input type="text" class="form-control" name="occupation_name" id="occupation_name" value="{{old('occupation_name')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="natureOfWork"><span class="text-danger font-weight-bold">*</span>Nature of Work</label>
                                            <select class="form-control" name="natureOfWork" id="natureOfWork">
                                              <option value="" disabled {{(is_null(old('natureOfWork'))) ? 'selected' : ''}}>Choose...</option>
                                              <option value="AGRICULTURE" {{(old('natureOfWork') == 'AGRICULTURE') ? 'selected' : ''}}>Agriculture</option>
                                              <option value="BPO" {{(old('natureOfWork') == 'BPO') ? 'selected' : ''}}>BPO (Outsourcing E.G. eTelecare Global Sol. Inc)</option>
                                              <option value="COMMUNICATIONS" {{(old('natureOfWork') == 'COMMUNICATIONS') ? 'selected' : ''}}>Communications (E.G. PLDT)</option>
                                              <option value="CONSTRUCTION" {{(old('natureOfWork') == 'CONSTRUCTION') ? 'selected' : ''}}>Construction (E.G. Makati Dev Corp)</option>
                                              <option value="EDUCATION" {{(old('natureOfWork') == 'EDUCATION') ? 'selected' : ''}}>Education (E.G. DLSU)</option>
                                              <option value="ELECTRICITY" {{(old('natureOfWork') == 'ELECTRICITY') ? 'selected' : ''}}>Electricity</option>
                                              <option value="FINANCIAL" {{(old('natureOfWork') == 'FINANCIAL') ? 'selected' : ''}}>Financial (E.G. Banks)</option>
                                              <option value="GOVERNMENT UNITS/ORGANIZATIONS" {{(old('natureOfWork') == 'GOVERNMENT UNITS/ORGANIZATIONS') ? 'selected' : ''}}>Government Units/Organizations (E.G. GSIS)</option>
                                              <option value="HOTEL AND RESTAURANT" {{(old('natureOfWork') == 'HOTEL AND RESTAURANT') ? 'selected' : ''}}>Hotel and Restaurant (E.G. Jollibee Foods Corp)</option>
                                              <option value="MANNING/SHIPPING AGENCY" {{(old('natureOfWork') == 'MANNING/SHIPPING AGENCY') ? 'selected' : ''}}>Manning/Shipping Agency (E.G. Fil Star Maritime)</option>
                                              <option value="MANUFACTURING" {{(old('natureOfWork') == 'MANUFACTURING') ? 'selected' : ''}}>Manufacturing (E.G. Nestle Phils Inc)</option>
                                              <option value="MEDICAL AND HEALTH SERVICES" {{(old('natureOfWork') == 'MEDICAL AND HEALTH SERVICES') ? 'selected' : ''}}>Medical and Health Services</option>
                                              <option value="MICROFINANCE" {{(old('natureOfWork') == 'MICROFINANCE') ? 'selected' : ''}}>Microfinance (E.G. Ahon sa Hirap Inc)</option>
                                              <option value="MINING AND QUARRYING" {{(old('natureOfWork') == 'MINING AND QUARRYING') ? 'selected' : ''}}>Mining and Quarrying (E.G. Philex Mining Corp)</option>
                                              <option value="NON PROFIT ORGANIZATIONS" {{(old('natureOfWork') == 'NON PROFIT ORGANIZATIONS') ? 'selected' : ''}}>Non Profit Organizations (E.G. Iglesia Ni Cristo)</option>
                                              <option value="REAL ESTATE" {{(old('natureOfWork') == 'REAL ESTATE') ? 'selected' : ''}}>Real Estate (E.G. Megaworld Corp)</option>
                                              <option value="STORAGE" {{(old('natureOfWork') == 'STORAGE') ? 'selected' : ''}}>Storage (Include Freight Forwarding E.G. Dhl)</option>
                                              <option value="TRANSPORTATION" {{(old('natureOfWork') == 'TRANSPORTATION') ? 'selected' : ''}}>Transportation (E.G. Philippine Airlines)</option>
                                              <option value="WHOLESALE AND RETAIL TRADE" {{(old('natureOfWork') == 'WHOLESALE AND RETAIL TRADE') ? 'selected' : ''}}>Wholesale and Retail Trade (E.G. Mercury Drug)</option>
                                              <option value="OTHERS" {{(old('natureOfWork') == 'OTHERS') ? 'selected' : ''}}>Others (Specify)</option>
                                            </select>
                                              @error('natureOfWork')
                                              <small class="text-danger">{{$message}}</small>
                                              @enderror
                                        </div>
                                        <div id="specifyWorkNatureDiv">
                                            <div class="form-group">
                                                <label for="natureOfWorkIfOthers"><span class="text-danger font-weight-bold">*</span>Please specify</label>
                                                <input type="text" class="form-control" name="natureOfWorkIfOthers" id="natureOfWorkIfOthers" value="{{old('natureOfWorkIfOthers')}}">
                                                @error('natureOfWorkIfOthers')
                                                <small class="text-danger">{{$message}}</small>
                                                @enderror
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">4. Clinical Information</div>
                        <div class="card-body">
                            <div class="form-group">
                              <label for="haveSymptoms"><span class="text-danger font-weight-bold">*</span>Are you currently experiencing any COVID-19 signs or symptoms?</label>
                              <select class="form-control" name="haveSymptoms" id="haveSymptoms">
                                <option value="" disabled {{is_null(old('haveSymptoms')) ? 'selected' : ''}}>Choose...</option>
                                <option value="1" {{(old('haveSymptoms') == '1') ? 'selected' : ''}}>Yes</option>
                                <option value="0" {{(old('haveSymptoms') == '0') ? 'selected' : ''}}>No</option>
                              </select>
                            </div>
                            <div id="ifHaveSymptoms">
                                <div class="form-group">
                                    <label for="dateOnsetOfIllness"><span class="text-danger font-weight-bold">*</span>Date of Onset of Illness (Kailan Nagsimula ang Sintomas)</label>
                                    <input type="date" class="form-control" name="dateOnsetOfIllness" id="dateOnsetOfIllness" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="card">
                                    <div class="card-header">Signs and Symptoms (Check all that apply)</div>
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
                                                      <label for="SASFeverDeg"><span class="text-danger font-weight-bold">*</span>Degrees (in Celcius)</label>
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
                                                    <label class="form-check-label" for="signsCheck16">Anosmia <small>(loss of smell, w/o any identified cause)</small></label>
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
                                                    <label class="form-check-label" for="signsCheck17">Ageusia <small>(loss of taste, w/o any identified cause)</small></label>
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
                                                      <label for="SASOtherRemarks"><span class="text-danger font-weight-bold">*</span>Specify Findings</label>
                                                      <input type="text" class="form-control" name="SASOtherRemarks" id="SASOtherRemarks" value="{{old('SASOtherRemarks')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
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
                                                  <label for="COMOOtherRemarks"><span class="text-danger font-weight-bold">*</span>Specify Findings</label>
                                                  <input type="text" class="form-control" name="COMOOtherRemarks" id="COMOOtherRemarks" value="{{old('COMOOtherRemarks')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">5. Chest X-ray Details</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                      <label for=""><span class="text-danger font-weight-bold">*</span>Date done</label>
                                      <input type="date" class="form-control" name="imagingDoneDate" id="imagingDoneDate" value="{{old('imagingDoneDate')}}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                      <label for="imagingDone"><span class="text-danger font-weight-bold">*</span>Chest X-Ray Type</label>
                                      <select class="form-control" name="imagingDone" id="imagingDone" required>
                                        <option value="None" {{(old('imagingDone') == "None") ? 'selected' : ''}}>None</option>
                                        <option value="Chest Radiography" {{(old('imagingDone') == "Chest Radiography") ? 'selected' : ''}}>Chest Radiography</option>
                                        <option value="Chest CT" {{(old('imagingDone') == "Chest CT") ? 'selected' : ''}}>Chest CT</option>
                                        <option value="Lung Ultrasound" {{(old('imagingDone') == "Lung Ultrasound") ? 'selected' : ''}}>Lung Ultrasound</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="imagingResult"><span class="text-danger font-weight-bold">*</span>Results</label>
                                      <select class="form-control" name="imagingResult" id="imagingResult">
                                      </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4">
                                    <div id="divImagingOthers">
                                        <div class="form-group">
                                          <label for="imagingOtherFindings"><span class="text-danger font-weight-bold">*</span>Specify findings</label>
                                          <input type="text" class="form-control" name="imagingOtherFindings" id="imagingOtherFindings" value="{{old('imagingOtherFindings')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">6. Exposure History</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="expoitem1"><span class="text-danger font-weight-bold">*</span>Do you have history of exposure to someone who was Confirmed COVID-19 Positive 14 days ago?</label>
                                <select class="form-control" name="expoitem1" id="expoitem1" required>
                                    <option value="" disabled {{is_null(old('expoitem1')) ? 'selected' : ''}}>Choose...</option>
                                    <option value="1" {{(old('expoitem1') == '1') ? 'selected' : ''}}>Yes</option>
                                    <option value="2" {{(old('expoitem1') == '2') ? 'selected' : ''}}>No</option>
                                    <option value="3" {{(old('expoitem1') == '3') ? 'selected' : ''}}>Unknown</option>
                                </select>
                            </div>
                            <div id="divExpoitem1">
                                <div class="form-group">
                                    <label for=""><span class="text-danger font-weight-bold">*</span>Date of Exposure</label>
                                    <input type="date" class="form-control" name="expoDateLastCont" id="expoDateLastCont" max="{{date('Y-m-d')}}" value="{{old('expoDateLastCont')}}">
                                </div>
                                <div class="card">
                                    <div class="card-header">List the Names of your Close Contact</div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="contact1Name">Name of Close Contact #1</label>
                                                  <input type="text" class="form-control" name="contact1Name" id="contact1Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact1No">Mobile Number of Close Contact #1</label>
                                                    <input type="text" class="form-control" name="contact1No" id="contact1No">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="contact2Name">Name of Close Contact #2</label>
                                                  <input type="text" class="form-control" name="contact2Name" id="contact2Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact2No">Mobile Number of Close Contact #2</label>
                                                    <input type="text" class="form-control" name="contact2No" id="contact2No">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="contact3Name">Name of Close Contact #3</label>
                                                  <input type="text" class="form-control" name="contact3Name" id="contact3Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact3No">Mobile Number of Close Contact #3</label>
                                                    <input type="text" class="form-control" name="contact3No" id="contact3No">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="contact4Name">Name of Close Contact #4</label>
                                                  <input type="text" class="form-control" name="contact4Name" id="contact4Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact4No">Mobile Number of Close Contact #4</label>
                                                    <input type="text" class="form-control" name="contact4No" id="contact4No">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header font-weight-bold">Data Privacy Statement of General Trias</div>
                        <div class="card-body text-center">
                            <p>By choosing "I Agree" and clicking the "Submit" button below, I hereby acknowledge and certify that I have carefully read and understood the Terms and Conditions of the Data Privacy Policy/Notice of the City Government of General Trias. By providing personal information to City Government of General Trias, I am confirming that the data is true and correct. I understand that City Government of General Trias reserves the right to revise any decision made on the basis of the information I provided should the information be found to be untrue or incorrect. I likewise agree that any issue that may arise in connection with the processing of my personal information will be settled amicably with City Government of General Trias before resorting to appropriate arbitration or court proceedings within the Philippine jurisdiction. Finally, I am providing my voluntary consent and authorization to City Government of General Trias and its authorized representatives to lawfully process my data/information.</p>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="dpsagree" id="dpsagree" required>
                                I Agree
                              </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block" onclick="return confirm('Please double check your details before proceeding. If checking is done, click OK to proceed.')">Submit</button>
                </div>
            </div>
        </div>
    </form>
    
    <div class="modal fade" id="announcement" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notice</h5>
                </div>
                <div class="modal-body text-center">
                    Test
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">I Understand, Proceed</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if(session('skipmodal') == false)
        $('#announcement').modal({backdrop: 'static', keyboard: false});
        $('#announcement').modal('show');
        @endif

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
        
        $('#addresstext').hide();
        $('#saddress_city').prop('disabled', true);
		$('#address_brgy').prop('disabled', true);

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
					selected: (val.provCode == '0421') ? true : false, //default for Cavite
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
							selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
						})); 
					}
				});
			});
		}).trigger('change');

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
						$("#address_brgy").append('<option value="'+val.brgyDesc.toUpperCase()+'">'+val.brgyDesc.toUpperCase()+'</option>');
					}
				});
			});
		}).trigger('change');

		//for Setting Default values on hidden address/json for Cavite - General Trias
		$("#address_province").val('CAVITE');
		$("#address_provincejson").val('0421');
		$("#address_city").val('GENERAL TRIAS');
		$('#address_cityjson').val('042108');

        $('#haveOccupation').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#occupationRow').show();
                $('#occupation').prop('required', true);
                $('#natureOfWork').prop('required', true);
            }
            else {
                $('#occupationRow').hide();
                $('#occupation').prop('required', false);
                $('#natureOfWork').prop('required', false);
            }
        }).trigger('change');

        $('#gender').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "MALE" || $(this).val() == null) {
                $('#ifGenderFemale').hide();
                $('#isPregnant').prop('required', false);
            }
            else {
                $('#ifGenderFemale').show();
                $('#isPregnant').prop('required', true);
            }
        }).trigger('change');

        $('#isPregnant').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '0' || $(this).val() == null) {
                $('#ifPregnant').hide();
                $('#lmp').prop('required', false);
            }
            else {
                $('#ifPregnant').show();
                $('#lmp').prop('required', true);
            }
        }).trigger('change');

        $('#natureOfWork').change(function (e) { 
			e.preventDefault();
			if($(this).val() == 'OTHERS') {
				$('#specifyWorkNatureDiv').show();
				$('#natureOfWorkIfOthers').prop('required', true);
			}
			else {
				$('#specifyWorkNatureDiv').hide();
				$('#natureOfWorkIfOthers').prop('required', false);
			}
		}).trigger('change');

        $('#expoitem1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 1) {
                $('#divExpoitem1').show();
                $('#expoDateLastCont').prop('required', true);
            }
            else {
                $('#divExpoitem1').hide();
                $('#expoDateLastCont').prop('required', false);
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

        $('#haveSymptoms').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '0' || $(this).val() == null) {
                $('#ifHaveSymptoms').hide();
                $('#dateOnsetOfIllness').prop('required', false);
            }
            else {
                $('#ifHaveSymptoms').show();
                $('#dateOnsetOfIllness').prop('required', true);
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

        $('#imagingDone').change(function (e) { 
            e.preventDefault();
            $('#divImagingOthers').hide();
            $('#imagingOtherFindings').val("");
            if($(this).val() == "None") {
                $('#imagingDoneDate').prop({disabled: true, required: false});
                $('#imagingResult').prop({disabled: true, required: false});
                $("#imagingResult").empty();
            }
            else {
                $('#imagingDoneDate').prop({disabled: false, required: true});
                $('#imagingResult').prop({disabled: false, required: true});
                $("#imagingResult").empty();
                $("#imagingResult").append(new Option("Normal", "NORMAL"));
                $("#imagingResult").append(new Option("Pending", "PENDING"));

                $('#divImagingOthers').hide();

                if($(this).val() == "Chest Radiography") {
                    $("#imagingResult").append(new Option("Hazy opacities, often rounded in morphology, with peripheral and lower lung dist.", "HAZY"));
                }
                else if($(this).val() == "Chest CT") {
                    $("#imagingResult").append(new Option("Multiple bilateral ground glass opacities, often rounded in morphology, w/ peripheral and lower lung dist.", "MULTIPLE"));
                }
                else if($(this).val() == "Lung Ultrasound") {
                    $("#imagingResult").append(new Option("Thickened pleural lines, B lines, consolidative patterns with or without air bronchograms.", "THICKENED"));
                }
                
                if($(this).val() != "OTHERS") {
                    $("#imagingResult").append(new Option("Other findings", "OTHERS"));
                }
            }
        }).trigger('change');

        $('#imagingResult').change(function (e) { 
            e.preventDefault();
            $('#imagingOtherFindings').val("");
            if($(this).val() == "OTHERS") {
                $('#divImagingOthers').show();
                $('imagingOtherFindings').prop({disabled: false, required: true});
            }
            else {
                $('#divImagingOthers').hide();
                $('imagingOtherFindings').prop({disabled: true, required: false});
            }
        }).trigger('change');
    </script>
@endsection