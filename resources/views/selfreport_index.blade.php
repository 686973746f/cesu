@extends('layouts.app')

@section('content')
    <form action="" method="POST">
        <div class="container">
            <div class="card">
                <div class="card-header">Self-Report</div>
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

                    <div class="card mb-3">
                        <div class="card-header">Consultation Details</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="pType">Type of Client</label>
                                      <select class="form-control" name="pType" id="pType">
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="PROBABLE" @if(old('pType') == "PROBABLE"){{'selected'}}@endif>Suspected</option>
                                            <option value="CLOSE CONTACT" @if(old('pType') == "CLOSE CONTACT"){{'selected'}}@endif>Close Contact</option>
                                            <option value="TESTING" @if(old('pType') == "TESTING"){{'selected'}}@endif>Not A Case of COVID</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="interviewDate"><span class="text-danger font-weight-bold">*</span>Date Interviewed in Barangay</label>
                                        <input type="date" name="interviewDate" id="interviewDate" class="form-control" min="{{date('Y-m-d', strtotime("-14 Days"))}}" max="{{date('Y-m-d')}}" value="{{old('interviewDate')}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for=""><span class="text-danger font-weight-bold">*</span>Personal Message to CESU Staffs/Encoders (Optional)</label>
                            <textarea class="form-control" name="" id="" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">2. Personal na Impormasyon / Personal Information</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lname"><span class="text-danger font-weight-bold">*</span>Apelyido / Last Name</label>
                                        <input type="text" class="form-control @error('lname') border-danger @enderror" id="lname" name="lname" value="{{old('lname')}}" max="50" style="text-transform: uppercase;" required>
                                        @error('lname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fname"><span class="text-danger font-weight-bold">*</span>Unang Pangalan / First Name (and Suffix)</label>
                                        <input type="text" class="form-control @error('fname') border-danger @enderror" id="fname" name="fname" value="{{old('fname')}}" max="50" style="text-transform: uppercase;" required>
                                        @error('fname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mname">Gitnang Pangalan / Middle Name <small><i>(Iwanang blangko kung N/A / Leave blank if N/A)</i></small></label>
                                        <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" style="text-transform: uppercase;" max="50">
                                        @error('mname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bdate"><span class="text-danger font-weight-bold">*</span>Araw ng kapanganakan / Birthdate</label>
                                        <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                        @error('bdate')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender"><span class="text-danger font-weight-bold">*</span>Kasarian / Gender</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Pumili / Choose</option>
                                            <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Lalaki / Male</option>
                                            <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Babae / Female</option>
                                        </select>
                                        @error('gender')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div id="ifGenderFemale">
                                        <div class="form-group">
                                            <label for="isPregnant"><span class="text-danger font-weight-bold">*</span>Ikaw ba ay Buntis? / Are you Pregnant?</label>
                                            <select class="form-control" name="isPregnant" id="isPregnant">
                                                <option value="" disabled {{(is_null(old('isPregnant'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="0" {{(old('isPregnant') == '0') ? 'selected' : ''}}>Hindi / No</option>
                                                <option value="1" {{(old('isPregnant') == '1') ? 'selected' : ''}}>Oo / Yes</option>
                                            </select>
                                        </div>
                                        <div id="ifPregnant">
                                            <div class="form-group">
                                              <label for="lmp"><span class="text-danger font-weight-bold">*</span>Kailan ang huling araw ng regla / Last Menstrual Period (LMP)</label>
                                              <input type="date" class="form-control" name="lmp" id="lmp" value="{{old('lmp')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cs"><span class="text-danger font-weight-bold">*</span>Katayuan ng Sibil / Civil Status</label>
                                        <select class="form-control" id="cs" name="cs" required>
                                            <option value="" disabled {{(is_null(old('cs'))) ? 'selected' : ''}}>Pumili / Choose</option>
                                            <option value="SINGLE" @if(old('cs') == 'SINGLE') {{'selected'}} @endif>Walang Asawa / Single</option>
                                            <option value="MARRIED" @if(old('cs') == 'MARRIED') {{'selected'}} @endif>Kasal / Married</option>
                                            <option value="WIDOWED" @if(old('cs') == 'WIDOWED') {{'selected'}} @endif>Balo / Widowed</option>
                                            <option value="N/A" @if(old('cs') == 'N/A') {{'selected'}} @endif>N/A</option>
                                        </select>
                                        @error('cs')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nationality"><span class="text-danger font-weight-bold">*</span>Nasyonalidad / Nationality</label>
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
                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile')}}" pattern="[0-9]{11}" placeholder="0917xxxxxxx" required>
                                        @error('mobile')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="philhealth">Philhealth Number <small><i>(Iwanang blangko kung N/A / Leave blank if N/A)</i></small></label>
                                        <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" minlength="12" maxlength="14">
                                        <small class="form-text text-muted">Tandaan: Iwasan ang maraming abala sa iyong schedule ng swab sa pagbigay ng iyong Philhealth Number <i>(WALANG NAKATAGONG BAYAD ang sisingilin sa iyo gamit ng iyong Philhealth Account)</i></small>
                                        <hr>
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
                                        <label for="phoneno">Telephone Number (& Area Code) <small><i>(Iwanang blangko kung N/A / Leave blank if N/A)</i></small></label>
                                        <input type="text" class="form-control" id="phoneno" name="phoneno" value="{{old('phoneno')}}">
                                        @error('phoneno')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <small><i>(Iwanang blangko kung N/A / Leave blank if N/A)</i></small></label>
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
                                        <label for="saddress_province"><span class="text-danger font-weight-bold">*</span>Probinsya / Province</label>
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
                                      <label for="saddress_city"><span class="text-danger font-weight-bold">*</span>Siyudad o Munisipalidad / City or Municipality</label>
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
                                        <small class="text-muted">Kung N/A, lagyan ng pinakamalapit na establisyemento kung saan ka nakatira (e.g Near Brgy. Hall, Near Alfamart, Near Tulay, Near Ilog, etc.)</small>
                                        @error('address_street')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="req_file">Upload Positive Test Result Document</label>
                      <input type="file" class="form-control-file" name="req_file" id="req_file">
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection