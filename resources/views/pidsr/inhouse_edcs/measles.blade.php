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
                @include('pidsr.inhouse_edcs.patient_defaults1')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_of_parentcaregiver" class="form-label">Name of Parent/Caregiver</label>
                            <input type="text" class="form-control" id="name_of_parentcaregiver" name="name_of_parentcaregiver" style="text-transform: uppercase;" value="{{old('name_of_parentcaregiver')}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="parent_contactno">Contact Number</label>
                            <input type="text" class="form-control" id="parent_contactno" name="parent_contactno" value="{{old('parent_contactno')}}" pattern="[0-9]{11}" placeholder="09*********">
                        </div>
                    </div>
                </div>
                @include('pidsr.inhouse_edcs.patient_defaults_investigator')
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="fever" id="fever" value="Y">
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
                            <input type="checkbox" class="form-check-input" name="Rash" id="Rash" value="Y">
                            Rash
                            </label>
                        </div>
                        <div class="d-none mt-3" id="rash_div">
                            <div class="form-group">
                                <label for="DONSET"><b class="text-danger">*</b>Rash Onset</label>
                                <input type="date" class="form-control" name="DONSET" id="DONSET" value="{{old('DONSET')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="Cough" id="Cough" value="Y">
                            Cough
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="KoplikSpot" id="KoplikSpot" value="Y">
                            Koplik Sign
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="RunnyNose" id="RunnyNose" value="Y">
                            Colds/Runny nose/Coryza
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="RedEyes" id="RedEyes" value="Y">
                            Red Eyes/Conjunctivitis
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="ArthritisArthralgia" id="ArthritisArthralgia" value="Y">
                            Arthralgia/Arthritis
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="SwoLympNod" id="SwoLympNod" value="Y">
                            Swollen lymphatic nodules
                            </label>
                        </div>
                        <div class="d-none mt-3" id="lymp_div">
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="SwoLympNod" id="SwoLympNod" value="Y">
                                    Cervical
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="SwoLympNod" id="SwoLympNod" value="Y">
                                    Post-auricular
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="SwoLympNod" id="SwoLympNod" value="Y">
                                    Sub-occipital
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="SwoLympNod" id="SwoLympNod" value="Y">
                                    Others
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="SwoLympNod" id="SwoLympNod" value="Y">
                                Are there any complications?
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="OthSymptoms" class="form-label">Other Symptoms</label>
                            <input type="text" class="form-control" id="OthSymptoms" name="OthSymptoms" style="text-transform: uppercase;" value="{{old('OthSymptoms')}}">
                        </div>
                        <div class="form-group">
                            <label for="wfdiagnosis" class="form-label">Working/Final Diagnosis</label>
                            <input type="text" class="form-control" id="wfdiagnosis" name="wfdiagnosis" style="text-transform: uppercase;" value="{{old('wfdiagnosis')}}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-check">
                    <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="SwoLympNod" id="SwoLympNod" value="Y">
                        Patient received measles-containing vaccine (MCV)?
                    </label>
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
$('#fever').change(function (e) { 
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

$('#Rash').change(function (e) { 
    e.preventDefault();
    if($(this).is(':checked')) {
      $('#rash_div').removeClass('d-none');
      $('#DONSET').prop('required', true);
    }
    else {
      $('#rash_div').addClass('d-none');
      $('#DONSET').prop('required', false);
    }
});
</script>
@endsection