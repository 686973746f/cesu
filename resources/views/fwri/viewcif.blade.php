@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('fwri_update', $d->id)}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header"><b>View/Edit FWRI Patient</b></div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="form-group">
                      <label for="status"><b class="text-danger">*</b>Account Status</label>
                      <select class="form-control" name="status" id="status" required>
                        <option value="ENABLED" {{(old('status', $d->status) == 'ENABLED') ? 'selected' : ''}}>ENABLED</option>
                        <option value="DISABLED" {{(old('status', $d->status) == 'DISABLED') ? 'selected' : ''}}>DISABLED</option>
                      </select>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header text-center"><b>METADATA</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for=""><b class="text-danger">*</b>Facility Name</label>
                                      <input type="text" class="form-control" name="hospital_name" id="hospital_name" value="{{old('hospital_name', $d->hospital_name)}}" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Date Reported</label>
                                        <input type="date" class="form-control" name="report_date" id="report_date" value="{{old('report_date', $d->report_date)}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="reported_by"><b class="text-danger">*</b>Name of Reporter</label>
                                        <input type="text" class="form-control" name="reported_by" id="reported_by" value="{{old('reported_by', $d->reported_by)}}" style="text-transform: uppercase;" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header text-center"><b>PATIENT DATA</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lname"><b class="text-danger">*</b>Last Name</label>
                                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', $d->lname)}}" placeholder="DELA CRUZ" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', $d->fname)}}" placeholder="JUAN" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', $d->mname)}}" placeholder="SANCHEZ" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="suffix">Name Extension <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', $d->suffix)}}" minlength="2" maxlength="3" placeholder="JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bdate"><b class="text-danger">*</b>Date of Birth</label>
                                        <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate', $d->bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Gender</label>
                                                  <select class="form-control" name="gender" id="gender" required>
                                                      <option value="MALE" {{(old('gender', $d->gender) == 'MALE') ? 'selected' : ''}}>Male</option>
                                                      <option value="FEMALE" {{(old('gender', $d->gender) == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                                  </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label for="is_4ps"><span class="text-danger font-weight-bold">*</span>4P's Member?</label>
                                                <select class="form-control" name="is_4ps" id="is_4ps" required>
                                                    <option value="Y" {{(old('is_4ps', $d->is_4ps) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                    <option value="N" {{(old('is_4ps', $d->is_4ps) == 'N') ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header text-center"><b>PATIENT'S CURRENT ADDRESS</b></div>
                                <div class="card-body">
                                    <div class="row">
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
                                                <select class="form-control" name="brgy_id" id="address_brgy_code" required disabled>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address_street"><b class="text-danger">*</b>House/Lot No. & Street/Purok/Subdivision</label>
                                        <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street', $d->address_street)}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header text-center"><b>INCIDENT DATA</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Date and Time of Injury</label>
                                        <input type="datetime-local" class="form-control" name="injury_date" id="injury_date" value="{{old('injury_date', $d->injury_date)}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Date and Time of Consultation</label>
                                        <input type="datetime-local" class="form-control" name="consultation_date" id="consultation_date" value="{{old('consultation_date', $d->consultation_date)}}" required>
                                    </div>
                                    <div class="form-group">
                                    <label for="reffered_anotherhospital"><span class="text-danger font-weight-bold">*</span>Referral from another hospital?</label>
                                        <select class="form-control" name="reffered_anotherhospital" id="reffered_anotherhospital" required>
                                            <option value="N" {{(old('reffered_anotherhospital', $d->reffered_anotherhospital) == 'N') ? 'selected' : ''}}>No</option>
                                            <option value="Y" {{(old('reffered_anotherhospital', $d->reffered_anotherhospital) == 'Y') ? 'selected' : ''}}>Yes</option>
                                        </select>
                                    </div>
                                    <div id="ifReferral" class="d-none">
                                        <div class="form-group">
                                            <label for="nameof_hospital"><b class="text-danger">*</b>Name of referring hospital</label>
                                            <input type="text" class="form-control" name="nameof_hospital" id="nameof_hospital" value="{{old('nameof_hospital', $d->nameof_hospital)}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="place_of_occurrence"><span class="text-danger font-weight-bold">*</span>Place of Occurrence</label>
                                        <select class="form-control" name="place_of_occurrence" id="place_of_occurrence" required>
                                            <option value="" disabled {{(is_null(old('place_of_occurrence', $d->place_of_occurrence))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="HOME" {{(old('place_of_occurrence', $d->place_of_occurrence) == 'HOME') ? 'selected' : ''}}>HOME</option>
                                            <option value="STREET" {{(old('place_of_occurrence', $d->place_of_occurrence) == 'STREET') ? 'selected' : ''}}>STREET</option>
                                            <option value="DESIGNATED AREA" {{(old('place_of_occurrence', $d->place_of_occurrence) == 'DESIGNATED AREA') ? 'selected' : ''}}>DESIGNATED AREAS</option>
                                            <option value="OTHERS" {{(old('place_of_occurrence', $d->place_of_occurrence) == 'OTHERS') ? 'selected' : ''}}>OTHERS, SPECIFY</option>
                                        </select>
                                    </div>
                                    <div id="ifOtherPlace" class="d-none">
                                        <div class="form-group">
                                            <label for="place_of_occurrence_others"><b class="text-danger">*</b>If Other place, specify</label>
                                            <input type="text" class="form-control" name="place_of_occurrence_others" id="place_of_occurrence_others" value="{{old('place_of_occurrence_others', $d->place_of_occurrence_others)}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header"><b>ADDRESS WHERE INJURY OCCURRED</b></div>
                                <div class="card-body">
                                    <div class="form-group">
                                    <label for="injury_sameadd"><span class="text-danger font-weight-bold">*</span>Place of Injury the same as the Patient's Current Address?</label>
                                        <select class="form-control" name="injury_sameadd" id="injury_sameadd" required>
                                            <option value="Y" {{(old('injury_sameadd', $d->injury_sameadd) == 'Y') ? 'selected' : ''}}>Yes</option>
                                            <option value="N" {{(old('injury_sameadd', $d->injury_sameadd) == 'N') ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div id="ifDiffInjuryAdd" class="d-none">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="injury_address_region_code"><b class="text-danger">*</b>Injury Region</label>
                                                    <select class="form-control" name="injury_address_region_code" id="injury_address_region_code" tabindex="-1">
                                                    @foreach(App\Models\Regions::orderBy('regionName', 'ASC')->get() as $a)
                                                    <option value="{{$a->id}}" {{($a->id == 1) ? 'selected' : ''}}>{{$a->regionName}}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="injury_address_province_code"><b class="text-danger">*</b>Injury Province</label>
                                                    <select class="form-control" name="injury_address_province_code" id="injury_address_province_code" tabindex="-1" disabled>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="injury_address_muncity_code"><b class="text-danger">*</b>Injury City/Municipality</label>
                                                    <select class="form-control" name="injury_address_muncity_code" id="injury_address_muncity_code" tabindex="-1" disabled>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="injury_address_brgy_code"><b class="text-danger">*</b>Injury Barangay</label>
                                                    <select class="form-control" name="inj_brgy_id" id="injury_address_brgy_code" disabled>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="injury_address_street"><b class="text-danger">*</b>Injury House/Lot No. & Street/Purok/Subdivision</label>
                                            <input type="text" class="form-control" id="injury_address_street" name="injury_address_street" style="text-transform: uppercase;" value="{{old('injury_address_street', $d->injury_address_street)}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="involvement_type"><span class="text-danger font-weight-bold">*</span>Type of Involvement</label>
                                        <select class="form-control" name="involvement_type" id="involvement_type" required>
                                            <option value="ACTIVE" {{(old('involvement_type', $d->involvement_type) == 'ACTIVE') ? 'selected' : ''}}>ACTIVE (NAGPAPUTOK)</option>
                                            <option value="PASSIVE" {{(old('involvement_type', $d->involvement_type) == 'PASSIVE') ? 'selected' : ''}}>PASSIVE (NADAMAY/NAPADAAN LANG)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="nature_injury"><span class="text-danger font-weight-bold">*</span>Nature of Injury</label>
                                        <select class="form-control" name="nature_injury" id="nature_injury" required>
                                            <option value="FIREWORKS INJURY" {{(old('nature_injury', $d->nature_injury) == 'FIREWORKS INJURY') ? 'selected' : ''}}>FIREWORKS INJURY/NASUGATAN NG PAPUTOK</option>
                                            <option value="FIREWORKS INGESTION" {{(old('nature_injury', $d->nature_injury) == 'FIREWORKS INGESTION') ? 'selected' : ''}}>FIREWORKS INGESTION/NAKALUNOK NG PAPUTOK</option>
                                            <option value="STRAY BULLET INJURY" {{(old('nature_injury', $d->nature_injury) == 'STRAY BULLET INJURY') ? 'selected' : ''}}>STRAY BULLET INJURY/LIGAW NA BALA</option>
                                            <option value="TETANUS" {{(old('nature_injury', $d->nature_injury) == 'TETANUS') ? 'selected' : ''}}>TETANUS</option>
                                        </select>
                                    </div>
                                    <div id="ifFireWorkInjury" class="d-none">
                                        <div class="form-group">
                                        <label for="iffw_typeofinjury"><span class="text-danger font-weight-bold">*</span>IF fireworks injury, type of injury (multiple responses)</label>
                                            <select class="form-control" name="iffw_typeofinjury[]" id="iffw_typeofinjury" multiple>
                                                <option value="BLAST/BURN INJURY WITH AMPUTATION" {{(in_array('BLAST/BURN INJURY WITH AMPUTATION', explode(',', $d->iffw_typeofinjury))) ? 'selected' : ''}}>BLAST/BURN INJURY WITH AMPUTATION</option>
                                                <option value="BLAST/BURN INJURY NO AMPUTATION" {{(in_array('BLAST/BURN INJURY NO AMPUTATION', explode(',', $d->iffw_typeofinjury))) ? 'selected' : ''}}>BLAST/BURN INJURY NO AMPUTATION</option>
                                                <option value="EYE INJURY" {{(in_array('EYE INJURY', explode(',', $d->iffw_typeofinjury))) ? 'selected' : ''}}>EYE INJURY</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="complete_diagnosis">Complete Diagnosis <i>(include nature, site, and laterality)</i></label>
                                      <textarea class="form-control" name="complete_diagnosis" id="complete_diagnosis" rows="3" style="text-transform: uppercase;">{{old('complete_diagnosis', $d->complete_diagnosis)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="anatomical_location"><span class="text-danger font-weight-bold">*</span>Anatomical Location (multiple responses)</label>
                                        <select class="form-control" name="anatomical_location[]" id="anatomical_location" required multiple>
                                            <option value="HEAD" {{(in_array('HEAD', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>HEAD</option>
                                            <option value="EYE" {{(in_array('EYE', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>EYE</option>
                                            <option value="NECK" {{(in_array('NECK', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>NECK</option>
                                            <option value="CHEST" {{(in_array('CHEST', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>CHEST</option>
                                            <option value="BACK" {{(in_array('BACK', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>BACK</option>
                                            <option value="ABDOMEN" {{(in_array('ABDOMEN', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>ABDOMEN</option>
                                            <option value="PELVIS" {{(in_array('PELVIS', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>PELVIS</option>
                                            <option value="THIGH" {{(in_array('THIGH', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>THIGH</option>
                                            <option value="BUTTOCKS" {{(in_array('BUTTOCKS', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>BUTTOCKS</option>
                                            <option value="LEGS" {{(in_array('LEGS', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>LEGS</option>
                                            <option value="KNEE" {{(in_array('KNEE', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>KNEE</option>
                                            <option value="FOOT" {{(in_array('FOOT', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>FOOT</option>
                                            <option value="FOREARM/ARM" {{(in_array('FOREARM/ARM', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>FOREARM/ARM</option>
                                            <option value="HAND" {{(in_array('HAND', explode(',', $d->anatomical_location))) ? 'selected' : ''}}>HAND</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="firework_name"><b class="text-danger">*</b>Name of Firework/Firecracker</label>
                                        <input type="text" class="form-control" name="firework_name" id="firework_name" value="{{old('firework_name', $d->firework_name)}}" style="text-transform: uppercase;" required>
                                    </div>
                                    <div class="form-group">
                                    <label for="firework_illegal"><span class="text-danger font-weight-bold">*</span>Is the firework/firecracker used Illegal?</label>
                                        <select class="form-control" name="firework_illegal" id="firework_illegal" required>
                                            <option value="" disabled {{(is_null(old('firework_illegal'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="Y" {{(old('firework_illegal', $d->firework_illegal) == 'Y') ? 'selected' : ''}}>YES/ILLEGAL</option>
                                            <option value="N" {{(old('firework_illegal', $d->firework_illegal) == 'N') ? 'selected' : ''}}>NO/NOT ILLEGAL</option>
                                            <option value="U" {{(old('firework_illegal', $d->firework_illegal) == 'U') ? 'selected' : ''}}>Unknown</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="liquor_intoxication"><span class="text-danger font-weight-bold">*</span>Liquor Intoxication?</label>
                                        <select class="form-control" name="liquor_intoxication" id="liquor_intoxication" required>
                                            <option value="Y" {{(old('liquor_intoxication', $d->liquor_intoxication) == 'Y') ? 'selected' : ''}}>Yes</option>
                                            <option value="N" {{(old('liquor_intoxication', $d->liquor_intoxication) == 'N') ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="treatment_given"><span class="text-danger font-weight-bold">*</span>Treatment Given (multiple responses)</label>
                                        <select class="form-control" name="treatment_given[]" id="treatment_given" multiple required>
                                            <option value="ATS/TIG" {{(in_array('ATS/TIG', explode(',', $d->treatment_given))) ? 'selected' : ''}}>ATS/TIG</option>
                                            <option value="TOXOID" {{(in_array('TOXOID', explode(',', $d->treatment_given))) ? 'selected' : ''}}>TOXOID</option>
                                            <option value="NO TREATMENT" {{(in_array('NO TREATMENT', explode(',', $d->treatment_given))) ? 'selected' : ''}}>NO TREATMENT</option>
                                            <option value="OTHER" {{(in_array('OTHER', explode(',', $d->treatment_given))) ? 'selected' : ''}}>OTHER</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="disposition_after_consultation"><span class="text-danger font-weight-bold">*</span>Disposition after consultation</label>
                                        <select class="form-control" name="disposition_after_consultation" id="disposition_after_consultation" required>
                                            <option value="" disabled {{(is_null(old('disposition_after_consultation', $d->disposition_after_consultation))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="TREATED AND SENT HOME" {{(old('disposition_after_consultation', $d->disposition_after_consultation) == 'TREATED AND SENT HOME') ? 'selected' : ''}}>TREATED AND SENT HOME (TRASH)</option>
                                            <option value="ADMITTED" {{(old('disposition_after_consultation', $d->disposition_after_consultation) == 'ADMITTED') ? 'selected' : ''}}>ADMITTED</option>
                                            <option value="REFUSED ADMISSION" {{(old('disposition_after_consultation', $d->disposition_after_consultation) == 'REFUSED ADMISSION') ? 'selected' : ''}}>REFUSED ADMISSION</option>
                                            <option value="ABSCONDED" {{(old('disposition_after_consultation', $d->disposition_after_consultation) == 'ABSCONDED') ? 'selected' : ''}}>ABSCONDED</option>
                                            <option value="TRANSFERRED TO ANOTHER HOSPITAL" {{(old('disposition_after_consultation', $d->disposition_after_consultation) == 'TRANSFERRED TO ANOTHER HOSPITAL') ? 'selected' : ''}}>TRANSFERRED TO ANOTHER HOSPITAL</option>
                                            <option value="ER DEATH" {{(old('disposition_after_consultation', $d->disposition_after_consultation) == 'ER DEATH') ? 'selected' : ''}}>ER DEATH</option>
                                            <option value="DEAD ON ARRIVAL (DOA)" {{(old('disposition_after_consultation', $d->disposition_after_consultation) == 'DEAD ON ARRIVAL (DOA)') ? 'selected' : ''}}>DEAD ON ARRIVAL (DOA)</option>
                                        </select>
                                    </div>
                                    <div id="afterConsultationDiv" class="d-none">
                                        <div class="form-group">
                                            <label for="disposition_after_consultation_transferred_hospital"><b class="text-danger">*</b>Name of Hospital</label>
                                            <input type="text" class="form-control" name="disposition_after_consultation_transferred_hospital" id="disposition_after_consultation_transferred_hospital" value="{{old('disposition_after_consultation_transferred_hospital', $d->disposition_after_consultation_transferred_hospital)}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="disposition_after_admission"><span class="text-danger font-weight-bold">*</span>Disposition after admission</label>
                                        <select class="form-control" name="disposition_after_admission" id="disposition_after_admission" required>
                                            <option value="" disabled {{(is_null(old('disposition_after_admission', $d->disposition_after_admission))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="DISCHARGED" {{(old('disposition_after_admission', $d->disposition_after_admission) == 'DISCHARGED') ? 'selected' : ''}} id="da_option1">DISCHARGED</option>
                                            <option value="ABSCONDED" {{(old('disposition_after_admission', $d->disposition_after_admission) == 'ABSCONDED') ? 'selected' : ''}} id="da_option3">ABSCONDED</option>
                                            <option value="HOME AGAINST MEDICAL ADVICE (HAMA)" {{(old('disposition_after_admission', $d->disposition_after_admission) == 'HOME AGAINST MEDICAL ADVICE (HAMA)') ? 'selected' : ''}} id="da_option2">HOME AGAINST MEDICAL ADVICE (HAMA)</option>
                                            <option value="TRANSFERRED TO ANOTHER HOSPITAL" {{(old('disposition_after_admission', $d->disposition_after_admission) == 'TRANSFERRED TO ANOTHER HOSPITAL') ? 'selected' : ''}} id="da_option4">TRANSFERRED TO ANOTHER HOSPITAL</option>
                                            <option value="DIED DURING ADMISSION" {{(old('disposition_after_admission', $d->disposition_after_admission) == 'DIED DURING ADMISSION') ? 'selected' : ''}} id="da_option5">DIED DURING ADMISSION</option>
                                        </select>
                                    </div>
                                    <div id="afterAdmissionDiv" class="d-none">
                                        <div class="form-group">
                                            <label for="disposition_after_admission_transferred_hospital"><b class="text-danger">*</b>Name of Hospital</label>
                                            <input type="text" class="form-control" name="disposition_after_admission_transferred_hospital" id="disposition_after_admission_transferred_hospital" value="{{old('disposition_after_admission_transferred_hospital', $d->disposition_after_admission_transferred_hospital)}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div id="ifDied" class="d-none">
                                        <div class="form-group">
                                            <label for="date_died"><b class="text-danger">*</b>Date Died</label>
                                            <input type="date" class="form-control" name="date_died" id="date_died" value="{{old('date_died', $d->date_died)}}" min="date('Y-12-01')" max="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                            <label for="aware_healtheducation_list"><span class="text-danger font-weight-bold">*</span>Is the patient aware of any health education materials regarding fireworks (multiple responses)</label>
                                <select class="form-control" name="aware_healtheducation_list[]" id="aware_healtheducation_list" required multiple>
                                    <option value="TV" {{(in_array('TV', explode(',', $d->aware_healtheducation_list))) ? 'selected' : ''}}>TV</option>
                                    <option value="NEWSPAPER/PRINT" {{(in_array('NEWSPAPER/PRINT', explode(',', $d->aware_healtheducation_list))) ? 'selected' : ''}}>NEWSPAPER/PRINT</option>
                                    <option value="RADIO" {{(in_array('RADIO', explode(',', $d->aware_healtheducation_list))) ? 'selected' : ''}}>RADIO</option>
                                    <option value="POSTER/TARPAULIN" {{(in_array('POSTER/TARPAULIN', explode(',', $d->aware_healtheducation_list))) ? 'selected' : ''}}>POSTER/TARPAULIN</option>
                                    <option value="INTERNET/SOCIAL MEDIA" {{(in_array('INTERNET/SOCIAL MEDIA', explode(',', $d->aware_healtheducation_list))) ? 'selected' : ''}}>INTERNET/SOCIAL MEDIA</option>
                                    <option value="HEALTH WORKER" {{(in_array('HEALTH WORKER', explode(',', $d->aware_healtheducation_list))) ? 'selected' : ''}}>HEALTH WORKER</option>
                                    <option value="NOT AWARE" {{(in_array('NOT AWARE', explode(',', $d->aware_healtheducation_list))) ? 'selected' : ''}}>NOT AWARE</option>
                                </select>
                            </div>
                            <hr>
                            <div class="form-group">
                              <label for="remarks">Notes/Remarks <i>(Optional)</i></label>
                              <textarea class="form-control" name="remarks" id="remarks" rows="3" style="text-transform: uppercase;">{{mb_strtoupper(old('remarks', $d->remarks))}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" id="submitBtn">Update (CTRL + S)</button>
                    <hr>
                    <h6 class="text-center">DOH-EB-AEHMD-FWRIPIS-2023-2</h6>
                </div>
            </div>
            <p class="text-center mt-3">Developed and Maintained by <b class="text-primary">CJH</b> for CESU General Trias, Cavite</p>
        </form>
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

        var regionDefault = {{$d->brgy->city->province->region->id}};
        var provinceDefault = {{$d->brgy->city->province->id}};
        var cityDefault = {{$d->brgy->city->id}};
        var brgyDefault = {{$d->brgy->id}}

        $('#address_region_code').change(function (e) { 
            e.preventDefault();

            var regionId = $(this).val();
            var getProvinceUrl = "{{ route('address_get_provinces', ['region_id' => ':regionId']) }}";

            if (regionId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', true);
                $('#address_brgy_code').prop('disabled', true);

                $('#address_province_code').empty();
                $('#address_muncity_code').empty();
                $('#address_brgy_code').empty();

                $.ajax({
                    url: getProvinceUrl.replace(':regionId', regionId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#address_province_code').empty();
                        $('#address_province_code').append('<option value="" disabled selected>Select Province</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#address_province_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#address_province_code').empty();
            }
        }).trigger('change');

        $('#address_province_code').change(function (e) { 
            e.preventDefault();

            var provinceId = $(this).val();
            var getCityUrl = "{{ route('address_get_citymun', ['province_id' => ':provinceId']) }}";

            if (provinceId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', false);
                $('#address_brgy_code').prop('disabled', true);

                $('#address_muncity_code').empty();
                $('#address_brgy_code').empty();

                $.ajax({
                    url: getCityUrl.replace(':provinceId', provinceId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#address_muncity_code').empty();
                        $('#address_muncity_code').append('<option value="" disabled selected>Select City/Municipality</option>');
                        
                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#address_muncity_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#address_muncity_code').empty();
            }
        });

        $('#address_muncity_code').change(function (e) { 
            e.preventDefault();

            var cityId = $(this).val();
            var getBrgyUrl = "{{ route('address_get_brgy', ['city_id' => ':cityId']) }}";

            if (cityId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', false);
                $('#address_brgy_code').prop('disabled', false);

                $('#address_brgy_code').empty();

                $.ajax({
                    url: getBrgyUrl.replace(':cityId', cityId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#address_brgy_code').empty();
                        $('#address_brgy_code').append('<option value="" disabled selected>Select Barangay</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#address_brgy_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#address_brgy_code').empty();
            }
        });

        if ($('#address_region_code').val()) {
            $('#address_region_code').trigger('change'); // Automatically load provinces on page load
        }

        if (provinceDefault) {
            setTimeout(function() {
                $('#address_province_code').val(provinceDefault).trigger('change');
            }, 1500); // Slight delay to ensure province is loaded
        }
        if (cityDefault) {
            setTimeout(function() {
                $('#address_muncity_code').val(cityDefault).trigger('change');
            }, 2500); // Slight delay to ensure city is loaded
        }
        if (brgyDefault) {
            setTimeout(function() {
                $('#address_brgy_code').val(brgyDefault).trigger('change');
            }, 3500); // Slight delay to ensure city is loaded
        }

        var inj_regionDefault = {{$d->injurybrgy->city->province->region->id}};
        var inj_provinceDefault = {{$d->injurybrgy->city->province->id}};
        var inj_cityDefault = {{$d->injurybrgy->city->id}};
        var inj_brgyDefault = {{$d->injurybrgy->id}}

        $('#injury_address_region_code').change(function (e) { 
            e.preventDefault();

            var regionId = $(this).val();
            var getProvinceUrl = "{{ route('address_get_provinces', ['region_id' => ':regionId']) }}";

            if (regionId) {
                $('#injury_address_province_code').prop('disabled', false);
                $('#injury_address_muncity_code').prop('disabled', true);
                $('#injury_address_brgy_code').prop('disabled', true);

                $('#injury_address_province_code').empty();
                $('#injury_address_muncity_code').empty();
                $('#injury_address_brgy_code').empty();

                $.ajax({
                    url: getProvinceUrl.replace(':regionId', regionId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#injury_address_province_code').empty();
                        $('#injury_address_province_code').append('<option value="" disabled selected>Select Province</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#injury_address_province_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#injury_address_province_code').empty();
            }
        }).trigger('change');

        $('#injury_address_province_code').change(function (e) { 
            e.preventDefault();

            var provinceId = $(this).val();
            var getCityUrl = "{{ route('address_get_citymun', ['province_id' => ':provinceId']) }}";

            if (provinceId) {
                $('#injury_address_province_code').prop('disabled', false);
                $('#injury_address_muncity_code').prop('disabled', false);
                $('#injury_address_brgy_code').prop('disabled', true);

                $('#injury_address_muncity_code').empty();
                $('#injury_address_brgy_code').empty();

                $.ajax({
                    url: getCityUrl.replace(':provinceId', provinceId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#injury_address_muncity_code').empty();
                        $('#injury_address_muncity_code').append('<option value="" disabled selected>Select City/Municipality</option>');
                        
                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#injury_address_muncity_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#injury_address_muncity_code').empty();
            }
        });

        $('#injury_address_muncity_code').change(function (e) { 
            e.preventDefault();

            var cityId = $(this).val();
            var getBrgyUrl = "{{ route('address_get_brgy', ['city_id' => ':cityId']) }}";

            if (cityId) {
                $('#injury_address_province_code').prop('disabled', false);
                $('#injury_address_muncity_code').prop('disabled', false);
                $('#injury_address_brgy_code').prop('disabled', false);

                $('#injury_address_brgy_code').empty();

                $.ajax({
                    url: getBrgyUrl.replace(':cityId', cityId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#injury_address_brgy_code').empty();
                        $('#injury_address_brgy_code').append('<option value="" disabled selected>Select Barangay</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#injury_address_brgy_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#injury_address_brgy_code').empty();
            }
        });

        if ($('#injury_address_region_code').val()) {
            $('#injury_address_region_code').trigger('change'); // Automatically load provinces on page load
        }

        if (inj_provinceDefault) {
            setTimeout(function() {
                $('#injury_address_province_code').val(inj_provinceDefault).trigger('change');
            }, 1500); // Slight delay to ensure province is loaded
        }
        if (inj_cityDefault) {
            setTimeout(function() {
                $('#injury_address_muncity_code').val(inj_cityDefault).trigger('change');
            }, 2500); // Slight delay to ensure city is loaded
        }
        if (inj_brgyDefault) {
            setTimeout(function() {
                $('#injury_address_brgy_code').val(inj_brgyDefault).trigger('change');
            }, 3500); // Slight delay to ensure city is loaded
        }

        $('#reffered_anotherhospital').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#ifReferral').removeClass('d-none');
                $('#nameof_hospital').prop('required', true);
            }
            else {
                $('#ifReferral').addClass('d-none');
                $('#nameof_hospital').prop('required', false);
            }
        }).trigger('change');

        $('#place_of_occurrence').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'OTHERS') {
                $('#ifOtherPlace').removeClass('d-none');
                $('#place_of_occurrence_others').prop('required', true);
            }
            else {
                $('#ifOtherPlace').addClass('d-none');
                $('#place_of_occurrence_others').prop('required', false);
            }
        }).trigger('change');

        $('#nature_injury').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'FIREWORKS INJURY') {
                $('#ifFireWorkInjury').removeClass('d-none');
                $('#iffw_typeofinjury').prop('required', true);
            }
            else {
                $('#ifFireWorkInjury').addClass('d-none');
                $('#iffw_typeofinjury').prop('required', false);
            }
        }).trigger('change');

        $('#disposition_after_consultation').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'TRANSFERRED TO ANOTHER HOSPITAL') {
                $('#afterConsultationDiv').removeClass('d-none');
                $('#disposition_after_consultation_transferred_hospital').prop('required', true);
                
                $('#da_option1').removeClass('d-none');
                $('#da_option2').removeClass('d-none');
                $('#da_option3').removeClass('d-none');
                $('#da_option4').removeClass('d-none');
                $('#da_option5').removeClass('d-none');
            }
            else if($(this).val() == 'DEAD ON ARRIVAL (DOA)' || $(this).val() == 'ER DEATH') {
                $('#disposition_after_admission').val('DIED DURING ADMISSION');
                $('#disposition_after_admission').trigger('change');
                
                $('#da_option1').addClass('d-none');
                $('#da_option2').addClass('d-none');
                $('#da_option3').addClass('d-none');
                $('#da_option4').addClass('d-none');
                $('#da_option5').removeClass('d-none');
            }
            else {
                $('#afterConsultationDiv').addClass('d-none');
                $('#disposition_after_consultation_transferred_hospital').prop('required', false);

                $('#da_option1').removeClass('d-none');
                $('#da_option2').removeClass('d-none');
                $('#da_option3').removeClass('d-none');
                $('#da_option4').removeClass('d-none');
                $('#da_option5').removeClass('d-none');
            }
        }).trigger('change');

        $('#disposition_after_admission').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'TRANSFERRED TO ANOTHER HOSPITAL') {
                $('#afterAdmissionDiv').removeClass('d-none');
                $('#ifDied').addClass('d-none');
                $('#date_died').prop('required', false);
                $('#disposition_after_admission_transferred_hospital').prop('required', true);
            }
            else if($(this).val() == 'DIED DURING ADMISSION') {
                $('#afterAdmissionDiv').addClass('d-none');
                $('#ifDied').removeClass('d-none');
                $('#date_died').prop('required', true);
                $('#disposition_after_admission_transferred_hospital').prop('required', false);
            }
            else {
                $('#afterAdmissionDiv').addClass('d-none');
                $('#ifDied').addClass('d-none');
                $('#date_died').prop('required', false);
                $('#disposition_after_admission_transferred_hospital').prop('required', false);
            }
        }).trigger('change');

        $('#injury_sameadd').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'N') {
                $('#ifDiffInjuryAdd').removeClass('d-none');

                $('#injury_address_region_code').prop('required', true);
                $('#injury_address_province_code').prop('required', true);
                $('#injury_address_muncity_code').prop('required', true);
                $('#injury_address_brgy_code').prop('required', true);
                $('#injury_address_street').prop('required', true);
                $('#injury_address_street').prop('disabled', false);
            }
            else {
                $('#ifDiffInjuryAdd').addClass('d-none');
                
                $('#injury_address_region_code').prop('required', false);
                $('#injury_address_province_code').prop('required', false);
                $('#injury_address_muncity_code').prop('required', false);
                $('#injury_address_brgy_code').prop('required', false);
                $('#injury_address_street').prop('required', false);
                $('#injury_address_street').prop('disabled', true);
            }
        }).trigger('change');
    </script>
@endsection