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
                @include('pidsr.inhouse_edcs.patient_defaults1')
                <div class="row">
                    <div class="col-md-6">
                        <div id="additional_admitted_div">
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
                            <select class="form-control" name="previous_hospitalization" id="previous_hospitalization">
                                <option value="" disabled {{(is_null(old('previous_hospitalization'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('previous_hospitalization') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('previous_hospitalization') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id="previous_hospitalization_div">
                            <div class="form-group">
                                <label for="prevhosp_date"><b class="text-danger">*</b>Date of Previous Hospitalization</label>
                                <input type="date" class="form-control" name="prevhosp_date" id="prevhosp_date" value="{{old('prevhosp_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="vomiting"><span class="text-danger font-weight-bold">*</span>Vomiting</label>
                            <select class="form-control" name="vomiting" id="vomiting">
                                <option value="" disabled {{(is_null(old('vomiting'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('vomiting') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('vomiting') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="vomiting_div">
                            <div class="form-group">
                                <label for="vomiting_date"><b class="text-danger">*</b>Date of onset of vomiting</label>
                                <input type="date" class="form-control" name="vomiting_date" id="vomiting_date" value="{{old('vomiting_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fever"><span class="text-danger font-weight-bold">*</span>Fever</label>
                            <select class="form-control" name="fever" id="fever">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="more_diarrheacases"><span class="text-danger font-weight-bold">*</span>Are there two or more diarrhea cases?</label>
                            <select class="form-control" name="more_diarrheacases" id="more_diarrheacases">
                                <option value="" disabled {{(is_null(old('more_diarrheacases'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('more_diarrheacases') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('more_diarrheacases') == 'N') ? 'selected' : ''}}>No</option>
                                <option value="U" {{(old('more_diarrheacases') == 'U') ? 'selected' : ''}}>Unknown</option>
                            </select>
                        </div>
                        <div id="more_diarrhea_div">
                            <h6>Where (check all that apply)</h6>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="mdiarrhea[]" id="mdiarrhea_1" value="COMMUNITY">
                                Community
                              </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                  <input type="checkbox" class="form-check-input" name="mdiarrhea[]" id="mdiarrhea_1" value="SCHOOL">
                                  School
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="mdiarrhea[]" id="mdiarrhea_1" value="HOUSEHOLD">
                                Household
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="received_rotavaccine"><span class="text-danger font-weight-bold">*</span>Received Rotavirus Vaccine?</label>
                            <select class="form-control" name="received_rotavaccine" id="received_rotavaccine">
                                <option value="" disabled {{(is_null(old('received_rotavaccine'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('received_rotavaccine') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('received_rotavaccine') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id="rotavaccine_div">
                            <div class="form-group">
                              <label for="noofdose">Total Doses Received</label>
                              <input type="number" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                              <small id="helpId" class="form-text text-muted">Help text</small>
                            </div>
                        </div>
                    </div>
                </div>
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
</script>
@endsection