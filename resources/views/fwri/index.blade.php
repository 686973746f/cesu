@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('fwri_store', $code)}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header"><b>CESU Gen. Trias - Fireworks Related Injury (FWRI) Online Reporting Tool</b></div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <b>Note:</b> All fields marked with an asterisk (<b class="text-danger">*</b>) is required to be filled-out.
                    </div>
                    <div class="card mb-3">
                        <div class="card-header text-center"><b>METADATA</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for=""><b class="text-danger">*</b>Facility Name</label>
                                      <input type="text" class="form-control" name="hospital_name" id="hospital_name" value="{{old('hospital_name', mb_strtoupper($hospital_name))}}" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Date Reported</label>
                                        <input type="date" class="form-control" name="report_date" id="report_date" value="{{old('report_date', date('Y-m-d'))}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="reported_by"><b class="text-danger">*</b>Name of Reporter</label>
                                        <input type="text" class="form-control" name="reported_by" id="reported_by" value="{{old('reported_by')}}" style="text-transform: uppercase;" required>
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
                                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" placeholder="DELA CRUZ" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" placeholder="JUAN" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" placeholder="SANCHEZ" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="suffix">Suffix <i>(If Applicable)</i></label>
                                        <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="3" placeholder="JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                                        <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
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
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
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
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header text-center"><b>INCIDENT DATA</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Date and Time of Injury</label>
                                        <input type="datetime-local" class="form-control" name="injury_date" id="injury_date" value="{{old('injury_date')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for=""><b class="text-danger">*</b>Date and Time of Consultation</label>
                                        <input type="datetime-local" class="form-control" name="consultation_date" id="consultation_date" value="{{old('consultation_date')}}" required>
                                    </div>
                                    <div class="form-group">
                                    <label for="reffered_anotherhospital"><span class="text-danger font-weight-bold">*</span>Referral from another hospital?</label>
                                        <select class="form-control" name="reffered_anotherhospital" id="reffered_anotherhospital" required>
                                            <option value="" disabled {{(is_null(old('reffered_anotherhospital'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="N" {{(old('reffered_anotherhospital') == 'HOME') ? 'selected' : ''}}>No</option>
                                            <option value="Y" {{(old('reffered_anotherhospital') == 'HOME') ? 'selected' : ''}}>Yes</option>
                                        </select>
                                    </div>
                                    <div id="ifReferral" class="d-none">
                                        <div class="form-group">
                                            <label for="nameof_hospital"><b class="text-danger">*</b>Name of referring hospital</label>
                                            <input type="text" class="form-control" name="nameof_hospital" id="nameof_hospital" value="{{old('nameof_hospital')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="place_of_occurrence"><span class="text-danger font-weight-bold">*</span>Place of Occurrence</label>
                                        <select class="form-control" name="place_of_occurrence" id="place_of_occurrence" required>
                                            <option value="" disabled {{(is_null(old('place_of_occurrence'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="HOME" {{(old('place_of_occurrence') == 'HOME') ? 'selected' : ''}}>HOME</option>
                                            <option value="STREET" {{(old('place_of_occurrence') == 'STREET') ? 'selected' : ''}}>STREET</option>
                                            <option value="DESIGNATED AREA" {{(old('place_of_occurrence') == 'DESIGNATED AREA') ? 'selected' : ''}}>DESIGNATED AREAS</option>
                                            <option value="OTHERS" {{(old('place_of_occurrence') == 'OTHERS') ? 'selected' : ''}}>OTHERS, SPECIFY</option>
                                        </select>
                                    </div>
                                    <div id="ifOtherPlace" class="d-none">
                                        <div class="form-group">
                                            <label for="place_of_occurrence_others"><b class="text-danger">*</b>If Other place, specify</label>
                                            <input type="text" class="form-control" name="place_of_occurrence_others" id="place_of_occurrence_others" value="{{old('place_of_occurrence_others')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header text-center"><b>ADDRESS WHERE INJURY OCCURRED</b></div>
                                <div class="card-body">
                                    <div id="address_text" class="d-none">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" id="injury_address_region_text" name="injury_address_region_text" value="{{old('injury_address_region_text')}}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" id="injury_address_province_text" name="injury_address_province_text" value="{{old('injury_address_province_text')}}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" id="injury_address_muncity_text" name="injury_address_muncity_text" value="{{old('injury_address_muncity_text')}}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                              <label for="injury_address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Injury Occured Region</label>
                                              <select class="form-control" name="injury_address_region_code" id="injury_address_region_code" required>
                                              </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="injury_address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Injury Occured Province</label>
                                                <select class="form-control" name="injury_address_province_code" id="injury_address_province_code" required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="injury_address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Injury Occured City/Municipality</label>
                                                <select class="form-control" name="injury_address_muncity_code" id="injury_address_muncity_code" required>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="injury_address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Injury Occured Barangay</label>
                                                <select class="form-control" name="injury_address_brgy_text" id="injury_address_brgy_text" required>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="injury_address_houseno" class="form-label"><b class="text-danger">*</b>Injury Occured House No./Lot/Building</label>
                                                <input type="text" class="form-control" id="injury_address_houseno" name="injury_address_houseno" style="text-transform: uppercase;" value="{{old('injury_address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="injury_address_street" class="form-label"><b class="text-danger">*</b>Injury Occured Street/Subdivision/Purok/Sitio</label>
                                                <input type="text" class="form-control" id="injury_address_street" name="injury_address_street" style="text-transform: uppercase;" value="{{old('injury_address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="involvement_type"><span class="text-danger font-weight-bold">*</span>Type of Involvement</label>
                                        <select class="form-control" name="involvement_type" id="involvement_type" required>
                                            <option value="" disabled {{(is_null(old('involvement_type'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="ACTIVE" {{(old('involvement_type') == 'HOME') ? 'selected' : ''}}>ACTIVE (NAGPAPUTOK)</option>
                                            <option value="PASSIVE" {{(old('involvement_type') == 'STREET') ? 'selected' : ''}}>PASSIVE (NADAMAY LANG)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="nature_injury"><span class="text-danger font-weight-bold">*</span>Nature of Injury</label>
                                        <select class="form-control" name="nature_injury" id="nature_injury" required>
                                            <option value="" disabled {{(is_null(old('nature_injury'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="FIREWORKS INJURY" {{(old('nature_injury') == 'FIREWORKS INJURY') ? 'selected' : ''}}>FIREWORKS INJURY/NASUGATAN NG PAPUTOK</option>
                                            <option value="FIREWORKS INGESTION" {{(old('nature_injury') == 'FIREWORKS INGESTION') ? 'selected' : ''}}>FIREWORKS INGESTION/NAKALUNOK NG PAPUTOK</option>
                                            <option value="STRAY BULLET INJURY" {{(old('nature_injury') == 'STRAY BULLET INJURY') ? 'selected' : ''}}>STRAY BULLET INJURY/LIGAW NA BALA</option>
                                            <option value="TETANUS" {{(old('nature_injury') == 'TETANUS') ? 'selected' : ''}}>TETANUS</option>
                                        </select>
                                    </div>
                                    <div id="ifFireWorkInjury" class="d-none">
                                        <div class="form-group">
                                        <label for="iffw_typeofinjury"><span class="text-danger font-weight-bold">*</span>IF fireworks injury, type of injury (multiple responses)</label>
                                            <select class="form-control" name="iffw_typeofinjury[]" id="iffw_typeofinjury" multiple>
                                                <option value="BLAST/BURN INJURY WITH AMPUTATION" {{(in_array('BLAST/BURN INJURY WITH AMPUTATION', explode(',', old('iffw_typeofinjury')))) ? 'selected' : ''}}>BLAST/BURN INJURY WITH AMPUTATION</option>
                                                <option value="BLAST/BURN INJURY NO AMPUTATION" {{(in_array('BLAST/BURN INJURY NO AMPUTATION', explode(',', old('iffw_typeofinjury')))) ? 'selected' : ''}}>BLAST/BURN INJURY NO AMPUTATION</option>
                                                <option value="EYE INJURY" {{(in_array('EYE INJURY', explode(',', old('iffw_typeofinjury')))) ? 'selected' : ''}}>EYE INJURY</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="complete_diagnosis">Complete Diagnosis <i>(include nature, site, and laterality)</i></label>
                                      <textarea class="form-control" name="complete_diagnosis" id="complete_diagnosis" rows="3" style="text-transform: uppercase;">{{old('complete_diagnosis')}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="anatomical_location"><span class="text-danger font-weight-bold">*</span>Anatomical Location (multiple responses)</label>
                                        <select class="form-control" name="anatomical_location[]" id="anatomical_location" required multiple>
                                            <option value="HEAD" {{(in_array('HEAD', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>HEAD</option>
                                            <option value="EYE" {{(in_array('EYE', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>EYE</option>
                                            <option value="NECK" {{(in_array('NECK', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>NECK</option>
                                            <option value="CHEST" {{(in_array('CHEST', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>CHEST</option>
                                            <option value="BACK" {{(in_array('BACK', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>BACK</option>
                                            <option value="ABDOMEN" {{(in_array('ABDOMEN', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>ABDOMEN</option>
                                            <option value="PELVIS" {{(in_array('PELVIS', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>PELVIS</option>
                                            <option value="THIGH" {{(in_array('THIGH', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>THIGH</option>
                                            <option value="BUTTOCKS" {{(in_array('BUTTOCKS', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>BUTTOCKS</option>
                                            <option value="LEGS" {{(in_array('LEGS', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>LEGS</option>
                                            <option value="KNEE" {{(in_array('KNEE', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>KNEE</option>
                                            <option value="FOOT" {{(in_array('FOOT', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>FOOT</option>
                                            <option value="FOREARM/ARM" {{(in_array('FOREARM/ARM', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>FOREARM/ARM</option>
                                            <option value="HAND" {{(in_array('HAND', explode(',', old('anatomical_location')))) ? 'selected' : ''}}>HAND</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="firework_name"><b class="text-danger">*</b>Name of Firework</label>
                                        <input type="text" class="form-control" name="firework_name" id="firework_name" value="{{old('firework_name')}}" style="text-transform: uppercase;" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="liquor_intoxication"><span class="text-danger font-weight-bold">*</span>Liquor Intoxication?</label>
                                        <select class="form-control" name="liquor_intoxication" id="liquor_intoxication" required>
                                            <option value="" disabled {{(is_null(old('liquor_intoxication'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="Y" {{(old('liquor_intoxication') == 'Y') ? 'selected' : ''}}>Yes</option>
                                            <option value="N" {{(old('liquor_intoxication') == 'N') ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="treatment_given"><span class="text-danger font-weight-bold">*</span>Treatment Given (multiple responses)</label>
                                        <select class="form-control" name="treatment_given[]" id="treatment_given" multiple required>
                                            <option value="ATS/TIG" {{(in_array('ATS/TIG', explode(',', old('treatment_given')))) ? 'selected' : ''}}>ATS/TIG</option>
                                            <option value="TOXOID" {{(in_array('TOXOID', explode(',', old('treatment_given')))) ? 'selected' : ''}}>TOXOID</option>
                                            <option value="NO TREATMENT" {{(in_array('NO TREATMENT', explode(',', old('treatment_given')))) ? 'selected' : ''}}>NO TREATMENT</option>
                                            <option value="OTHER" {{(in_array('OTHER', explode(',', old('treatment_given')))) ? 'selected' : ''}}>OTHER, SPECIFY</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="disposition_after_consultation"><span class="text-danger font-weight-bold">*</span>Disposition after consultation</label>
                                        <select class="form-control" name="disposition_after_consultation" id="disposition_after_consultation" required>
                                            <option value="" disabled {{(is_null(old('disposition_after_consultation'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="TREATED AND SENT HOME" {{(old('disposition_after_consultation') == 'TREATED AND SENT HOME') ? 'selected' : ''}}>TREATED AND SENT HOME</option>
                                            <option value="ADMITTED" {{(old('disposition_after_consultation') == 'ADMITTED') ? 'selected' : ''}}>ADMITTED</option>
                                            <option value="REFUSED ADMISSION" {{(old('disposition_after_consultation') == 'REFUSED ADMISSION') ? 'selected' : ''}}>REFUSED ADMISSION</option>
                                            <option value="TRANSFERRED TO ANOTHER HOSPITAL" {{(old('disposition_after_consultation') == 'TRANSFERRED TO ANOTHER HOSPITAL') ? 'selected' : ''}}>TRANSFERRED TO ANOTHER HOSPITAL</option>
                                            <option value="ER DEATH" {{(old('disposition_after_consultation') == 'ER DEATH') ? 'selected' : ''}}>ER DEATH</option>
                                            <option value="DEAD ON ARRIVAL (DOA)" {{(old('disposition_after_consultation') == 'DEAD ON ARRIVAL (DOA)') ? 'selected' : ''}}>DEAD ON ARRIVAL (DOA)</option>
                                        </select>
                                    </div>
                                    <div id="afterConsultationDiv" class="d-none">
                                        <div class="form-group">
                                            <label for="disposition_after_consultation_transferred_hospital"><b class="text-danger">*</b>Name of Hospital</label>
                                            <input type="text" class="form-control" name="disposition_after_consultation_transferred_hospital" id="disposition_after_consultation_transferred_hospital" value="{{old('disposition_after_consultation_transferred_hospital')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label for="disposition_after_admission"><span class="text-danger font-weight-bold">*</span>Disposition after admission</label>
                                        <select class="form-control" name="disposition_after_admission" id="disposition_after_admission" required>
                                            <option value="" disabled {{(is_null(old('disposition_after_admission'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="DISCHARGED IMPROVED" {{(old('disposition_after_admission') == 'DISCHARGED IMPROVED') ? 'selected' : ''}}>DISCHARGED IMPROVED</option>
                                            <option value="HOME AGAINST MEDICAL ADVICE (HAMA)" {{(old('disposition_after_admission') == 'HOME AGAINST MEDICAL ADVICE (HAMA)') ? 'selected' : ''}}>HOME AGAINST MEDICAL ADVICE (HAMA)</option>
                                            <option value="ABSCONDED" {{(old('disposition_after_admission') == 'ABSCONDED') ? 'selected' : ''}}>ABSCONDED/UMALIS</option>
                                            <option value="TRANSFERRED TO ANOTHER HOSPITAL" {{(old('disposition_after_admission') == 'TRANSFERRED TO ANOTHER HOSPITAL') ? 'selected' : ''}}>TRANSFERRED TO ANOTHER HOSPITAL</option>
                                            <option value="DIED DURING ADMISSION" {{(old('disposition_after_admission') == 'DIED DURING ADMISSION') ? 'selected' : ''}}>DIED DURING ADMISSION</option>
                                        </select>
                                    </div>
                                    <div id="afterAdmissionDiv" class="d-none">
                                        <div class="form-group">
                                            <label for="disposition_after_admission_transferred_hospital"><b class="text-danger">*</b>Name of Hospital</label>
                                            <input type="text" class="form-control" name="disposition_after_admission_transferred_hospital" id="disposition_after_admission_transferred_hospital" value="{{old('disposition_after_admission_transferred_hospital')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div id="ifDied" class="d-none">
                                        <div class="form-group">
                                            <label for="date_died"><b class="text-danger">*</b>Date Died</label>
                                            <input type="date" class="form-control" name="date_died" id="date_died" value="{{old('date_died')}}" min="date('Y-12-01')" max="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                            <label for="aware_healtheducation_list"><span class="text-danger font-weight-bold">*</span>Is the patient aware of any health education materials regarding fireworks (multiple responses)</label>
                                <select class="form-control" name="aware_healtheducation_list[]" id="aware_healtheducation_list" required multiple>
                                    <option value="TV" {{(in_array('TV', explode(',', old('aware_healtheducation_list')))) ? 'selected' : ''}}>TV</option>
                                    <option value="NEWSPAPER/PRINT" {{(in_array('NEWSPAPER/PRINT', explode(',', old('aware_healtheducation_list')))) ? 'selected' : ''}}>NEWSPAPER/PRINT</option>
                                    <option value="RADIO" {{(in_array('RADIO', explode(',', old('aware_healtheducation_list')))) ? 'selected' : ''}}>RADIO</option>
                                    <option value="POSTER/TARPAULIN" {{(in_array('POSTER/TARPAULIN', explode(',', old('aware_healtheducation_list')))) ? 'selected' : ''}}>POSTER/TARPAULIN</option>
                                    <option value="INTERNET/SOCIAL MEDIA" {{(in_array('INTERNET/SOCIAL MEDIA', explode(',', old('aware_healtheducation_list')))) ? 'selected' : ''}}>INTERNET/SOCIAL MEDIA</option>
                                    <option value="HEALTH WORKER" {{(in_array('HEALTH WORKER', explode(',', old('aware_healtheducation_list')))) ? 'selected' : ''}}>HEALTH WORKER</option>
                                    <option value="NOT AWARE" {{(in_array('NOT AWARE', explode(',', old('aware_healtheducation_list')))) ? 'selected' : ''}}>NOT AWARE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" id="submitBtn">Submit</button>
                    <hr>
                    <h6 class="text-center">DOH-EB-AEHMD-FWRIPIS-2021-0</h6>
                </div>
            </div>
            <p class="text-center mt-3">Developed and Maintained by <b class="text-primary">Christian James Historillo</b> for CESU General Trias, Cavite</p>
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

        //Select2 Init for Address Bar
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text, #injury_address_region_code, #injury_address_province_code, #injury_address_muncity_code, #injury_address_brgy_text').select2({
            theme: 'bootstrap',
        });

        $('#anatomical_location, #treatment_given, #aware_healtheducation_list, #iffw_typeofinjury').select2({
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

            //INJURY PARAMS
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
                    $('#injury_address_region_code').append($('<option>', {
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

            $('#injury_address_region_code').change(function (e) { 
                e.preventDefault();
                //Empty and Disable
                $('#injury_address_province_code').empty();
                $("#injury_address_province_code").append('<option value="" selected disabled>Choose...</option>');

                $('#injury_address_muncity_code').empty();
                $("#injury_address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

                //Re-disable Select
                $('#injury_address_muncity_code').prop('disabled', true);
                $('#injury_address_brgy_text').prop('disabled', true);

                //Set Values for Hidden Box
                $('#injury_address_region_text').val($('#injury_address_region_code option:selected').text());

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
                        if($('#injury_address_region_code').val() == val.regCode) {
                            $('#injury_address_province_code').append($('<option>', {
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

            $('#injury_address_province_code').change(function (e) {
                e.preventDefault();
                //Empty and Disable
                $('#injury_address_muncity_code').empty();
                $("#injury_address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

                //Re-disable Select
                $('#injury_address_muncity_code').prop('disabled', false);
                $('#injury_address_brgy_text').prop('disabled', true);

                //Set Values for Hidden Box
                $('#injury_address_province_text').val($('#injury_address_province_code option:selected').text());

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
                        if($('#injury_address_province_code').val() == val.provCode) {
                            $('#injury_address_muncity_code').append($('<option>', {
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

            $('#injury_address_muncity_code').change(function (e) {
                e.preventDefault();
                //Empty and Disable
                $('#injury_address_brgy_text').empty();
                $("#injury_address_brgy_text").append('<option value="" selected disabled>Choose...</option>');

                //Re-disable Select
                $('#injury_address_muncity_code').prop('disabled', false);
                $('#injury_address_brgy_text').prop('disabled', false);

                //Set Values for Hidden Box
                $('#injury_address_muncity_text').val($('#injury_address_muncity_code option:selected').text());

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
                        if($('#injury_address_muncity_code').val() == val.citymunCode) {
                            $('#injury_address_brgy_text').append($('<option>', {
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

            $('#injury_address_region_text').val('REGION IV-A (CALABARZON)');
            $('#injury_address_province_text').val('CAVITE');
            $('#injury_address_muncity_text').val('GENERAL TRIAS');
        });

        $('#reffered_anotherhospital').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#ifReferral').removeClass('d-none');
                $('#nameof_hospital').prop('required', true);
            }
            else {
                $('#ifReferral').addClass('d-none');
                $('#v').prop('required', false);
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
            if($(this).val() == 'FIREWORKS INJURY') {
                $('#afterConsultationDiv').removeClass('d-none');
                $('#disposition_after_consultation_transferred_hospital').prop('required', true);
            }
            else {
                $('#afterConsultationDiv').addClass('d-none');
                $('#disposition_after_consultation_transferred_hospital').prop('required', false);
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
    </script>
@endsection