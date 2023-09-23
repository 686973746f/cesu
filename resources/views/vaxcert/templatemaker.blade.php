@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{route('vaxcert_vquery_templatemakerprocess')}}" id="myForm" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header"><b>VaxCert VASLL Template Maker</b></div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}}" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <div class="alert alert-info" role="alert">
                            <b class="text-danger">Note:</b> Template Maker can only be used in <b>GenTrias LGU Vaccination Sites ONLY</b>. All fields marked with an asterisk (<b class="text-danger">*</b>) are required to be filled-out properly.
                        </div>
                        <div class="form-group">
                            <label for="last_name"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{old('last_name', $pretemp['last_name'])}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name"><span class="text-danger font-weight-bold">*</span>First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{old('first_name', $pretemp['first_name'])}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{old('middle_name', $pretemp['middle_name'])}}" minlength="2" maxlength="50" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="suffix">Suffix <small>(ex. JR, SR, II, III, etc.)</small></label>
                                    <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix', $pretemp['suffix'])}}" minlength="2" maxlength="6" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label for="birthdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{old('birthdate', $pretemp['birthdate'])}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                        </div>
                        <div class="form-group">
                            <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                            <select class="form-control" name="sex" id="sex" required>
                              <option disabled {{(is_null(old('sex', $pretemp['sex']))) ? 'selected' : ''}}>Choose...</option>
                              <option value="M" {{(old('sex', $pretemp['sex']) == 'M') ? 'selected' : ''}}>Male</option>
                              <option value="F" {{(old('sex', $pretemp['sex']) == 'F') ? 'selected' : ''}}>Female</option>
                            </select>
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
                        <div class="form-group">
                            <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                            <select class="form-control" name="address_region_code" id="address_region_code" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                            <select class="form-control" name="address_province_code" id="address_province_code" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                            <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                            </select>
                        </div>
                        <div class="form-group">
                          <label for="barangay"><b class="text-danger">*</b>Barangay</label>
                          <input type="text" class="form-control" name="barangay" id="barangay" value="{{old('barangay', $pretemp['barangay'])}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_no"><span class="text-danger font-weight-bold">*</span>Contact Number</label>
                            <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{old('contact_no', $pretemp['contact_no'])}}" pattern="[0-9]{11}" placeholder="09*********" required>
                        </div>
                        <div id="ifguardian" class="d-none">
                            <div class="form-group">
                                <label for="guardian_name"><b class="text-danger">*</b>Name of Guardian (format: SURNAME, FIRST NAME)</label>
                                <input type="text" class="form-control" name="guardian_name" id="guardian_name" value="{{old('guardian_name', $pretemp['guardian_name'])}}" style="text-transform: uppercase;" pattern="^[^0-9]*,[^0-9]*$">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="indigenous_member"><span class="text-danger font-weight-bold">*</span>Indigenous Member</label>
                          <select class="form-control" name="indigenous_member" id="indigenous_member" required>
                            <option value="N" {{(old('indigenous_member', $pretemp['indigenous_member']) == 'N') ? 'selected' : ''}}>No</option>
                            @foreach($indg_list as $indg)
                            <option value="{{$indg}}" {{(old('indigenous_member', $pretemp['indigenous_member']) == $indg) ? 'selected' : ''}}>{{$indg}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group">
                            <label for="category"><span class="text-danger font-weight-bold">*</span>Category</label>
                            <select class="form-control" name="category" id="category" required>
                                <option disabled {{(is_null(old('category', $pretemp['category']))) ? 'selected' : ''}}>Choose...</option>
                                <option value="A1" {{(old('category', $pretemp['category']) == 'A1') ? 'selected' : ''}}>A1 (Workers in Frontline Health Services)</option>
                                <option value="A1.8" {{(old('category', $pretemp['category']) == 'A1.8') ? 'selected' : ''}}>A1.8 (OFW living within 4 months)</option>
                                <option value="A1.9" {{(old('category', $pretemp['category']) == 'A1') ? 'selected' : ''}}>A1.9 (Family members of A1.1 to A1.3, newly hired priority A1)</option>
                                <option value="ADDITIONAL A1" {{(old('category', $pretemp['category']) == 'ADDITIONAL A1') ? 'selected' : ''}}>Additional A1 (All adult population eligible to be categorized as Priority Group A1)</option>
                                <option value="A2" {{(old('category', $pretemp['category']) == 'A2') ? 'selected' : ''}}>A2 (All Senior Citizens)</option>
                                <option value="A3 - IMMUNOCOMPETENT" {{(old('category', $pretemp['category']) == 'A3 - IMMUNOCOMPETENT') ? 'selected' : ''}}>A3 - Immunocompetent (HIV; 3rd Dose to be considered fully vaccinated)</option>
                                <option value="A3 - IMMUNOCOMPROMISED" {{(old('category', $pretemp['category']) == 'A3 - IMMUNOCOMPROMISED') ? 'selected' : ''}}>A3 - Immunocompromised (Asthma; if approved, will be considered a booster)</option>
                                <option value="EXPANDED A3" {{(old('category', $pretemp['category']) == 'EXPANDED A3') ? 'selected' : ''}}>Expanded A3 (Pregnant with Comorbidity)</option>
                                <option value="A4" {{(old('category', $pretemp['category']) == 'A4') ? 'selected' : ''}}>A4 (Frontline personnel in essential sectors, including uniformed personnel)</option>
                                <option value="A5" {{(old('category', $pretemp['category']) == 'A5') ? 'selected' : ''}}>A5 (Indigent Population)</option>
                                <option value="PEDRIATRIC A3 (12-17 YEARS OLD)" {{(old('category', $pretemp['category']) == 'PEDRIATRIC A3 (12-17 YEARS OLD)') ? 'selected' : ''}}>Pediatric A3 (12-17 years old with comorbidity)</option>
                                <option value="PEDRIATRIC A3 (5-11 YEARS OLD)" {{(old('category', $pretemp['category']) == 'PEDRIATRIC A3 (5-11 YEARS OLD)') ? 'selected' : ''}}>Pediatric A3 (5-11 years old with comorbidity)</option>
                                <option value="ROAP" {{(old('category', $pretemp['category']) == 'ROAP') ? 'selected' : ''}}>ROAP - Rest of Adult Population</option>
                                <option value="ROPP (12-17 YEARS OLD)" {{(old('category', $pretemp['category']) == 'ROPP (12-17 YEARS OLD)') ? 'selected' : ''}}>ROPP - Rest of Pediatric Population (12-17 years old)</option>
                                <option value="ROPP (5-11 YEARS OLD)" {{(old('category', $pretemp['category']) == 'ROPP (5-11 YEARS OLD)') ? 'selected' : ''}}>ROPP - Rest of Pediatric Population (5-11 years old)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comorbidity">Comorbidity</label>
                            <input type="text" class="form-control" name="comorbidity" id="comorbidity" value="{{old('comorbidity', $pretemp['comorbidity'])}}" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="pwd"><span class="text-danger font-weight-bold">*</span>Person with Disability (PWD)</label>
                            <select class="form-control" name="pwd" id="pwd" required>
                              <option value="N" {{(old('pwd', $pretemp['pwd']) == 'N') ? 'selected' : ''}}>No</option>
                              <option value="Y" {{(old('pwd', $pretemp['pwd']) == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="unique_person_id">Vaccination Card ID No./Unique Person ID</label>
                            <input type="text" class="form-control" name="unique_person_id" id="unique_person_id" value="{{old('unique_person_id', $pretemp['unique_person_id'])}}" style="text-transform: uppercase;">
                        </div>
                        <hr>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="process_dose1" id="process_dose1" value="1" {{ old('process_dose1') ? 'checked' : '' }}>Process 1st Dose
                          </label>
                        </div>
                        <div id="dose1_div" class="d-none">
                            <div class="form-group mt-3">
                                <label for="dose1_vaccination_date"><span class="text-danger font-weight-bold">*</span>1ST Dose Date</label>
                                <input type="date" class="form-control" name="dose1_vaccination_date" id="dose1_vaccination_date" value="{{old('dose1_vaccination_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                                <label for="dose1_vaccine_manufacturer_name"><span class="text-danger font-weight-bold">*</span>1ST Dose Manufacturer</label>
                                <select class="form-control" name="dose1_vaccine_manufacturer_name" id="dose1_vaccine_manufacturer_name">
                                    <option disabled {{(is_null(old('dose1_vaccine_manufacturer_name'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach(App\Models\VaxCertConcern::getVaccineBrandsList() as $vl)
                                    <option value="{{$vl['code']}}" {{(old('dose1_vaccine_manufacturer_name') == $vl['code']) ? 'selected' : ''}}>{{$vl['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dose1_bakuna_center_cbcr_id"><span class="text-danger font-weight-bold">*</span>1ST Dose Bakuna Center CBCR ID (GenTrias LGU ONLY)</label>
                                <select class="form-control" name="dose1_bakuna_center_cbcr_id" id="dose1_bakuna_center_cbcr_id">
                                  <option disabled {{(is_null(old('dose1_bakuna_center_cbcr_id'))) ? 'selected' : ''}}>Choose...</option>
                                  @foreach(App\Models\VaxCertConcern::getCbcrList() as $g)
                                  <option value="{{$g['cbcr_code']}}" {{(old('dose1_bakuna_center_cbcr_id') == $g['cbcr_code']) ? 'selected' : ''}}>{{$g['cbcr_name']}} ({{$g['cbcr_code']}})</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dose1_batch_number"><span class="text-danger font-weight-bold">*</span>1ST Dose Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose1_batch_number" id="dose1_batch_number" value="{{old('dose1_batch_number')}}" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                                <label for="dose1_vaccinator_name"><span class="text-danger font-weight-bold">*</span>1ST Dose Name of Vaccinator (format: LAST NAME, FIRST NAME)</label>
                                <input type="text" class="form-control" name="dose1_vaccinator_name" id="dose1_vaccinator_name" value="{{old('dose1_vaccinator_name')}}" pattern="^[^0-9]*,[^0-9]*$" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="form-check" id="dose2_checkbox">
                            <hr>
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="process_dose2" id="process_dose2" value="1" {{ old('process_dose2') ? 'checked' : '' }}>Process 2nd Dose
                            </label>
                        </div>
                        <div id="dose2_div" class="d-none">
                            <div class="form-group mt-3">
                                <label for="dose2_vaccination_date"><span class="text-danger font-weight-bold">*</span>2ND Dose Date</label>
                                <input type="date" class="form-control" name="dose2_vaccination_date" id="dose2_vaccination_date" value="{{old('dose2_vaccination_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                                <label for="dose2_vaccine_manufacturer_name"><span class="text-danger font-weight-bold">*</span>2ND Dose Manufacturer</label>
                                <select class="form-control" name="dose2_vaccine_manufacturer_name" id="dose2_vaccine_manufacturer_name">
                                    <option disabled {{(is_null(old('dose2_vaccine_manufacturer_name'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach(App\Models\VaxCertConcern::getVaccineBrandsList() as $vl)
                                        @if($vl['code'] != 'J&J')
                                        <option value="{{$vl['code']}}" {{(old('dose2_vaccine_manufacturer_name') == $vl['code']) ? 'selected' : ''}}>{{$vl['name']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dose2_bakuna_center_cbcr_id"><span class="text-danger font-weight-bold">*</span>2ND Dose Bakuna Center CBCR ID (GenTrias LGU ONLY)</label>
                                <select class="form-control" name="dose2_bakuna_center_cbcr_id" id="dose2_bakuna_center_cbcr_id">
                                  <option disabled {{(is_null(old('dose2_bakuna_center_cbcr_id'))) ? 'selected' : ''}}>Choose...</option>
                                  @foreach(App\Models\VaxCertConcern::getCbcrList() as $g)
                                  <option value="{{$g['cbcr_code']}}" {{(old('dose2_bakuna_center_cbcr_id') == $g['cbcr_code']) ? 'selected' : ''}}>{{$g['cbcr_name']}} ({{$g['cbcr_code']}})</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dose2_batch_number"><span class="text-danger font-weight-bold">*</span>2ND Dose Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose2_batch_number" id="dose2_batch_number" value="{{old('dose2_batch_number')}}" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                                <label for="dose2_vaccinator_name"><span class="text-danger font-weight-bold">*</span>2ND Dose Name of Vaccinator (format: LAST NAME, FIRST NAME)</label>
                                <input type="text" class="form-control" name="dose2_vaccinator_name" id="dose2_vaccinator_name" value="{{old('dose2_vaccinator_name')}}" pattern="^[^0-9]*,[^0-9]*$" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="form-check">
                            <hr>
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="process_dose3" id="process_dose3" value="1" {{ old('process_dose3') ? 'checked' : '' }}>Process 3rd Dose (Booster)
                            </label>
                        </div>
                        <div id="dose3_div" class="d-none">
                            <div class="form-group">
                                <label for="dose3_vaccination_date"><span class="text-danger font-weight-bold">*</span>3RD Dose Date</label>
                                <input type="date" class="form-control" name="dose3_vaccination_date" id="dose3_vaccination_date" value="{{old('dose3_vaccination_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                                <label for="dose3_vaccine_manufacturer_name"><span class="text-danger font-weight-bold">*</span>3RD Dose Manufacturer</label>
                                <select class="form-control" name="dose3_vaccine_manufacturer_name" id="dose3_vaccine_manufacturer_name">
                                    <option disabled {{(is_null(old('dose3_vaccine_manufacturer_name'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach(App\Models\VaxCertConcern::getVaccineBrandsList() as $vl)
                                    <option value="{{$vl['code']}}" {{(old('dose3_vaccine_manufacturer_name') == $vl['code']) ? 'selected' : ''}}>{{$vl['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dose3_bakuna_center_cbcr_id"><span class="text-danger font-weight-bold">*</span>3RD Dose Bakuna Center CBCR ID (GenTrias LGU ONLY)</label>
                                <select class="form-control" name="dose3_bakuna_center_cbcr_id" id="dose3_bakuna_center_cbcr_id">
                                  <option disabled {{(is_null(old('dose3_bakuna_center_cbcr_id'))) ? 'selected' : ''}}>Choose...</option>
                                  @foreach(App\Models\VaxCertConcern::getCbcrList() as $g)
                                  <option value="{{$g['cbcr_code']}}" {{(old('dose3_bakuna_center_cbcr_id') == $g['cbcr_code']) ? 'selected' : ''}}>{{$g['cbcr_name']}} ({{$g['cbcr_code']}})</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dose3_batch_number"><span class="text-danger font-weight-bold">*</span>3RD Dose Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose3_batch_number" id="dose3_batch_number" value="{{old('dose3_batch_number')}}" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                                <label for="dose3_vaccinator_name"><span class="text-danger font-weight-bold">*</span>3RD Dose Name of Vaccinator (format: LAST NAME, FIRST NAME)</label>
                                <input type="text" class="form-control" name="dose3_vaccinator_name" id="dose3_vaccinator_name" value="{{old('dose3_vaccinator_name')}}" pattern="^[^0-9]*,[^0-9]*$" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="form-check">
                            <hr>
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="process_dose4" id="process_dose4" value="1" {{ old('process_dose4') ? 'checked' : '' }}>Process 4th Dose (2nd Booster)
                            </label>
                        </div>
                        <div id="dose4_div" class="d-none">
                            <div class="form-group">
                                <label for="dose4_vaccination_date"><span class="text-danger font-weight-bold">*</span>4TH Dose Date</label>
                                <input type="date" class="form-control" name="dose4_vaccination_date" id="dose4_vaccination_date" value="{{old('dose4_vaccination_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                                <label for="dose4_vaccine_manufacturer_name"><span class="text-danger font-weight-bold">*</span>4TH Dose Manufacturer</label>
                                <select class="form-control" name="dose4_vaccine_manufacturer_name" id="dose4_vaccine_manufacturer_name">
                                    <option disabled {{(is_null(old('dose4_vaccine_manufacturer_name'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach(App\Models\VaxCertConcern::getVaccineBrandsList() as $vl)
                                    <option value="{{$vl['code']}}" {{(old('dose4_vaccine_manufacturer_name') == $vl['code']) ? 'selected' : ''}}>{{$vl['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dose4_bakuna_center_cbcr_id"><span class="text-danger font-weight-bold">*</span>4TH Dose Bakuna Center CBCR ID (GenTrias LGU ONLY)</label>
                                <select class="form-control" name="dose4_bakuna_center_cbcr_id" id="dose4_bakuna_center_cbcr_id">
                                  <option disabled {{(is_null(old('dose4_bakuna_center_cbcr_id'))) ? 'selected' : ''}}>Choose...</option>
                                  @foreach(App\Models\VaxCertConcern::getCbcrList() as $g)
                                  <option value="{{$g['cbcr_code']}}" {{(old('dose4_bakuna_center_cbcr_id') == $g['cbcr_code']) ? 'selected' : ''}}>{{$g['cbcr_name']}} ({{$g['cbcr_code']}})</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dose4_batch_number"><span class="text-danger font-weight-bold">*</span>4TH Dose Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose4_batch_number" id="dose4_batch_number" value="{{old('dose4_batch_number')}}" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                                <label for="dose4_vaccinator_name"><span class="text-danger font-weight-bold">*</span>4TH Dose Name of Vaccinator (format: LAST NAME, FIRST NAME)</label>
                                <input type="text" class="form-control" name="dose4_vaccinator_name" id="dose4_vaccinator_name" value="{{old('dose4_vaccinator_name')}}" pattern="^[^0-9]*,[^0-9]*$" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-block">Generate .XLSX File</button>
                        <h6 class="text-center mt-3">Next Step: The generated Excel File (.XLSX) should be uploaded into <a href="https://vaslinelist.dict.gov.ph/vas-line-import/approved">VASLL Import Page</a> using the email: <b>cesugentrias.vaxcert@gmail.com</b></h6>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #dose1_vaccine_manufacturer_name, #dose1_bakuna_center_cbcr_id, #dose2_vaccine_manufacturer_name, #dose2_bakuna_center_cbcr_id, #dose3_vaccine_manufacturer_name, #dose3_bakuna_center_cbcr_id, #dose4_vaccine_manufacturer_name, #dose4_bakuna_center_cbcr_id, #category').select2({
        theme: 'bootstrap',
    });

    $(document).ready(function () {
        $("#myForm").submit(function (event) {
            // Check if at least one checkbox is checked
            if (!$("input[name='process_dose1']").is(":checked") &&
                !$("input[name='process_dose2']").is(":checked") &&
                !$("input[name='process_dose3']").is(":checked") &&
                !$("input[name='process_dose4']").is(":checked")) {
                alert("At least one Dose to Process must be checked.");
                event.preventDefault(); // Prevent form submission
            }
        });

        $('#birthdate').change(function(){
            var dob = new Date($(this).val());
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            if (age < 18) {
                $('#ifguardian').removeClass('d-none');
                $('#guardian_name').prop('required', true);
            }
            else {
                $('#ifguardian').addClass('d-none');
                $('#guardian_name').prop('required', false);
            }
        }).trigger('change');
    });
    
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
                selected: (val.regCode == "{{$pretemp['region_json']}}") ? true : false, //default is Region IV-A
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
        //$('#address_brgy_text').prop('disabled', true);

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
                        selected: (val.provCode == "{{$pretemp['province_json']}}") ? true : false, //default for Cavite
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
        //$('#address_brgy_text').prop('disabled', true);

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
                        selected: (val.citymunCode == "{{$pretemp['muni_city_json']}}") ? true : false, //default for General Trias
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

    //$('#address_region_text').val("{{$pretemp['region']}}");

    $('#process_dose1').change(function (e) { 
        e.preventDefault();
        if ($(this).prop("checked")) {
            $('#dose1_div').removeClass('d-none');
            $('#dose1_vaccination_date').prop('required', true);
            $('#dose1_vaccine_manufacturer_name').prop('required', true);
            $('#dose1_bakuna_center_cbcr_id').prop('required', true);
            $('#dose1_batch_number').prop('required', true);
            $('#dose1_vaccinator_name').prop('required', true);
        } else {
            $('#dose1_div').addClass('d-none');
            $('#dose1_vaccination_date').prop('required', false);
            $('#dose1_vaccine_manufacturer_name').prop('required', false);
            $('#dose1_bakuna_center_cbcr_id').prop('required', false);
            $('#dose1_batch_number').prop('required', false);
            $('#dose1_vaccinator_name').prop('required', false);
        }
    }).trigger('change');

    //FOR J&J
    $('#dose1_vaccine_manufacturer_name').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'J&J') {
            $('#dose2_checkbox').addClass('d-none');
            $('#process_dose2').prop('checked', false);
        }
        else {
            $('#dose2_checkbox').removeClass('d-none');
            $('#process_dose2').prop('checked', false);
        }
    });

    $('#process_dose2').change(function (e) { 
        e.preventDefault();
        if ($(this).prop("checked")) {
            $('#dose2_div').removeClass('d-none');
            $('#dose2_vaccination_date').prop('required', true);
            $('#dose2_vaccine_manufacturer_name').prop('required', true);
            $('#dose2_bakuna_center_cbcr_id').prop('required', true);
            $('#dose2_batch_number').prop('required', true);
            $('#dose2_vaccinator_name').prop('required', true);
        } else {
            $('#dose2_div').addClass('d-none');
            $('#dose2_vaccination_date').prop('required', false);
            $('#dose2_vaccine_manufacturer_name').prop('required', false);
            $('#dose2_bakuna_center_cbcr_id').prop('required', false);
            $('#dose2_batch_number').prop('required', false);
            $('#dose2_vaccinator_name').prop('required', false);
        }
    }).trigger('change');

    $('#process_dose3').change(function (e) { 
        e.preventDefault();
        if ($(this).prop("checked")) {
            $('#dose3_div').removeClass('d-none');
            $('#dose3_vaccination_date').prop('required', true);
            $('#dose3_vaccine_manufacturer_name').prop('required', true);
            $('#dose3_bakuna_center_cbcr_id').prop('required', true);
            $('#dose3_batch_number').prop('required', true);
            $('#dose3_vaccinator_name').prop('required', true);
        } else {
            $('#dose3_div').addClass('d-none');
            $('#dose3_vaccination_date').prop('required', false);
            $('#dose3_vaccine_manufacturer_name').prop('required', false);
            $('#dose3_bakuna_center_cbcr_id').prop('required', false);
            $('#dose3_batch_number').prop('required', false);
            $('#dose3_vaccinator_name').prop('required', false);
        }
    }).trigger('change');

    $('#process_dose4').change(function (e) { 
        e.preventDefault();
        if ($(this).prop("checked")) {
            $('#dose4_div').removeClass('d-none');
            $('#dose4_vaccination_date').prop('required', true);
            $('#dose4_vaccine_manufacturer_name').prop('required', true);
            $('#dose4_bakuna_center_cbcr_id').prop('required', true);
            $('#dose4_batch_number').prop('required', true);
            $('#dose4_vaccinator_name').prop('required', true);
        } else {
            $('#dose4_div').addClass('d-none');
            $('#dose4_vaccination_date').prop('required', false);
            $('#dose4_vaccine_manufacturer_name').prop('required', false);
            $('#dose4_bakuna_center_cbcr_id').prop('required', false);
            $('#dose4_batch_number').prop('required', false);
            $('#dose4_vaccinator_name').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection