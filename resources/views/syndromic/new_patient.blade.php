@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('syndromic_storePatient')}}" method="POST">
            @csrf
            <input type="hidden" name="request_uuid" value="{{ Str::uuid() }}">
            @if(!is_null(request()->input('from_etcl')))
            <input type="hidden" name="from_etcl" value="{{request()->input('from_etcl')}}">
            @endif
            <div class="card">
                <div class="card-header"><b>New ITR - Step 2/3</b></div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="alert alert-primary" role="alert">
                        <b class="text-danger">Note:</b> All fields marked with an Asterisk (<b class="text-danger">*</b>) are <b>REQUIRED</b> to be filled-out.
                    </div>
                    @if(auth()->user()->opdfacility->enable_customemr1 == 1)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Facility</label>
                                <input type="text" class="form-control" name="" id="" value="{{auth()->user()->opdfacility->facility_name}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="facility_controlnumber"><b class="text-danger">*</b>ITR Control No.</label>
                                <input type="text" class="form-control" name="facility_controlnumber" id="facility_controlnumber" value="{{old('facility_controlnumber')}}" style="text-transform: uppercase;" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <label for=""><b class="text-danger">*</b>Facility</label>
                        <input type="text" class="form-control" name="" id="" value="{{auth()->user()->opdfacility->facility_name}}" readonly>
                    </div>
                    @endif
                    @if(is_null(request()->input('from_etcl')))
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <canvas id="canvas" width="640" height="480" class="d-none" style="width: 50%"></canvas>
                            <input type="hidden" name="selfie_image" id="imageData">
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#cameraModal"><i class="fa fa-camera mr-2" aria-hidden="true"></i>Open Camera</button>
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#fingerPrint"><i class="fa fa-hand-paper-o" aria-hidden="true"></i>Get Patient Fingerprint</button>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                    @endif
                    @if(auth()->user()->isSyndromicHospitalLevelAccess())
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="unique_opdnumber"><b class="text-danger">*</b>Hospital/OPD Number</label>
                                <input type="text" class="form-control" name="unique_opdnumber" id="unique_opdnumber" value="{{old('unique_opdnumber')}}" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_presented"><b class="text-danger">*</b>ID Presented</label>
                                <input type="text" class="form-control" name="id_presented" id="id_presented" value="{{old('id_presented')}}" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{request()->input('lname')}}" max="50" style="text-transform: uppercase;" readonly required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{request()->input('fname')}}" max="50" style="text-transform: uppercase;" readonly required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{request()->input('mname')}}" min="3" max="50" style="text-transform: uppercase;" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{request()->input('suffix')}}" max="50" style="text-transform: uppercase;" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{request()->input('bdate')}}" readonly>
                                <small>Age: {{$getage}}</small>
                            </div>
                            <div class="form-group">
                              <label for="isph_member"><b class="text-danger">*</b>Philhealth Member/Dependent?</label>
                              <select class="form-control" name="isph_member" id="isph_member" required>
                                <option value="" disabled {{is_null(old('isph_member')) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('isph_member') == 'Y') ? 'selected' : ''}}>Yes (NH)</option>
                                <option value="N" {{(old('isph_member') == 'N') ? 'selected' : ''}}>No (NN)</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                  <select class="form-control" name="gender" id="gender" required>
                                    @if(!is_null(request()->input('from_etcl')) && request()->input('from_etcl') == 'maternal_care')
                                    <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                    @else
                                      <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                      <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                    @endif
                                  </select>
                            </div>
                            <div class="form-group">
                              <label for="philhealth">Philhealth # (Optional)</label>
                              <input type="text" class="form-control" name="philhealth" id="philhealth">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cs"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                <select class="form-control" id="cs" name="cs" required>
                                    @if(!is_null(request()->input('from_etcl')) && request()->input('from_etcl') == 'child_care')
                                    <option value="SINGLE" {{(old('cs') == 'SINGLE') ? 'selected' : ''}}>Single</option>
                                    @else
                                    <option value="" disabled {{(is_null(old('cs'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="SINGLE" {{(old('cs') == 'SINGLE') ? 'selected' : ''}}>Single</option>
                                    <option value="MARRIED" {{(old('cs') == 'MARRIED') ? 'selected' : ''}}>Married</option>
                                    <option value="WIDOWED" {{(old('cs') == 'WIDOWED') ? 'selected' : ''}}>Widowed</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group d-none" id="ifmarried_div">
                                <label for="spouse_name">Spouse Name</label>
                                <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{old('spouse_name')}}" style="text-transform: uppercase;">
                              </div>
                            <div class="form-group">
                              <label for="">Email Address (Optional)</label>
                              <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********">
                            </div>
                            <div class="form-group">
                                <label for="contact_number2">Contact Number 2 (Optional)</label>
                                <input type="text" class="form-control" id="contact_number2" name="contact_number2" value="{{old('contact_number2')}}" pattern="[0-9]{11}" placeholder="09*********">
                            </div>
                        </div>
                    </div>
                    @if(!auth()->user()->isSyndromicHospitalLevelAccess())
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="philhealth_statustype"><b class="text-danger d-none" id="philhealth_statustype_asterisk">*</b>Philhealth Status Type</label>
                                <select class="form-control" name="philhealth_statustype" id="philhealth_statustype">
                                    <option value="" disabled {{(is_null(old('philhealth_statustype'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="MEMBER" {{(old('philhealth_statustype') == 'Member') ? 'selected' : ''}}>Member</option>
                                    <option value="DEPENDENT" {{(old('philhealth_statustype') == 'Dependent') ? 'selected' : ''}}>Dependent</option>
                                </select>
                            </div>
                            @if(is_null(request()->input('from_etcl')))
                            <div class="form-group">
                                <label for="has_fe"><b class="text-danger">*</b>Has first encounter record this year ({{date('Y')}})</label>
                                <select class="form-control" name="has_fe" id="has_fe" required>
                                    <option value="" disabled {{(is_null(old('has_fe'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('has_fe') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('has_fe') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="fe_div" class="d-none">
                                <div class="form-group">
                                    <label for="date_of_first_encounter">Date of First Encounter</label>
                                    <input type="date" class="form-control" name="date_of_first_encounter" id="date_of_first_encounter" value="{{old('date_of_first_encounter')}}" max="{{date('Y-m-d')}}">
                                  </div>
                                  <div class="form-group">
                                    <label for="philhealth_pcu"><b class="text-danger">*</b>Philhealth Check Utility (PCU) Number</label>
                                    <input type="text" class="form-control" name="philhealth_pcu" id="philhealth_pcu" value="{{old('philhealth_pcu')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="family_member"><b class="text-danger">*</b>Family Member (Posisyon sa Pamilya)</label>
                                <select class="form-control" name="family_member" id="family_member" required>
                                    <option value="" disabled {{(is_null(old('family_member'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="FATHER" {{(old('family_member') == 'FATHER') ? 'selected' : ''}} id="fam_male1">Father/Ama</option>
                                    <option value="MOTHER" {{(old('family_member') == 'MOTHER') ? 'selected' : ''}} id="fam_female1">Mother/Ina</option>
                                    <option value="SON" {{(old('family_member') == 'SON') ? 'selected' : ''}} id="fam_male2">Son/Anak na Lalaki</option>
                                    <option value="DAUGHTER" {{(old('family_member') == 'DAUGHTER') ? 'selected' : ''}} id="fam_female2">Daughter/Anak na Babae</option>
                                    <option value="OTHERS" {{(old('family_member') == 'OTHERS') ? 'selected' : ''}}>Others/Iba pa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @if(!auth()->user()->isSyndromicHospitalLevelAccess())
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">Facility Household No.</div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" value="{{old('inhouse_householdno')}}" id="inhouse_householdno" name="inhouse_householdno" readonly>
                                <div class="input-group-append">
                                  <button class="btn btn-outline-primary" id="household_search_btn" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>Family Serial No.</div>
                            <div class="mb-2 text-muted"><b>Note:</b> You may input the family serial number manually or use the search button to generate.</div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" value="{{old('inhouse_familyserialno')}}" id="inhouse_familyserialno" name="inhouse_familyserialno" autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" id="familyserial_search_btn" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    @if(is_null(request()->input('from_etcl')))
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                              <label for="occupation">Occupation</label>
                              <input type="text" class="form-control" name="occupation" id="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="occupation_place">Place of Work/School</label>
                                <input type="text" class="form-control" name="occupation_place" id="occupation_place" value="{{old('occupation_place')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="mother_name">Mother's Name</label>
                              <input type="text" class="form-control" name="mother_name" id="mother_name" value="{{old('mother_name')}}" style="text-transform: uppercase;" {{(!is_null(request()->input('from_etcl')) && request()->input('from_etcl') == 'child_care' ? 'required' : '')}}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="father_name">Father's Name</label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="{{old('father_name')}}" style="text-transform: uppercase;">
                              </div>
                        </div>
                    </div>

                    @if($getage <= 17 && is_null(request()->input('from_etcl')))
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="ifminor_resperson">Patient is minor, input Name of Responsible Person/Guardian/Parent (Mother or Father)</label>
                              <input type="text" class="form-control" name="ifminor_resperson" id="ifminor_resperson" value="{{old('ifminor_resperson')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="ifminor_resrelation">Relationship</label>
                              <select class="form-control" name="ifminor_resrelation" id="ifminor_resrelation">
                                <option value="" {{(is_null(old('ifminor_resrelation'))) ? 'selected' : ''}}>None</option>
                                <option value="PARENT" {{(old('cs') == 'PARENT') ? 'selected' : ''}}>Parent/Magulang</option>
                                <option value="SIBLING" {{(old('cs') == 'SIBLING') ? 'selected' : ''}}>Sibling/Kapatid</option>
                                <option value="OTHERS" {{(old('cs') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <hr>
                    <div class="row">
                        <div class="col-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_indg" name="is_indg" value="Y" {{(old('is_indg') == 'Y') ? 'checked' : ''}}>
                                <label class="form-check-label">Indigenous People</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_4ps" name="is_4ps" value="Y" {{(old('is_4ps') == 'Y') ? 'checked' : ''}}>
                                <label class="form-check-label">4Ps Member</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_nhts" name="is_nhts" value="Y" {{(old('is_nhts') == 'Y') ? 'checked' : ''}}>
                                <label class="form-check-label">NHTS</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_pwd" name="is_pwd" value="Y" {{(old('is_pwd') == 'Y') ? 'checked' : ''}}>
                                <label class="form-check-label">PWD</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_singleparent" name="is_singleparent" value="Y" {{(old('is_singleparent') == 'Y') ? 'checked' : ''}}>
                                <label class="form-check-label">Single Parent</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="is_others" name="is_others" value="Y" {{(old('is_others') == 'Y') ? 'checked' : ''}}>
                                <label class="form-check-label">Others</label>
                            </div>
                        </div>
                        <div class="col-3">
                            <div id="ifCheckboxOthersDiv" class="d-none">
                                <div class="form-group">
                                  <label for="is_others_specify"><b class="text-danger">*</b>Specify</label>
                                  <input type="text" class="form-control" name="is_others_specify" id="is_others_specify" minlength="1" maxlength="100" value="{{old('is_others_specify')}}" style="text-transform: uppercase;" >
                                </div>
                            </div>
                        </div>
                    </div>
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
                    <hr>
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
                                <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_street" class="form-label"><b class="text-danger">*</b>Street/Subdivision/Purok/Sitio</label>
                                <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group {{(auth()->user()->isSyndromicHospitalLevelAccess() || !is_null(request()->input('from_etcl'))) ? 'd-none' : ''}}">
                        <hr>
                        <label for="is_lgustaff"><b class="text-danger">*</b>Is Patient a LGU/Government Employee?</label>
                        <select class="form-control" name="is_lgustaff" id="is_lgustaff" required>
                            <option value="N" {{(old('is_lgustaff') == 'N') ? 'selected' : ''}}>No</option>
                            <option value="Y" {{(old('is_lgustaff') == 'Y') ? 'selected' : ''}}>Yes</option>
                        </select>
                    </div>
                    <div class="form-group d-none" id="if_lgustaff">
                        <label for="lgu_office_name"><b class="text-danger">*</b>Name of LGU/Government Office</label>
                        <input type="text" class="form-control" name="lgu_office_name" id="lgu_office_name" value="{{old('lgu_office_name')}}" style="text-transform: uppercase;">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block" id="submitBtn">Submit (CTRL + S)</button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="cameraModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Patient Picture</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <video id="video" width="1920" height="1080"></video>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="snap" class="btn btn-success btn-block">Capture</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fingerPrint" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Get Patient Fingerprint</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <p>Currently in Development.</p>
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>

    @if(!auth()->user()->isSyndromicHospitalLevelAccess())
    <div class="modal fade" id="household_search_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Household Search</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="household_search_input" placeholder="Type to search household no / family member name...">
                    </div>
              
                      <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                          <thead class="thead-light text-center">
                            <tr>
                              <th>Household No.</th>
                              <th>Name</th>
                              <th>Address</th>
                            </tr>
                          </thead>
                          <tbody id="household_table_body">
                            <tr><td colspan="3" class="text-center text-muted">Loading...</td></tr>
                          </tbody>
                        </table>
                      </div>
              
                      <small class="text-muted">Click a household number to select.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" id="btn_generate_householdno">Generate New</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="familyserial_search_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Family Serial Search</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <div class="form-group">
                            <input type="text" class="form-control" id="family_search_input" placeholder="Type to search family serial no / family member name...">
                        </div>

                        <table class="table table-sm table-bordered">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>Family Serial No.</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                </tr>
                                </thead>
                            <tbody id="familyserial_table_body">
                                <tr><td colspan="3" class="text-center text-muted">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>

                    <small class="text-muted">Click a family serial number to select.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" id="btn_generate_familyno">Generate New</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        // --- JSON retry helper (put this ABOVE $(document).ready) ---
        const jsonCache = {};

        function getJSONWithRetry(url, { retries = 3, timeout = 20000, backoff = 600 } = {}) {
            if (jsonCache[url]) return jsonCache[url]; // same promise (prevents multiple downloads)

            const attempt = (n) => {
                return $.ajax({
                url: url,
                dataType: "json",
                cache: true,
                timeout: timeout
                }).catch((jqxhr, textStatus, error) => {
                if (n <= 0) return $.Deferred().reject(jqxhr, textStatus, error).promise();

                const delay = backoff * Math.pow(2, (retries - n)); // exponential backoff
                return $.Deferred(function (d) {
                    setTimeout(() => attempt(n - 1).then(d.resolve).catch(d.reject), delay);
                }).promise();
                });
            };

            jsonCache[url] = attempt(retries);
            return jsonCache[url];
        }

        function showJsonLoadError(msg) {
            console.log(msg);
            // IMPORTANT: avoid window.location.reload() loop
            alert(msg + "\n\nCheck your internet then click OK to retry.");
        }

        @if(!auth()->user()->isSyndromicHospitalLevelAccess())
        function escapeHtml(text) {
            return $('<div/>').text(text ?? '').html();
        }

        function loadHouseholds(query) {
            $('#household_table_body').html('<tr><td colspan="3" class="text-center text-muted">Loading...</td></tr>');

            $.ajax({
                url: "{{ route('inhouse_familyserials_search') }}",
                method: "GET",
                data: { q: query },
                success: function(rows) {
                    if (!rows || rows.length === 0) {
                    $('#household_table_body').html('<tr><td colspan="3" class="text-center text-muted">No results</td></tr>');
                    return;
                    }

                    let html = '';
                    rows.forEach(function (r) {
                    let fullname = r.patient
                        ? `${r.patient.lname}, ${r.patient.fname} ${r.patient.mname ?? ''}`
                        : '—';

                    html += `
                        <tr>
                        <td>
                            <a href="#" class="pick-household" data-householdno="${r.inhouse_householdno}">
                            ${r.inhouse_householdno}
                            </a>
                        </td>
                        <td>${fullname}</td>
                        <td></td>
                        </tr>
                    `;
                    });

                    $('#household_table_body').html(html);
                },
                error: function() {
                    $('#household_table_body').html('<tr><td colspan="3" class="text-center text-danger">Failed to load</td></tr>');
                }
            });
        }

        function loadFamilySerials(query) {
            $('#familyserial_table_body').html('<tr><td colspan="3" class="text-center text-muted">Loading...</td></tr>');

            $.ajax({
                url: "{{ route('inhouse_familyserials_search') }}",
                method: "GET",
                data: { q: query },
                success: function(rows) {
                    if (!rows || rows.length === 0) {
                        $('#familyserial_table_body').html('<tr><td colspan="3" class="text-center text-muted">No results</td></tr>');
                        return;
                    }

                    let html = '';
                    rows.forEach(function (r) {
                    let fullname = r.patient
                        ? `${r.patient.lname}, ${r.patient.fname} ${r.patient.mname ?? ''}`
                        : '—';

                    html += `
                        <tr>
                        <td>
                            <a href="#" class="pick-familyserial" data-familyserialno="${r.inhouse_familyserialno}">
                            ${r.inhouse_familyserialno}
                            </a>
                        </td>
                        <td>${fullname}</td>
                        <td></td>
                        </tr>
                    `;
                    });

                    $('#familyserial_table_body').html(html);
                },
                error: function() {
                    $('#familyserial_table_body').html('<tr><td colspan="3" class="text-center text-danger">Failed to load</td></tr>');
                }
            });
        }

        // Open modal on Search click
        $('#familyserial_search_btn').on('click', function() {
            $('#familyserial_search_modal').modal('toggle');
            $('#family_search_input').val('');
            loadFamilySerials('');
            setTimeout(() => $('#family_search_input').trigger('focus'), 200);
        });

        // Search as you type (simple debounce)
        let familySerialTimer = null;
        $('#family_search_input').on('keyup', function() {
            clearTimeout(familySerialTimer);
            const q = $(this).val();
            familySerialTimer = setTimeout(function() {
            loadFamilySerials(q);
            }, 300);
        });

        // Click household no -> set input then close modal
        $(document).on('click', '.pick-familyserial', function(e) {
            e.preventDefault();
            const familyserialno = $(this).data('familyserialno');
            $('#inhouse_familyserialno').val(familyserialno);
            $('#familyserial_search_modal').modal('toggle');

            $('#inhouse_householdno').prop('required', true);
        });

        // Open modal on Search click
        $('#household_search_btn').on('click', function() {
            $('#household_search_modal').modal('toggle');
            $('#household_search_input').val('');
            loadHouseholds('');
            setTimeout(() => $('#household_search_input').trigger('focus'), 200);
        });

        // Search as you type (simple debounce)
        let householdTimer = null;
        $('#household_search_input').on('keyup', function() {
            clearTimeout(householdTimer);
            const q = $(this).val();
            householdTimer = setTimeout(function() {
            loadHouseholds(q);
            }, 300);
        });

        // Click household no -> set input then close modal
        $(document).on('click', '.pick-household', function(e) {
            e.preventDefault();
            const householdno = $(this).data('householdno');
            $('#inhouse_householdno').val(householdno);
            $('#household_search_modal').modal('toggle');

            $('#inhouse_familyserialno').prop('required', true);
        });
        
        $('#btn_generate_householdno').on('click', function () {
            $.ajax({
            url: "{{ route('inhouse_generate_householdno') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.householdno) {
                $('#inhouse_householdno').val(res.householdno);

                // optional: close modal
                $('#household_search_modal').modal('toggle');
                }
            },
            error: function () {
                alert('Failed to generate household number.');
            }
            });
        });

        $('#btn_generate_familyno').on('click', function () {
            $.ajax({
            url: "{{ route('inhouse_generate_familyserial') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.familyserialno) {
                $('#inhouse_familyserialno').val(res.familyserialno);

                // optional: close modal
                $('#familyserial_search_modal').modal('toggle');
                }
            },
            error: function () {
                alert('Failed to generate family serial.');
            }
            });
        });
        @endif

        @if(is_null(request()->input('from_etcl')))

        let mediaStream = null;

        // Function to start camera
        function startCamera() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then((stream) => {
                        mediaStream = stream;
                        document.getElementById('video').srcObject = stream;
                        document.getElementById('video').play();
                    })
                    .catch((error) => {
                        console.error('Error accessing the camera:', error);
                    });
            }
        }

        // Function to stop camera
        function stopCamera() {
            if (mediaStream) {
                mediaStream.getTracks().forEach(track => track.stop());
                mediaStream = null;
            }
        }

        // Start camera when modal is shown
        $('#cameraModal').on('shown.bs.modal', function () {
            startCamera();
        });

        // Stop camera when modal is hidden
        $('#cameraModal').on('hidden.bs.modal', function () {
            stopCamera();
        });

        // Capture frame and stop camera
        const canvas = document.getElementById('canvas');
        const snap = document.getElementById('snap');
        const imageData = document.getElementById('imageData');
        const context = canvas.getContext('2d');

        document.getElementById('snap').addEventListener('click', function () {
            const canvas = document.getElementById('canvas');
            const video = document.getElementById('video');
            const context = canvas.getContext('2d');
            
            // Draw the cropped video frame to the canvas
            context.drawImage(video, 0, 0, 640, 480);
            const dataURL = canvas.toDataURL('image/jpeg'); // Convert canvas to dataURL in JPG format
            imageData.value = dataURL;
            $('#cameraModal').modal('hide');
            $('#canvas').removeClass('d-none');
            // Optionally stop the camera after capturing
            stopCamera();
        });

        @endif

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

        @if(!auth()->user()->isSyndromicHospitalLevelAccess())
        $('#gender').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'MALE') {
                $('#family_member').prop('disabled', false);
                $('#family_member').val('');

                $('#fam_female1').addClass('d-none');
                $('#fam_female2').addClass('d-none');

                $('#fam_male1').removeClass('d-none');
                $('#fam_male2').removeClass('d-none');
            }
            else if($(this).val() == 'FEMALE') {
                $('#family_member').prop('disabled', false);
                $('#family_member').val('');

                $('#fam_female1').removeClass('d-none');
                $('#fam_female2').removeClass('d-none');

                $('#fam_male1').addClass('d-none');
                $('#fam_male2').addClass('d-none');
            }
            else {
                $('#family_member').prop('disabled', true);
            }
        }).trigger('change');
        @endif

        $('#isph_member').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#philhealth').prop('readonly', false);
                @if(!auth()->user()->isSyndromicHospitalLevelAccess())
                $('#philhealth_statustype').prop('disabled', false);
                $('#philhealth_statustype').prop('required', true);
                $('#philhealth_statustype_asterisk').removeClass('d-none');
                @endif
            }
            else {
                $('#philhealth').prop('readonly', true);
                $('#philhealth').val('');
                @if(!auth()->user()->isSyndromicHospitalLevelAccess())
                $('#philhealth_statustype').prop('disabled', true);
                $('#philhealth_statustype').prop('required', false);
                $('#philhealth_statustype_asterisk').addClass('d-none');
                @endif
            }
        }).trigger('change');

        $('#is_lgustaff').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#if_lgustaff').removeClass('d-none');
                $('#lgu_office_name').prop('required', true);
            }
            else {
                $('#if_lgustaff').addClass('d-none');
                $('#lgu_office_name').prop('required', false);
            }
        }).trigger('change');
        
        //Select2 Init for Address Bar
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
            theme: 'bootstrap',
        });

        $(document).ready(function () {
            const regionUrl  = "{{ asset('json/refregion.json') }}";
            const provUrl    = "{{ asset('json/refprovince.json') }}";
            const citymunUrl = "{{ asset('json/refcitymun.json') }}";
            const brgyUrl    = "{{ asset('json/refbrgy.json') }}";

            // Prepare select placeholders
            $('#address_region_code').empty().append('<option value="" selected disabled>Choose...</option>');
            $('#address_province_code').empty().append('<option value="" selected disabled>Choose...</option>');
            $('#address_muncity_code').empty().append('<option value="" selected disabled>Choose...</option>').prop('disabled', true);
            $('#address_brgy_text').empty().append('<option value="" selected disabled>Choose...</option>').prop('disabled', true);

            // 1) Load regions first (with retry)
            getJSONWithRetry(regionUrl).then(function (data) {
                var sorted = data.sort((a,b) => (a.regDesc || '').localeCompare(b.regDesc || ''));

                sorted.forEach(function (val) {
                    $('#address_region_code').append($('<option>', {
                    value: val.regCode,
                    text: val.regDesc
                    }));
                });

                // default Region IV-A
                $('#address_region_code').val('04').trigger('change');
                $('#address_region_text').val('REGION IV-A (CALABARZON)');
            })
            .catch(function (jqxhr, textStatus, error) {
                showJsonLoadError("Failed to load Region JSON (" + textStatus + ").");
            });

            // 2) On region change -> load provinces
            $('#address_region_code').off('change.addr').on('change.addr', function (e) {
                e.preventDefault();

                $('#address_province_code').empty().append('<option value="" selected disabled>Choose...</option>');
                $('#address_muncity_code').empty().append('<option value="" selected disabled>Choose...</option>').prop('disabled', true);
                $('#address_brgy_text').empty().append('<option value="" selected disabled>Choose...</option>').prop('disabled', true);

                $('#address_region_text').val($('#address_region_code option:selected').text());

                getJSONWithRetry(provUrl)
                .then(function (data) {
                    var sorted = data.sort((a,b) => (a.provDesc || '').localeCompare(b.provDesc || ''));

                    sorted.forEach(function (val) {
                    if ($('#address_region_code').val() == val.regCode) {
                        $('#address_province_code').append($('<option>', {
                        value: val.provCode,
                        text: val.provDesc
                        }));
                    }
                    });

                    // default Cavite
                    $('#address_province_code').val('0421').trigger('change');
                    $('#address_province_text').val('CAVITE');
                })
                .catch(function (jqxhr, textStatus, error) {
                    showJsonLoadError("Failed to load Province JSON (" + textStatus + ").");
                });
            });

            // 3) On province change -> load city/mun
            $('#address_province_code').off('change.addr').on('change.addr', function (e) {
                e.preventDefault();

                $('#address_muncity_code').empty().append('<option value="" selected disabled>Choose...</option>').prop('disabled', false);
                $('#address_brgy_text').empty().append('<option value="" selected disabled>Choose...</option>').prop('disabled', true);

                $('#address_province_text').val($('#address_province_code option:selected').text());

                getJSONWithRetry(citymunUrl)
                .then(function (data) {
                    var sorted = data.sort((a,b) => (a.citymunDesc || '').localeCompare(b.citymunDesc || ''));

                    sorted.forEach(function (val) {
                    if ($('#address_province_code').val() == val.provCode) {
                        $('#address_muncity_code').append($('<option>', {
                        value: val.citymunCode,
                        text: val.citymunDesc
                        }));
                    }
                    });

                    // default General Trias
                    $('#address_muncity_code').val('042108').trigger('change');
                    $('#address_muncity_text').val('GENERAL TRIAS');
                })
                .catch(function (jqxhr, textStatus, error) {
                    showJsonLoadError("Failed to load CityMun JSON (" + textStatus + ").");
                });
            });

            // 4) On city/mun change -> load barangays
            $('#address_muncity_code').off('change.addr').on('change.addr', function (e) {
                e.preventDefault();

                $('#address_brgy_text').empty().append('<option value="" selected disabled>Choose...</option>').prop('disabled', false);

                $('#address_muncity_text').val($('#address_muncity_code option:selected').text());

                getJSONWithRetry(brgyUrl)
                .then(function (data) {
                    var sorted = data.sort((a,b) => (a.brgyDesc || '').localeCompare(b.brgyDesc || ''));

                    sorted.forEach(function (val) {
                    if ($('#address_muncity_code').val() == val.citymunCode) {
                        $('#address_brgy_text').append($('<option>', {
                        value: (val.brgyDesc || '').toUpperCase(),
                        text: (val.brgyDesc || '').toUpperCase()
                        }));
                    }
                    });
                })
                .catch(function (jqxhr, textStatus, error) {
                    showJsonLoadError("Failed to load Barangay JSON (" + textStatus + ").");
                });
            });
        });
        
        $('#cs').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'MARRIED') {
                $('#ifmarried_div').removeClass('d-none');
                //$('#spouse_name').prop('required', true);
            }
            else {
                $('#ifmarried_div').addClass('d-none');
                //$('#spouse_name').prop('required', false);
            }
        }).trigger('change');

        $('#is_others').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked')) {
                $('#ifCheckboxOthersDiv').removeClass('d-none');
                $('#is_others_specify').prop('required', true);
            }
            else {
                $('#ifCheckboxOthersDiv').addClass('d-none');
                $('#is_others_specify').prop('required', false);
            }
        }).trigger('change');

        @if(is_null(request()->input('from_etcl')))
        $('#has_fe').change(function (e) { 
            e.preventDefault();
            $("#fe_div").addClass('d-none');
            $('#date_of_first_encounter').prop('required', false);
            $('#philhealth_pcu').prop('required', false);

            if($(this).val() == 'Y') {
                $("#fe_div").removeClass('d-none');
                $('#philhealth_pcu').prop('required', true);
            }
            
        }).trigger('change');
        @endif
    </script>
@endsection