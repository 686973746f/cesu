@extends('layouts.app')

@section('content')
<form action="{{route('edcs_addcase_store', request()->input('disease'))}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <b>
                    <div>{{$f->facility_name}}</div>
                    <div>Report Influenza-Like Illness Case</div>
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
                        <div class="form-group">
                            <label for="Admitted"><span class="text-danger font-weight-bold">*</span>Admitted?</label>
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
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="DOnset"><b class="text-danger">*</b>Date Onset of Illness (Kailan nagsimula ang sintomas)</label>
                            <input type="date" class="form-control" name="DOnset" id="DOnset" value="{{old('DOnset')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fever"><span class="text-danger font-weight-bold">*</span>Has Fever (Lagnat)?</label>
                            <select class="form-control" name="fever" id="fever" required>
                                <option value="" disabled {{(is_null(old('fever'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('fever') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('fever') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id="fever_div" class="d-none">
                            <div class="form-group">
                                <label for="fever_temperature"><b class="text-danger">*</b>Highest Temperature recorded during Fever (in Celcius)</label>
                                <input type="number" min="37" max="45" step="0.1" class="form-control" name="fever_temperature" id="fever_temperature" value="{{old('fever_temperature')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cough"><span class="text-danger font-weight-bold">*</span>Has Cough (Ubo)?</label>
                            <select class="form-control" name="cough" id="cough" required>
                                <option value="" disabled {{(is_null(old('fever'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('cough') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('cough') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sore_throat"><span class="text-danger font-weight-bold">*</span>Has Sore Throat (Masakit ang lalamunan)?</label>
                            <select class="form-control" name="sore_throat" id="sore_throat" required>
                                <option value="" disabled {{(is_null(old('sore_throat'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('sore_throat') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('sore_throat') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="travelabroad_history"><span class="text-danger font-weight-bold">*</span>Has History of Travel Abroad?</label>
                            <select class="form-control" name="travelabroad_history" id="travelabroad_history" required>
                                <option value="" disabled {{(is_null(old('travelabroad_history'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('travelabroad_history') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('travelabroad_history') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id="travelabroad_div" class="d-none">
                            <div class="form-group">
                                <label for="specify_travel"><b class="text-danger">*</b>Specify Place/Country</label>
                                <input type="text" class="form-control" name="specify_travel" id="specify_travel" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="received_vaccination"><span class="text-danger font-weight-bold">*</span>Received Influenza Vaccine?</label>
                            <select class="form-control" name="received_vaccination" id="received_vaccination" required>
                                <option value="" disabled {{(is_null(old('received_vaccination'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('received_vaccination') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('received_vaccination') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id="received_vaccination_div" class="d-none">
                            <div class="form-group">
                                <label for="date_vaccine"><b class="text-danger">*</b>Date of Influenza Vaccination</label>
                                <input type="date" class="form-control" name="date_vaccine" id="date_vaccine" value="{{old('date_vaccine')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
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
    $('#Admitted').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#hospitalizedDiv').removeClass('d-none');
            $('#sys_hospitalized_name').prop('required', true);
            $('#sys_hospitalized_datestart').prop('required', true);
            $('#sys_hospitalized_dateend').prop('required', true);
        }
        else {
            $('#hospitalizedDiv').addClass('d-none');
            $('#sys_hospitalized_name').prop('required', false);
            $('#sys_hospitalized_datestart').prop('required', false);
            $('#sys_hospitalized_dateend').prop('required', false);
        }
    }).trigger('change');

    $('#travelabroad_history').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#travelabroad_div').removeClass('d-none');
            $('#specify_travel').prop('required', true);
        }
        else {
            $('#travelabroad_div').addClass('d-none');
            $('#specify_travel').prop('required', false);
        }
    }).trigger('change');

    $('#received_vaccination').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#received_vaccination_div').removeClass('d-none');
            $('#date_vaccine').prop('required', true);
        }
        else {
            $('#received_vaccination_div').addClass('d-none');
            $('#date_vaccine').prop('required', false);
        }
    }).trigger('change');

    $('#fever').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#fever_div').removeClass('d-none');
            $('#fever_temperature').prop('required', true);
        }
        else {
            $('#fever_div').addClass('d-none');
            $('#fever_temperature').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection