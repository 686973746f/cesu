@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('syndromic_newRecord', $patient->id)}}" method="POST">
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
                            <input type="text" class="form-control" name="bloodpressure" id="bloodpressure" value="{{old('bloodpressure')}}">
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
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="rash_yn" id="rash_yn" value="checkedValue">
                                    Rash
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="cough_yn" id="cough_yn" value="checkedValue">
                                    Cough
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="colds_yn" id="colds_yn" value="checkedValue">
                                    Colds
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="conjunctivitis_yn" id="conjunctivitis_yn" value="checkedValue">
                                    Conjunctivitis
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="mouthsore_yn" id="mouthsore_yn" value="checkedValue">
                                    Mouth Sore
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="lossoftaste_yn" id="lossoftaste_yn" value="checkedValue">
                                    Loss of Taste
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="lossofsmell_yn" id="lossofsmell_yn" value="checkedValue">
                                    Loss of Smell
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="headache_yn" id="headache_yn" value="checkedValue">
                                    Headache
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="jointpain_yn" id="jointpain_yn" value="checkedValue">
                                    Joint Pain
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="musclepain_yn" id="musclepain_yn" value="checkedValue">
                                    Muscle Pain
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="diarrhea_yn" id="diarrhea_yn" value="checkedValue">
                                    Diarrhea
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="abdominalpain_yn" id="abdominalpain_yn" value="checkedValue">
                                    Abdominal Pain
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="vomiting_yn" id="vomiting_yn" value="checkedValue">
                                    Vomiting
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="weaknessofextremities_yn" id="weaknessofextremities_yn" value="checkedValue">
                                    Weakness of Extemities
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="paralysis_yn" id="paralysis_yn" value="checkedValue">
                                    Paralysis
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="alteredmentalstatus_yn" id="alteredmentalstatus_yn" value="checkedValue">
                                    Altered Mental Status
                                  </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="animalbite_yn" id="animalbite_yn" value="checkedValue">
                                    Animal Bite
                                  </label>
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
@endsection