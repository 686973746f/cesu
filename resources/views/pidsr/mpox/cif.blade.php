@extends('layouts.app')

@section('content')
@if($mode == 'EDIT')
<!--Edit Page-->
<form action="#" method="POST">
    @php
    $morbidity_month = $d->morbidity_month;
    $date_reported = $d->date_reported;
    $epid_number = $d->epid_number;

    $dru_name = $d->dru_name;
    $dru_region = $d->dru_region;
    $dru_province = $d->dru_province;
    $dru_muncity = $d->dru_muncity;
    $dru_street = $d->dru_street;
    @endphp
@else
<!--Create Page-->
<form action="#" method="POST">
    @php
    $morbidity_month = date('Y-m-d');
    $date_reported = date('Y-m-d');
    $epid_number = NULL;

    $dru_name = 'CHO GENERAL TRIAS';
    $dru_region = 'IV-A';
    $dru_province = 'CAVITE';
    $dru_muncity = 'GENERAL TRIAS';
    $dru_street = 'PRIA RD';
    @endphp
@endif

    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @if($mode == 'EDIT')
                    <div><b>Edit Monkeypox CIF of <a href="{{route('records.edit', $d->records->id)}}">{{$d->records->getName()}} | {{$d->records->getAge()}}/{{substr($d->records->gender,0,1)}} | {{date('m/d/Y', strtotime($d->records->bdate))}}</a> [ICD 10 - CM Code: B04]</b></div>
                    @else
                    <div><b>New Monkeypox Case Investigation Form (CIF) [ICD 10 - CM Code: B04]</b></div>
                    @endif
                    <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appendix">Appendix</div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="text-center alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dru_name"><span class="text-danger font-weight-bold">*</span>Name of DRU</label>
                            <input type="text"class="form-control" name="dru_name" id="dru_name" value="{{old('dru_name', $dru_name)}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_name"><span class="text-danger font-weight-bold">*</span>Address of DRU</label>
                            <input type="text"class="form-control" name="dru_name" id="dru_name" value="{{old('dru_name', $dru_name)}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_investigation"><span class="text-danger font-weight-bold">*</span>Date of Investigation</label>
                            <input type="date"class="form-control" name="date_investigation" id="date_investigation" value="{{old('date_investigation', $d->date_investigation)}}" max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="type"><span class="text-danger font-weight-bold">*</span>Type of DRU</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value="C/MHO" {{(old('type') == 'C/MHO') ? 'selected' : ''}}>C/MHO</option>
                                <option value="GOVT HOSPITAL" {{(old('type') == 'GOVT HOSPITAL') ? 'selected' : ''}}>GOV'T HOSPITAL</option>
                                <option value="PRIVATE HOSPITAL" {{(old('type') == 'PRIVATE HOSPITAL') ? 'selected' : ''}}>PRIVATE HOSPITAL</option>
                                <option value="AIRPORT" {{(old('type') == 'AIRPORT') ? 'selected' : ''}}>AIRPORT</option>
                                <option value="SEAPORT" {{(old('type') == 'SEAPORT') ? 'selected' : ''}}>SEAPORT</option>
                                <option value="GOVT LABORATORY" {{(old('type') == 'GOVT LABORATORY') ? 'selected' : ''}}>GOV'T LABORATORY</option>
                                <option value="PRIVATE LABORATORY" {{(old('type') == 'PRIVATE LABORATORY') ? 'selected' : ''}}>PRIVATE LABORATORY</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>I. PATIENT INFORMATION</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="lname"><b class="text-danger">*</b>Last Name</label>
                                    <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', request()->input('lname'))}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="fname"><b class="text-danger">*</b>First Name</label>
                                    <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', request()->input('fname'))}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="mname">Middle Name</label>
                                    <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', request()->input('mname'))}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="suffix">Suffix</label>
                                    <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', request()->input('suffix'))}}" minlength="2" maxlength="3" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                                    <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate', request()->input('bdate'))}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" tabindex="-1" readonly required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                      <select class="form-control" name="gender" id="gender" required>
                                          <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                          <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                          <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                      </select>
                                </div>
                                <div class="d-none" id="ifFemaleDiv">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Pregnant?</label>
                                        <select class="form-control" name="is_pregnant" id="is_pregnant">
                                            <option value="" disabled {{(is_null(old('is_pregnant'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="Y" {{(old('is_pregnant') == 'Y') ? 'selected' : ''}}>Yes</option>
                                            <option value="N" {{(old('is_pregnant') == 'N') ? 'selected' : ''}}>No</option>
                                      </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_number"><b class="text-danger">*</b>Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b class="text-danger">*</b>Nationality</label>
                                    <select class="form-control" name="nationality" id="nationality" required>
                                        <option value="FILIPINO" {{(old('nationality') == 'FILIPINO') ? 'selected' : ''}}>Filipino</option>
                                        <option value="FOREIGN" {{(old('nationality') == 'FOREIGN') ? 'selected' : ''}}>Foreign</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b class="text-danger">*</b>Member of Indigenous People?</label>
                                    <select class="form-control" name="is_ip" id="is_ip" required>
                                        <option value="N" {{(old('is_ip') == 'N') ? 'selected' : ''}}>No</option>
                                        <option value="Y" {{(old('is_ip') == 'Y') ? 'selected' : ''}}>Yes</option>
                                  </select>
                                </div>
                                <div id="ifIpDiv" class="d-none">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Specify IP Group</label>
                                        <input type="text"class="form-control" name="is_ip_specify" id="is_ip_specify" value="{{old('is_ip_specify')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="address_text" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text')}}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_province_text')}}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_muncity_text')}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                  <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                                  <select class="form-control" name="address_region_code" id="address_region_code" required>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                                    <select class="form-control" name="address_province_code" id="address_province_code" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                                    <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                    <select class="form-control" name="address_brgy_text" id="address_brgy_text" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_houseno" class="form-label"><b class="text-danger">*</b>House No./Lot/Building</label>
                                    <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" placeholder="ex. S1 B2 L3 PHASE 4 MIRAGE ST." required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address_street" class="form-label"><b class="text-danger">*</b>Street/Subdivision/Purok/Sitio</label>
                                    <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" placeholder="ex. SUBDIVISION HOMES" required>
                                </div>
                            </div>
                        </div>
        
                        <div id="perm_address_text" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="perm_address_region_text" name="perm_address_region_text" value="{{old('perm_address_region_text')}}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="perm_address_province_text" name="perm_address_province_text" value="{{old('perm_address_province_text')}}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="perm_address_muncity_text" name="perm_address_muncity_text" value="{{old('perm_address_muncity_text')}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Permanent Address is Different from Current Address?</label>
                            <select class="form-control" name="permaddress_isdifferent" id="permaddress_isdifferent" required>
                                <option value="" disabled {{(is_null(old('permaddress_isdifferent'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="N" {{(old('permaddress_isdifferent') == 'No') ? 'selected' : ''}}>No (Same as above)</option>
                                <option value="Y" {{(old('permaddress_isdifferent') == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                        <div id="permAddressDiv" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                      <label for="perm_address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Permanent Address Region</label>
                                      <select class="form-control" name="perm_address_region_code" id="perm_address_region_code" required>
                                      </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="perm_address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Permanent Address Province</label>
                                        <select class="form-control" name="perm_address_province_code" id="perm_address_province_code" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="perm_address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Permanent Address City/Municipality</label>
                                        <select class="form-control" name="perm_address_muncity_code" id="perm_address_muncity_code" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="perm_address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Permanent Address Barangay</label>
                                        <select class="form-control" name="perm_address_brgy_text" id="perm_address_brgy_text" required>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="perm_address_houseno" class="form-label"><b class="text-danger">*</b>Permanent Address House No./Lot/Building</label>
                                        <input type="text" class="form-control" id="perm_address_houseno" name="perm_address_houseno" style="text-transform: uppercase;" value="{{old('perm_address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" placeholder="ex. S1 B2 L3 PHASE 4 MIRAGE ST." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="perm_address_street" class="form-label"><b class="text-danger">*</b>Permanent Address Street/Subdivision/Purok/Sitio</label>
                                        <input type="text" class="form-control" id="perm_address_street" name="perm_address_street" style="text-transform: uppercase;" value="{{old('perm_address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" placeholder="ex. SUBDIVISION HOMES" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="occupation">Occupation</label>
                                    <input type="text"class="form-control" name="occupation" id="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
                                </div>
                                <div class="form-group">
                                    <label for="workplace_name">Name of Workplace</label>
                                    <input type="text"class="form-control" name="workplace_name" id="workplace_name" value="{{old('workplace_name')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="workplace_address">Address of Workplace</label>
                                    <input type="text"class="form-control" name="workplace_address" id="workplace_address" value="{{old('workplace_address')}}" style="text-transform: uppercase;">
                                </div>
                                <div class="form-group">
                                    <label for="workplace_contactnumber">Workplace Contact No.</label>
                                    <input type="text"class="form-control" name="workplace_contactnumber" id="workplace_contactnumber" value="{{old('workplace_contactnumber')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="informant_name">Name of Informant</label>
                                    <input type="text"class="form-control" name="informant_name" id="informant_name" value="{{old('informant_name')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="informant_relationship">Relationship with Patient</label>
                                    <input type="text"class="form-control" name="informant_relationship" id="informant_relationship" value="{{old('informant_relationship')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="informant_contactnumber">Informant Contact No.</label>
                                    <input type="text"class="form-control" name="informant_contactnumber" id="informant_contactnumber" value="{{old('informant_contactnumber')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="other_medical_information">Any other known medical information</label>
                            <input type="text"class="form-control" name="other_medical_information" id="other_medical_information" value="{{old('other_medical_information')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-header"><b>II. PATIENT STATUS</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_admitted"><span class="text-danger font-weight-bold">*</span>Date Admitted/Seen/Consult</label>
                                    <input type="date"class="form-control" name="date_admitted" id="date_admitted" value="{{old('date_admitted', $d->date_admitted)}}" max="{{date('Y-m-d')}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="admission_er"><span class="text-danger font-weight-bold">*</span>Admitted ER</label>
                                    <select class="form-control" name="admission_er" id="admission_er" required>
                                        <option value="N" {{(old('admission_er', $d->admission_er) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_er', $d->admission_er) == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_er', $d->admission_er) == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admission_ward"><span class="text-danger font-weight-bold">*</span>Admitted Ward</label>
                                    <select class="form-control" name="admission_ward" id="admission_ward" required>
                                        <option value="N" {{(old('admission_ward', $d->admission_ward) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_ward', $d->admission_ward) == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_ward', $d->admission_ward) == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admission_icu"><span class="text-danger font-weight-bold">*</span>Admitted ICU</label>
                                    <select class="form-control" name="admission_icu" id="admission_icu" required>
                                        <option value="N" {{(old('admission_icu', $d->admission_icu) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_icu', $d->admission_icu) == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_icu', $d->admission_icu) == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ifhashistory_blooddonation_transfusion"><span class="text-danger font-weight-bold">*</span>Blood Donation/Transfusion History</label>
                                    <select class="form-control" name="ifhashistory_blooddonation_transfusion" id="ifhashistory_blooddonation_transfusion">
                                        <option value="" {{(old('ifhashistory_blooddonation_transfusion', $d->ifhashistory_blooddonation_transfusion) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="DONOR" {{(old('ifhashistory_blooddonation_transfusion', $d->ifhashistory_blooddonation_transfusion) == 'DONOR') ? 'selected' : ''}}>DONOR</option>
                                        <option value="RECIPIENT" {{(old('ifhashistory_blooddonation_transfusion', $d->ifhashistory_blooddonation_transfusion) == 'RECIPIENT') ? 'selected' : ''}}>RECIPIENT</option>
                                    </select>
                                </div>
                                <div id="blooddono_div" class="d-none">
                                    <div class="form-group">
                                        <label for="ifhashistory_blooddonation_transfusion_place"><span class="text-danger font-weight-bold">*</span>Place of Donation/Transfusion</label>
                                        <input type="text"class="form-control" name="ifhashistory_blooddonation_transfusion_place" id="ifhashistory_blooddonation_transfusion_place" value="{{old('ifhashistory_blooddonation_transfusion_place', $d->ifhashistory_blooddonation_transfusion_place)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="ifhashistory_blooddonation_transfusion_date"><span class="text-danger font-weight-bold">*</span>Date of Donation/Transfusion</label>
                                        <input type="text"class="form-control" name="ifhashistory_blooddonation_transfusion_date" id="ifhashistory_blooddonation_transfusion_date" value="{{old('ifhashistory_blooddonation_transfusion_date', $d->ifhashistory_blooddonation_transfusion_date)}}" max="{{date('Y-m-d')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header"><b>III. CLINICAL HISTORY/PRESENTATION</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_onsetofillness"><span class="text-danger font-weight-bold">*</span>Date onset of illness</label>
                                    <input type="date"class="form-control" name="date_onsetofillness" id="date_onsetofillness" value="{{old('date_onsetofillness', $d->date_onsetofillness)}}" max="{{date('Y-m-d')}}" required>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="have_cutaneous_rash"><span class="text-danger font-weight-bold">*</span>1. Does the patient have a cutaneous rash?</label>
                                    <select class="form-control" name="have_cutaneous_rash" id="have_cutaneous_rash" required>
                                        <option value="" disabled {{(old('have_cutaneous_rash', $d->have_cutaneous_rash) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="N" {{(old('have_cutaneous_rash', $d->have_cutaneous_rash) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('have_cutaneous_rash', $d->have_cutaneous_rash) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="div_have_cutaneous_rash">
                                    <label for="have_cutaneous_rash_date"><span class="text-danger font-weight-bold">*</span>If yes, date of onset for the rash</label>
                                    <input type="date"class="form-control" name="have_cutaneous_rash_date" id="have_cutaneous_rash_date" value="{{old('have_cutaneous_rash_date', $d->have_cutaneous_rash_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="have_fever"><span class="text-danger font-weight-bold">*</span>2. Did the patient have fever?</label>
                                    <select class="form-control" name="have_fever" id="have_fever" required>
                                        <option value="" disabled {{(old('have_fever', $d->have_fever) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="N" {{(old('have_fever', $d->have_fever) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('have_fever', $d->have_fever) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_have_fever" class="d-none">
                                    <div class="form-group">
                                        <label for="have_fever_date"><span class="text-danger font-weight-bold">*</span>If yes, date of onset for the fever</label>
                                        <input type="date" class="form-control" name="have_fever_date" id="have_fever_date" value="{{old('have_fever_date', $d->have_fever_date)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="have_fever_days_duration"><span class="text-danger font-weight-bold">*</span>Duration of fever (Days)</label>
                                        <input type="date" class="form-control" name="have_fever_days_duration" id="have_fever_days_duration" value="{{old('have_fever_days_duration', $d->have_fever_days_duration)}}" min="1" max="99">
                                    </div>
                                </div>
                                <label for="have_fever_date">3. If there is active disease,</label>
                                <ul>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_lesion_samestate"><span class="text-danger font-weight-bold">*</span>3.1 Lesions are in the same state of development on the body?</label>
                                            <select class="form-control" name="have_activedisease_lesion_samestate" id="have_activedisease_lesion_samestate" required>
                                                <option value="" disabled {{(old('have_activedisease_lesion_samestate', $d->have_activedisease_lesion_samestate) == '') ? 'selected' : ''}}>Choose...</option>
                                                <option value="N" {{(old('have_activedisease_lesion_samestate', $d->have_activedisease_lesion_samestate) == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_lesion_samestate', $d->have_activedisease_lesion_samestate) == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_lesion_samesize"><span class="text-danger font-weight-bold">*</span>3.2 Are all of the lesions the same size?</label>
                                            <select class="form-control" name="have_activedisease_lesion_samesize" id="have_activedisease_lesion_samesize" required>
                                                <option value="" disabled {{(old('have_activedisease_lesion_samesize', $d->have_activedisease_lesion_samesize) == '') ? 'selected' : ''}}>Choose...</option>
                                                <option value="N" {{(old('have_activedisease_lesion_samesize', $d->have_activedisease_lesion_samesize) == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_lesion_samesize', $d->have_activedisease_lesion_samesize) == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_lesion_deep"><span class="text-danger font-weight-bold">*</span>3.3 Are the lesions deep and profound?</label>
                                            <select class="form-control" name="have_activedisease_lesion_deep" id="have_activedisease_lesion_deep" required>
                                                <option value="" disabled {{(old('have_activedisease_lesion_deep', $d->have_activedisease_lesion_deep) == '') ? 'selected' : ''}}>Choose...</option>
                                                <option value="N" {{(old('have_activedisease_lesion_deep', $d->have_activedisease_lesion_samesize) == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_lesion_deep', $d->have_activedisease_lesion_samesize) == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_develop_ulcers"><span class="text-danger font-weight-bold">*</span>3.4. Did the patient develop ulcers?</label>
                                            <select class="form-control" name="have_activedisease_develop_ulcers" id="have_activedisease_develop_ulcers" required>
                                                <option value="" disabled {{(old('have_activedisease_develop_ulcers', $d->have_activedisease_develop_ulcers) == '') ? 'selected' : ''}}>Choose...</option>
                                                <option value="N" {{(old('have_activedisease_develop_ulcers', $d->have_activedisease_develop_ulcers) == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_develop_ulcers', $d->have_activedisease_develop_ulcers) == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                                <div class="form-group">
                                    <label for="have_activedisease_lesion_type"><span class="text-danger font-weight-bold">*</span>4. Type of lesions</label>
                                    <select class="form-control" name="have_activedisease_lesion_type[]" id="have_activedisease_lesion_type" multiple required>
                                        <option value="" disabled {{(old('have_activedisease_lesion_type', $d->have_activedisease_lesion_type) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="MACULE" {{(collect(old('have_activedisease_lesion_type', explode(',', $d->have_activedisease_lesion_type)))->contains('MACULE')) ? 'selected' : ''}}>MACULE</option>
                                        <option value="PAPULE" {{(collect(old('have_activedisease_lesion_type', explode(',', $d->have_activedisease_lesion_type)))->contains('PAPULE')) ? 'selected' : ''}}>PAPULE</option>
                                        <option value="VESICLE" {{(collect(old('have_activedisease_lesion_type', explode(',', $d->have_activedisease_lesion_type)))->contains('VESICLE')) ? 'selected' : ''}}>VESICLE</option>
                                        <option value="PUSTULE" {{(collect(old('have_activedisease_lesion_type', explode(',', $d->have_activedisease_lesion_type)))->contains('PUSTULE')) ? 'selected' : ''}}>PUSTULE</option>
                                        <option value="SCAB" {{(collect(old('have_activedisease_lesion_type', explode(',', $d->have_activedisease_lesion_type)))->contains('SCAB')) ? 'selected' : ''}}>SCAB</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="have_activedisease_lesion_localization"><span class="text-danger font-weight-bold">*</span>5. Localization of the lesions</label>
                                    <select class="form-control" name="have_activedisease_lesion_localization[]" id="have_activedisease_lesion_localization" multiple required>
                                        <option value="" disabled {{(old('have_activedisease_lesion_localization', $d->have_activedisease_lesion_localization) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="FACE" {{(collect(old('have_activedisease_lesion_localization', explode(',', $d->have_activedisease_lesion_localization)))->contains('FACE')) ? 'selected' : ''}}>FACE</option>
                                        <option value="PALMS OF THE HANDS" {{(collect(old('have_activedisease_lesion_localization', explode(',', $d->have_activedisease_lesion_localization)))->contains('PALMS OF THE HANDS')) ? 'selected' : ''}}>PALMS OF THE HANDS</option>
                                        <option value="THORAX" {{(collect(old('have_activedisease_lesion_localization', explode(',', $d->have_activedisease_lesion_localization)))->contains('THORAX')) ? 'selected' : ''}}>THORAX</option>
                                        <option value="ARMS" {{(collect(old('have_activedisease_lesion_localization', explode(',', $d->have_activedisease_lesion_localization)))->contains('ARMS')) ? 'selected' : ''}}>ARMS</option>
                                        <option value="LEGS" {{(collect(old('have_activedisease_lesion_localization', explode(',', $d->have_activedisease_lesion_localization)))->contains('LEGS')) ? 'selected' : ''}}>LEGS</option>
                                        <option value="SOLES OF THE FEET" {{(collect(old('have_activedisease_lesion_localization', explode(',', $d->have_activedisease_lesion_localization)))->contains('SOLES OF THE FEET')) ? 'selected' : ''}}>SOLES OF THE FEET</option>
                                        <option value="GENITALS" {{(collect(old('have_activedisease_lesion_localization', explode(',', $d->have_activedisease_lesion_localization)))->contains('GENITALS')) ? 'selected' : ''}}>GENITALS</option>
                                        <option value="ALL OVER THE BODY" {{(collect(old('have_activedisease_lesion_localization', explode(',', $d->have_activedisease_lesion_localization)))->contains('ALL OVER THE BODY')) ? 'selected' : ''}}>ALL OVER THE BODY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="have_activedisease_lesion_localization_otherareas">List other areas of the lesions</label>
                                    <input type="text"class="form-control" name="have_activedisease_lesion_localization_otherareas" id="have_activedisease_lesion_localization_otherareas" value="{{old('have_activedisease_lesion_localization_otherareas', $d->have_activedisease_lesion_localization_otherareas)}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="symptoms_list"><span class="text-danger font-weight-bold">*</span>Signs and Symptoms (Select all that apply)</label>
                                    <select class="form-control" name="symptoms_list[]" id="symptoms_list" multiple required>
                                        <option value="" disabled {{(old('symptoms_list', $d->symptoms_list) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="VOMITING/NAUSEA" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('VOMITING/NAUSEA')) ? 'selected' : ''}}>VOMITING/NAUSEA</option>
                                        <option value="HEADACHE" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('HEADACHE')) ? 'selected' : ''}}>HEADACHEA</option>
                                        <option value="COUGH" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('COUGH')) ? 'selected' : ''}}>COUGH</option>
                                        <option value="MUSCLE PAIN (MYALGIA)" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('MUSCLE PAIN (MYALGIA)')) ? 'selected' : ''}}>MUSCLE PAIN (MYALGIA)</option>
                                        <option value="ASTHENIA (WEAKNESS)" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('ASTHENIA (WEAKNESS)')) ? 'selected' : ''}}>ASTHENIA (WEAKNESS)</option>
                                        <option value="FATIGUE" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('FATIGUE')) ? 'selected' : ''}}>FATIGUE</option>
                                        <option value="CONJUNCTIVITIS" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('CONJUNCTIVITIS')) ? 'selected' : ''}}>CONJUNCTIVITIS</option>
                                        <option value="CHILLS OR SWEATS" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('CHILLS OR SWEATS')) ? 'selected' : ''}}>CHILLS OR SWEATS</option>
                                        <option value="SENSITIVITY TO LIGHT" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('SENSITIVITY TO LIGHT')) ? 'selected' : ''}}>SENSITIVITY TO LIGHT</option>
                                        <option value="SORE THROAT WHEN SWALLOWING" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('SORE THROAT WHEN SWALLOWING')) ? 'selected' : ''}}>SORE THROAT WHEN SWALLOWING</option>
                                        <option value="ORAL ULCERS" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('ORAL ULCERS')) ? 'selected' : ''}}>ORAL ULCERS</option>
                                        <option value="LYMPHADENOPATHY" {{(collect(old('symptoms_list', explode(',', $d->symptoms_list)))->contains('LYMPHADENOPATHY')) ? 'selected' : ''}}>LYMPHADENOPATHY (SPECIFY LOCALIZATION)</option>
                                    </select>
                                </div>
                                <div id="div_lymp" class="d-none">
                                    <div class="form-group">
                                        <label for="symptoms_lymphadenopathy_localization"><span class="text-danger font-weight-bold">*</span>Specify Localiztion of Lymphadenopathy</label>
                                        <select class="form-control" name="symptoms_lymphadenopathy_localization[]" id="symptoms_lymphadenopathy_localization" multiple>
                                            <option value="" disabled {{(old('symptoms_lymphadenopathy_localization', $d->symptoms_lymphadenopathy_localization) == '') ? 'selected' : ''}}>Choose...</option>
                                            <option value="CERVICAL" {{(collect(old('symptoms_lymphadenopathy_localization', explode(',', $d->symptoms_lymphadenopathy_localization)))->contains('CERVICAL')) ? 'selected' : ''}}>CERVICAL</option>
                                            <option value="AXILLARY" {{(collect(old('symptoms_lymphadenopathy_localization', explode(',', $d->symptoms_lymphadenopathy_localization)))->contains('AXILLARY')) ? 'selected' : ''}}>AXILLARY</option>
                                            <option value="INGUINAL" {{(collect(old('symptoms_lymphadenopathy_localization', explode(',', $d->symptoms_lymphadenopathy_localization)))->contains('INGUINAL')) ? 'selected' : ''}}>INGUINAL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header"><b>IV. HISTORY OF EXPOSURE</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="history1_yn"><span class="text-danger font-weight-bold">*</span>1. Did the patient travel anytime in the three weeks before becoming ill?</label>
                                    <select class="form-control" name="history1_yn" id="history1_yn" required>
                                        <option value="" disabled {{(old('history1_yn', $d->history1_yn) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="N" {{(old('history1_yn', $d->history1_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history1_yn', $d->history1_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_history1" class="d-none">
                                    <div class="form-group">
                                        <label for="history1_specify"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                        <input type="text"class="form-control" name="history1_specify" id="history1_specify" value="{{old('history1_specify', $d->history1_specify)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history1_date_travel"><span class="text-danger font-weight-bold">*</span>Date of Travel</label>
                                        <input type="date"class="form-control" name="history1_date_travel" id="history1_date_travel" value="{{old('history1_date_travel', $d->history1_date_travel)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history1_flightno"><span class="text-danger font-weight-bold">*</span>Flight/Vessel #</label>
                                        <input type="text"class="form-control" name="history1_flightno" id="history1_flightno" value="{{old('history1_flightno', $d->history1_flightno)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history1_date_arrival"><span class="text-danger font-weight-bold">*</span>Date of Arrival</label>
                                        <input type="date"class="form-control" name="history1_date_arrival" id="history1_date_arrival" value="{{old('history1_date_arrival', $d->history1_date_arrival)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history1_pointandexitentry"><span class="text-danger font-weight-bold">*</span>Point of entry and exit</label>
                                        <input type="text"class="form-control" name="history1_pointandexitentry" id="history1_pointandexitentry" value="{{old('history1_pointandexitentry', $d->history1_pointandexitentry)}}" style="text-transform: uppercase;">
                                    </div>
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <label for="history2_yn"><span class="text-danger font-weight-bold">*</span>2. Did the patient travel during illness?</label>
                                    <select class="form-control" name="history2_yn" id="history2_yn" required>
                                        <option value="" disabled {{(old('history2_yn', $d->history2_yn) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="N" {{(old('history2_yn', $d->history2_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history2_yn', $d->history2_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_history2" class="d-none">
                                    <div class="form-group">
                                        <label for="history2_specify"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                        <input type="text"class="form-control" name="history2_specify" id="history2_specify" value="{{old('history2_specify', $d->history2_specify)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history2_date_travel"><span class="text-danger font-weight-bold">*</span>Date of Travel</label>
                                        <input type="date"class="form-control" name="history2_date_travel" id="history2_date_travel" value="{{old('history2_date_travel', $d->history2_date_travel)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history2_flightno"><span class="text-danger font-weight-bold">*</span>Flight/Vessel #</label>
                                        <input type="text"class="form-control" name="history2_flightno" id="history2_flightno" value="{{old('history2_flightno', $d->history2_flightno)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history2_date_arrival"><span class="text-danger font-weight-bold">*</span>Date of Arrival</label>
                                        <input type="date"class="form-control" name="history2_date_arrival" id="history2_date_arrival" value="{{old('history2_date_arrival', $d->history2_date_arrival)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history2_pointandexitentry"><span class="text-danger font-weight-bold">*</span>Point of entry and exit</label>
                                        <input type="text"class="form-control" name="history2_pointandexitentry" id="history2_pointandexitentry" value="{{old('history2_pointandexitentry', $d->history2_pointandexitentry)}}" style="text-transform: uppercase;">
                                    </div>
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <label for="history3_yn"><span class="text-danger font-weight-bold">*</span>3. Within 21 days befores symptom onset, did the patient have contact with one or more persons who had similar symptoms?</label>
                                    <select class="form-control" name="history3_yn" id="history3_yn" required>
                                        <option value="" disabled {{(old('history3_yn', $d->history3_yn) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="N" {{(old('history3_yn', $d->history3_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history3_yn', $d->history3_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                    <small class="text-muted d-none" id="div_history3">If YES, accomplish  Appendix A "Monkepox Contact listing Form" than can be downloaded <a href="{{asset('MONKEYPOX_APPENDIXA.pdf')}}" target="_blank">HERE</a></small>
                                </div>
                                <div class="form-group">
                                    <label for="history4_yn"><span class="text-danger font-weight-bold">*</span>4. Did the patient touch a domestic or wild animal within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history4_yn" id="history4_yn" required>
                                        <option value="" disabled {{(old('history4_yn', $d->history4_yn) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="N" {{(old('history4_yn', $d->history4_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history4_yn', $d->history4_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_history4" class="d-none">
                                    <div class="form-group">
                                        <label for="history4_typeofanimal"><span class="text-danger font-weight-bold">*</span>What kind of animal</label>
                                        <input type="text" class="form-control" name="history4_typeofanimal" id="history4_typeofanimal" value="{{old('history4_typeofanimal', $d->history4_typeofanimal)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history4_firstexposure"><span class="text-danger font-weight-bold">*</span>Date of FIRST exposure/contact</label>
                                        <input type="date" class="form-control" name="history4_firstexposure" id="history4_firstexposure" value="{{old('history4_firstexposure', $d->history4_firstexposure)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history4_lastexposure"><span class="text-danger font-weight-bold">*</span>Date of LAST exposure/contact</label>
                                        <input type="date" class="form-control" name="history4_lastexposure" id="history4_lastexposure" value="{{old('history4_lastexposure', $d->history4_lastexposure)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                      <label for="history4_type"><span class="text-danger font-weight-bold">*</span>Type of contact (Select all that apply)</label>
                                      <select class="form-control" name="history4_type[]" id="history4_type" multiple>
                                        <option value="Rodents alive in the house" {{(collect(old('history4_type', explode(',', $d->history4_type)))->contains('Rodents alive in the house')) ? 'selected' : ''}}>Rodents alive in the house</option>
                                        <option value="Dead animal found in the forest" {{(collect(old('history4_type', explode(',', $d->history4_type)))->contains('Dead animal found in the forest')) ? 'selected' : ''}}>Dead animal found in the forest</option>
                                        <option value="Alive animal living in the forest" {{(collect(old('history4_type', explode(',', $d->history4_type)))->contains('Alive animal living in the forest')) ? 'selected' : ''}}>Alive animal living in the forest</option>
                                        <option value="Animal bought for meat" {{(collect(old('history4_type', explode(',', $d->history4_type)))->contains('Animal bought for meat')) ? 'selected' : ''}}>Animal bought for meat</option>
                                        <option value="Others" {{(collect(old('history4_type', explode(',', $d->history4_type)))->contains('Others')) ? 'selected' : ''}}>Others</option>
                                      </select>
                                    </div>
                                    <div class="form-group d-none" id="div_history4_type_others">
                                        <label for="history4_type_others"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                        <input type="text" class="form-control" name="history4_type_others" id="history4_type_others" value="{{old('history4_type_others', $d->history4_type_others)}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="history5_genderidentity"><span class="text-danger font-weight-bold">*</span>5. Patients Gender Identity</label>
                                    <select class="form-control" name="history5_genderidentity" id="history5_genderidentity" required>
                                        <option value="" disabled {{(old('history5_genderidentity', $d->history5_genderidentity) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="MAN" {{(old('history5_genderidentity', $d->history5_genderidentity) == 'MAN') ? 'selected' : ''}}>MAN</option>
                                        <option value="WOMAN" {{(old('history5_genderidentity', $d->history5_genderidentity) == 'WOMAN') ? 'selected' : ''}}>WOMAN</option>
                                        <option value="IN THE MIDDLE" {{(old('history5_genderidentity', $d->history5_genderidentity) == 'IN THE MIDDLE') ? 'selected' : ''}}>IN THE MIDDLE</option>
                                        <option value="NON BINARY" {{(old('history5_genderidentity', $d->history5_genderidentity) == 'NON BINARY') ? 'selected' : ''}}>NON BINARY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="history6_yn"><span class="text-danger font-weight-bold">*</span>6. Did the patient engage in sex (vaginal, oral, or anal) within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history6_yn" id="history6_yn" required>
                                        <option value="" disabled {{(old('history6_yn', $d->history6_yn) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="N" {{(old('history6_yn', $d->history6_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history6_yn', $d->history6_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_history6" class="d-none">
                                    <table class="table table-bordered">
                                        <thead class="text-center thead-light">
                                            <tr>
                                                <th></th>
                                                <th>History of sexual activity or close initmate contact</th>
                                                <th>No. of Sexual Partners</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Male to male</td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" name="history6_mtm" id="history6_mtm">
                                                            <option value="" disabled {{(old('history6_mtm', $d->history6_mtm) == '') ? 'selected' : ''}}>Choose...</option>
                                                            <option value="N" {{(old('history6_mtm', $d->history6_mtm) == 'N') ? 'selected' : ''}}>NO</option>
                                                            <option value="Y" {{(old('history6_mtm', $d->history6_mtm) == 'Y') ? 'selected' : ''}}>YES</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" name="history6_mtm_nosp" id="history6_mtm_nosp" value="{{old('history6_mtm_nosp', $d->history6_mtm_nosp)}}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Male to female</td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" name="history6_mtf" id="history6_mtf">
                                                            <option value="" disabled {{(old('history6_mtf', $d->history6_mtf) == '') ? 'selected' : ''}}>Choose...</option>
                                                            <option value="N" {{(old('history6_mtf', $d->history6_mtf) == 'N') ? 'selected' : ''}}>NO</option>
                                                            <option value="Y" {{(old('history6_mtf', $d->history6_mtf) == 'Y') ? 'selected' : ''}}>YES</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" name="history6_mtf_nosp" id="history6_mtf_nosp" value="{{old('history6_mtf_nosp', $d->history6_mtf_nosp)}}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Unknown</td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" name="history6_uknown" id="history6_uknown">
                                                            <option value="" disabled {{(old('history6_uknown', $d->history6_uknown) == '') ? 'selected' : ''}}>Choose...</option>
                                                            <option value="N" {{(old('history6_uknown', $d->history6_uknown) == 'N') ? 'selected' : ''}}>NO</option>
                                                            <option value="Y" {{(old('history6_uknown', $d->history6_uknown) == 'Y') ? 'selected' : ''}}>YES</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" name="history6_uknown_nosp" id="history6_uknown_nosp" value="{{old('history6_uknown_nosp', $d->history6_uknown_nosp)}}">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <label for="history7_yn"><span class="text-danger font-weight-bold">*</span>7. Did the patient experience close intimate contact (cuddling, kissing, mutual masturbation, sharing sex toys) within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history7_yn" id="history7_yn" required>
                                        <option value="" disabled {{(old('history7_yn', $d->history7_yn) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="N" {{(old('history7_yn', $d->history7_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history7_yn', $d->history7_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="history8_yn"><span class="text-danger font-weight-bold">*</span>8. Sharing of items (e.g towels, beddings, food, utensils, etc.) with your sexual partners within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history8_yn" id="history8_yn" required>
                                        <option value="" disabled {{(old('history8_yn', $d->history8_yn) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="N" {{(old('history8_yn', $d->history8_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history8_yn', $d->history8_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="R" {{(old('history8_yn', $d->history8_yn) == 'r') ? 'selected' : ''}}>REFUSE TO ANSWER</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="history9_choice"><span class="text-danger font-weight-bold">*</span>9. Did the patient have sex and/or close intimate contact with some one who had recently traveled outside of your city or community within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history9_choice" id="history9_choice" required>
                                        <option value="" disabled {{(old('history9_choice', $d->history9_choice) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="NO" {{(old('history9_choice') == 'NO') ? 'selected' : ''}}>NO</option>
                                        <option value="YES, TO ANOTHER COUNTRY" {{(old('history9_choice', $d->history9_choice) == 'YES, TO ANOTHER COUNTRY') ? 'selected' : ''}}>YES, TO ANOTHER COUNTRY</option>
                                        <option value="YES, TO ANOTHER PROVINCE" {{(old('history9_choice', $d->history9_choice) == 'YES, TO ANOTHER PROVINCE') ? 'selected' : ''}}>YES, TO ANOTHER PROVINCE</option>
                                        <option value="YES, TO ANOTHER CITY WITHIN MY PROVINCE" {{(old('history9_choice', $d->history9_choice) == 'YES, TO ANOTHER CITY WITHIN MY PROVINCE') ? 'selected' : ''}}>YES, TO ANOTHER CITY WITHIN MY PROVINCE</option>
                                        <option value="UNKNOWN" {{(old('history9_choice', $d->history9_choice) == 'UNKNOWN') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="history9_choice_div">
                                    <label for="history9_choice_othercountry"><span class="text-danger font-weight-bold">*</span>Specify Country</label>
                                    <input type="text" class="form-control" name="history9_choice_othercountry" id="history9_choice_othercountry" value="{{old('history9_choice_othercountry', $d->history9_choice)}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header"><b>V. LABORATORY TESTS</b></div>
                    <div class="card-body">
                        <p class="text-center">Can be added after finishing this CIF first.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><b>VI. HEALTH STATUS</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="health_status"><span class="text-danger font-weight-bold">*</span>Current Health Status</label>
                                    <select class="form-control" name="health_status" id="health_status" required>
                                        <option value="ACTIVE" {{(old('health_status', $d->health_status) == 'ACTIVE') ? 'selected' : ''}}>ACTIVE</option>
                                        <option value="DISCHARGED" {{(old('health_status', $d->health_status) == 'DISCHARGED') ? 'selected' : ''}}>DISCHARGED</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="div_health_status_date_discharged">
                                    <label for="health_status_date_discharged"><span class="text-danger font-weight-bold">*</span>Date Discharged</label>
                                    <input type="date"class="form-control" name="health_status_date_discharged" id="health_status_date_discharged" value="{{old('health_status_date_discharged', $d->health_status_date_discharged)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="health_status_final_diagnosis">Final Diagnosis</label>
                                    <input type="text"class="form-control" name="health_status_final_diagnosis" id="health_status_final_diagnosis" value="{{old('health_status_final_diagnosis', $d->health_status_final_diagnosis)}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
                                    <select class="form-control" name="outcome" id="outcome">
                                        <option value="" {{(old('outcome', $d->outcome) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="RECOVERED" {{(old('outcome', $d->outcome) == 'RECOVERED') ? 'selected' : ''}}>RECOVERED</option>
                                        <option value="DIED" {{(old('outcome', $d->outcome) == 'DIED') ? 'selected' : ''}}>DIED</option>
                                        <option value="UNKNOWN" {{(old('outcome', $d->outcome) == 'UNKNOWN') ? 'selected' : ''}}>UNKNOWN</option>
                                        <option value="TRANSFERRED TO OTHER HEALTHCARE SETTING" {{(old('outcome', $d->outcome) == 'TRANSFERRED TO OTHER HEALTHCARE SETTING') ? 'selected' : ''}}>TRANSFERRED TO OTHER HEALTHCARE SETTING</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="div_outcome_date_recovered">
                                    <label for="outcome_date_recovered"><span class="text-danger font-weight-bold">*</span>Date Recovered</label>
                                    <input type="date"class="form-control" name="outcome_date_recovered" id="outcome_date_recovered" value="{{old('outcome_date_recovered', $d->outcome_date_recovered)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div id="div_outcome_date_died" class="d-none">
                                    <div class="form-group">
                                        <label for="outcome_date_died"><span class="text-danger font-weight-bold">*</span>Date Died</label>
                                        <input type="date"class="form-control" name="outcome_date_died" id="outcome_date_died" value="{{old('outcome_date_recovered', $d->outcome_date_recovered)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="outcome_causeofdeath"><span class="text-danger font-weight-bold">*</span>Cause of death</label>
                                        <input type="text"class="form-control" name="outcome_causeofdeath" id="outcome_causeofdeath" value="{{old('outcome_causeofdeath', $d->outcome_causeofdeath)}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div id="div_outcome_unknown_type" class="d-none">
                                    <div class="form-group">
                                        <label for="outcome_unknown_type"><span class="text-danger font-weight-bold">*</span>Type</label>
                                        <select class="form-control" name="outcome_unknown_type" id="outcome_unknown_type" required>
                                            <option value="HAMA" {{(old('outcome_unknown_type', $d->outcome_unknown_type) == 'HAMA') ? 'selected' : ''}}>HAMA</option>
                                            <option value="LOST TO FOLLOW-UP" {{(old('outcome_unknown_type', $d->outcome_unknown_type) == 'LOST TO FOLLOW-UP') ? 'selected' : ''}}>LOST TO FOLLOW-UP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="case_classification"><span class="text-danger font-weight-bold">*</span>Case Classification</label>
                                    <select class="form-control" name="case_classification" id="case_classification" required>
                                        <option value="SUSPECT" {{(old('case_classification', $d->case_classification) == 'SUSPECT') ? 'selected' : ''}}>SUSPECT</option>
                                        <option value="PROBABLE" {{(old('case_classification', $d->case_classification) == 'PROBABLE') ? 'selected' : ''}}>PROBABLE</option>
                                        <option value="CONFIRMED" {{(old('case_classification', $d->case_classification) == 'CONFIRMED') ? 'selected' : ''}}>CONFIRMED</option>
                                        <option value="CONTACT" {{(old('case_classification', $d->case_classification) == 'CONTACT') ? 'selected' : ''}}>CONTACT</option>
                                        <option value="DISCARDED" {{(old('case_classification', $d->case_classification) == 'DISCARDED') ? 'selected' : ''}}>DISCARDED</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">{{($mode == 'EDIT') ? 'Update' : 'Save'}} (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

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

    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
        theme: 'bootstrap',
    });

    $('#perm_address_region_code, #perm_address_province_code, #perm_address_muncity_code, #perm_address_brgy_text').select2({
        theme: 'bootstrap',
    });

    $(document).ready(function () {
        //Region Select Initialize
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
                $('#address_region_code').append($('<option>', {
                    value: val.regCode,
                    text: val.regDesc,
                    selected: (val.regCode == '04') ? true : false, //default is Region IV-A
                }));
            });
        }).fail(function(jqxhr, textStatus, error) {
            // Error callback
            var err = textStatus + ", " + error;
            console.log("Failed to load Region JSON: " + err);
            window.location.reload(); // Reload the page
        });

        $('#address_region_code').change(function (e) { 
            e.preventDefault();
            //Empty and Disable
            $('#address_province_code').empty();
            $("#address_province_code").append('<option value="" selected disabled>Choose...</option>');

            $('#address_muncity_code').empty();
            $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

            //Re-disable Select
            $('#address_muncity_code').prop('disabled', true);
            $('#address_brgy_text').prop('disabled', true);

            //Set Values for Hidden Box
            $('#address_region_text').val($('#address_region_code option:selected').text());

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
                    if($('#address_region_code').val() == val.regCode) {
                        $('#address_province_code').append($('<option>', {
                            value: val.provCode,
                            text: val.provDesc,
                            selected: (val.provCode == '0421') ? true : false, //default for Cavite
                        }));
                    }
                });
            }).fail(function(jqxhr, textStatus, error) {
                // Error callback
                var err = textStatus + ", " + error;
                console.log("Failed to load Region JSON: " + err);
                window.location.reload(); // Reload the page
            });
        }).trigger('change');

        $('#address_province_code').change(function (e) {
            e.preventDefault();
            //Empty and Disable
            $('#address_muncity_code').empty();
            $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

            //Re-disable Select
            $('#address_muncity_code').prop('disabled', false);
            $('#address_brgy_text').prop('disabled', true);

            //Set Values for Hidden Box
            $('#address_province_text').val($('#address_province_code option:selected').text());

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
                    if($('#address_province_code').val() == val.provCode) {
                        $('#address_muncity_code').append($('<option>', {
                            value: val.citymunCode,
                            text: val.citymunDesc,
                            selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
                        })); 
                    }
                });
            }).fail(function(jqxhr, textStatus, error) {
                // Error callback
                var err = textStatus + ", " + error;
                console.log("Failed to load CityMun JSON: " + err);
                window.location.reload(); // Reload the page
            });
        }).trigger('change');

        $('#address_muncity_code').change(function (e) {
            e.preventDefault();
            //Empty and Disable
            $('#address_brgy_text').empty();
            $("#address_brgy_text").append('<option value="" selected disabled>Choose...</option>');

            //Re-disable Select
            $('#address_muncity_code').prop('disabled', false);
            $('#address_brgy_text').prop('disabled', false);

            //Set Values for Hidden Box
            $('#address_muncity_text').val($('#address_muncity_code option:selected').text());

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
                    if($('#address_muncity_code').val() == val.citymunCode) {
                        $('#address_brgy_text').append($('<option>', {
                            value: val.brgyDesc.toUpperCase(),
                            text: val.brgyDesc.toUpperCase(),
                        }));
                    }
                });
            }).fail(function(jqxhr, textStatus, error) {
                // Error callback
                var err = textStatus + ", " + error;
                console.log("Failed to load Province BRGY: " + err);
                window.location.reload(); // Reload the page
            });
        }).trigger('change');

        $('#address_region_text').val('REGION IV-A (CALABARZON)');
        $('#address_province_text').val('CAVITE');
        $('#address_muncity_text').val('GENERAL TRIAS');
        

        //PERM ADDRESS
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
                $('#perm_address_region_code').append($('<option>', {
                    value: val.regCode,
                    text: val.regDesc,
                    selected: (val.regCode == '04') ? true : false, //default is Region IV-A
                }));
            });
        }).fail(function(jqxhr, textStatus, error) {
            // Error callback
            var err = textStatus + ", " + error;
            console.log("Failed to load Region JSON: " + err);
            window.location.reload(); // Reload the page
        });

        $('#perm_address_region_code').change(function (e) { 
            e.preventDefault();
            //Empty and Disable
            $('#perm_address_province_code').empty();
            $("#perm_address_province_code").append('<option value="" selected disabled>Choose...</option>');

            $('#perm_address_muncity_code').empty();
            $("#perm_address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

            //Re-disable Select
            $('#perm_address_muncity_code').prop('disabled', true);
            $('#perm_address_brgy_text').prop('disabled', true);

            //Set Values for Hidden Box
            $('#perm_address_region_text').val($('#perm_address_region_code option:selected').text());

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
                    if($('#perm_address_region_code').val() == val.regCode) {
                        $('#perm_address_province_code').append($('<option>', {
                            value: val.provCode,
                            text: val.provDesc,
                            selected: (val.provCode == '0421') ? true : false, //default for Cavite
                        }));
                    }
                });
            }).fail(function(jqxhr, textStatus, error) {
                // Error callback
                var err = textStatus + ", " + error;
                console.log("Failed to load Region JSON: " + err);
                window.location.reload(); // Reload the page
            });
        }).trigger('change');

        $('#perm_address_province_code').change(function (e) {
            e.preventDefault();
            //Empty and Disable
            $('#perm_address_muncity_code').empty();
            $("#perm_address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

            //Re-disable Select
            $('#perm_address_muncity_code').prop('disabled', false);
            $('#perm_address_brgy_text').prop('disabled', true);

            //Set Values for Hidden Box
            $('#perm_address_province_text').val($('#perm_address_province_code option:selected').text());

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
                    if($('#perm_address_province_code').val() == val.provCode) {
                        $('#perm_address_muncity_code').append($('<option>', {
                            value: val.citymunCode,
                            text: val.citymunDesc,
                            selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
                        })); 
                    }
                });
            }).fail(function(jqxhr, textStatus, error) {
                // Error callback
                var err = textStatus + ", " + error;
                console.log("Failed to load CityMun JSON: " + err);
                window.location.reload(); // Reload the page
            });
        }).trigger('change');

        $('#perm_address_muncity_code').change(function (e) {
            e.preventDefault();
            //Empty and Disable
            $('#perm_address_brgy_text').empty();
            $("#perm_address_brgy_text").append('<option value="" selected disabled>Choose...</option>');

            //Re-disable Select
            $('#perm_address_muncity_code').prop('disabled', false);
            $('#perm_address_brgy_text').prop('disabled', false);

            //Set Values for Hidden Box
            $('#perm_address_muncity_text').val($('#perm_address_muncity_code option:selected').text());

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
                    if($('#perm_address_muncity_code').val() == val.citymunCode) {
                        $('#perm_address_brgy_text').append($('<option>', {
                            value: val.brgyDesc.toUpperCase(),
                            text: val.brgyDesc.toUpperCase(),
                        }));
                    }
                });
            }).fail(function(jqxhr, textStatus, error) {
                // Error callback
                var err = textStatus + ", " + error;
                console.log("Failed to load Province BRGY: " + err);
                window.location.reload(); // Reload the page
            });
        }).trigger('change');

        $('#perm_address_region_text').val('REGION IV-A (CALABARZON)');
        $('#perm_address_province_text').val('CAVITE');
        $('#perm_address_muncity_text').val('GENERAL TRIAS');
    });

    $('#have_cutaneous_rash').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_have_cutaneous_rash').removeClass('d-none');
            $('#have_cutaneous_rash_date').prop('required', true);
        }
        else {
            $('#div_have_cutaneous_rash').addClass('d-none');
            $('#have_cutaneous_rash_date').prop('required', false);
        }
    }).trigger('change');

    $('#have_fever').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_have_fever').removeClass('d-none');
            $('#have_fever_date').prop('required', true);
            $('#have_fever_days_duration').prop('required', true);
        }
        else {
            $('#div_have_fever').addClass('d-none');
            $('#have_fever_date').prop('required', false);
            $('#have_fever_days_duration').prop('required', false);
        }
    }).trigger('change');

    $('#health_status').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'ACTIVE') {
            $('#div_health_status_date_discharged').addClass('d-none');
            $('#health_status_date_discharged').prop('required', false);
        }
        else if($(this).val() == 'DISCHARGED') {
            $('#div_health_status_date_discharged').removeClass('d-none');
            $('#health_status_date_discharged').prop('required', true);
        }
    }).trigger('change');

    $('#history1_yn').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_history1').removeClass('d-none');
            $('#history1_specify').prop('required', true);
            $('#history1_date_travel').prop('required', true);
            $('#history1_flightno').prop('required', true);
            $('#history1_date_arrival').prop('required', true);
            $('#history1_pointandexitentry').prop('required', true);
        }
        else {
            $('#div_history1').addClass('d-none');
            $('#history1_specify').prop('required', false);
            $('#history1_date_travel').prop('required', false);
            $('#history1_flightno').prop('required', false);
            $('#history1_date_arrival').prop('required', false);
            $('#history1_pointandexitentry').prop('required', false);
        }
    }).trigger('change');

    $('#history2_yn').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_history2').removeClass('d-none');
            $('#history2_specify').prop('required', true);
            $('#history2_date_travel').prop('required', true);
            $('#history2_flightno').prop('required', true);
            $('#history2_date_arrival').prop('required', true);
            $('#history2_pointandexitentry').prop('required', true);
        }
        else {
            $('#div_history2').addClass('d-none');
            $('#history2_specify').prop('required', false);
            $('#history2_date_travel').prop('required', false);
            $('#history2_flightno').prop('required', false);
            $('#history2_date_arrival').prop('required', false);
            $('#history2_pointandexitentry').prop('required', false);
        }
    }).trigger('change');

    $('#history4_yn').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_history4').removeClass('d-none');
            $('#history4_typeofanimal').prop('required', true);
            $('#history4_firstexposure').prop('required', true);
            $('#history4_lastexposure').prop('required', true);
            $('#history4_type').prop('required', true);
        }
        else {
            $('#div_history4').addClass('d-none');
            $('#history4_typeofanimal').prop('required', false);
            $('#history4_firstexposure').prop('required', false);
            $('#history4_lastexposure').prop('required', false);
            $('#history4_type').prop('required', false);
        }
    }).trigger('change');

    $('#ifhashistory_blooddonation_transfusion').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'DONOR' || $(this).val() == 'RECIPIENT') {
            $('#blooddono_div').removeClass('d-none');
            
            $('#ifhashistory_blooddonation_transfusion').prop('required', true);
            $('#ifhashistory_blooddonation_transfusion_place').prop('required', true);
            $('#ifhashistory_blooddonation_transfusion_date').prop('required', true);
        }
        else {
            $('#blooddono_div').addClass('d-none');

            $('#ifhashistory_blooddonation_transfusion').prop('required', false);
            $('#ifhashistory_blooddonation_transfusion_place').prop('required', false);
            $('#ifhashistory_blooddonation_transfusion_date').prop('required', false);
        }
    }).trigger('change');

    $('#outcome').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'RECOVERED') {
            $('#div_outcome_date_recovered').removeClass('d-none');
            $('#div_outcome_date_died').addClass('d-none');
            $('#div_outcome_unknown_type').addClass('d-none');

            $('#outcome_unknown_type').prop('required', false);
            $('#outcome_date_recovered').prop('required', true);
            $('#outcome_date_died').prop('required', false);
            $('#outcome_causeofdeath').prop('required', false);
        }
        else if($(this).val() == 'DIED') {
            $('#div_outcome_date_recovered').addClass('d-none');
            $('#div_outcome_date_died').removeClass('d-none');
            $('#div_outcome_unknown_type').addClass('d-none');

            $('#outcome_unknown_type').prop('required', false);
            $('#outcome_date_recovered').prop('required', false);
            $('#outcome_date_died').prop('required', true);
            $('#outcome_causeofdeath').prop('required', true);
        }
        else if($(this).val() == 'UNKNOWN') {
            $('#div_outcome_date_recovered').addClass('d-none');
            $('#div_outcome_date_died').addClass('d-none');
            $('#div_outcome_unknown_type').removeClass('d-none');

            $('#outcome_unknown_type').prop('required', true);
            $('#outcome_date_recovered').prop('required', false);
            $('#outcome_date_died').prop('required', false);
            $('#outcome_causeofdeath').prop('required', false);
        }
        else {
            $('#div_outcome_date_recovered').addClass('d-none');
            $('#div_outcome_date_died').addClass('d-none');
            $('#div_outcome_unknown_type').addClass('d-none');

            $('#outcome_unknown_type').prop('required', false);
            $('#outcome_date_recovered').prop('required', false);
            $('#outcome_date_died').prop('required', false);
            $('#outcome_causeofdeath').prop('required', false);
        }
    }).trigger('change');

    $('#history6_yn').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'Y') {
            $('#div_history6').removeClass('d-none');
        }
        else {
            $('#div_history6').addClass('d-none');
        }
    }).trigger('change');

    $('#history6_mtm').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'Y') {
            $("#history6_mtm_nosp").prop('required', true);
            $("#history6_mtm_nosp").prop('disabled', false);
        }
        else {
            $("#history6_mtm_nosp").prop('required', false);
            $("#history6_mtm_nosp").prop('disabled', true);
        }
    }).trigger('change');

    $('#history6_mtf').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'Y') {
            $("#history6_mtf_nosp").prop('required', true);
            $("#history6_mtf_nosp").prop('disabled', false);
        }
        else {
            $("#history6_mtf_nosp").prop('required', false);
            $("#history6_mtf_nosp").prop('disabled', true);
        }
    }).trigger('change');

    $('#history6_uknown').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'Y') {
            $("#history6_uknown_nosp").prop('required', true);
            $("#history6_uknown_nosp").prop('disabled', false);
        }
        else {
            $("#history6_uknown_nosp").prop('required', false);
            $("#history6_uknown_nosp").prop('disabled', true);
        }
    }).trigger('change');

    $('#history4_type').change(function (e) { 
        e.preventDefault();
        if($(this).val().includes('Others')) {
            $('#div_history4_type_others').removeClass('d-none');
            $('#history4_type_others').prop('required', true);
        }
        else {
            $('#div_history4_type_others').addClass('d-none');
            $('#history4_type_others').prop('required', false);
        }
    }).trigger('change');

    $('#history9_choice').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'YES, TO ANOTHER COUNTRY') {
            $('#history9_choice_div').removeClass('d-none');
            $('#history9_choice_othercountry').prop('required', true);
        }
        else {
            $('#history9_choice_div').addClass('d-none');
            $('#history9_choice_othercountry').prop('required', false);
        }
    }).trigger('change');

    $('#symptoms_list').change(function (e) { 
        e.preventDefault();

        if($(this).val().includes('LYMPHADENOPATHY')) {
            $('#div_lymp').removeClass('d-none');
            $('#symptoms_lymphadenopathy_localization').prop('required', true);
        }
        else {
            $('#div_lymp').addClass('d-none');
            $('#symptoms_lymphadenopathy_localization').prop('required', false);
        }
    }).trigger('change');

    $('#history3_yn').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_history3').removeClass('d-none');
        }
        else {
            $('#div_history3').addClass('d-none');
        }
    }).trigger('change');
</script>
@endsection