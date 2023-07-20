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
                <div class="form-group">
                  <label for="chief_complain"><span class="text-danger font-weight-bold">*</span>Chief Complain</label>
                  <input type="text" class="form-control" name="chief_complain" id="chief_complain" value="{{old('chief_complain')}}" required>
                </div>
                <hr>
                <div class="card mb-3">
                    <div class="card-header"><b>Signs and Symptoms</b> (Please check if applicable)</div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="abdominalpain_yn" id="abdominalpain_yn" value="checkedValue">
                              Abdominal Pain
                            </label>
                          </div>
                          <div id="abdominalpain_div" class="d-none">
                            <div class="form-group">
                              <label for="abdominalpain_onset">Abdominal Pain Onset</label>
                              <input type="date" class="form-control" name="abdominalpain_onset" id="abdominalpain_onset" value="{{old('abdominalpain_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="abdominalpain_remarks">Abdominal Pain Remarks</label>
                              <input type="text" class="form-control" name="abdominalpain_remarks" id="abdominalpain_remarks" value="{{old('abdominalpain_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="alteredmentalstatus_yn" id="alteredmentalstatus_yn" value="checkedValue">
                              Altered Mental Status
                            </label>
                          </div>
                          <div class="d-none" id="alteredmentalstatus_div">
                            <div class="form-group">
                              <label for="alteredmentalstatus_onset">ltered Mental Status Onset</label>
                              <input type="date" class="form-control" name="alteredmentalstatus_onset" id="alteredmentalstatus_onset" value="{{old('alteredmentalstatus_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="alteredmentalstatus_remarks">Altered Mental Status Remarks</label>
                              <input type="text" class="form-control" name="alteredmentalstatus_remarks" id="alteredmentalstatus_remarks" value="{{old('alteredmentalstatus_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="animalbite_yn" id="animalbite_yn" value="checkedValue">
                                Animal Bite
                              </label>
                            </div>
                            <div id="animalbite_div" class="d-none">
                              <div class="form-group">
                                <label for="animalbite_onset">Animal Bite Onset</label>
                                <input type="date" class="form-control" name="animalbite_onset" id="animalbite_onset" value="{{old('animalbite_onset')}}">
                              </div>
                              <div class="form-group">
                                <label for="animalbite_remarks">Animal Bite Remarks</label>
                                <input type="text" class="form-control" name="animalbite_remarks" id="animalbite_remarks" value="{{old('animalbite_remarks')}}">
                              </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="cough_yn" id="cough_yn" value="checkedValue">
                              Cough
                            </label>
                          </div>
                          <div id="cough_div" class="d-none">
                            <div class="form-group">
                              <label for="cough_onset">Cough Onset</label>
                              <input type="date" class="form-control" name="cough_onset" id="cough_onset" value="{{old('cough_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="cough_remarks">Cough Remarks</label>
                              <input type="text" class="form-control" name="cough_remarks" id="cough_remarks" value="{{old('cough_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="colds_yn" id="colds_yn" value="checkedValue">
                              Colds/Coryza
                            </label>
                          </div>
                          <div id="colds_div" class="d-none">
                            <div class="form-group">
                              <label for="colds_onset">Colds Onset</label>
                              <input type="date" class="form-control" name="colds_onset" id="colds_onset" value="{{old('colds_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="colds_remarks">Colds Remarks</label>
                              <input type="text" class="form-control" name="colds_remarks" id="colds_remarks" value="{{old('colds_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="conjunctivitis_yn" id="conjunctivitis_yn" value="checkedValue">
                              Conjunctivitis/Red Eyes
                            </label>
                          </div>
                          <div id="conjunctivitis_div" class="d-none">
                            <div class="form-group">
                              <label for="conjunctivitis_onset">Conjunctivitis Onset</label>
                              <input type="date" class="form-control" name="conjunctivitis_onset" id="conjunctivitis_onset" value="{{old('conjunctivitis_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="conjunctivitis_remarks">Conjunctivitis Remarks</label>
                              <input type="text" class="form-control" name="conjunctivitis_remarks" id="conjunctivitis_remarks" value="{{old('conjunctivitis_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="anorexia_yn" id="anorexia_yn" value="checkedValue">
                              Eating Disorder (Anorexia)
                            </label>
                          </div>
                          <div id="anorexia_div" class="d-none">
                            <div class="form-group">
                              <label for="anorexia_onset">Eating Disorder Onset</label>
                              <input type="date" class="form-control" name="anorexia_onset" id="anorexia_onset" value="{{old('anorexia_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="anorexia_remarks">Eating Disorder Remarks</label>
                              <input type="text" class="form-control" name="anorexia_remarks" id="anorexia_remarks" value="{{old('anorexia_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="fatigue_yn" id="fatigue_yn" value="checkedValue">
                              Fatigue
                            </label>
                          </div>
                          <div id="fatigue_div" class="d-none">
                            <div class="form-group">
                              <label for="fatigue_onset">Fatigue Onset</label>
                              <input type="date" class="form-control" name="fatigue_onset" id="fatigue_onset" value="{{old('fatigue_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="fatigue_remarks">Fatigue Remarks</label>
                              <input type="text" class="form-control" name="fatigue_remarks" id="fatigue_remarks" value="{{old('fatigue_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="fever_yn" id="fever_yn" value="checkedValue">
                              Fever
                            </label>
                          </div>
                          <div id="fever_div" class="d-none">
                            <div class="form-group">
                              <label for="fever_onset">Fever Onset</label>
                              <input type="date" class="form-control" name="fever_onset" id="fever_onset" value="{{old('fever_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="fever_remarks">Fever Remarks</label>
                              <input type="text" class="form-control" name="fever_remarks" id="fever_remarks" value="{{old('fever_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="headache_yn" id="headache_yn" value="checkedValue">
                              Headache
                            </label>
                          </div>
                          <div id="headache_div" class="d-none">
                            <div class="form-group">
                              <label for="headache_onset">Headache Onset</label>
                              <input type="date" class="form-control" name="headache_onset" id="headache_onset" value="{{old('headache_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="headache_remarks">Headache Remarks</label>
                              <input type="text" class="form-control" name="headache_remarks" id="headache_remarks" value="{{old('headache_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="jointpain_yn" id="jointpain_yn" value="checkedValue">
                                Joint Pain
                              </label>
                            </div>
                            <div id="jointpain_div" class="d-none">
                              <div class="form-group">
                                <label for="jointpain_onset">Joint Pain Onset</label>
                                <input type="date" class="form-control" name="jointpain_onset" id="jointpain_onset" value="{{old('jointpain_onset')}}">
                              </div>
                              <div class="form-group">
                                <label for="jointpain_remarks">Joint Pain Remarks</label>
                                <input type="text" class="form-control" name="jointpain_remarks" id="jointpain_remarks" value="{{old('jointpain_remarks')}}">
                              </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="jaundice_yn" id="jaundice_yn" value="checkedValue">
                              Jaundice
                            </label>
                          </div>
                          <div id="jaundice_div" class="d-none">
                            <div class="form-group">
                              <label for="jaundice_onset">Jaundice Onset</label>
                              <input type="date" class="form-control" name="jaundice_onset" id="jaundice_onset" value="{{old('jaundice_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="jaundice_remarks">Jaundice Remarks</label>
                              <input type="text" class="form-control" name="jaundice_remarks" id="jaundice_remarks" value="{{old('jaundice_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="lossofsmell_yn" id="lossofsmell_yn" value="checkedValue">
                              Loss of Smell (Anosmia)
                            </label>
                          </div>
                          <div id="lossofsmell_div" class="d-none">
                            <div class="form-group">
                              <label for="lossofsmell_onset">Loss of Smell Onset</label>
                              <input type="date" class="form-control" name="lossofsmell_onset" id="lossofsmell_onset" value="{{old('lossofsmell_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="lossofsmell_remarks">Loss of Smell Remarks</label>
                              <input type="text" class="form-control" name="lossofsmell_remarks" id="lossofsmell_remarks" value="{{old('lossofsmell_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="lossoftaste_yn" id="lossoftaste_yn" value="checkedValue">
                              Loss of Taste (Ageusia)
                            </label>
                          </div>
                          <div id="lossoftaste_div" class="d-none">
                            <div class="form-group">
                              <label for="lossoftaste_onset">Loss of Taste Onset</label>
                              <input type="date" class="form-control" name="lossoftaste_onset" id="lossoftaste_onset" value="{{old('lossoftaste_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="lossoftaste_remarks">Loss of Taste Remarks</label>
                              <input type="text" class="form-control" name="lossoftaste_remarks" id="lossoftaste_remarks" value="{{old('lossoftaste_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="musclepain_yn" id="musclepain_yn" value="checkedValue">
                              Muscle Pain
                            </label>
                          </div>
                          <div id="musclepain_div" class="d-none">
                            <div class="form-group">
                              <label for="musclepain_onset">Muscle Pain Onset</label>
                              <input type="date" class="form-control" name="musclepain_onset" id="musclepain_onset" value="{{old('musclepain_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="musclepain_remarks">Muscle Pain Remarks</label>
                              <input type="text" class="form-control" name="musclepain_remarks" id="musclepain_remarks" value="{{old('musclepain_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="nausea_yn" id="nausea_yn" value="checkedValue">
                              Nausea
                            </label>
                          </div>
                          <div id="nausea_div" class="d-none">
                            <div class="form-group">
                              <label for="nausea_onset">Nausea Onset</label>
                              <input type="date" class="form-control" name="nausea_onset" id="nausea_onset" value="{{old('nausea_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="nausea_remarks">Nausea Remarks</label>
                              <input type="text" class="form-control" name="nausea_remarks" id="nausea_remarks" value="{{old('nausea_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="paralysis_yn" id="paralysis_yn" value="checkedValue">
                              Paralysis
                            </label>
                          </div>
                          <div id="paralysis_div" class="d-none">
                            <div class="form-group">
                              <label for="paralysis_onset">Paralysis Onset</label>
                              <input type="date" class="form-control" name="paralysis_onset" id="paralysis_onset" value="{{old('paralysis_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="paralysis_remarks">Paralysis Remarks</label>
                              <input type="text" class="form-control" name="paralysis_remarks" id="paralysis_remarks" value="{{old('paralysis_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="rash_yn" id="rash_yn" value="checkedValue">
                              Rash
                            </label>
                          </div>
                          <div id="rash_div" class="d-none">
                            <div class="form-group">
                              <label for="rash_onset">Rash Onset</label>
                              <input type="date" class="form-control" name="rash_onset" id="rash_onset" value="{{old('rash_onset')}}">
                            </div>
                            <div class="form-group" >
                              <label for="rash_remarks">Rash Remarks</label>
                              <input type="text" class="form-control" name="rash_remarks" id="rash_remarks" value="{{old('rash_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="mouthsore_yn" id="mouthsore_yn" value="checkedValue">
                              Sore Mouth
                            </label>
                          </div>
                          <div id="mouthsore_div" class="d-none">
                            <div class="form-group">
                              <label for="mouthsore_onset">Mouth Sore Onset</label>
                              <input type="date" class="form-control" name="mouthsore_onset" id="mouthsore_onset" value="{{old('mouthsore_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="mouthsore_remarks">Mouth Sore Remarks</label>
                              <input type="text" class="form-control" name="mouthsore_remarks" id="mouthsore_remarks" value="{{old('mouthsore_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="sorethroat_yn" id="sorethroat_yn" value="checkedValue">
                              Sore Throat
                            </label>
                          </div>
                          <div id="sorethroat_div" class="d-none">
                            <div class="form-group">
                              <label for="sorethroat_onset">Sore Throat Onset</label>
                              <input type="date" class="form-control" name="sorethroat_onset" id="sorethroat_onset" value="{{old('sorethroat_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="sorethroat_remarks">Sore Throat Remarks</label>
                              <input type="text" class="form-control" name="sorethroat_remarks" id="sorethroat_remarks" value="{{old('sorethroat_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="dyspnea_yn" id="dyspnea_yn" value="checkedValue">
                              Shortness of Breath (Dyspnea)
                            </label>
                          </div>
                          <div id="dyspnea_div" class="d-none">
                            <div class="form-group">
                              <label for="dyspnea_onset">Shortness of Breath Onset</label>
                              <input type="date" class="form-control" name="dyspnea_onset" id="dyspnea_onset" value="{{old('dyspnea_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="dyspnea_remarks">Shortness of Breath Remarks</label>
                              <input type="text" class="form-control" name="dyspnea_remarks" id="dyspnea_remarks" value="{{old('dyspnea_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="vomiting_yn" id="vomiting_yn" value="checkedValue">
                              Vomiting
                            </label>
                          </div>
                          <div id="vomiting_div" class="d-none">
                            <div class="form-group">
                              <label for="vomiting_onset">Vomiting Onset</label>
                              <input type="date" class="form-control" name="vomiting_onset" id="vomiting_onset" value="{{old('vomiting_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="vomiting_remarks">Vomiting Remarks</label>
                              <input type="text" class="form-control" name="vomiting_remarks" id="vomiting_remarks" value="{{old('vomiting_remarks')}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="weaknessofextremities_yn" id="weaknessofextremities_yn" value="checkedValue">
                              Weakness of Extremities
                            </label>
                          </div>
                          <div id="weaknessofextremities_div" class="d-none">
                            <div class="form-group">
                              <label for="weaknessofextremities_onset">Weakness of Extremities Onset</label>
                              <input type="date" class="form-control" name="weaknessofextremities_onset" id="weaknessofextremities_onset" value="{{old('weaknessofextremities_onset')}}">
                            </div>
                            <div class="form-group">
                              <label for="weaknessofextremities_remarks">Weakness of Extremities Remarks</label>
                              <input type="text" class="form-control" name="weaknessofextremities_remarks" id="weaknessofextremities_remarks" value="{{old('weaknessofextremities_remarks')}}">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="bigmessage">Findings/Remarks</label>
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
      //$('#fever_remarks').prop('required', true);
    }
    else {
      $('#fever_div').addClass('d-none');
      //$('#fever_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#rash_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#rash_div').removeClass('d-none');
      //$('#rash_remarks').prop('required', true);
    }
    else {
      $('#rash_div').addClass('d-none');
      //$('#rash_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#cough_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#cough_div').removeClass('d-none');
      //$('#cough_remarks').prop('required', true);
    }
    else {
      $('#cough_div').addClass('d-none');
      //$('#cough_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#colds_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#colds_div').removeClass('d-none');
      //$('#colds_remarks').prop('required', true);
    }
    else {
      $('#colds_div').addClass('d-none');
      //$('#colds_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#conjunctivitis_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#conjunctivitis_div').removeClass('d-none');
      //$('#conjunctivitis_remarks').prop('required', true);
    }
    else {
      $('#conjunctivitis_div').addClass('d-none');
      //$('#conjunctivitis_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#mouthsore_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#mouthsore_div').removeClass('d-none');
      //$('#mouthsore_remarks').prop('required', true);
    }
    else {
      $('#mouthsore_div').addClass('d-none');
      //$('#mouthsore_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#lossoftaste_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#lossoftaste_div').removeClass('d-none');
      //$('#lossoftaste_remarks').prop('required', true);
    }
    else {
      $('#lossoftaste_div').addClass('d-none');
      //$('#lossoftaste_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#lossofsmell_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#lossofsmell_div').removeClass('d-none');
      //$('#lossofsmell_remarks').prop('required', true);
    }
    else {
      $('#lossofsmell_div').addClass('d-none');
      //$('#lossofsmell_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#headache_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#headache_div').removeClass('d-none');
      //$('#headache_remarks').prop('required', true);
    }
    else {
      $('#headache_div').addClass('d-none');
      //$('#headache_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#jointpain_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#jointpain_div').removeClass('d-none');
      //$('#jointpain_remarks').prop('required', true);
    }
    else {
      $('#jointpain_div').addClass('d-none');
      //$('#jointpain_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#musclepain_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#musclepain_div').removeClass('d-none');
      //$('#musclepain_remarks').prop('required', true);
    }
    else {
      $('#musclepain_div').addClass('d-none');
      //$('#musclepain_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#diarrhea_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#diarrhea_div').removeClass('d-none');
      //$('#diarrhea_remarks').prop('required', true);
    }
    else {
      $('#diarrhea_div').addClass('d-none');
      //$('#diarrhea_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#abdominalpain_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#abdominalpain_div').removeClass('d-none');
      //$('#abdominalpain_remarks').prop('required', true);
    }
    else {
      $('#abdominalpain_div').addClass('d-none');
      //$('#abdominalpain_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#vomiting_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#vomiting_div').removeClass('d-none');
      //$('#vomiting_remarks').prop('required', true);
    }
    else {
      $('#vomiting_div').addClass('d-none');
      //$('#vomiting_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#weaknessofextremities_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#weaknessofextremities_div').removeClass('d-none');
      //$('#weaknessofextremities_remarks').prop('required', true);
    }
    else {
      $('#weaknessofextremities_div').addClass('d-none');
      //$('#weaknessofextremities_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#paralysis_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#paralysis_div').removeClass('d-none');
      //$('#paralysis_remarks').prop('required', true);
    }
    else {
      $('#paralysis_div').addClass('d-none');
      //$('#paralysis_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#alteredmentalstatus_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#alteredmentalstatus_div').removeClass('d-none');
      //$('#alteredmentalstatus_remarks').prop('required', true);
    }
    else {
      $('#alteredmentalstatus_div').addClass('d-none');
      //$('#alteredmentalstatus_remarks').prop('required', false);
    }
  }).trigger('change');

  $('#animalbite_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#animalbite_div').removeClass('d-none');
    }
    else {
      $('#animalbite_div').addClass('d-none');
    }
  }).trigger('change');

  $('#anorexia_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#anorexia_div').removeClass('d-none');
    }
    else {
      $('#anorexia_div').addClass('d-none');
    }
  }).trigger('change');

  $('#fatigue_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#fatigue_div').removeClass('d-none');
    }
    else {
      $('#fatigue_div').addClass('d-none');
    }
  }).trigger('change');

  $('#dyspnea_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#dyspnea_div').removeClass('d-none');
    }
    else {
      $('#dyspnea_div').addClass('d-none');
    }
  }).trigger('change');

  $('#jaundice_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#jaundice_div').removeClass('d-none');
    }
    else {
      $('#jaundice_div').addClass('d-none');
    }
  }).trigger('change');

  $('#nausea_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#nausea_div').removeClass('d-none');
    }
    else {
      $('#nausea_div').addClass('d-none');
    }
  }).trigger('change');

  $('#sorethroat_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#sorethroat_div').removeClass('d-none');
    }
    else {
      $('#sorethroat_div').addClass('d-none');
    }
  }).trigger('change');
</script>
@endsection