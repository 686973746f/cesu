@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('syndromic_storeRecord', $patient->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>New ITR - Step 3/3</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="consulation_date">Date and Time of Consultation</label>
                            <input type="datetime-local" class="form-control" name="consulation_date" id="consulation_date" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="temperature"><span class="text-danger font-weight-bold">*</span>Temperature</label>
                            <input type="number" step="0.1" pattern="\d+(\.\d{1})?" class="form-control" name="temperature" id="temperature" value="{{old('temperature', '36.3')}}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bloodpressure"><span class="text-danger font-weight-bold">*</span>Blood Pressure</label>
                            <input type="text" class="form-control" name="bloodpressure" id="bloodpressure" value="{{old('bloodpressure')}}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="weight"><span class="text-danger font-weight-bold">*</span>Weight (in kilograms)</label>
                            <input type="number" step="0.1" pattern="\d+(\.\d{1})?" class="form-control" name="weight" id="weight" value="{{old('weight')}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="respiratoryrate">Respiratory Rate (RR)</label>
                            <input type="text" class="form-control" name="respiratoryrate" id="respiratoryrate" value="{{old('respiratoryrate')}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pulserate">Pulse Rate (PR)</label>
                            <input type="text" class="form-control" name="pulserate" id="pulserate" value="{{old('pulserate')}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="saturationperioxigen">Saturation of Oxygen (SpO2)</label>
                            <input type="text" class="form-control" name="saturationperioxigen" id="saturationperioxigen" value="{{old('saturationperioxigen')}}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card mb-3">
                    <div class="card-header"><b>Signs and Symptoms</b> (Please check if applicable)</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="fever_yn" id="fever_yn" value="checkedValue">
                                    Fever
                                  </label>
                                </div>
                                <div class="form-group d-none" id="fever_div">
                                  <label for="fever_remarks"><b style="color: red">*</b>Fever Remarks</label>
                                  <input type="text" class="form-control" name="fever_remarks" id="fever_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="rash_yn" id="rash_yn" value="checkedValue">
                                    Rash
                                  </label>
                                </div>
                                <div class="form-group d-none" id="rash_div">
                                  <label for="rash_remarks"><b style="color: red">*</b>Rash Remarks</label>
                                  <input type="text" class="form-control" name="rash_remarks" id="rash_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="cough_yn" id="cough_yn" value="checkedValue">
                                    Cough
                                  </label>
                                </div>
                                <div class="form-group d-none" id="cough_div">
                                  <label for="cough_remarks"><b style="color: red">*</b>Cough Remarks</label>
                                  <input type="text" class="form-control" name="cough_remarks" id="cough_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="colds_yn" id="colds_yn" value="checkedValue">
                                    Colds
                                  </label>
                                </div>
                                <div class="form-group d-none" id="colds_div">
                                  <label for="colds_remarks"><b style="color: red">*</b>Colds Remarks</label>
                                  <input type="text" class="form-control" name="colds_remarks" id="colds_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="conjunctivitis_yn" id="conjunctivitis_yn" value="checkedValue">
                                    Conjunctivitis
                                  </label>
                                </div>
                                <div class="form-group d-none" id="conjunctivitis_div">
                                  <label for="conjunctivitis_remarks"><b style="color: red">*</b>Conjunctivitis Remarks</label>
                                  <input type="text" class="form-control" name="conjunctivitis_remarks" id="conjunctivitis_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="mouthsore_yn" id="mouthsore_yn" value="checkedValue">
                                    Mouth Sore
                                  </label>
                                </div>
                                <div class="form-group d-none" id="mouthsore_div">
                                  <label for="mouthsore_remarks"><b style="color: red">*</b>Mouth Sore Remarks</label>
                                  <input type="text" class="form-control" name="mouthsore_remarks" id="mouthsore_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="lossoftaste_yn" id="lossoftaste_yn" value="checkedValue">
                                    Loss of Taste
                                  </label>
                                </div>
                                <div class="form-group d-none" id="lossoftaste_div">
                                  <label for="lossoftaste_remarks"><b style="color: red">*</b>Loss of Taste Remarks</label>
                                  <input type="text" class="form-control" name="lossoftaste_remarks" id="lossoftaste_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="lossofsmell_yn" id="lossofsmell_yn" value="checkedValue">
                                    Loss of Smell
                                  </label>
                                </div>
                                <div class="form-group d-none" id="lossofsmell_div">
                                  <label for="lossofsmell_remarks"><b style="color: red">*</b>Loss of Smell Remarks</label>
                                  <input type="text" class="form-control" name="lossofsmell_remarks" id="lossofsmell_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="headache_yn" id="headache_yn" value="checkedValue">
                                    Headache
                                  </label>
                                </div>
                                <div class="form-group d-none" id="headache_div">
                                  <label for="headache_remarks"><b style="color: red">*</b>Headache Remarks</label>
                                  <input type="text" class="form-control" name="headache_remarks" id="headache_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="jointpain_yn" id="jointpain_yn" value="checkedValue">
                                    Joint Pain
                                  </label>
                                </div>
                                <div class="form-group d-none" id="jointpain_div">
                                  <label for="jointpain_remarks"><b style="color: red">*</b>Joint Pain Remarks</label>
                                  <input type="text" class="form-control" name="jointpain_remarks" id="jointpain_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="musclepain_yn" id="musclepain_yn" value="checkedValue">
                                    Muscle Pain
                                  </label>
                                </div>
                                <div class="form-group d-none" id="musclepain_div">
                                  <label for="musclepain_remarks"><b style="color: red">*</b>Muscle Pain Remarks</label>
                                  <input type="text" class="form-control" name="musclepain_remarks" id="musclepain_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="diarrhea_yn" id="diarrhea_yn" value="checkedValue">
                                    Diarrhea
                                  </label>
                                </div>
                                <div class="form-group d-none" id="diarrhea_div">
                                  <label for="diarrhea_remarks"><b style="color: red">*</b>Diarrhea Remarks</label>
                                  <input type="text" class="form-control" name="diarrhea_remarks" id="diarrhea_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="abdominalpain_yn" id="abdominalpain_yn" value="checkedValue">
                                    Abdominal Pain
                                  </label>
                                </div>
                                <div class="form-group d-none" id="abdominalpain_div">
                                  <label for="abdominalpain_remarks"><b style="color: red">*</b>Abdominal Pain Remarks</label>
                                  <input type="text" class="form-control" name="abdominalpain_remarks" id="abdominalpain_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="vomiting_yn" id="vomiting_yn" value="checkedValue">
                                    Vomiting
                                  </label>
                                </div>
                                <div class="form-group d-none" id="vomiting_div">
                                  <label for="vomiting_remarks"><b style="color: red">*</b>Vomiting Remarks</label>
                                  <input type="text" class="form-control" name="vomiting_remarks" id="vomiting_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="weaknessofextremities_yn" id="weaknessofextremities_yn" value="checkedValue">
                                    Weakness of Extemities
                                  </label>
                                </div>
                                <div class="form-group d-none" id="weaknessofextremities_div">
                                  <label for="weaknessofextremities_remarks"><b style="color: red">*</b>Weakness of Extemities Remarks</label>
                                  <input type="text" class="form-control" name="weaknessofextremities_remarks" id="weaknessofextremities_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="paralysis_yn" id="paralysis_yn" value="checkedValue">
                                    Paralysis
                                  </label>
                                </div>
                                <div class="form-group d-none" id="paralysis_div">
                                  <label for="paralysis_remarks"><b style="color: red">*</b>Paralysis Remarks</label>
                                  <input type="text" class="form-control" name="paralysis_remarks" id="paralysis_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="alteredmentalstatus_yn" id="alteredmentalstatus_yn" value="checkedValue">
                                    Altered Mental Status
                                  </label>
                                </div>
                                <div class="form-group d-none" id="alteredmentalstatus_div">
                                  <label for="alteredmentalstatus_remarks"><b style="color: red">*</b>Altered Mental Status Remarks</label>
                                  <input type="text" class="form-control" name="alteredmentalstatus_remarks" id="alteredmentalstatus_remarks">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="animalbite_yn" id="animalbite_yn" value="checkedValue">
                                    Animal Bite
                                  </label>
                                </div>
                                <div class="form-group d-none" id="animalbite_div">
                                  <label for="animalbite_remarks"><b style="color: red">*</b>Animal Bite Remarks</label>
                                  <input type="text" class="form-control" name="animalbite_remarks" id="animalbite_remarks">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="bigmessage">Findings</label>
                  <textarea class="form-control" name="bigmessage" id="bigmessage" rows="3"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>

<script>
  $('#fever_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#fever_div').removeClass('d-none');
      $('#fever_remarks').prop('required', true);
    }
    else {
      $('#fever_div').addClass('d-none');
      $('#fever_remarks').prop('required', false);
    }
  });

  $('#rash_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#rash_div').removeClass('d-none');
      $('#rash_remarks').prop('required', true);
    }
    else {
      $('#rash_div').addClass('d-none');
      $('#rash_remarks').prop('required', false);
    }
  });

  $('#cough_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#cough_div').removeClass('d-none');
      $('#cough_remarks').prop('required', true);
    }
    else {
      $('#cough_div').addClass('d-none');
      $('#cough_remarks').prop('required', false);
    }
  });

  $('#colds_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#colds_div').removeClass('d-none');
      $('#colds_remarks').prop('required', true);
    }
    else {
      $('#colds_div').addClass('d-none');
      $('#colds_remarks').prop('required', false);
    }
  });

  $('#conjunctivitis_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#conjunctivitis_div').removeClass('d-none');
      $('#conjunctivitis_remarks').prop('required', true);
    }
    else {
      $('#conjunctivitis_div').addClass('d-none');
      $('#conjunctivitis_remarks').prop('required', false);
    }
  });

  $('#mouthsore_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#mouthsore_div').removeClass('d-none');
      $('#mouthsore_remarks').prop('required', true);
    }
    else {
      $('#mouthsore_div').addClass('d-none');
      $('#mouthsore_remarks').prop('required', false);
    }
  });

  $('#lossoftaste_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#lossoftaste_div').removeClass('d-none');
      $('#lossoftaste_remarks').prop('required', true);
    }
    else {
      $('#lossoftaste_div').addClass('d-none');
      $('#lossoftaste_remarks').prop('required', false);
    }
  });

  $('#lossofsmell_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#lossofsmell_div').removeClass('d-none');
      $('#lossofsmell_remarks').prop('required', true);
    }
    else {
      $('#lossofsmell_div').addClass('d-none');
      $('#lossofsmell_remarks').prop('required', false);
    }
  });

  $('#headache_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#headache_div').removeClass('d-none');
      $('#headache_remarks').prop('required', true);
    }
    else {
      $('#headache_div').addClass('d-none');
      $('#headache_remarks').prop('required', false);
    }
  });

  $('#jointpain_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#jointpain_div').removeClass('d-none');
      $('#jointpain_remarks').prop('required', true);
    }
    else {
      $('#jointpain_div').addClass('d-none');
      $('#jointpain_remarks').prop('required', false);
    }
  });

  $('#musclepain_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#musclepain_div').removeClass('d-none');
      $('#musclepain_remarks').prop('required', true);
    }
    else {
      $('#musclepain_div').addClass('d-none');
      $('#musclepain_remarks').prop('required', false);
    }
  });

  $('#diarrhea_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#diarrhea_div').removeClass('d-none');
      $('#diarrhea_remarks').prop('required', true);
    }
    else {
      $('#diarrhea_div').addClass('d-none');
      $('#diarrhea_remarks').prop('required', false);
    }
  });

  $('#abdominalpain_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#abdominalpain_div').removeClass('d-none');
      $('#abdominalpain_remarks').prop('required', true);
    }
    else {
      $('#abdominalpain_div').addClass('d-none');
      $('#abdominalpain_remarks').prop('required', false);
    }
  });

  $('#vomiting_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#vomiting_div').removeClass('d-none');
      $('#vomiting_remarks').prop('required', true);
    }
    else {
      $('#vomiting_div').addClass('d-none');
      $('#vomiting_remarks').prop('required', false);
    }
  });

  $('#weaknessofextremities_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#weaknessofextremities_div').removeClass('d-none');
      $('#weaknessofextremities_remarks').prop('required', true);
    }
    else {
      $('#weaknessofextremities_div').addClass('d-none');
      $('#weaknessofextremities_remarks').prop('required', false);
    }
  });

  $('#paralysis_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#paralysis_div').removeClass('d-none');
      $('#paralysis_remarks').prop('required', true);
    }
    else {
      $('#paralysis_div').addClass('d-none');
      $('#paralysis_remarks').prop('required', false);
    }
  });

  $('#alteredmentalstatus_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#alteredmentalstatus_div').removeClass('d-none');
      $('#alteredmentalstatus_remarks').prop('required', true);
    }
    else {
      $('#alteredmentalstatus_div').addClass('d-none');
      $('#alteredmentalstatus_remarks').prop('required', false);
    }
  });

  $('#animalbite_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#animalbite_div').removeClass('d-none');
      $('#animalbite_remarks').prop('required', true);
    }
    else {
      $('#animalbite_div').addClass('d-none');
      $('#animalbite_remarks').prop('required', false);
    }
  });
</script>
@endsection