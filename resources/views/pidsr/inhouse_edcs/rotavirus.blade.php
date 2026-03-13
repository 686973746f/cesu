@extends('layouts.app')

@section('content')
<form action="{{route('edcs_addcase_store', request()->input('disease'))}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <b>
                    <div>{{$f->facility_name}}</div>
                    <div>Report Rotavirus Case</div>
                </b>
            </div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <b>Note:</b> All fields marked with <b class="text-danger">*</b> are required. By filling out this form, the patient agrees to the collection of their data in accordance to the Data Privacy Act of 2012 and Republic Act 11332.
                </div>
                @if(!auth()->check())
                <div class="form-group d-none">
                    <label for="facility_code">Facility Code</label>
                    <input type="text" class="form-control" name="facility_code" id="facility_code" value="{{request()->input('facility_code')}}" readonly>
                  </div>
                @else
                <div class="form-group">
                    <label for="facility_list"><b class="text-danger">*</b>Override Facility</label>
                    <select class="form-control" name="facility_list" id="facility_list" required>
                        @foreach($facility_list as $f)
                        <option value="{{$f->id}}" {{(old('facility_list', auth()->user()->itr_facility_id) == $f->id) ? 'selected' : ''}}>{{$f->facility_name}}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                @include('pidsr.inhouse_edcs.patient_defaults')
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><b>III. CLINICAL DATA</b></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="DOnset"><b class="text-danger">*</b><span id="onset_text">Date Onset of Illness (Kailan nagsimula ang sintomas)</span></label>
                                    <input type="date" class="form-control" name="DOnset" id="DOnset" value="{{old('DOnset')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
                                </div>
        
                                <div class="form-group">
                                    <label for="Admitted"><span class="text-danger font-weight-bold">*</span><span id="admitted_select_text">Admitted?</span></label>
                                    <select class="form-control" name="Admitted" id="Admitted" required>
                                        <option value="" disabled {{(is_null(old('Admitted'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('Admitted') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('Admitted') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
        
                                <div id="hospitalizedDiv" class="d-none">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="sys_hospitalized_name"><b class="text-danger">*</b>Name of Hospital/Health Facility</label>
                                                <input type="text" class="form-control" name="sys_hospitalized_name" id="sys_hospitalized_name" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="DAdmit"><b class="text-danger">*</b>Date Admitted</label>
                                                <input type="date" class="form-control" name="DAdmit" id="DAdmit" value="{{old('DAdmit')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sys_hospitalized_dateend">Date Discharged</label>
                                                <input type="date" class="form-control" name="sys_hospitalized_dateend" id="sys_hospitalized_dateend" value="{{old('sys_hospitalized_dateend')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
        
                                <div id="additional_admitted_div" class="d-none">
                                    <div class="form-group">
                                        <label for="received_iv"><span class="text-danger font-weight-bold">*</span>Did patient receive IV rehydration therapy while at the ER?</label>
                                        <select class="form-control" name="received_iv" id="received_iv">
                                            <option value="" disabled {{(is_null(old('received_iv'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="Y" {{(old('received_iv') == 'Y') ? 'selected' : ''}}>Yes</option>
                                            <option value="N" {{(old('received_iv') == 'N') ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="previous_hospitalization"><span class="text-danger font-weight-bold">*</span>Did patient have previous hospitalization due to diarrhea?</label>
                                    <select class="form-control" name="previous_hospitalization" id="previous_hospitalization" required>
                                        <option value="" disabled {{(is_null(old('previous_hospitalization'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('previous_hospitalization') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('previous_hospitalization') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div id="previous_hospitalization_div" class="d-none">
                                    <div class="form-group">
                                        <label for="prevhosp_date"><b class="text-danger">*</b>Date of Previous Hospitalization</label>
                                        <input type="date" class="form-control" name="prevhosp_date" id="prevhosp_date" value="{{old('prevhosp_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="vomiting"><span class="text-danger font-weight-bold">*</span>Vomiting</label>
                                    <select class="form-control" name="vomiting" id="vomiting" required>
                                        <option value="" disabled {{(is_null(old('vomiting'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('vomiting') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('vomiting') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div id="vomiting_div" class="d-none">
                                    <div class="form-group">
                                        <label for="vomiting_date"><b class="text-danger">*</b>Date of onset of vomiting</label>
                                        <input type="date" class="form-control" name="vomiting_date" id="vomiting_date" value="{{old('vomiting_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dehydration_degree"><span class="text-danger font-weight-bold">*</span>Degree of Dehydration</label>
                                    <select class="form-control" name="dehydration_degree" id="dehydration_degree" required>
                                        <option value="" disabled {{(is_null(old('dehydration_degree'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="NO DEHYDRATION" {{(old('dehydration_degree') == 'NO DEHYDRATION') ? 'selected' : ''}}>No Dehydration</option>
                                        <option value="SOME DEHYDRATION" {{(old('dehydration_degree') == 'SOME DEHYDRATION') ? 'selected' : ''}}>Some Dehydration</option>
                                        <option value="SEVERE DEHYDRATION" {{(old('dehydration_degree') == 'SEVERE DEHYDRATION') ? 'selected' : ''}}>Severe Dehydration</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fever"><span class="text-danger font-weight-bold">*</span>Fever</label>
                                    <select class="form-control" name="fever" id="fever" required>
                                        <option value="" disabled {{(is_null(old('fever'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('fever') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('fever') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                  <label for="admitting_diagnosis">Admitting Diagnosis</label>
                                  <input type="text" class="form-control" name="admitting_diagnosis" id="admitting_diagnosis" style="text-transform: uppercase">
                                </div>
                                <div class="form-group">
                                    <label for="final_diagnosis">Final Diagnosis</label>
                                    <input type="text" class="form-control" name="final_diagnosis" id="final_diagnosis" style="text-transform: uppercase">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-header"><b>IV. EPIDEMIOLOGIC</b></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="more_diarrheacases"><span class="text-danger font-weight-bold">*</span>Are there two or more diarrhea cases?</label>
                                    <select class="form-control" name="more_diarrheacases" id="more_diarrheacases" required>
                                        <option value="" disabled {{(is_null(old('more_diarrheacases'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('more_diarrheacases') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('more_diarrheacases') == 'N') ? 'selected' : ''}}>No</option>
                                        <option value="U" {{(old('more_diarrheacases') == 'U') ? 'selected' : ''}}>Unknown</option>
                                    </select>
                                </div>
                                <div id="more_diarrhea_div" class="d-none mb-3">
                                    <h6>Where (check all that apply)</h6>
                                    <div class="form-check">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="mdiarrhea[]" id="mdiarrhea_1" value="COMMUNITY">
                                        Community
                                      </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                          <input type="checkbox" class="form-check-input" name="mdiarrhea[]" id="mdiarrhea_2" value="SCHOOL">
                                          School
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="mdiarrhea[]" id="mdiarrhea_3" value="HOUSEHOLD">
                                        Household
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header"><b>V. IMMUNIZATION HISTORY</b></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="received_rotavaccine"><span class="text-danger font-weight-bold">*</span>Received Rotavirus Vaccine?</label>
                                    <select class="form-control" name="received_rotavaccine" id="received_rotavaccine" required>
                                        <option value="" disabled {{(is_null(old('received_rotavaccine'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('received_rotavaccine') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('received_rotavaccine') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                                <div id="rotavaccine_div" class="d-none">
                                    <div class="form-group">
                                      <label for="rv_dose"><b class="text-danger">*</b>Total Doses Received</label>
                                      <input type="number" class="form-control" name="rv_dose" id="rv_dose">
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rv_dose1_date"><b class="text-danger">*</b>Date first dose received</label>
                                                <input type="date" class="form-control" name="rv_dose1_date" id="rv_dose1_date" value="{{old('rv_dose1_date')}}" max="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rv_dose2_date"><b class="text-danger">*</b>Date last dose received</label>
                                                <input type="date" class="form-control" name="rv_dose2_date" id="rv_dose2_date" value="{{old('rv_dose2_date')}}" max="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stool_collected"><b class="text-danger">*</b>Stool Collected</label>
                            <select class="form-control" name="stool_collected" id="stool_collected" required>
                                <option value="" disabled {{(is_null(old('stool_collected'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('stool_collected') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('stool_collected') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>

                        <div id="stool_div" class="d-none">
                            <div class="form-group">
                                <label for="stool_date"><b class="text-danger">*</b>Date taken</label>
                                <input type="date" class="form-control" name="stool_date" id="stool_date" value="{{old('stool_date')}}" max="{{date('Y-m-d')}}">
                            </div>

                            <div class="form-group">
                                <label for="stool_ritm_date">Date sent to RITM</label>
                                <input type="date" class="form-control" name="stool_ritm_date" id="stool_ritm_date" value="{{old('stool_ritm_date')}}" max="{{date('Y-m-d')}}">
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="classification"><b class="text-danger">*</b>Classification</label>
                            <select class="form-control" name="classification" id="classification" required>
                                <option value="S" {{(old('classification') == 'S') ? 'selected' : ''}}>Suspect</option>
                                <option value="C" {{(old('classification') == 'C') ? 'selected' : ''}}>Confirmed</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="outcome"><b class="text-danger">*</b>Outcome</label>
                            <select class="form-control" name="outcome" id="outcome" required>
                                <option value="" disabled {{(is_null(old('outcome'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="A" {{(old('outcome') == 'A') ? 'selected' : ''}}>Alive</option>
                                <option value="D" {{(old('outcome') == 'D') ? 'selected' : ''}}>Died</option>
                            </select>
                        </div>

                        <div id="outcome_div" class="d-none">
                            <div class="form-group">
                                <label for="outcome_date"><b class="text-danger">*</b><span id="outcome_text"></span></label>
                                <input type="date" class="form-control" name="outcome_date" id="outcome_date" value="{{old('outcome_date')}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                  <label for="system_remarks">Remarks</label>
                  <textarea class="form-control" name="system_remarks" id="system_remarks" rows="3"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">Submit (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

@include('pidsr.inhouse_edcs.patient_defaults_js')

<script>
    $('#onset_text').text('Date of Onset of Diarrhea');
    $('#admitted_select_text').text('Admitted at the wards for Diarrhea?');

    $('#Admitted').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#hospitalizedDiv').removeClass('d-none');
            $('#sys_hospitalized_name').prop('required', true);
            $('#DAdmit').prop('required', true);

            $('#additional_admitted_div').removeClass('d-none');
            $('#received_iv').prop('required', true);
        }
        else {
            $('#hospitalizedDiv').addClass('d-none');
            $('#sys_hospitalized_name').prop('required', false);
            $('#DAdmit').prop('required', false);

            $('#additional_admitted_div').addClass('d-none');
            $('#received_iv').prop('required', false);
        }
    }).trigger('change');

    $('#previous_hospitalization').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#previous_hospitalization_div').removeClass('d-none');
            $('#prevhosp_date').prop('required', true);
        }
        else {
            $('#previous_hospitalization_div').addClass('d-none');
            $('#prevhosp_date').prop('required', false);
        }
    }).trigger('change');

    $('#vomiting').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#vomiting_div').removeClass('d-none');
            $('#vomiting_date').prop('required', true);
        }
        else {
            $('#vomiting_div').addClass('d-none');
            $('#vomiting_date').prop('required', false);
        }
    }).trigger('change');

    $('#received_rotavaccine').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#rotavaccine_div').removeClass('d-none');
            $('#rv_dose').prop('required', true);
            $('#rv_dose1_date').prop('required', true);
            $('#rv_dose2_date').prop('required', true);
        }
        else {
            $('#rotavaccine_div').addClass('d-none');
            $('#rv_dose').prop('required', false);
            $('#rv_dose1_date').prop('required', false);
            $('#rv_dose2_date').prop('required', false);
        }
    }).trigger('change');

    $('#more_diarrheacases').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#more_diarrhea_div').removeClass('d-none');
        }
        else {
            $('#more_diarrhea_div').addClass('d-none');
        }
    }).trigger('change');

    $('#outcome').change(function (e) { 
        e.preventDefault();
        $('#outcome_date').prop('required', false);
        var outcome = $(this).val();

        if(outcome == 'D') {
            $('#outcome_text').text('Date of Death');
            $('#outcome_div').removeClass('d-none');
            $('#outcome_date').prop('required', true);
        }
        else if(outcome == 'A') {
            $('#outcome_text').text('Date of Discharge');
            $('#outcome_div').removeClass('d-none');
            $('#outcome_date').prop('required', true);
        }
    }).trigger('change');

    $('#stool_collected').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#stool_div').removeClass('d-none');
            $('#stool_date').prop('required', true);
        }
        else {
            $('#stool_div').addClass('d-none');
            $('#stool_date').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection