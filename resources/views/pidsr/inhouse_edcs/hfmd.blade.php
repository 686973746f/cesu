@extends('layouts.app')

@section('content')
<form action="{{route('edcs_addcase_store', request()->input('disease'))}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <b>
                    <div>{{$f->facility_name}}</div>
                    <div>Report HFMD Case</div>
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
                @include('pidsr.inhouse_edcs.patient_defaults_investigator')
                <div class="card mb-3">
                    <div class="card-header"><b>CLINICAL INFORMATION</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="Fever" id="Fever" value="Y">
                                    Fever
                                  </label>
                                </div>
                                <div class="d-none mt-3" id="fever_div">
                                  <div class="form-group">
                                    <label for="FeverOnset"><b class="text-danger">*</b>Fever Onset</label>
                                    <input type="date" class="form-control" name="FeverOnset" id="FeverOnset" value="{{old('FeverOnset')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                                  </div>
                                </div>
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="RashSores" id="RashSores" value="Y">
                                    Rash
                                  </label>
                                </div>
                                <div class="d-none mt-3" id="rash_div">
                                  <div class="form-group">
                                    <label for="SoreOnset"><b class="text-danger">*</b>Rash Onset</label>
                                    <input type="date" class="form-control" name="SoreOnset" id="SoreOnset" value="{{old('SoreOnset')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input rash-location" type="checkbox" id="Palms" name="Palms" value="Y">
                                    <label class="form-check-label" for="Palms">Palms</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input rash-location" type="checkbox" id="Fingers" name="Fingers" value="Y">
                                    <label class="form-check-label" for="Fingers">Fingers</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input rash-location" type="checkbox" id="FootSoles" name="FootSoles" value="Y">
                                    <label class="form-check-label" for="FootSoles">Soles of feet</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input rash-location" type="checkbox" id="Buttocks" name="Buttocks" value="Y">
                                    <label class="form-check-label" for="Buttocks">Buttocks</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input rash-location" type="checkbox" id="MouthUlcers" name="MouthUlcers" value="Y">
                                    <label class="form-check-label" for="MouthUlcers">Mouth ulcers</label>
                                  </div>
                                  <div class="form-group">
                                      <label for="Pain"><b class="text-danger">*</b>Painful?</label>
                                      <select class="form-control" name="Pain" id="Pain">
                                          <option value="" disabled {{(is_null(old('Pain'))) ? 'selected' : ''}}>Choose...</option>
                                          <option value="Y" {{(old('Pain') == 'Y') ? 'selected' : ''}}>Yes</option>
                                          <option value="N" {{(old('Pain') == 'N') ? 'selected' : ''}}>No</option>
                                      </select>
                                  </div>
                                  <h6 class="mt-3">Characteristic</h6>
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input rash-characteristic" name="Maculopapular" id="Maculopapular" value="Y">
                                      Maculopapular
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input rash-characteristic" name="Papulovesicular" id="Papulovesicular" value="Y">
                                      Papulovesicular
                                    </label>
                                  </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="Anorexia" id="Anorexia" value="Y">
                                    Poor/loss of appetite
                                  </label>
                                </div>
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="BM" id="BM" value="Y">
                                    Body malaise
                                  </label>
                                </div>
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="SoreThroat" id="SoreThroat" value="Y">
                                    Sore throat
                                  </label>
                                </div>
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="NausVom" id="NausVom" value="Y">
                                    Nausea & vomiting
                                  </label>
                                </div>
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="DiffBreath" id="DiffBreath" value="Y">
                                    Difficulty of breathing
                                  </label>
                                </div>
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="Paralysis" id="Paralysis" value="Y">
                                    Acute Flaccid Paralysis
                                  </label>
                                </div>
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="MeningLes" id="MeningLes" value="Y">
                                    Meningeal Irritation
                                  </label>
                                </div>

                                <div class="form-group mt-3">
                                    <label for="OthSymptoms">Other symptoms, specify</label>
                                    <input type="text" class="form-control" name="OthSymptoms" id="OthSymptoms" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="AnyComp" id="AnyComp" value="Y">
                                    Are there any complications?
                                  </label>
                                </div>
                                <div class="d-none form-group mt-3" id="compli_div">
                                  <label for="Complic8"><b class="text-danger">*</b>Specify Complications</label>
                                  <input type="text" class="form-control" name="Complic8" id="Complic8" style="text-transform: uppercase;">
                                </div>
                                <div class="form-group mt-3">
                                  <label for="WFDiag">Working/Final Diagnosis</label>
                                  <input type="text" class="form-control" name="WFDiag" id="WFDiag" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Travel"><b class="text-danger">*</b>Is there a history of travel within 12 weeks to an area with ongoing epidemic of HFMD or EV Disease?</label>
                            <select class="form-control" name="Travel" id="Travel" required>
                                <option value="" disabled {{(is_null(old('Travel'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('Travel') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('Travel') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="OtherCase"><b class="text-danger">*</b>Are there other known HFMD cases in the community?</label>
                            <select class="form-control" name="OtherCase" id="OtherCase" required>
                                <option value="" disabled {{(is_null(old('Travel'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('OtherCase') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('OtherCase') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                      <h6>Where did exposure probably occur?</h6>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="probexposure_1" name="ProbExposure[]" value="DAY CARE">
                        <label class="form-check-label" for="probexposure_1">Day care</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="probexposure_2" name="ProbExposure[]" value="HOME">
                        <label class="form-check-label" for="probexposure_2">Home</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="probexposure_3" name="ProbExposure[]" value="COMMUNITY">
                        <label class="form-check-label" for="probexposure_3">Community</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="probexposure_4" name="ProbExposure[]" value="HEALTHCARE FACILITIES">
                        <label class="form-check-label" for="probexposure_4">Healthcare Facilities</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="probexposure_5" name="ProbExposure[]" value="SCHOOL">
                        <label class="form-check-label" for="probexposure_5">School</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="probexposure_6" name="ProbExposure[]" value="DORMITORY">
                        <label class="form-check-label" for="probexposure_6">Dormitory</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="probexposure_7" name="ProbExposure[]" value="OTHERS">
                        <label class="form-check-label" for="probexposure_7">Others</label>
                      </div>

                      <div class="form-group mt-3">
                          <label for="Outcome"><b class="text-danger">*</b>Outcome</label>
                          <select class="form-control" name="Outcome" id="Outcome" required>
                              <option value="" disabled {{(is_null(old('Outcome'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="A" {{(old('Outcome') == 'A') ? 'selected' : ''}}>Alive</option>
                              <option value="D" {{(old('Outcome') == 'Y') ? 'selected' : ''}}>Died</option>
                          </select>
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
  $('form').on('submit', function(e) {
      if ($('.rash-location:checked').length === 0 && $('#RashSores').is(':checked')) {
        e.preventDefault();
        alert('Please check at least one Rash Location on Body.');
      }

      if($('.rash-characteristic:checked').length === 0 && $('#RashSores').is(':checked')) {
        e.preventDefault();
        alert('Please check at least one Rash Characteristic.');
      }
  });

  $('#Fever').change(function (e) { 
    e.preventDefault();
    if($(this).is(':checked')) {
      $('#fever_div').removeClass('d-none');
      $('#FeverOnset').prop('required', true);
    }
    else {
      $('#fever_div').addClass('d-none');
      $('#FeverOnset').prop('required', false);
    }
  });

  $('#RashSores').change(function (e) { 
    e.preventDefault();
    if($(this).is(':checked')) {
      $('#rash_div').removeClass('d-none');
      $('#SoreOnset').prop('required', true);
      $('#Pain').prop('required', true);
    }
    else {
      $('#rash_div').addClass('d-none');
      $('#SoreOnset').prop('required', false);
      $('#Pain').prop('required', false);
    }
  });

  $('#AnyComp').change(function (e) { 
    e.preventDefault();
    if($(this).is(':checked')) {
      $('#compli_div').removeClass('d-none');
      $('#Complic8').prop('required', true);
    }
    else {
      $('#compli_div').addClass('d-none');
      $('#Complic8').prop('required', false);
    }
  });
</script>
@endsection