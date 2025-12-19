@extends('layouts.app')

@section('content')
    <form action="">
        <div class="container">
            <div class="card">
                <div class="card-header"><b>Edit BLS Participant</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><b class="text-danger">*</b>Surname</label>
                                <input type="text" class="form-control" name="lname" id="lname" style="text-transform: uppercase" value="{{old('lname', $d->member->lname)}}" minlength="2" max="50" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><b class="text-danger">*</b>First Name</label>
                                <input type="text" class="form-control" name="fname" id="fname" style="text-transform: uppercase" value="{{old('fname', $d->member->fname)}}" minlength="2" max="50" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" name="mname" id="mname" style="text-transform: uppercase" value="{{old('mname' , $d->member->mname)}}" minlength="2" max="50">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Name Extension (Jr./Sr./III/IV, etc.)</label>
                                <input type="text" class="form-control" name="suffix" id="suffix" style="text-transform: uppercase" value="{{old('suffix', $d->member->suffix)}}" minlength="2" max="5">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bdate"><b class="text-danger">*</b>Date of Birth</label>
                                <input type="date" class="form-control" name="bdate" id="bdate" max="{{date('Y-m-d')}}" value="{{old('bdate', $d->member->bdate)}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender"><b class="text-danger">*</b>Gender</label>
                                <select class="form-control" name="gender" id="gender" required>
                                  <option value="M" {{(old('gender', $d->member->gender) == 'M') ? 'selected' : ''}}>Male</option>
                                  <option value="F" {{(old('gender', $d->member->gender) == 'F') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="provider_type"><b class="text-danger">*</b>Provider Type</label>
                                <select class="form-control" name="provider_type" id="provider_type" required>
                                <option value="HCP" {{(old('provider_type', $d->member->provider_type) == 'HCP') ? 'selected' : ''}}>Health Care Provider (HCP)</option>
                                <option value="LR" {{(old('provider_type', $d->member->provider_type) == 'LR') ? 'selected' : ''}}>Lay Rescuer (LR)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="position"><b class="text-danger">*</b>Position</label>
                                <input type="text" class="form-control" name="position" id="position" style="text-transform: uppercase" value="{{old('position', $d->member->position)}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div id="institution_fields">
                                <div class="form-group mt-2">
                                    <label for="institution"><b class="text-danger">*</b>Institution/Agency</label>
                                    <select class="form-control" name="institution" id="institution" required>
                                        <option value="" disabled {{(is_null(old('provider_type'))) ? 'selected' : ''}}>Choose...</option>
                                        @foreach($list_institutions as $a)
                                        <option value="{{$a}}" {{(old('institution', $d->member->institution) == $a) ? 'selected' : ''}}>{{$a}}</option>
                                        @endforeach
                                        <option value="UNLISTED" {{(old('institution', $d->member->institution) == 'UNLISTED') ? 'selected' : ''}}>UNLISTED</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="institution_other_fields">
                                    <label for="institution_other"><b class="text-danger">*</b>Please Specify</label>
                                    <input type="text" class="form-control" name="institution_other" id="institution_other" style="text-transform: uppercase" value="{{old('institution_other')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employee_type"><b class="text-danger">*</b>Status of Employment</label>
                                <select class="form-control" name="employee_type" id="employee_type" required>
                                <option value="JO" {{(old('employee_type', $d->member->employee_type) == 'JO') ? 'selected' : ''}}>Job Order (JO)</option>
                                <option value="CASUAL" {{(old('employee_type', $d->member->employee_type) == 'CASUAL') ? 'selected' : ''}}>Casual</option>
                                <option value="CWA" {{(old('employee_type', $d->member->employee_type) == 'CWA') ? 'selected' : ''}}>Contract of Service (CWA)</option>
                                <option value="PERMANENT" {{(old('employee_type', $d->member->employee_type) == 'PERMANENT') ? 'selected' : ''}}>Permanent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row" id="address_fields">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_region_code"><b class="text-danger">*</b>Region</label>
                                <select class="form-control" name="address_region_code" id="address_region_code" tabindex="-1" required>
                                @foreach(App\Models\Regions::orderBy('regionName', 'ASC')->get() as $a)
                                <option value="{{$a->id}}" {{($a->id == 1) ? 'selected' : ''}}>{{$a->regionName}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_province_code"><b class="text-danger">*</b>Province</label>
                                <select class="form-control" name="address_province_code" id="address_province_code" tabindex="-1" required disabled>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_muncity_code"><b class="text-danger">*</b>City/Municipality</label>
                                <select class="form-control" name="address_muncity_code" id="address_muncity_code" tabindex="-1" required disabled>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_brgy_code"><b class="text-danger">*</b>Barangay</label>
                                <select class="form-control" name="address_brgy_code" id="address_brgy_code" required disabled>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="street_purok"><b class="text-danger">*</b>Street/Purok/Sitio/Subdivision</label>
                        <input type="text" class="form-control" name="street_purok" id="street_purok" style="text-transform: uppercase" value="{{old('street_purok', $d->member->street_purok)}}" required>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email"><b class="text-danger">*</b>Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" value="{{old('email', $d->member->email)}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->member->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="codename"><b class="text-danger">*</b>Code Name</label>
                                <input type="text" class="form-control" name="codename" id="codename" style="text-transform: uppercase" value="{{old('codename', $d->member->codename)}}" required>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </form>
@endsection