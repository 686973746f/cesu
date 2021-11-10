@extends('layouts.app')

@section('content')
    @if($proceed == 1)
    <form action="{{route('paswab.store', ['locale' => app()->getLocale()])}}" method="POST" id="myForm" name="wholeForm" style="font-family: Arial, Helvetica, sans-serif">
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
                        <h4 class="alert-heading">{{__('paswab.notice.readcarefully') }}</h4>
                        <hr>
                        {{__('paswab.notice.asterisk') }}
                    </div>
                    <div class="form-group d-none">
                      <label for="linkcode">Link Code</label>
                      <input type="text" class="form-control" name="linkcode" id="linkcode" value="{{old('linkcode', request()->input('rlink'))}}" required readonly>
                    </div>

                    <div class="form-group d-none">
                        <label for="linkcode2nd">Link Code</label>
                        <input type="text" class="form-control" name="linkcode2nd" id="linkcode2nd" value="{{old('linkcode2nd', request()->input('s'))}}" required readonly>
                      </div>

                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">{{__('paswab.consultationDetails')}}</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Name of Interviewer</label>
                                <input type="text" class="form-control" value="{{$interviewerName}}" disabled>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pType"><span class="text-danger font-weight-bold">*</span>{{__('paswab.pType')}}</label>
                                        <select class="form-control" name="pType" id="pType" required>
                                            <option value="" disabled selected>{{__('paswab.select.Choose')}}</option>
                                            <option value="PROBABLE" @if(old('pType') == "PROBABLE"){{'selected'}}@endif>Suspected</option>
                                            <option value="CLOSE CONTACT" @if(old('pType') == "CLOSE CONTACT"){{'selected'}}@endif>Close Contact</option>
                                            <option value="TESTING" @if(old('pType') == "TESTING"){{'selected'}}@endif>Not A Case of COVID</option>
                                            <option value="FOR TRAVEL" @if(old('pType') == "FOR TRAVEL"){{'selected'}}@endif>For Travel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="interviewDate"><span class="text-danger font-weight-bold">*</span>{{__('paswab.interviewDate')}}</label>
                                        <input type="date" name="interviewDate" id="interviewDate" class="form-control" min="{{date('Y-m-d', strtotime("-7 Days"))}}" max="{{date('Y-m-d')}}" value="{{old('interviewDate')}}" required>
                                        <small class="text-muted">Note: This would be also used as the first day of your monitoring.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="isForHospitalization"><span class="text-danger font-weight-bold">*</span>{{__('paswab.isForHospitalization')}}</label>
                                        <select class="form-control" name="isForHospitalization" id="isForHospitalization" required>
                                            <option value="" disabled {{is_null(old('isForHospitalization')) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                            <option value="1" {{(old('isForHospitalization') == '1') ? 'selected' : ''}}>{{__('paswab.select.ChooseYes')}}</option>
                                            <option value="0" {{(old('isForHospitalization') == '0') ? 'selected' : ''}}>{{__('paswab.select.ChooseNo')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="forAntigen"><span class="text-danger font-weight-bold">*</span>{{__('paswab.forAntigen')}}</label>
                                        <select class="form-control" name="forAntigen" id="forAntigen" required>
                                            <option value="0" {{(old('forAntigen') == '0') ? 'selected' : ''}}>{{__('paswab.select.ChooseNo')}}</option>
                                        </select>
                                        <small class="text-muted">{{__('paswab.forAntigenNotice')}}</small>
                                        <small class="text-danger">Selecting "YES" for antigen is temporarily disabled. All Patients are suggested to take RT-PCR Test.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                              <label for="patientmsg">{{__('paswab.patientmsg')}} <small>{{__('paswab.optional')}}</small></label>
                              <textarea class="form-control" name="patientmsg" id="patientmsg" rows="3">{{old('patientmsg')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">{{__('paswab.personalInformation')}}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lname"><span class="text-danger font-weight-bold">*</span>{{__('paswab.lname')}}</label>
                                        <input type="text" class="form-control @error('lname') border-danger @enderror" id="lname" name="lname" value="{{old('lname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                                        @error('lname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fname"><span class="text-danger font-weight-bold">*</span>{{__('paswab.fname')}}</label>
                                        <input type="text" class="form-control @error('fname') border-danger @enderror" id="fname" name="fname" value="{{old('fname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                                        <small class="text-muted">{{__('paswab.fNameNotice')}}</small>
                                        @error('fname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mname">{{__('paswab.mname')}} <small><i>{{__('paswab.leaveBlank')}}</i></small></label>
                                        <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" style="text-transform: uppercase;" minlength="2" maxlength="50">
                                        <small class="form-text text-muted">{{__('paswab.mnameNotice')}}</small>
                                        @error('mname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bdate"><span class="text-danger font-weight-bold">*</span>{{__('paswab.bdate')}}</label>
                                        <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                        @error('bdate')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender"><span class="text-danger font-weight-bold">*</span>{{__('paswab.gender')}}</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                            <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>{{__('paswab.male')}}</option>
                                            <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>{{__('paswab.female')}}</option>
                                        </select>
                                        @error('gender')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div id="ifGenderFemale">
                                        <div class="form-group">
                                            <label for="isPregnant"><span class="text-danger font-weight-bold">*</span>{{__('paswab.isPregnant')}}</label>
                                            <select class="form-control" name="isPregnant" id="isPregnant">
                                                <option value="" disabled {{(is_null(old('isPregnant'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                                <option value="0" {{(old('isPregnant') == '0') ? 'selected' : ''}}>{{__('paswab.select.ChooseNo')}}</option>
                                                <option value="1" {{(old('isPregnant') == '1') ? 'selected' : ''}}>{{__('paswab.select.ChooseYes')}}</option>
                                            </select>
                                        </div>
                                        <div id="ifPregnant">
                                            <div class="form-group">
                                              <label for="lmp"><span class="text-danger font-weight-bold">*</span>{{__('paswab.lmp')}}</label>
                                              <input type="date" class="form-control" name="lmp" id="lmp" value="{{old('lmp')}}" max="{{date('Y-m-d', strtotime('yesterday'))}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cs"><span class="text-danger font-weight-bold">*</span>{{__('paswab.cs')}}</label>
                                        <select class="form-control" id="cs" name="cs" required>
                                            <option value="" disabled {{(is_null(old('cs'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                            <option value="SINGLE" @if(old('cs') == 'SINGLE') {{'selected'}} @endif>{{__('paswab.single')}}</option>
                                            <option value="MARRIED" @if(old('cs') == 'MARRIED') {{'selected'}} @endif>{{__('paswab.married')}}</option>
                                            <option value="WIDOWED" @if(old('cs') == 'WIDOWED') {{'selected'}} @endif>{{__('paswab.widowed')}}</option>
                                            <option value="N/A" @if(old('cs') == 'N/A') {{'selected'}} @endif>{{__('paswab.na')}}</option>
                                        </select>
                                        @error('cs')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nationality"><span class="text-danger font-weight-bold">*</span>{{__('paswab.nationality')}}</label>
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
                                        <label for="mobile"><span class="text-danger font-weight-bold">*</span>Mobile Number <small>(Format: 09*********)</small></label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                        <small class="text-muted">Note: Please type your CORRECT and ACTIVE Mobile Number as we will use this to contact you regarding on your swab test schedule.</small>
                                        @error('mobile')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="havePhilhealth"><span class="text-danger font-weight-bold">*</span>Do you have Philhealth Account?</label>
                                      <select class="form-control" name="havePhilhealth" id="havePhilhealth">
                                        <option value="" disabled {{(is_null(old('havePhilhealth'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                        <option value="YES">{{__('paswab.select.ChooseYes')}}</option>
                                        <option value="NO">{{__('paswab.select.ChooseNo')}}</option>
                                      </select>
                                      <small class="form-text text-muted">{{__('paswab.philhealth.notice')}}</i></small>
                                    </div>
                                    <div id="ask2" class="d-none">
                                        <div class="form-group">
                                          <label for="declaredDependent"><span class="text-danger font-weight-bold">*</span>Are you declared as dependent from your Parents Philhealth Number?</label>
                                          <select class="form-control" name="declaredDependent" id="declaredDependent">
                                            <option value="" disabled {{(is_null(old('declaredDependent'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                            <option value="YES">{{__('paswab.select.ChooseYes')}}</option>
                                            <option value="NO">{{__('paswab.select.ChooseNo')}}</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div id="philhealthbox" class="d-none">
                                        <div class="form-group">
                                            <label for="parentphilhealth" id="label_parentphilhealth" class="d-none"><span class="text-danger font-weight-bold">*</span>Write the Philhealth Number of your Parent</label>
                                            <label for="philhealth" id="label_ownphilhealth" class="d-none"><span class="text-danger font-weight-bold">*</span>Write your Philhealth Number</label>
                                            <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" pattern="[0-9]{12}">
                                            <small class="text-muted">(12 Numbers, No Dashes)</small>
                                            <div id="parentBringMDR" class="d-none">
                                                <hr>
                                                <p><strong class="text-danger">NOTE:</strong> In order to process your swab test, you must <strong>1.)</strong> Bring a Hardcopy of Philhealth Member Data Record (MDR) of your Parent as proof of dependent, <strong>2.)</strong> Fill up the required details for your Philhealth Member Registration Form (PMRF) and <strong>3.)</strong> Authorization Letter signed by your Parents.</p>
                                                <li><a href="{{asset('assets/docs/philhealth_pmrf.pdf')}}" target="_blank"><i class="fa fa-download mr-2" aria-hidden="true"></i>Download PMRF</a></li>
                                                <li><a href="{{asset('assets/docs/philhealth_authletter.pdf')}}" target="_blank"><i class="fa fa-download mr-2" aria-hidden="true"></i>Download Authorization Letter</a></li>
                                                <p>Please fill up <mark>ALL HIGHLIGHTED FIELDS</mark>. Please be guided accordingly.</p>
                                            </div>
                                            @error('philhealth')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div id="nophilhealthbox" class="d-none">
                                        <p><strong class="text-danger">NOTE:</strong> In order to process your swab test, please bring other requirements such as:</p>
                                        <li>Birth Certificate</li>
                                        <li>Valid ID of Parents <i>(IF Minor)</i></li>
                                        <li>Valid Goverment Issued Primary ID <i>(SSS ID/UMID, Postal ID, PRC ID, Passport, Drivers License, NBI Clearance, Senior Citizen ID)</i></li>
                                        <li>IF NO Valid ID, kindly request Certificate of Indigency to your Respective Barangay.</li>
                                        <hr>
                                        <p><span class="font-weight-bold text-danger">Barangay ID, TIN ID, School ID and Company ID are not accepted.</span> Please be guided accordingly.</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phoneno">Telephone Number (& Area Code) <small><i>{{__('paswab.leaveBlank')}}</i></small></label>
                                        <input type="text" class="form-control" id="phoneno" name="phoneno" value="{{old('phoneno')}}">
                                        @error('phoneno')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <small><i>{{__('paswab.leaveBlank')}}</i></small></label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}" placeholder="juandelacruz@example.com">
                                        @error('email')
                                              <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @if($enableLockAddress == 1)
                            <div id="addresstext" class="d-none">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                          <input type="text" class="form-control" name="address_province" id="address_province" value="{{$lock_province_text}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address_city" id="address_city" value="{{$lock_city_text}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                          <input type="text" class="form-control" name="address_provincejson" id="address_provincejson" value="{{$lock_province}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address_cityjson" id="address_cityjson" value="{{$lock_city}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="saddress_province"><span class="text-danger font-weight-bold">*</span>{{__('paswab.saddress_province')}}</label>
                                        <input type="text" class="form-control" name="saddress_province" id="" value="{{$lock_province_text}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="saddress_city"><span class="text-danger font-weight-bold">*</span>{{__('paswab.saddress_city')}}</label>
                                        <input type="text" class="form-control" name="saddress_city" id="" value="{{$lock_city_text}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address_brgy"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                        <input type="text" class="form-control" name="address_brgy" id="" value="{{$lock_brgy}}" readonly>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div id="addresstext" class="d-none">
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
                                        <label for="saddress_province"><span class="text-danger font-weight-bold">*</span>{{__('paswab.saddress_province')}}</label>
                                        <select class="form-control" name="saddress_province" id="saddress_province" required>
                                          <option value="" selected disabled>{{__('paswab.select.Choose')}}</option>
                                        </select>
                                            @error('saddress_province')
                                              <small class="text-danger">{{$message}}</small>
                                          @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="saddress_city"><span class="text-danger font-weight-bold">*</span>{{__('paswab.saddress_city')}}</label>
                                      <select class="form-control" name="saddress_city" id="saddress_city" required>
                                        <option value="" selected disabled>{{__('paswab.select.Choose')}}</option>
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
                                        <option value="" selected disabled>{{__('paswab.select.Choose')}}</option>
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
                                        <label for="address_houseno"><span class="text-danger font-weight-bold">*</span>House No./Section, Block and Lot/Building</label>
                                        <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" required>
                                        @error('address_houseno')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_street"><span class="text-danger font-weight-bold">*</span>Street/Purok/Sitio/Subdivision</label>
                                        <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" required>
                                        <small class="text-muted">{{__('paswab.street.notice')}}</small>
                                        @error('address_street')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">{{__('paswab.occupationDetails')}}</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="haveOccupation"><span class="text-danger font-weight-bold">*</span>{{__('paswab.haveOccupation')}}</label>
                                <select class="form-control" name="haveOccupation" id="haveOccupation" required>
                                    <option value="" disabled {{(is_null(old('haveOccupation'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                    <option value="1" {{(old('haveOccupation') == '1') ? 'selected' : ''}}>{{__('paswab.select.ChooseYes')}}</option>
                                    <option value="0" {{(old('haveOccupation') == '0') ? 'selected' : ''}}>{{__('paswab.select.ChooseNo')}}</option>
                                </select>
                            </div>
                            <div id="occupationRow">
                                <div class="alert alert-info" role="alert">
                                    <strong class="text-danger">Notice:</strong> starting October 06, 2021. <strong>Name of Company/Workplace and Complete Company/Workplace Address are now required fields to be filled up.</strong> This is to comply with the requirements of the Molecular Laboratory.
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="occupation"><span class="text-danger font-weight-bold">*</span>{{__('paswab.occupation')}}</label>
                                          <input type="text" class="form-control" name="occupation" id="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="occupation_name"><span class="text-danger font-weight-bold">*</span>{{__('paswab.occupation_name')}}</label>
                                            <input type="text" class="form-control" name="occupation_name" id="occupation_name" value="{{old('occupation_name')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="natureOfWork"><span class="text-danger font-weight-bold">*</span>{{__('paswab.natureOfWork')}}</label>
                                            <select class="form-control" name="natureOfWork" id="natureOfWork">
                                              <option value="" disabled {{(is_null(old('natureOfWork'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
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
                                              <option value="OTHERS" {{(old('natureOfWork') == 'OTHERS') ? 'selected' : ''}}>{{__('paswab.select.ChooseOthers')}}</option>
                                            </select>
                                              @error('natureOfWork')
                                              <small class="text-danger">{{$message}}</small>
                                              @enderror
                                        </div>
                                        <div id="specifyWorkNatureDiv">
                                            <div class="form-group">
                                                <label for="natureOfWorkIfOthers"><span class="text-danger font-weight-bold">*</span>{{__('paswab.specify')}}</label>
                                                <input type="text" class="form-control" name="natureOfWorkIfOthers" id="natureOfWorkIfOthers" value="{{old('natureOfWorkIfOthers')}}" style="text-transform: uppercase;">
                                                @error('natureOfWorkIfOthers')
                                                <small class="text-danger">{{$message}}</small>
                                                @enderror
                                          </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="worksInClosedSetting"><span class="text-danger font-weight-bold">*</span>Works in a closed setting?</label>
                                            <select class="form-control" name="worksInClosedSetting" id="worksInClosedSetting">
                                                <option value="" disabled {{(is_null(old('worksInClosedSetting'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                                <option value="YES" {{(old('worksInClosedSetting') == "YES") ? 'selected' : ''}}>Yes</option>
                                                <option value="NO" {{(old('worksInClosedSetting') == "NO") ? 'selected' : ''}}>No</option>
                                                <option value="UNKNOWN" {{(old('worksInClosedSetting') == "UNKNOWN") ? 'selected' : ''}}>Unknown</option>
                                            </select>
                                            @error('worksInClosedSetting')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div id="occupationaddresstext" class="d-none">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <input type="text" class="form-control" name="occupation_province" id="occupation_province" value="{{old('occupation_province')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="occupation_city" id="occupation_city" value="{{old('occupation_city')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                              <input type="text" class="form-control" name="occupation_provincejson" id="occupation_provincejson" value="{{old('occupation_provincejson')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="occupation_cityjson" id="occupation_cityjson" value="{{old('occupation_cityjson')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="soccupation_province"><span class="text-danger font-weight-bold">*</span>Province</label>
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
                                            <label for="soccupation_city"><span class="text-danger font-weight-bold">*</span>City</label>
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
                                            <label for="occupation_brgy"><span class="text-danger font-weight-bold">*</span>Barangay</label>
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
                                            <label for="occupation_lotbldg"><span class="text-danger font-weight-bold">*</span>Lot/Building</label>
                                            <input type="text" class="form-control" id="occupation_lotbldg" name="occupation_lotbldg" value="{{old('occupation_lotbldg')}}" style="text-transform: uppercase;">
                                            @error('occupation_lotbldg')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="occupation_street"><span class="text-danger font-weight-bold">*</span>Street</label>
                                            <input type="text" class="form-control" id="occupation_street" name="occupation_street" value="{{old('occupation_street')}}" style="text-transform: uppercase;">
                                            @error('occupation_street')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="occupation_mobile">Phone/Mobile No. <small>(Optional)</small></label>
                                            <input type="text" class="form-control" id="occupation_mobile" name="occupation_mobile" pattern="[0-9]{11}" placeholder="0917xxxxxxx" value="{{old('occupation_mobile')}}">
                                            @error('occupation_mobile')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="occupation_email">Email <small>(Optional)</small></label>
                                            <input type="email" class="form-control" name="occupation_email" id="occupation_email" value="{{old('occupation_email')}}">
                                            @error('occupation_email')
                                                  <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">4. COVID-19 Vaccination Information</div>
                        <div class="card-body">
                            <div class="form-group">
                              <label for="vaccineq1"><span class="text-danger font-weight-bold">*</span>{{__('paswab.vaccineq1')}}</label>
                              <select class="form-control" name="vaccineq1" id="vaccineq1" required>
                                <option value="" disabled {{is_null(old('vaccineq1')) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                <option value="1" {{(old('vaccineq1') == '1') ? 'selected' : ''}}>{{__('paswab.select.ChooseYes')}}</option>
                                <option value="0" {{(old('vaccineq1') == '0') ? 'selected' : ''}}>{{__('paswab.select.ChooseNo')}}</option>
                              </select>
                            </div>
                            <div id="ifVaccinated">
                                <div class="form-group">
                                  <label for="howManyDose"><span class="text-danger font-weight-bold">*</span>{{__('paswab.howManyDose')}}</label>
                                  <select class="form-control" name="howManyDose" id="howManyDose">
                                    <option value="" disabled {{is_null(old('howManyDose')) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                    <option value="1" {{(old('howManyDose') == '1') ? 'selected' : ''}}>1st Dose</option>
                                    <option value="2" id="2ndDoseOption" {{(old('howManyDose') == '2') ? 'selected' : ''}}>2nd Dose</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="nameOfVaccine"><span class="text-danger font-weight-bold">*</span>{{__('paswab.nameOfVaccine')}}</label>
                                  <select class="form-control" name="nameOfVaccine" id="nameOfVaccine">
                                    <option value="" disabled {{is_null(old('nameOfVaccine')) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                    <option value="BHARAT BIOTECH" {{(old('nameOfVaccine') == "BHARAT BIOTECH") ? 'selected' : ''}}>Bharat BioTech</option>
                                    <option value="GAMALEYA SPUTNIK V" {{(old('nameOfVaccine') == 'GAMALEYA SPUTNIK V') ? 'selected' : ''}}>Gamaleya Sputnik V</option>
                                    <option value="JANSSEN" {{(old('nameOfVaccine') == "JANSSEN") ? 'selected' : ''}}>Janssen</option>
                                    <option value="MODERNA" {{(old('nameOfVaccine') == 'MODERNA') ? 'selected' : ''}}>Moderna</option>
                                    <option value="NOVARAX" {{(old('nameOfVaccine') == 'NOVARAX') ? 'selected' : ''}}>Novarax</option>
                                    <option value="OXFORD ASTRAZENECA" {{(old('nameOfVaccine') == 'OXFORD ASTRAZENECA') ? 'selected' : ''}}>Oxford AstraZeneca</option>
                                    <option value="PFIZER BIONTECH" {{(old('nameOfVaccine') == 'PFIZER BIONTECH') ? 'selected' : ''}}>Pfizer BioNTech</option>
                                    <option value="SINOPHARM" {{(old('nameOfVaccine') == 'SINOPHARM') ? 'selected' : ''}}>Sinopharm</option>
                                    <option value="SINOVAC CORONAVAC" {{(old('nameOfVaccine') == 'SINOVAC CORONAVAC') ? 'selected' : ''}}>Sinovac Coronavac</option>
                                  </select>
                                </div>
                                <div id="VaccineDose1">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationDate1"><span class="text-danger font-weight-bold">*</span>1.) First (1st) Dose - Date of Vaccination</label>
                                                <input type="date" class="form-control" name="vaccinationDate1" id="vaccinationDate1" value="{{old('vaccinationDate1')}}" min="2019-01-01" max="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationFacility1">Vaccination Center/Facility <small>(Optional)</small></label>
                                                <input type="text" class="form-control" name="vaccinationFacility1" id="vaccinationFacility1" value="{{old('vaccinationFacility1')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationRegion1">Region of Health Facility <small>(Optional)</small></label>
                                                <input type="text" class="form-control" name="vaccinationRegion1" id="vaccinationRegion1" value="{{old('vaccinationRegion1')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="haveAdverseEvents1"><span class="text-danger font-weight-bold">*</span>Adverse Event/s</label>
                                                <select class="form-control" name="haveAdverseEvents1" id="haveAdverseEvents1">
                                                  <option value="" disabled {{(is_null(old('haveAdverseEvents1'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                                  <option value="1" {{(old('haveAdverseEvents1') == '1') ? 'selected' : ''}}>Yes</option>
                                                  <option value="0" {{(old('haveAdverseEvents1') == '0') ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div id="VaccineDose2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="vaccinationDate2"><span class="text-danger font-weight-bold">*</span>2.) Second (2nd) Dose - Date of Vaccination</label>
                                                  <input type="date" class="form-control" name="vaccinationDate2" id="vaccinationDate2" value="{{old('vaccinationDate2')}}" min="2019-01-01" max="{{date('Y-m-d')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vaccinationFacility2">Vaccination Center/Facility <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="vaccinationFacility2" id="vaccinationFacility2" value="{{old('vaccinationFacility2')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vaccinationRegion2">Region of Health Facility <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="vaccinationRegion2" id="vaccinationRegion2" value="{{old('vaccinationRegion2')}}" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="haveAdverseEvents2"><span class="text-danger font-weight-bold">*</span>Adverse Event/s</label>
                                                    <select class="form-control" name="haveAdverseEvents2" id="haveAdverseEvents2">
                                                      <option value="" disabled {{(is_null(old('haveAdverseEvents2'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                                      <option value="1" {{(old('haveAdverseEvents2') == '1') ? 'selected' : ''}}>Yes</option>
                                                      <option value="0" {{(old('haveAdverseEvents2') == '0') ? 'selected' : ''}}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">5. Clinical Information</div>
                        <div class="card-body">
                            <div class="form-group">
                              <label for="haveSymptoms"><span class="text-danger font-weight-bold">*</span>{{__('paswab.haveSymptoms')}}</label>
                              <select class="form-control" name="haveSymptoms" id="haveSymptoms">
                                <option value="" disabled {{is_null(old('haveSymptoms')) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                <option value="1" {{(old('haveSymptoms') == '1') ? 'selected' : ''}}>Oo / Yes</option>
                                <option value="0" {{(old('haveSymptoms') == '0') ? 'selected' : ''}}>Hindi / No</option>
                              </select>
                            </div>
                            <div id="ifHaveSymptoms">
                                <div class="form-group">
                                    <label for="dateOnsetOfIllness"><span class="text-danger font-weight-bold">*</span>{{__('paswab.dateOnsetOfIllness')}}</label>
                                    <input type="date" class="form-control" name="dateOnsetOfIllness" id="dateOnsetOfIllness" min="1999-01-01" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="card">
                                    <div class="card-header">{{__('paswab.sxText')}}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input
                                                      class="form-check-input"
                                                      type="checkbox"
                                                      value="Fever"
                                                      name="sasCheck[]"
                                                      id="signsCheck2"
                                                      {{(is_array(old('sasCheck')) && in_array("Fever", old('sasCheck'))) ? 'checked' : ''}}
                                                    />
                                                    <label class="form-check-label" for="signsCheck2">Lagnat / Fever</label>
                                                </div>
                                                <div id="divFeverChecked">
                                                    <div class="form-group mt-2">
                                                      <label for="SASFeverDeg"><span class="text-danger font-weight-bold">*</span>Degrees (in Celcius)</label>
                                                      <input type="number" class="form-control" name="SASFeverDeg" id="SASFeverDeg" min="1" max="90" step=".1" value="{{old('SASFeverDeg')}}">
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
                                                    <label class="form-check-label" for="signsCheck3">Ubo / Cough</label>
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
                                                    <label class="form-check-label" for="signsCheck4">Panghihina / General Weakness</label>
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
                                                    <label class="form-check-label" for="signsCheck5">Pagkapagod / Fatigue</label>
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
                                                    <label class="form-check-label" for="signsCheck6">Sakit ng Ulo / Headache</label>
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
                                                    <label class="form-check-label" for="signsCheck8">Sakit ng Lalamunan / Sore Throat</label>
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
                                                    <label class="form-check-label" for="signsCheck12">Pagduduwal / Nausea</label>
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
                                                    <label class="form-check-label" for="signsCheck13">Nagsusuka / Vomiting</label>
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
                                                    <label class="form-check-label" for="signsCheck14">Pagdudumi / Diarrhea</label>
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
                                                    <label class="form-check-label" for="signsCheck15">Nabago ang Katayuan sa Kaisipan / Altered Mental Status</label>
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
                                                    <label class="form-check-label" for="signsCheck16">Kawalan ng Pang-Amoy / Anosmia <small>(loss of smell, w/o any identified cause)</small></label>
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
                                                    <label class="form-check-label" for="signsCheck17">Kawalan ng Panglasa / Ageusia <small>(loss of taste, w/o any identified cause)</small></label>
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
                                                    <label class="form-check-label" for="signsCheck18">Iba pa / Others</label>
                                                </div>
                                                <div id="divSASOtherChecked">
                                                    <div class="form-group mt-2">
                                                      <label for="SASOtherRemarks"><span class="text-danger font-weight-bold">*</span>Tukuyin / Specify Findings</label>
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
                                                <label class="form-check-label" for="comCheck1">Wala / None</label>
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
                                                <label class="form-check-label" for="comCheck2">Alta-presyon / Hypertension</label>
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
                                                <label class="form-check-label" for="comCheck4">Sakit sa Puso / Heart Disease</label>
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
                                                <label class="form-check-label" for="comCheck5">Sakit sa Baga / Lung Disease</label>
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
                                                <label class="form-check-label" for="comCheck10">Iba pa / Others</label>
                                            </div>
                                            <div id="divComOthersChecked">
                                                <div class="form-group mt-2">
                                                  <label for="COMOOtherRemarks"><span class="text-danger font-weight-bold">*</span>Tukuyin / Specify Findings</label>
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
                        <div class="card-header font-weight-bold">6. Chest X-ray Details</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                      <label for=""><span class="text-danger font-weight-bold">*</span>Kailan natapos / Date done</label>
                                      <input type="date" class="form-control" name="imagingDoneDate" id="imagingDoneDate" value="{{old('imagingDoneDate')}}">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                      <label for="imagingDone"><span class="text-danger font-weight-bold">*</span>Uri ng Chest X-Ray / Chest X-Ray Type</label>
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
                                      <label for="imagingResult"><span class="text-danger font-weight-bold">*</span>Resulta / Results</label>
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
                                          <label for="imagingOtherFindings"><span class="text-danger font-weight-bold">*</span>Tukuyin / Specify findings</label>
                                          <input type="text" class="form-control" name="imagingOtherFindings" id="imagingOtherFindings" value="{{old('imagingOtherFindings')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">7. Exposure History</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="expoitem1"><span class="text-danger font-weight-bold">*</span>Ikaw ba ay na-expose sa taong nag-positibo sa COVID-19 nung nakaraang labing-apat (14) na araw? / Do you have history of exposure to someone who was Confirmed COVID-19 Positive 14 days ago?</label>
                                <select class="form-control" name="expoitem1" id="expoitem1" required>
                                    <option value="" disabled {{is_null(old('expoitem1')) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                    <option value="1" {{(old('expoitem1') == '1') ? 'selected' : ''}}>Oo / Yes</option>
                                    <option value="2" {{(old('expoitem1') == '2') ? 'selected' : ''}}>Hindi / No</option>
                                    <option value="3" {{(old('expoitem1') == '3') ? 'selected' : ''}}>Hindi sigurado / Unknown</option>
                                </select>
                            </div>
                            <div id="divExpoitem1">
                                <div class="form-group">
                                    <label for=""><span class="text-danger font-weight-bold">*</span>Kailan na-expose / Date of Exposure</label>
                                    <input type="date" class="form-control" name="expoDateLastCont" id="expoDateLastCont" min="{{date('Y-m-d', strtotime('-3 Months'))}}" max="{{date('Y-m-d')}}" value="{{old('expoDateLastCont')}}">
                                </div>
                                <div class="card">
                                    <div class="card-header">Isulat ang mga pangalan ng iyong mga nakasama / List the Names of your Close Contact</div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="contact1Name">Name of Close Contact #1</label>
                                                  <input type="text" class="form-control" name="contact1Name" id="contact1Name" style="text-transform: uppercase;">
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
                                                  <input type="text" class="form-control" name="contact2Name" id="contact2Name" style="text-transform: uppercase;">
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
                                                  <input type="text" class="form-control" name="contact3Name" id="contact3Name" style="text-transform: uppercase;">
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
                                                  <input type="text" class="form-control" name="contact4Name" id="contact4Name" style="text-transform: uppercase;">
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
                            <p>{{__('paswab.dataPrivacy')}}</p>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="dpsagree" id="dpsagree" required>
                                {{__('paswab.iAgree')}}
                              </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary btn-block" id="verifyButton" data-toggle="modal" data-target="#verifyDetails">Isumite / Submit</button>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="verifyDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="font-family: Arial, Helvetica, sans-serif">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">Confirm Details</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text-center font-weight-bold text-danger">PLEASE DOUBLE CHECK YOUR DETAILS CAREFULLY BEFORE PROCEEDING. KINDLY CHECK IF THERE ARE TYPOGRAPHICAL ERRORS OR INCORRECT SPELLING IN YOUR NAME.</p>
                        <hr>
                        <p>Client Type: <span id="vpType"></span></p>
                        <p>For Hospitalization: <span id="visForHospitalization"></span></p>
                        <p>For Antigen: <span id="vforAntigen"></span></p>
                        <hr>
                        <p>Last Name: <span id="vlname"></span></p>
                        <p>First Name: <span id="vfname"></span></p>
                        <p>Middle Name: <span id="vmname"></span></p>
                        <p>Birthdate: <span id="vbdate"></span></p>
                        <p>Gender: <span id="vgender"></span></p>
                        <p>Civil Status: <span id="vcs"></span></p>
                        <p>Mobile Number: <span id="vmobile"></span></p>
                        <p>Philhealth Number: <span id="vphilhealth"></span></p>
                        <hr>
                        <p>House No./Lot/Bldg: <span id="vaddress_houseno"></span></p>
                        <p>Street/Purok/Sitio: <span id="vaddress_street"></span></p>
                        <p>Barangay: <span id="vaddress_brgy"></span></p>
                        <p>City/Municipality: <span id="vaddress_city"></span></p>
                        <p>Province: <span id="vaddress_province"></span></p>
                        <hr>
                        <p class="text-center font-weight-bold">If you would like to change some details, press <span class="text-secondary">[Go Back]</span>. If all details stated are correct upon checking, then press <span class="text-success">[Proceed]</span> to finish the registration.</p>
                        <p class="text-center font-weight-bold text-danger">NOTE: YOU CANNOT EDIT YOUR OWN DETAILS ONCE IT IS SUBMITTED.</p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                        <button type="button" id="proceedbtn" class="btn btn-success">Proceed</button>
                        <button type="submit" id="submitbtn" class="btn btn-success d-none">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <div class="modal fade" id="announcement" tabindex="-1" role="dialog" style="font-family: Arial, Helvetica, sans-serif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notice</h5>
                </div>
                <div class="modal-body text-center">
                    <p class="font-weight-bold text-danger">PARA SA MGA NAKAPAG-REHISTRO NA, PAKIBASA PO</p>
                    <p>Upang makita ang status at kung kailan ka naka-schedule na is-swab, bumisita lamang sa <a href="{{route('main')}}">cesugentri.com</a> at dumako sa [I am a Patient] Section at gamitin ang "Schedule Code" na ibinigay sa iyo ng system.</p>
                    <p>Ikaw rin ay makakatanggap ng tawag o text mula sa iyong Barangay na kinabibilangan sa <strong>mismong araw ding iyon</strong> kung kailan ka naka-schedule na is-swab.</p>
                    <hr>
                    <p class="font-weight-bold text-danger">SA MGA MAGR-REHISTRO PA LANG,</p>
                    <p>Hangga't maaari, isulat ang iyong PhilHealth Number kapag magr-rehistro. Kung ikaw ay menorde-edad pa lang, isulat naman ang PhilHealth Number ng iyong magulang. Ito ay para makaiwas sa mahabang proseso at abala sa araw ng iyong schedule.</p>
                    <p>Kung walang PhilHealth Number, magdala na lamang ng ibang Valid ID/Birth Certificate na naka-photocopy sa araw ng iyong schedule.</p>
                    <hr>
                    <p>Maraming Salamat po!</p>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            @if($enableLockAddress != 1)
            //Patient Location Select2 Init
            $('#saddress_province, #saddress_city, #address_brgy').select2({
			    theme: "bootstrap",
            });
            @endif

            //Occupation Location Select2 Init
            $('#soccupation_province, #soccupation_city, #occupation_brgy, #natureOfWork').select2({
			    theme: "bootstrap",
            });
        });

        @if(session('skipmodal') == false)
        $('#announcement').modal({backdrop: 'static', keyboard: false});
        $('#announcement').modal('show');
        @endif

        $('#proceedbtn').click(function (e) { 
            $('#verifyDetails').modal('hide');
            setTimeout(function(){
                $('#submitbtn').trigger('click');
            }, 500);
        });

        $('#verifyButton').click(function (e) { 
            e.preventDefault();
            if($('#pType').val() == 'TESTING') {
                $('#vpType').text('NOT A CASE OF COVID');
            }
            else if($('#pType').val() == 'CLOSE CONTACT') {
                $('#vpType').text('CLOSE CONTACT');
            }
            else if($('#pType').val() == 'PROBABLE') {
                $('#vpType').text('SUSPECTED');
            }
            if($('#isForHospitalization').val() == 1) {
                $('#visForHospitalization').text('YES');
            }
            else {
                $('#visForHospitalization').text('NO');
            }
            if($('#forAntigen').val() == 1) {
                $('#vforAntigen').text('YES');
            }
            else {
                $('#vforAntigen').text('NO');
            }
            $('#vlname').text($('#lname').val().toUpperCase());
            $('#vfname').text($('#fname').val().toUpperCase());
            $('#vmname').text($('#mname').val().toUpperCase());
            $('#vbdate').text($('#bdate').val());
            $('#vgender').text($('#gender').val());
            $('#vcs').text($('#cs').val());
            $('#vmobile').text($('#mobile').val());
            $('#vphilhealth').text($('#philhealth').val());
            $('#vaddress_province').text($('#address_province').val());
            $('#vaddress_city').text($('#address_city').val());
            $('#vaddress_brgy').text($('#address_brgy').val());
            $('#vaddress_houseno').text($('#address_houseno').val().toUpperCase());
            $('#vaddress_street').text($('#address_street').val().toUpperCase());
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
        
        @if($enableLockAddress != 1)
        $('#saddress_city').prop('disabled', true);
		$('#address_brgy').prop('disabled', true);

        //Patient Province JSON Init
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
        @endif
        
        //Occupation Province JSON Init
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
                $('#soccupation_province').append($('<option>', {
					value: val.provCode,
					text: val.provDesc,
				}));
			});
        });

        $('#soccupation_city').prop('disabled', true);
		$('#occupation_brgy').prop('disabled', true);

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
						$("#soccupation_city").append('<option value="'+val.citymunCode+'">'+val.citymunDesc+'</option>');
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
						$("#occupation_brgy").append('<option value="'+val.brgyDesc.toUpperCase()+'">'+val.brgyDesc.toUpperCase()+'</option>');
					}
				});
			});
		});
        
        $('#haveOccupation').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#occupationRow').show();
                $('#occupation').prop('required', true);
                $('#occupation_name').prop('required', true);
                $('#natureOfWork').prop('required', true);
                $('#worksInClosedSetting').prop('required', true);
                $('#soccupation_province').prop('required', true);
                $('#soccupation_city').prop('required', true);
                $('#occupation_brgy').prop('required', true);
                $('#occupation_lotbldg').prop('required', true);
                $('#occupation_street').prop('required', true);
            }
            else {
                $('#occupationRow').hide();
                $('#occupation').prop('required', false);
                $('#occupation_name').prop('required', false);
                $('#natureOfWork').prop('required', false);
                $('#worksInClosedSetting').prop('required', false);
                $('#soccupation_province').prop('required', false);
                $('#soccupation_city').prop('required', false);
                $('#occupation_brgy').prop('required', false);
                $('#occupation_lotbldg').prop('required', false);
                $('#occupation_street').prop('required', false);
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

        $('#pType').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "CLOSE CONTACT") {
                $('#expoitem1').empty();
                $('#expoitem1').append($('<option>', {
					value: '1',
					text: 'Oo / Yes',
					selected: true,
				}));
                $('#expoitem1').trigger('change');    
            }
            else {
                $('#expoitem1').empty();
                $('#expoitem1').append($('<option>', {
					value: "",
					text: 'Pumili... / Choose...',
					selected: true,
                    disabled: true,
				}));
                $('#expoitem1').append($('<option>', {
					value: '1',
					text: 'Oo / Yes',
				}));
                $('#expoitem1').append($('<option>', {
					value: '2',
					text: 'Hindi / No',
				}));
                $('#expoitem1').append($('<option>', {
					value: '3',
					text: 'Hindi sigurado / Unknown',
				}));
                $('#expoitem1').trigger('change');
            }
        });

        $('#forAntigen').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "1") {
                alert('You chose "Antigen" as the Type of Test for your COVID-19 Testing. Kindly take note that this is different from RT-PCR Test. To proceed in Antigen Testing, click OK to proceed. But if you want to undergo RT-PCR Testing, change this option to [NO].');
            }
        });
        
        $('#vaccineq1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#ifVaccinated').show();
                $('#howManyDose').prop('required', true);
                $('#nameOfVaccine').prop('required', true);
            }
            else {
                $('#ifVaccinated').hide();
                $('#howManyDose').prop('required', false);
                $('#nameOfVaccine').prop('required', false);
            }
        }).trigger('change');

        $('#howManyDose').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#VaccineDose1').show();
                $('#VaccineDose2').hide();
                $('#vaccinationDate1').prop('required', true);
                $('#haveAdverseEvents1').prop('required', true);
                $('#vaccinationDate2').prop('required', false);
                $('#haveAdverseEvents2').prop('required', false);
            }
            else if($(this).val() == '2') {
                $('#VaccineDose1').show();
                $('#VaccineDose2').show();
                $('#vaccinationDate1').prop('required', true);
                $('#haveAdverseEvents1').prop('required', true);
                $('#vaccinationDate2').prop('required', true);
                $('#haveAdverseEvents2').prop('required', true);
            }
            else {
                $('#VaccineDose1').hide();
                $('#VaccineDose2').hide();
                $('#vaccinationDate1').prop('required', false);
                $('#haveAdverseEvents1').prop('required', false);
                $('#vaccinationDate2').prop('required', false);
                $('#haveAdverseEvents2').prop('required', false);
            }
        }).trigger('change');

        $('#vaccineName').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'JANSSEN') {
                $('#howManyDose').val(1).trigger('change');
                $('#2ndDoseOption').hide();
            }
            else {
                $('#2ndDoseOption').show();
            }
        }).trigger('change');

        $('#myForm').on('submit', function() {
            $('#expoitem1').prop('disabled', false);
        });

        $('#havePhilhealth').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'YES') {
                $('#philhealthbox').removeClass('d-none');
                $('#nophilhealthbox').addClass('d-none');
                $('#ask2').addClass('d-none');
                $('#label_parentphilhealth').addClass('d-none');
                $('#label_ownphilhealth').removeClass('d-none');
                $('#philhealth').prop('required', true);
                $('#parentBringMDR').addClass('d-none');
            }
            else if($(this).val() == 'NO') {
                $('#philhealthbox').addClass('d-none');
                $('#ask2').removeClass('d-none');
                $('#declaredDependent').val('');
                $('#philhealth').prop('required', false);
                $('#philhealth').val('');
            }
            else {
                $('#philhealthbox').addClass('d-none');
                $('#ask2').addClass('d-none');
                $('#philhealth').prop('required', false);
                $('#philhealth').val('');
            }
        }).trigger('change');

        $('#declaredDependent').change(function (e) {
            e.preventDefault();
            if($(this).val() == 'YES') {
                $('#philhealthbox').removeClass('d-none');
                $('#label_parentphilhealth').removeClass('d-none');
                $('#label_ownphilhealth').addClass('d-none');
                $('#nophilhealthbox').addClass('d-none');
                $('#philhealth').prop('required', true);
                $('#parentBringMDR').removeClass('d-none');
            }
            else if($(this).val() == 'NO') {
                $('#philhealthbox').addClass('d-none');
                $('#nophilhealthbox').removeClass('d-none');
                $('#philhealth').prop('required', false);
                $('#philhealth').val('');
            }
            else {
                $('#philhealthbox').addClass('d-none');
                $('#nophilhealthbox').addClass('d-none');
                $('#philhealth').prop('required', false);
                $('#philhealth').val('');
            }
        });
    </script>
    @else
    <div class="container">
        <div class="card">
            <div class="card-header">Notice</div>
            <div class="card-body text-center">
                <p>As of July 10, 2021, <span class="text-primary">paswab.cesugentri.com</span> will require a valid Referral Code before proceeding into registration.</p>
                <p>
                    This is to prevent unauthorized and unmonitored patients from barangay to register. This will also provide information on where the patients information is coming from.
                </p>
            </div>
        </div>
    </div>
    @endif
@endsection