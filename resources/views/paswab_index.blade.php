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
                        <p>{{Str::plural('Error', $errors->count())}} detected during processing your Pa-swab Data:</p>
                        <hr>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </div>
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
                                <input type="text" class="form-control" value="{{$interviewerName}} {{(!is_null($lock_brgy)) ? '(BRGY.'.$lock_brgy.')' : ''}}" disabled>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pType"><span class="text-danger font-weight-bold">*</span>{{__('paswab.pType')}}</label>
                                        <select class="form-control" name="pType" id="pType" required>
                                            <option value="" disabled selected>{{__('paswab.select.Choose')}}</option>
                                            <option value="PROBABLE" @if(old('pType') == "PROBABLE"){{'selected'}}@endif>Suspected (May Sintomas)</option>
                                            <option value="CLOSE CONTACT" @if(old('pType') == "CLOSE CONTACT"){{'selected'}}@endif>Close Contact</option>
                                            <option value="TESTING" @if(old('pType') == "TESTING"){{'selected'}}@endif>Hospitalization (Buntis/Operation/Dialysis/Chemotheraphy, etc.)</option>
                                            <!--<option value="FOR TRAVEL" @if(old('pType') == "FOR TRAVEL"){{'selected'}}@endif>For Travel</option>-->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="interviewDate"><span class="text-danger font-weight-bold">*</span>{{__('paswab.interviewDate')}}</label>
                                        <input type="date" name="interviewDate" id="interviewDate" class="form-control" min="{{date('Y-m-d', strtotime("-7 Days"))}}" max="{{date('Y-m-d')}}" value="{{old('interviewDate', date('Y-m-d'))}}" required>
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
                                            <option value="0" id="isForHospitalization_sno" {{(old('isForHospitalization') == '0') ? 'selected' : ''}}>{{__('paswab.select.ChooseNo')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="forAntigen"><span class="text-danger font-weight-bold">*</span>{{__('paswab.forAntigen')}}</label>
                                        <select class="form-control" name="forAntigen" id="forAntigen" required>
                                            <option value="" disabled {{is_null(old('forAntigen')) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                            <option value="1" {{(old('forAntigen') == '1') ? 'selected' : ''}}>{{__('paswab.forAntigen_yes')}}</option>
                                            <option value="0" {{(old('forAntigen') == '0') ? 'selected' : ''}} id="forAntigen_no">{{__('paswab.forAntigen_no')}}</option>
                                        </select>
                                        <small class="text-muted">{{__('paswab.forAntigenNotice')}}</small>
                                        <!--<small class="text-danger">Selecting "YES" for antigen is temporarily disabled. All Patients are suggested to take RT-PCR Test.</small>-->
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
                                        <input type="text" class="form-control @error('lname') border-danger @enderror" id="lname" name="lname" value="{{old('lname')}}" placeholder="DELA CRUZ" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                                        @error('lname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fname"><span class="text-danger font-weight-bold">*</span>{{__('paswab.fname')}}</label>
                                        <input type="text" class="form-control @error('fname') border-danger @enderror" id="fname" name="fname" value="{{old('fname')}}" placeholder="JUAN JR" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                                        <small class="text-muted">{{__('paswab.fNameNotice')}}</small>
                                        @error('fname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mname">{{__('paswab.mname')}} <small><i>{{__('paswab.leaveBlank')}}</i></small></label>
                                        <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" placeholder="SANTOS" minlength="2" maxlength="50" style="text-transform: uppercase;">
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
                                    <div id="ifGenderFemale" class="d-none">
                                        <div class="form-group">
                                            <label for="isPregnant"><span class="text-danger font-weight-bold">*</span>{{__('paswab.isPregnant')}}</label>
                                            <select class="form-control" name="isPregnant" id="isPregnant">
                                                <option value="" disabled {{(is_null(old('isPregnant'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                                <option value="0" {{(old('isPregnant') == '0') ? 'selected' : ''}}>{{__('paswab.select.ChooseNo')}}</option>
                                                <option value="1" {{(old('isPregnant') == '1') ? 'selected' : ''}}>{{__('paswab.select.ChooseYes')}}</option>
                                            </select>
                                        </div>
                                        <div id="ifPregnant" class="d-none">
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
                                        <label for="mobile"><span class="text-danger font-weight-bold">*</span>Mobile Number</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile', '09')}}" pattern="[0-9]{11}" placeholder="09*********" required>
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
                                        <option value="YES" {{(old('havePhilhealth') == 'YES') ? 'selected' : ''}}>{{__('paswab.select.ChooseYes')}}</option>
                                        <option value="NO" {{(old('havePhilhealth') == 'NO') ? 'selected' : ''}}>{{__('paswab.select.ChooseNo')}}</option>
                                      </select>
                                      <small class="form-text text-muted">{{__('paswab.philhealth.notice')}}</i></small>
                                    </div>
                                    <div id="ask2" class="d-none">
                                        <div class="form-group">
                                          <label for="declaredDependent"><span class="text-danger font-weight-bold">*</span>Are you declared as dependent from your Parents Philhealth Number?</label>
                                          <select class="form-control" name="declaredDependent" id="declaredDependent">
                                            <option value="" disabled {{(is_null(old('declaredDependent'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                            <option value="YES" {{(old('declaredDependent') == 'YES') ? 'selected' : ''}}>{{__('paswab.select.ChooseYes')}}</option>
                                            <option value="NO" {{(old('declaredDependent') == 'NO') ? 'selected' : ''}}>{{__('paswab.select.ChooseNo')}}</option>
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
                                        <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}" placeholder="someone@example.com">
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
                            <div class="alert alert-info" role="alert">
                                <strong class="text-danger">Note:</strong> Special Characters such as <strong>! @ # _ $ , . ( )</strong> etc. are not allowed to input in the adress bar. 
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
                                        <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" maxlength="30" required>
                                        @error('address_houseno')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if(is_null($lock_subd_array))
                                    <div class="form-group">
                                        <label for="address_street"><span class="text-danger font-weight-bold">*</span>Street/Purok/Sitio/Subdivision</label>
                                        <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" minlength="4" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" maxlength="50" required>
                                        <small class="text-muted">{{__('paswab.street.notice')}}</small>
                                        @error('address_street')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    @else
                                    <div class="form-group">
                                      <label for="address_street"><span class="text-danger font-weight-bold">*</span>Select Subdivision</label>
                                      <select class="form-control" name="address_street" id="address_street" required>
                                        <option value="" disabled {{(is_null(old('address_street'))) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                        @foreach(explode(",", $lock_subd_array) as $s)
                                        <option value="{{$s}}">{{$s}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    @endif
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
                            <div id="occupationRow" class="d-none">
                                <div class="alert alert-info" role="alert">
                                    <strong class="text-danger">Notice:</strong> starting October 06, 2021. <strong>Workplace/Company Details should be COMPLETED and CORRECT.</strong> This is to comply with the requirements of the Molecular Laboratory.
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
                                              <option value="MASS MEDIA" {{(old('natureOfWork') == 'MASS MEDIA') ? 'selected' : ''}}>Mass Media (Field Reporters, Photographers, etc.)</option>
                                              <option value="MEDICAL AND HEALTH SERVICES" {{(old('natureOfWork') == 'MEDICAL AND HEALTH SERVICES') ? 'selected' : ''}}>Medical and Health Services</option>
                                              <option value="MICROFINANCE" {{(old('natureOfWork') == 'MICROFINANCE') ? 'selected' : ''}}>Microfinance (E.G. Ahon sa Hirap Inc)</option>
                                              <option value="MINING AND QUARRYING" {{(old('natureOfWork') == 'MINING AND QUARRYING') ? 'selected' : ''}}>Mining and Quarrying (E.G. Philex Mining Corp)</option>
                                              <option value="NON PROFIT ORGANIZATIONS" {{(old('natureOfWork') == 'NON PROFIT ORGANIZATIONS') ? 'selected' : ''}}>Non Profit Organizations (E.G. Iglesia Ni Cristo)</option>
                                              <option value="REAL ESTATE" {{(old('natureOfWork') == 'REAL ESTATE') ? 'selected' : ''}}>Real Estate (E.G. Megaworld Corp)</option>
                                              <option value="SERVICES" {{(old('natureOfWork') == 'SERVICES') ? 'selected' : ''}}>Services (Hairdressers, manicurist, embalmers, security guards, messengers, massage therapists, etc.)</option>
                                              <option value="STORAGE" {{(old('natureOfWork') == 'STORAGE') ? 'selected' : ''}}>Storage (Include Freight Forwarding E.G. Dhl)</option>
                                              <option value="TRANSPORTATION" {{(old('natureOfWork') == 'TRANSPORTATION') ? 'selected' : ''}}>Transportation (E.G. Philippine Airlines)</option>
                                              <option value="WHOLESALE AND RETAIL TRADE" {{(old('natureOfWork') == 'WHOLESALE AND RETAIL TRADE') ? 'selected' : ''}}>Wholesale and Retail Trade (E.G. Mercury Drug)</option>
                                              <option value="OTHERS" {{(old('natureOfWork') == 'OTHERS') ? 'selected' : ''}}>{{__('paswab.select.ChooseOthers')}}</option>
                                            </select>
                                              @error('natureOfWork')
                                              <small class="text-danger">{{$message}}</small>
                                              @enderror
                                        </div>
                                        <div id="specifyWorkNatureDiv" class="d-none">
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
                                            <label for="soccupation_province"><span class="text-danger font-weight-bold">*</span>Province of Workplace</label>
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
                                            <label for="soccupation_city"><span class="text-danger font-weight-bold">*</span>City of Workplace</label>
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
                                            <label for="occupation_brgy"><span class="text-danger font-weight-bold">*</span>Barangay of Workplace</label>
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
                                            <label for="occupation_lotbldg"><span class="text-danger font-weight-bold">*</span>Lot/Building of Workplace</label>
                                            <input type="text" class="form-control" id="occupation_lotbldg" name="occupation_lotbldg" value="{{old('occupation_lotbldg')}}" pattern="(^[a-zA-Z0-9 ]+$)+" maxlength="30" style="text-transform: uppercase;">
                                            @error('occupation_lotbldg')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="occupation_street"><span class="text-danger font-weight-bold">*</span>Street of Workplace</label>
                                            <input type="text" class="form-control" id="occupation_street" name="occupation_street" value="{{old('occupation_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" maxlength="50" style="text-transform: uppercase;">
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
                            <div id="ifVaccinated" class="d-none">
                                <div class="form-group">
                                  <label for="howManyDose"><span class="text-danger font-weight-bold">*</span>{{__('paswab.howManyDose')}}</label>
                                  <select class="form-control" name="howManyDose" id="howManyDose">
                                    <option value="" disabled {{is_null(old('howManyDose')) ? 'selected' : ''}}>{{__('paswab.select.Choose')}}</option>
                                    <option value="1" {{(old('howManyDose') == '1') ? 'selected' : ''}}>1st Dose Only</option>
                                    <option value="2" id="2ndDoseOption" {{(old('howManyDose') == '2') ? 'selected' : ''}}>1st and 2nd Dose Completed</option>
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
                                  <small class="text-muted">Vaccine Name not Included in the List? You may contact CESU Staff.</small>
                                </div>
                                <div id="VaccineDose1" class="d-none">
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
                                                <input type="text" class="form-control" name="vaccinationFacility1" id="vaccinationFacility1" value="{{old('vaccinationFacility1')}}" pattern="(^[a-zA-Z0-9 ]+$)+" maxlength="100" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vaccinationRegion1">Region of Health Facility <small>(Optional)</small></label>
                                                <input type="text" class="form-control" name="vaccinationRegion1" id="vaccinationRegion1" value="{{old('vaccinationRegion1')}}" pattern="(^[a-zA-Z0-9 ]+$)+" maxlength="100" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="haveAdverseEvents1"><span class="text-danger font-weight-bold">*</span>Adverse Event/s</label>
                                                <select class="form-control" name="haveAdverseEvents1" id="haveAdverseEvents1">
                                                    <option value="0" {{(old('haveAdverseEvents1') == '0') ? 'selected' : ''}}>No</option>
                                                    <option value="1" {{(old('haveAdverseEvents1') == '1') ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="VaccineDose2" class="d-none">
                                        <hr>
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
                                                    <input type="text" class="form-control" name="vaccinationFacility2" id="vaccinationFacility2" value="{{old('vaccinationFacility2')}}" pattern="(^[a-zA-Z0-9 ]+$)+" maxlength="100" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vaccinationRegion2">Region of Health Facility <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="vaccinationRegion2" id="vaccinationRegion2" value="{{old('vaccinationRegion2')}}" pattern="(^[a-zA-Z0-9 ]+$)+" maxlength="100" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="haveAdverseEvents2"><span class="text-danger font-weight-bold">*</span>Adverse Event/s</label>
                                                    <select class="form-control" name="haveAdverseEvents2" id="haveAdverseEvents2">
                                                        <option value="0" {{(old('haveAdverseEvents2') == '0') ? 'selected' : ''}}>No</option>
                                                        <option value="1" {{(old('haveAdverseEvents2') == '1') ? 'selected' : ''}}>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="booster_question" class="d-none">
                                        <hr>
                                        <div class="form-group">
                                          <label for="haveBooster"><span class="text-danger font-weight-bold">*</span>Already Vaccinated with Booster Vaccine?</label>
                                          <select class="form-control" name="haveBooster" id="haveBooster" required>
                                            <option value="0" {{(old('haveBooster') == '0') ? 'selected' : ''}}>No</option>
                                            <option value="1" {{(old('haveBooster') == '1') ? 'selected' : ''}}>Yes</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div id="ifBoosterVaccine" class="d-none">
                                        <div class="form-group">
                                            <label for="vaccinationName3"><span class="text-danger font-weight-bold">*</span>Booster Vaccine Name</label>
                                            <select class="form-control" name="vaccinationName3" id="vaccinationName3">
                                              <option value="" disabled {{is_null(old('vaccinationName3')) ? 'selected' : ''}}>Choose...</option>
                                              <option value="BHARAT BIOTECH" {{(old('vaccinationName3') == "BHARAT BIOTECH") ? 'selected' : ''}}>Bharat BioTech</option>
                                              <option value="GAMALEYA SPUTNIK V" {{(old('vaccinationName3') == 'GAMALEYA SPUTNIK V') ? 'selected' : ''}}>Gamaleya Sputnik V</option>
                                              <option value="JANSSEN" {{(old('vaccinationName3') == "JANSSEN") ? 'selected' : ''}}>Janssen</option>
                                              <option value="MODERNA" {{(old('vaccinationName3') == 'MODERNA') ? 'selected' : ''}}>Moderna</option>
                                              <option value="NOVARAX" {{(old('vaccinationName3') == 'NOVARAX') ? 'selected' : ''}}>Novarax</option>
                                              <option value="OXFORD ASTRAZENECA" {{(old('vaccinationName3') == 'OXFORD ASTRAZENECA') ? 'selected' : ''}}>Oxford AstraZeneca</option>
                                              <option value="PFIZER BIONTECH" {{(old('vaccinationName3') == 'PFIZER BIONTECH') ? 'selected' : ''}}>Pfizer BioNTech</option>
                                              <option value="SINOPHARM" {{(old('vaccinationName3') == 'SINOPHARM') ? 'selected' : ''}}>Sinopharm</option>
                                              <option value="SINOVAC CORONAVAC" {{(old('vaccinationName3') == 'SINOVAC CORONAVAC') ? 'selected' : ''}}>Sinovac Coronavac</option>
                                            </select>
                                            <small class="text-muted">Vaccine Name not Included in the List? You may contact CESU Staff.</small>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vaccinationDate3"><span class="text-danger font-weight-bold">*</span>Booster Date Vaccinated</label>
                                                    <input type="date" class="form-control" name="vaccinationDate3" id="vaccinationDate3" value="{{old('vaccinationDate3')}}" max="{{date('Y-m-d')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="haveAdverseEvents3"><span class="text-danger font-weight-bold">*</span>Booster Adverse Event/s</label>
                                                    <select class="form-control" name="haveAdverseEvents3" id="haveAdverseEvents3">
                                                        <option value="0" {{(old('haveAdverseEvents3') == '0') ? 'selected' : ''}}>No</option>
                                                        <option value="1" {{(old('haveAdverseEvents3') == '1') ? 'selected' : ''}}>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vaccinationFacility3">Booster Vaccination Center/Facility <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="vaccinationFacility3" id="vaccinationFacility3" value="{{old('vaccinationFacility3')}}" pattern="(^[a-zA-Z0-9 ]+$)+" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="vaccinationRegion3">Booster Region of Health Facility <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="vaccinationRegion3" id="vaccinationRegion3" value="{{old('vaccinationRegion3')}}" pattern="(^[a-zA-Z0-9 ]+$)+" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label for="haveBooster2"><span class="text-danger font-weight-bold">*</span>Have 2nd Booster Vaccine?</label>
                                            <select class="form-control" name="haveBooster2" id="haveBooster2" required>
                                              <option value="0" {{(old('haveBooster2') == '0') ? 'selected' : ''}}>No</option>
                                              <option value="1" {{(old('haveBooster2') == '1') ? 'selected' : ''}}>Yes</option>
                                            </select>
                                        </div>
                                        <div id="ifBoosterVaccine2" class="d-none">
                                            <div class="form-group">
                                                <label for="vaccinationName4"><span class="text-danger font-weight-bold">*</span>2ND Booster Vaccine Name</label>
                                                <select class="form-control" name="vaccinationName4" id="vaccinationName4">
                                                  <option value="" disabled {{is_null(old('vaccinationName4')) ? 'selected' : ''}}>Choose...</option>
                                                  <option value="BHARAT BIOTECH" {{(old('vaccinationName4') == "BHARAT BIOTECH") ? 'selected' : ''}}>Bharat BioTech</option>
                                                  <option value="GAMALEYA SPUTNIK V" {{(old('vaccinationName4') == 'GAMALEYA SPUTNIK V') ? 'selected' : ''}}>Gamaleya Sputnik V</option>
                                                  <option value="JANSSEN" {{(old('vaccinationName4') == "JANSSEN") ? 'selected' : ''}}>Janssen</option>
                                                  <option value="MODERNA" {{(old('vaccinationName4') == 'MODERNA') ? 'selected' : ''}}>Moderna</option>
                                                  <option value="NOVARAX" {{(old('vaccinationName4') == 'NOVARAX') ? 'selected' : ''}}>Novarax</option>
                                                  <option value="OXFORD ASTRAZENECA" {{(old('vaccinationName4') == 'OXFORD ASTRAZENECA') ? 'selected' : ''}}>Oxford AstraZeneca</option>
                                                  <option value="PFIZER BIONTECH" {{(old('vaccinationName4') == 'PFIZER BIONTECH') ? 'selected' : ''}}>Pfizer BioNTech</option>
                                                  <option value="SINOPHARM" {{(old('vaccinationName4') == 'SINOPHARM') ? 'selected' : ''}}>Sinopharm</option>
                                                  <option value="SINOVAC CORONAVAC" {{(old('vaccinationName4') == 'SINOVAC CORONAVAC') ? 'selected' : ''}}>Sinovac Coronavac</option>
                                                </select>
                                                <small class="text-muted">Vaccine Name not Included in the List? You may contact CESU Staff.</small>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="vaccinationDate4"><span class="text-danger font-weight-bold">*</span>2ND Booster Date Vaccinated</label>
                                                        <input type="date" class="form-control" name="vaccinationDate4" id="vaccinationDate4" value="{{old('vaccinationDate4')}}" max="{{date('Y-m-d')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="haveAdverseEvents4"><span class="text-danger font-weight-bold">*</span>2ND Booster Adverse Event/s</label>
                                                        <select class="form-control" name="haveAdverseEvents4" id="haveAdverseEvents4">
                                                            <option value="0" {{(old('haveAdverseEvents4') == '0') ? 'selected' : ''}}>No</option>
                                                            <option value="1" {{(old('haveAdverseEvents4') == '1') ? 'selected' : ''}}>Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="vaccinationFacility4">2ND Booster Vaccination Center/Facility <small>(Optional)</small></label>
                                                        <input type="text" class="form-control" name="vaccinationFacility4" id="vaccinationFacility4" value="{{old('vaccinationFacility4')}}" pattern="(^[a-zA-Z0-9 ]+$)+" style="text-transform: uppercase;">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="vaccinationRegion4">2ND Booster Region of Health Facility <small>(Optional)</small></label>
                                                        <input type="text" class="form-control" name="vaccinationRegion4" id="vaccinationRegion4" value="{{old('vaccinationRegion4')}}" pattern="(^[a-zA-Z0-9 ]+$)+" style="text-transform: uppercase;">
                                                    </div>
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
                            <div id="ifHaveSymptoms" class="d-none">
                                <div class="form-group">
                                    <label for="dateOnsetOfIllness"><span class="text-danger font-weight-bold">*</span>{{__('paswab.dateOnsetOfIllness')}}</label>
                                    <input type="date" class="form-control" name="dateOnsetOfIllness" id="dateOnsetOfIllness" min="1999-01-01" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="card">
                                    <div class="card-header">{{__('paswab.sxText')}}</div>
                                    <div class="card-body">
                                        <div class="row symptomsList">
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
                                                    <label class="form-check-label" for="signsCheck2">Lagnat/Fever</label>
                                                </div>
                                                <div id="divFeverChecked" class="d-none">
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
                                                    <label class="form-check-label" for="signsCheck3">Ubo/Cough</label>
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
                                                    <label class="form-check-label" for="signsCheck4">Panghihina/General Weakness</label>
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
                                                    <label class="form-check-label" for="signsCheck5">Pagkapagod/Fatigue</label>
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
                                                    <label class="form-check-label" for="signsCheck6">Sakit ng Ulo/Headache</label>
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
                                                    <label class="form-check-label" for="signsCheck7">Sakit ng Katawan/Body Pain</label>
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
                                                    <label class="form-check-label" for="signsCheck8">Sakit ng Lalamunan/Sore Throat</label>
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
                                                    <label class="form-check-label" for="signsCheck9">Sipon/Colds <small>(Coryza)</small></label>
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
                                                    <label class="form-check-label" for="signsCheck10">Nahihirapang Huminga/Difficulty of Breath <small>(Dyspnea)</small></label>
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
                                                    <label class="form-check-label" for="signsCheck11">Eating Disorder <small>(Anorexia)</small></label>
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
                                                    <label class="form-check-label" for="signsCheck12">Pagkahilo/Nausea</label>
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
                                                    <label class="form-check-label" for="signsCheck13">Nagsusuka/Vomiting</label>
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
                                                    <label class="form-check-label" for="signsCheck14">Pagdudumi/Diarrhea</label>
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
                                                    <label class="form-check-label" for="signsCheck15">Nabago ang Katayuan sa Kaisipan/Altered Mental Status</label>
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
                                                    <label class="form-check-label" for="signsCheck16">Kawalan ng Pang-Amoy/Loss of Smell</small></label>
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
                                                    <label class="form-check-label" for="signsCheck17">Kawalan ng Panglasa/Loss of Taste</label>
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
                                                    <label class="form-check-label" for="signsCheck18">Iba pa/Others</label>
                                                </div>
                                                <div id="divSASOtherChecked" class="d-none">
                                                    <div class="form-group mt-2">
                                                      <label for="SASOtherRemarks"><span class="text-danger font-weight-bold">*</span>Tukuyin/Specify Findings</label>
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
                                <div class="card-header">Comorbidities / Reason for Hospitalization <small><i>(Check all that apply if present)</i></small></div>
                                <div class="card-body">
                                    <div class="alert alert-info d-none" id="useHospAlert" role="alert">
                                        <span><b class="text-danger">Note:</b> You will use the swab for Hospitalization. Please check at least one reason below.</span>
                                    </div>  
                                    <div class="row comoOpt">
                                        <div class="col-md-6">
                                            <div class="form-check" id="como_none">
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
                                                  value="Cancer"
                                                  name="comCheck[]"
                                                  id="comCheck9"
                                                  required
                                                  {{(is_array(old('comCheck')) && in_array("Cancer", old('comCheck'))) ? 'checked' : ''}}
                                                />
                                                <label class="form-check-label" for="comCheck9">Cancer (for Chemotheraphy/Radiotheraphy)</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Dialysis"
                                                  name="comCheck[]"
                                                  id="comCheck11"
                                                  required
                                                  {{(is_array(old('comCheck')) && in_array("Dialysis", old('comCheck'))) ? 'checked' : ''}}
                                                />
                                                <label class="form-check-label" for="comCheck11">For Dialysis</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Operation"
                                                  name="comCheck[]"
                                                  id="comCheck12"
                                                  required
                                                  {{(is_array(old('comCheck')) && in_array("Operation", old('comCheck'))) ? 'checked' : ''}}
                                                />
                                                <label class="form-check-label" for="comCheck12">For Operation</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Transplant"
                                                  name="comCheck[]"
                                                  id="comCheck13"
                                                  required
                                                  {{(is_array(old('comCheck')) && in_array("Transplant", old('comCheck'))) ? 'checked' : ''}}
                                                />
                                                <label class="form-check-label" for="comCheck13">Had Organ Transplant/Bone Marrow/Stem Cell Transplant (for the Past 6 Months)</label>
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
                                                  value="Others"
                                                  name="comCheck[]"
                                                  id="comCheck10"
                                                  required
                                                  {{(is_array(old('comCheck')) && in_array("Others", old('comCheck'))) ? 'checked' : ''}}
                                                />
                                                <label class="form-check-label" for="comCheck10">Iba pa / Others</label>
                                            </div>
                                            <div id="divComOthersChecked" class="d-none">
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
                                        <option value="None" {{(old('imagingDone') == "None") ? 'selected' : ''}}>None / Wala</option>
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
                                    <div id="divImagingOthers" class="d-none">
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
                            <div class="alert alert-info" role="alert">
                                <b class="text-danger">Note:</b> Starting <b>August 09, 2022</b>, Date of Exposure and Names of Close Contact on Home or other places <b>will be REQUIRED to be filled up</b>. This is to comply with the requirements of the Molecular Laboratory.
                            </div>
                            <div class="form-group">
                                <label for="expoitem1"><span class="text-danger font-weight-bold">*</span>Ikaw ba ay na-expose sa taong nag-positibo sa COVID-19 o pumunta sa lugar na may aktibong kaso ng COVID-19 nung nakaraang labing-apat (14) na araw? / Do you have history of exposure to someone who was Confirmed COVID-19 OR went to a place that has active COVID-19 case/s 14 days ago?</label>
                                <select class="form-control" name="expoitem1" id="expoitem1" required>
                                    <option value="1" {{(old('expoitem1') == '1') ? 'selected' : ''}}>Oo / Yes</option>
                                    <!--
                                    <option id="sexpoitem1_no" class="d-none" value="2" {{(old('expoitem1') == '2') ? 'selected' : ''}}>Hindi / No</option>
                                    <option id="sexpoitem1_unknown" class="d-none" value="3" {{(old('expoitem1') == '3') ? 'selected' : ''}}>Hindi sigurado / Unknown</option>
                                    -->
                                </select>
                            </div>
                            <div id="divExpoitem1" class="d-none">
                                <div class="form-group">
                                    <label for=""><span class="text-danger font-weight-bold">*</span>Kailan na-expose sa nag-positibo o pumunta sa Lugar na may aktibong kaso ng COVID-19?</label>
                                    <input type="date" class="form-control" name="expoDateLastCont" id="expoDateLastCont" min="{{date('Y-m-d', strtotime('-21 Days'))}}" max="{{date('Y-m-d')}}" value="{{old('expoDateLastCont')}}">
                                </div>
                                <div class="card">
                                    <div class="card-header"><span class="text-danger font-weight-bold">*</span>Contact Tracing - Ilista ang mga pangalan ng mga kasama sa bahay o mga nakasalamuha noong mga nakaraang araw <i>(Atleast One (1) Required, Maximum of Four(4))</i></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="contact1Name"><span class="text-danger font-weight-bold">*</span>Name of Close Contact #1</label>
                                                  <input type="text" class="form-control" name="contact1Name" id="contact1Name" minlength="5" maxlength="60" style="text-transform: uppercase;" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact1No">Mobile Number of Close Contact #1</label>
                                                    <input type="text" class="form-control" name="contact1No" id="contact1No" pattern="[0-9]{11}" placeholder="09*********">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-none" id="namelist2">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="contact2Name">Name of Close Contact #2</label>
                                                  <input type="text" class="form-control" name="contact2Name" id="contact2Name" minlength="5" maxlength="60" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact2No">Mobile Number of Close Contact #2</label>
                                                    <input type="text" class="form-control" name="contact2No" id="contact2No" pattern="[0-9]{11}" placeholder="09*********">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-none" id="namelist3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="contact3Name">Name of Close Contact #3</label>
                                                  <input type="text" class="form-control" name="contact3Name" id="contact3Name" minlength="5" maxlength="60" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact3No">Mobile Number of Close Contact #3</label>
                                                    <input type="text" class="form-control" name="contact3No" id="contact3No" pattern="[0-9]{11}" placeholder="09*********">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-none" id="namelist4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="contact4Name">Name of Close Contact #4</label>
                                                  <input type="text" class="form-control" name="contact4Name" id="contact4Name" minlength="5" maxlength="60" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact4No">Mobile Number of Close Contact #4</label>
                                                    <input type="text" class="form-control" name="contact4No" id="contact4No" pattern="[0-9]{11}" placeholder="09*********">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header font-weight-bold"><span class="text-danger font-weight-bold">*</span>Data Privacy Statement of General Trias</div>
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
                    <button type="submit" class="btn btn-primary btn-block">Isumite / Submit</button>
                    <hr>
                    <p class="text-center">Note: If errors/issues has been found or if site not working properly, please contact CESU Staff Immediately.</p>
                </div>
            </div>
            <p class="text-center mt-3">For inquiries: 0919 066 43 24/25/27 | (046) 509 - 5289 | <a href = "mailto: cesu.gentrias@gmail.com">cesu.gentrias@gmail.com</a> | <a href="https://www.facebook.com/cesugentrias">Facebook Page</a></p>
            <hr>
            <p class="mt-3 text-center">Developed and Maintained by <u>CJH</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
        </div>
        
        <!--
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
        -->
    </form>
    
    <div class="modal fade" id="announcement" tabindex="-1" role="dialog" style="font-family: Arial, Helvetica, sans-serif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title"><b class="text-info">Welcome to CESU General Trias Swab Scheduling System (aka Pa-Swab)</b></h5>
                </div>
                <div class="modal-body">
                    <h4 class="text-danger text-center"><b>PAKIBASA</b></h4>
                    <p><b>ANG MGA MAAARI LAMANG MAG-REQUEST NG SWAB TEST SCHEDULE AY ANG MGA PASYENTENG:</b></p>
                    <ul>
                        <li>May Sintomas ng COVID-19 <i>(Ubo, sipon, makating lalamunan, nahihirapan huminga, walang pang-amoy/pang-lasa, atbp.)</i></li>
                        <li>Close Contact <i>(May nakasalamuhang nag-positibo sa COVID-19)</i></li>
                        <li>Buntis</li>
                        <li>Gagamitin sa Ospital <i>(Operasyon, Dialysis, Chemotherapy, etc.)</i></li>
                    </ul>
                    <hr>
                    <div id="btlist">
                        <p class="text-center">Pumili upang magpatuloy:</p>
                        <button class="btn btn-primary btn-lg btn-block" id="dd1">Ako ay miyembro na ng Philhealth</button>
                        <button class="btn btn-primary btn-lg btn-block" id="dd2">Ako ay Kasal na at hindi pa miyembro ng Philhealth, pero naka-deklara ako bilang dependent sa Philhealth ng Asawa ko</button>
                        <button class="btn btn-primary btn-lg btn-block" id="dd3">Ako ay Menor de Edad pa lamang <i>(17 taong gulang pababa)</i> at hindi pa miyembro ng Philhealth, pero naka-deklara ako bilang dependent sa Philhealth ng Magulang ko</button>
                        <button class="btn btn-primary btn-lg btn-block" id="dd4">Ako ay hindi pa talaga miyembro ng Philhealth, at may nakahanda akong Birth/Baptismal Certificate</button>
                        <button class="btn btn-primary btn-lg btn-block" id="dd5">Wala sa mga nabanggit</button>
                    </div>
                    <div id="dc1" class="d-none">
                        <p><b>Kung ikaw ay isang miyembro na ng Philhealth, paki-handa ang sumusunod:</b></p>
                        <ul>
                            <li>Photocopy ng iyong Philhealth ID o MDR (Member Data Record)</li>
                        </ul>
                    </div>
                    <div id="dc2" class="d-none">
                        <p><b>Kung ikaw ay kasal na at hindi pa miyembro ng Philhealth, pero naka-deklara bilang DEPENDENT sa Philhealth ng Asawa, paki-handa ang mga sumusunod:</b></p>
                        <ul>
                            <li>Photocopy ng Valid ID ng iyong Asawa</li>
                            <li>Photocopy ng iyong Valid ID o Birth/Baptismal Certificate</li>
                            <li>Photocopy ng Philhealth MDR (Member Data Record) ng iyong asawa kung saan naka-deklara ang iyong pangalan sa list of dependents</li>
                            <li>Photocopy ng Marriage Certificate <i>(bilang proof of relationship)</i></li>
                        </ul>
                    </div>
                    <div id="dc3" class="d-none">
                        <p><b>Kung ikaw ay Menor de Edad pa lamang <i>(17 taon gulang pababa)</i> at hindi pa miyembro ng Philhealth, pero naka-deklara bilang DEPENDENT sa Philhealth ng Magulang, paki-handa ang mga sumusunod:</b></p>
                        <ul>
                            <li>Photopopy ng Philhealth MDR (Member Data Record) ng iyong Magulang na naka-deklara ang iyong pangalan sa list of dependents</li>
                            <li>Photocopy ng Valid ID ng iyong Magulang <i>(bilang proof of relationship)</i></li>
                            <li>Photocopy ng iyong Birth Certificate o Baptismal Certificate</li>
                        </ul>
                    </div>
                    <div id="dc4" class="d-none">
                        <p><b>Kung ikaw ay hindi pa talaga miyembro ng Philhealth, paki-handa ang mga sumusunod:</b></p>
                        <ul>
                            <li>Photocopy ng iyong Birth/Baptismal Certificate o Dalawang (2) Valid IDs</li>
                            <li>Filled up Philhealth Member Registration Form (PMRF), na pwedeng <a href="https://bit.ly/3RiBsRt">MA-DOWNLOAD DITO</a> o sa mismong swabbing area.</li>
                            <ul>
                                <li>Note: Sagutan lamang ang mga may-check (✓) sa form.</li>
                            </ul>
                        </ul>
                    </div>
                    <div id="dc5" class="d-none">
                        <p><b>Kung ikaw ay wala talagang maipapakitang kahit na anong patunay</b></p>
                        <ul>
                            <li>Hindi ka makakapag RT-PCR Test at ikaw na lang ay inaanyayahan namin na magpa-Antigen Test <i>(Kung gagamitin sa hospital, tanungin kung natanggap sila ng Antigen)</i></li>
                            <ul>
                                <li>Accredited ng Department of Health (DOH) ang Antigen Kit na ginagamit - Wondfo/Abbott/SD Biosensor</li>
                            </ul>
                        </ul>
                    </div>
                    <div id="cmode" class="d-none">
                        <div class="d-none" id="cmr1"><p>Ito ay pag-sunod lamang sa requirements na kailangangan ng mga Molecular Laboratory <i>(LaSalle/Imus Molecular Laboratory)</i> upang tanggapin ang inyong request at upang ilabas nila ang iyong resulta.</p></div>
                        <p>Paalala:</p>
                        <ul>
                            <div id="cmr2" class="d-none">
                                <li>Sa mga Philhealth Member/Dependent, Maaaring kumuha ng Philhealth MDR sa <a href="https://memberinquiry.philhealth.gov.ph/member/">PHILHEALTH MEMBER PORTAL</a></li>
                                <li>Ang resulta ng RT-PCR ay lumalabas makalipas ng 2-3 Araw. Tatawag ang iyong Barangay Health Center upang makuha sa kanila ang iyong resulta kapag ito ay nailabas na ng Molecular Laboratory.</li>
                                <li>Ang mga ID katulad ng School ID, Company ID, Barangay ID, TIN ID ay hindi po tinatanggap.</li>
                            </div>
                            <li>Magdala ng Sariling Black Ballpen.</li>
                            <li>Kung ikaw ay menor de edad, hanggat maaari ay kasama mo dapat ang iyong Magulang/Guardian sa pagpunta sa swabbing area.</li>
                        </ul>
                        <hr>
                        <p>Kung may mga katanungan o ibang concern, makipag-usap sa inyong Barangay Health Center o maaari kaming ma-kontak sa:</p>
                        <ul>
                            <li>Mobile Number (Call/Text)</li>
                            <ul>
                                <li>0919 066 4324</li>
                                <li>0919 066 4325</li>
                                <li>0919 066 4327</li>
                            </ul>
                            <li>Telephone Number: (046) 509 - 5289</li>
                            <li>Email: <a href = "mailto: cesu.gentrias@gmail.com">cesu.gentrias@gmail.com</a></li>
                            <li><a href="https://www.facebook.com/cesugentrias">Facebook Page</a></li>
                            <li>Address: City Health Office (3rd Floor CESU Office), Pria Rd., Hospital Area - Main, Brgy. Pinagtipunan, General Trias, Cavite, 4107</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer d-none" id="dfooter">
                    <button type="button" class="btn btn-success btn-block d-none" data-dismiss="modal" id="forrtpcr"><b>Naiintindihan ko, magpatuloy</b></button>
                    <button type="button" class="btn btn-success btn-block d-none" data-dismiss="modal" id="walarequirements"><b>Naiintindihan ko, magpapa-Antigen Test na lang ako, magpatuloy</b></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var getCurrentPtype = $('#pType').val();
        var getCurrentExpo1 = $('#expoitem1').val();

        $('#dd1').click(function (e) { 
            e.preventDefault();
            $('#btlist').addClass('d-none');
            $('#dc1').removeClass('d-none');
            $('#cmode').removeClass('d-none');
            $('#dfooter').removeClass('d-none');
            $('#cmr1').removeClass('d-none');
            $('#cmr2').removeClass('d-none');
            
            $('#forrtpcr').removeClass('d-none');
        });

        $('#dd2').click(function (e) { 
            e.preventDefault();
            $('#btlist').addClass('d-none');
            $('#dc2').removeClass('d-none');
            $('#cmode').removeClass('d-none');
            $('#dfooter').removeClass('d-none');
            $('#cmr1').removeClass('d-none');
            $('#cmr2').removeClass('d-none');

            $('#forrtpcr').removeClass('d-none');
        });

        $('#dd3').click(function (e) { 
            e.preventDefault();
            $('#btlist').addClass('d-none');
            $('#dc3').removeClass('d-none');
            $('#cmode').removeClass('d-none');
            $('#dfooter').removeClass('d-none');
            $('#cmr1').removeClass('d-none');
            $('#cmr2').removeClass('d-none');

            $('#forrtpcr').removeClass('d-none');
        });

        $('#dd4').click(function (e) { 
            e.preventDefault();
            $('#btlist').addClass('d-none');
            $('#dc4').removeClass('d-none');
            $('#cmode').removeClass('d-none');
            $('#dfooter').removeClass('d-none');
            $('#cmr1').removeClass('d-none');
            $('#cmr2').removeClass('d-none');

            $('#forrtpcr').removeClass('d-none');
        });

        $('#dd5').click(function (e) { 
            e.preventDefault();
            $('#btlist').addClass('d-none');
            $('#dc5').removeClass('d-none');
            $('#cmode').removeClass('d-none');
            $('#dfooter').removeClass('d-none');

            $('#walarequirements').removeClass('d-none');
        });

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

        /*
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
        */

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
                $('#occupationRow').removeClass('d-none');
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
                $('#occupationRow').addClass('d-none');
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
                $('#ifGenderFemale').addClass('d-none');
                $('#isPregnant').prop('required', false);
            }
            else {
                $('#ifGenderFemale').removeClass('d-none');
                $('#isPregnant').prop('required', true);
            }
        }).trigger('change');

        $('#isPregnant').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#ifPregnant').removeClass('d-none');
                $('#lmp').prop('required', true);

                $('#pType').val('TESTING');
                $('#pType').trigger('change');
            }
            else {
                $('#ifPregnant').addClass('d-none');
                $('#lmp').prop('required', false);
                
                $('#pType').val(getCurrentPtype);
                $('#pType').trigger('change');
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

        $('#expoitem1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 1) {
                $('#divExpoitem1').removeClass('d-none');
                $('#expoDateLastCont').prop('required', true);
            }
            else {
                $('#divExpoitem1').addClass('d-none');
                $('#expoDateLastCont').prop('required', false);
            }
        }).trigger('change');

        $('#signsCheck2').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divFeverChecked').removeClass('d-none');
                $('#SASFeverDeg').prop('required', true);
            }
            else {
                $('#divFeverChecked').addClass('d-none');
                $('#SASFeverDeg').prop('required', false);
            }
        }).trigger('change');

        $('#signsCheck18').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divSASOtherChecked').removeClass('d-none');
                $('#SASOtherRemarks').prop('required', true);
            }
            else {
                $('#divSASOtherChecked').addClass('d-none');
                $('#SASOtherRemarks').prop('required', false);
            }
        }).trigger('change');

        $('#haveSymptoms').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '0' || $(this).val() == null) {
                $('#ifHaveSymptoms').addClass('d-none');
                $('#dateOnsetOfIllness').prop('required', false);
                $('#expoitem1').val(getCurrentExpo1).change();
                $('#sexpoitem1_no').removeClass('d-none');
                $('#sexpoitem1_unknown').removeClass('d-none');
                $('#sexpoitem1_choose').removeClass('d-none');
                $('#pType').val(getCurrentPtype);
                $('#pType').trigger('change');
            }
            else {
                $('#ifHaveSymptoms').removeClass('d-none');
                $('#dateOnsetOfIllness').prop('required', true);
                $('#expoitem1').val('1').change();
                $('#sexpoitem1_no').addClass('d-none');
                $('#sexpoitem1_unknown').addClass('d-none');
                $('#sexpoitem1_choose').addClass('d-none');
                $('#pType').val('PROBABLE');
                $('#pType').trigger('change');
            }
        }).trigger('change');

        $('#comCheck10').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divComOthersChecked').removeClass('d-none');
                $('#COMOOtherRemarks').prop('required', true);
            }
            else {
                $('#divComOthersChecked').addClass('d-none');
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
                $('#comCheck11').prop({'disabled': true, 'checked': false});
                $('#comCheck12').prop({'disabled': true, 'checked': false});
                $('#comCheck13').prop({'disabled': true, 'checked': false});
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
                $('#comCheck11').prop({'disabled': false, 'checked': false});
                $('#comCheck12').prop({'disabled': false, 'checked': false});
                $('#comCheck13').prop({'disabled': false, 'checked': false});
            }
        });

        @if(is_null(old('comCheck')))
            $('#comCheck1').prop('checked', true);
        @endif

        $('#imagingDone').change(function (e) { 
            e.preventDefault();
            $('#divImagingOthers').addClass('d-none');
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

                $('#divImagingOthers').addClass('d-none');

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
                $('#divImagingOthers').removeClass('d-none');
                $('imagingOtherFindings').prop({disabled: false, required: true});
            }
            else {
                $('#divImagingOthers').addClass('d-none');
                $('imagingOtherFindings').prop({disabled: true, required: false});
            }
        }).trigger('change');

        $('#pType').change(function (e) { 
            e.preventDefault();
            getCurrentPtype = $(this).val();

            if($(this).val() == 'TESTING') {
                $('#isForHospitalization').val('1');
                $('#isForHospitalization').trigger('change');
                $('#isForHospitalization_sno').addClass('d-none');
            }
            else {
                $('#isForHospitalization').val('0');
                $('#isForHospitalization').trigger('change');
                $('#isForHospitalization_sno').removeClass('d-none');
            }

            /*
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
            */
        });
        
        $('#vaccineq1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#ifVaccinated').removeClass('d-none');
                $('#howManyDose').prop('required', true);
                $('#nameOfVaccine').prop('required', true);
            }
            else {
                $('#ifVaccinated').addClass('d-none');
                $('#howManyDose').prop('required', false);
                $('#nameOfVaccine').prop('required', false);
                $('#howManyDose').val('');
                $('#howManyDose').trigger('change');
            }
        }).trigger('change');

        $('#howManyDose').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#VaccineDose1').removeClass('d-none');
                $('#VaccineDose2').addClass('d-none');
                $('#vaccinationDate1').prop('required', true);
                $('#haveAdverseEvents1').prop('required', true);
                $('#vaccinationDate2').prop('required', false);
                $('#haveAdverseEvents2').prop('required', false);

                $('#booster_question').addClass('d-none');
                $('#haveBooster').val('0');
                $('#haveBooster').trigger('change');

                $('#haveBooster2').val('0');
                $('#haveBooster2').trigger('change');
            }
            else if($(this).val() == '2') {
                $('#VaccineDose1').removeClass('d-none');
                $('#VaccineDose2').removeClass('d-none');
                $('#vaccinationDate1').prop('required', true);
                $('#haveAdverseEvents1').prop('required', true);
                $('#vaccinationDate2').prop('required', true);
                $('#haveAdverseEvents2').prop('required', true);

                $('#booster_question').removeClass('d-none');
                $('#haveBooster').val('0');
                $('#haveBooster').trigger('change');

                $('#haveBooster2').val('0');
                $('#haveBooster2').trigger('change');
            }
            else {
                $('#VaccineDose1').addClass('d-none');
                $('#VaccineDose2').addClass('d-none');
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

        $('#nameOfVaccine').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'JANSSEN') {
                $('#howManyDose').val(1).trigger('change');
                $('#2ndDoseOption').hide();
                $('#booster_question').removeClass('d-none');
            }
            else {
                $('#2ndDoseOption').show();
                if($('#howManyDose').val() == 2) {
                    $('#booster_question').removeClass('d-none');
                }
                else {
                    $('#booster_question').addClass('d-none');
                }
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

        $('#isForHospitalization').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                if($('#isPregnant').val() == '1') {
                    $('#como_none').removeClass('d-none');
                    $('#comCheck1').prop('checked', true);
                    $('#comCheck1').trigger('change');

                    $('#useHospAlert').addClass('d-none');
                }
                else {
                    $('#como_none').addClass('d-none');
                    $('#comCheck1').prop('checked', false);
                    $('#comCheck1').trigger('change');

                    $('#useHospAlert').removeClass('d-none');
                }
            }
            else {
                $('#como_none').removeClass('d-none');
                $('#comCheck1').prop('checked', true);
                $('#comCheck1').trigger('change');

                $('#useHospAlert').addClass('d-none');
            }
        }).trigger('change');

        $('#contact1Name').keyup(function (e) { 
            if($(this).val().length >= 5) {
                $('#namelist2').removeClass('d-none');
            }
            else {
                $('#namelist2').addClass('d-none');
            }
        });

        $('#contact2Name').keyup(function (e) { 
            if($(this).val().length >= 5) {
                $('#namelist3').removeClass('d-none');
            }
            else {
                $('#namelist3').addClass('d-none');
            }
        });

        $('#contact3Name').keyup(function (e) { 
            if($(this).val().length >= 5) {
                $('#namelist4').removeClass('d-none');
            }
            else {
                $('#namelist4').addClass('d-none');
            }
        });

        $('#walarequirements').click(function (e) { 
            e.preventDefault();
            $('#forAntigen_no').addClass('d-none');
            $('#forAntigen').val('1').change();
        });
    </script>
    @else
    <div class="container">
        <div class="card">
            <div class="card-header">Notice</div>
            <div class="card-body text-center">
                @if(isset($msg))
                <div class="alert alert-{{$msgtype}}" role="alert">
                    {{$msg}}
                </div>
                @endif
                <p>As of July 10, 2021, <span class="text-primary">paswab.cesugentri.com</span> will require a valid Referral Code before proceeding into registration.</p>
                <p>
                    This is to prevent unauthorized and unmonitored patients from barangay to register. This will also provide information on where the patients information is coming from.
                </p>
            </div>
        </div>
    </div>
    @endif
@endsection