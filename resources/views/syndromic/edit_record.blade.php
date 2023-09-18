@extends('layouts.app')

@section('content')
<div class="container">
  <div class="text-right mb-3">
    <a href="{{route('syndromic_newRecord', $d->syndromic_patient->id)}}" class="btn btn-success">New ITR/New Check-up</a>
    <a href="{{route('syndromic_downloadItr', $d->id)}}" class="btn btn-primary">Download ITR Form</a>
    @if($d->outcome != 'DIED')
      @if(in_array('ITR_ENCODER', auth()->user()->getPermissions()) || in_array('ITR_ADMIN', auth()->user()->getPermissions()) || in_array('GLOBAL_ADMIN', auth()->user()->getPermissions()))
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateMedCert">Generate Medical Certificate</button>
      @endif
    @endif
  </div>
    <form action="{{route('syndromic_updateRecord', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
              <div class="d-flex justify-content-between">
                <div><b>Edit ITR</b> | <b>ITR ID:</b> {{$d->id}} | <b>Patient:</b> <a href="{{route('syndromic_viewPatient', $d->syndromic_patient->id)}}">{{$d->syndromic_patient->getName()}}</a></div>
                <div><b>Date Encoded:</b> {{date('m/d/Y h:i A', strtotime($d->created_at))}} by {{$d->user->name}} @if(!is_null($d->updated_by)) | <b>Date Updated:</b> {{date('m/d/Y h:i A', strtotime($d->updated_at))}} by {{$d->getUpdatedBy->name}}@endif</div>
              </div>
              
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <hr>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="consultation_date">Date and Time of Consultation</label>
                            <input type="datetime-local" class="form-control" name="consultation_date" id="consultation_date" value="{{old('consultation_date', \Carbon\Carbon::parse($d->consultation_date)->format('Y-m-d\TH:i'))}}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="temperature"><span class="text-danger font-weight-bold">*</span>Temperature</label>
                            <input type="number" step="0.1" pattern="\d+(\.\d{1})?" class="form-control" name="temperature" id="temperature" value="{{old('temperature', $d->temperature)}}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bloodpressure"><b id="bp_asterisk" class="text-danger d-none">*</b>Blood Pressure</label>
                            <input type="text" class="form-control" name="bloodpressure" id="bloodpressure" value="{{old('bloodpressure', $d->bloodpressure)}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="weight"><span class="text-danger font-weight-bold">*</span>Weight (in kilograms)</label>
                            <input type="number" step="0.1" pattern="\d+(\.\d{1})?" class="form-control" name="weight" id="weight" value="{{old('weight', $d->weight)}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="height">Height (in Centimeters)</label>
                        <input type="number" class="form-control" name="height" id="height" value="{{old('height', $d->height)}}">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="respiratoryrate">Respiratory Rate (RR)</label>
                        <input type="text" class="form-control" name="respiratoryrate" id="respiratoryrate" value="{{old('respiratoryrate', $d->respiratoryrate)}}">
                    </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="pulserate">Pulse Rate (PR)</label>
                        <input type="text" class="form-control" name="pulserate" id="pulserate" value="{{old('pulserate', $d->pulserate)}}">
                      </div>
                        <!--
                        <div class="form-group">
                            <label for="saturationperioxigen">Saturation of Oxygen (SpO2)</label>
                            <input type="text" class="form-control" name="saturationperioxigen" id="saturationperioxigen" value="{{old('saturationperioxigen', $d->saturationperioxigen)}}">
                        </div>
                        -->
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="chief_complain"><span class="text-danger font-weight-bold">*</span>Chief Complain</label>
                      <input type="text" class="form-control" name="chief_complain" id="chief_complain" value="{{old('chief_complain', $d->chief_complain)}}" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="is_hospitalized"><span class="text-danger font-weight-bold">*</span>Patient was Admitted?</label>
                      <select class="form-control" name="is_hospitalized" id="is_hospitalized" required>
                        <option value="Y" {{(old('is_hospitalized') == 'Y' || $d->is_hospitalized == 1) ? 'selected' : ''}}>YES</option>
                        <option value="N" {{(old('is_hospitalized') == 'N' || $d->is_hospitalized == 0) ? 'selected' : ''}}>NO</option>
                      </select>
                    </div>
                    <div id="if_hospitalized" class="d-none">
                      <div class="form-group">
                        <label for="hospital_name"><span class="text-danger font-weight-bold">*</span>Name of Hospital</label>
                        <input type="text" class="form-control" name="hospital_name" id="hospital_name" value="{{old('hospital_name', $d->hospital_name)}}">
                      </div>
                      <div class="form-group">
                        <label for="date_admitted"><span class="text-danger font-weight-bold">*</span>Date Admitted</label>
                        <input type="date" class="form-control" name="date_admitted" id="date_admitted" value="{{old('date_admitted', $d->date_admitted)}}" max="{{date('Y-m-d')}}">
                      </div>
                      <div class="form-group">
                        <label for="date_released">Date Released</label>
                        <input type="date" class="form-control" name="date_released" id="date_released" value="{{old('date_released', $d->date_released)}}" max="{{date('Y-m-d')}}">
                      </div>
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
                              <input type="checkbox" class="form-check-input" name="abdominalpain_yn" id="abdominalpain_yn" value="checkedValue" {{(old('abdominalpain_yn') || $d->abdominalpain == 1) ? 'checked' : ''}}>
                              Abdominal Pain
                            </label>
                          </div>
                          <div id="abdominalpain_div" class="d-none">
                            <div class="form-group">
                              <label for="abdominalpain_onset">Abdominal Pain Onset</label>
                              <input type="date" class="form-control" name="abdominalpain_onset" id="abdominalpain_onset" value="{{old('abdominalpain_onset', $d->abdominalpain_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="abdominalpain_remarks">Abdominal Pain Remarks</label>
                              <input type="text" class="form-control" name="abdominalpain_remarks" id="abdominalpain_remarks" value="{{old('abdominalpain_remarks', $d->abdominalpain_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="alteredmentalstatus_yn" id="alteredmentalstatus_yn" value="checkedValue" {{(old('alteredmentalstatus_yn') || $d->alteredmentalstatus == 1)  ? 'checked' : ''}}>
                              Altered Mental Status
                            </label>
                          </div>
                          <div class="d-none" id="alteredmentalstatus_div">
                            <div class="form-group">
                              <label for="alteredmentalstatus_onset">ltered Mental Status Onset</label>
                              <input type="date" class="form-control" name="alteredmentalstatus_onset" id="alteredmentalstatus_onset" value="{{old('alteredmentalstatus_onset', $d->alteredmentalstatus_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="alteredmentalstatus_remarks">Altered Mental Status Remarks</label>
                              <input type="text" class="form-control" name="alteredmentalstatus_remarks" id="alteredmentalstatus_remarks" value="{{old('alteredmentalstatus_remarks', $d->alteredmentalstatus_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="animalbite_yn" id="animalbite_yn" value="checkedValue" {{(old('animalbite_yn') || $d->animalbite == 1) ? 'checked' : ''}}>
                                Animal Bite
                              </label>
                            </div>
                            <div id="animalbite_div" class="d-none">
                              <div class="form-group">
                                <label for="animalbite_onset">Animal Bite Onset</label>
                                <input type="date" class="form-control" name="animalbite_onset" id="animalbite_onset" value="{{old('animalbite_onset', $d->animalbite_onset)}}" max="{{date('Y-m-d')}}">
                              </div>
                              <div class="form-group">
                                <label for="animalbite_remarks">Animal Bite Remarks</label>
                                <input type="text" class="form-control" name="animalbite_remarks" id="animalbite_remarks" value="{{old('animalbite_remarks', $d->animalbite_remarks)}}">
                              </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="cough_yn" id="cough_yn" value="checkedValue" {{(old('cough_yn') || $d->cough == 1) ? 'checked' : ''}}>
                              Cough
                            </label>
                          </div>
                          <div id="cough_div" class="d-none">
                            <div class="form-group">
                              <label for="cough_onset">Cough Onset</label>
                              <input type="date" class="form-control" name="cough_onset" id="cough_onset" value="{{old('cough_onset', $d->cough_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="cough_remarks">Cough Remarks</label>
                              <input type="text" class="form-control" name="cough_remarks" id="cough_remarks" value="{{old('cough_remarks', $d->cough_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="colds_yn" id="colds_yn" value="checkedValue" {{(old('colds_yn') || $d->colds == 1) ? 'checked' : ''}}>
                              Colds/Coryza
                            </label>
                          </div>
                          <div id="colds_div" class="d-none">
                            <div class="form-group">
                              <label for="colds_onset">Colds Onset</label>
                              <input type="date" class="form-control" name="colds_onset" id="colds_onset" value="{{old('colds_onset', $d->colds_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="colds_remarks">Colds Remarks</label>
                              <input type="text" class="form-control" name="colds_remarks" id="colds_remarks" value="{{old('colds_remarks', $d->colds_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="conjunctivitis_yn" id="conjunctivitis_yn" value="checkedValue" {{(old('conjunctivitis_yn') || $d->conjunctivitis == 1) ? 'checked' : ''}}>
                              Conjunctivitis/Red Eyes
                            </label>
                          </div>
                          <div id="conjunctivitis_div" class="d-none">
                            <div class="form-group">
                              <label for="conjunctivitis_onset">Conjunctivitis Onset</label>
                              <input type="date" class="form-control" name="conjunctivitis_onset" id="conjunctivitis_onset" value="{{old('conjunctivitis_onset', $d->conjunctivitis_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="conjunctivitis_remarks">Conjunctivitis Remarks</label>
                              <input type="text" class="form-control" name="conjunctivitis_remarks" id="conjunctivitis_remarks" value="{{old('conjunctivitis_remarks', $d->conjunctivitis_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="diarrhea_yn" id="diarrhea_yn" value="checkedValue" {{(old('diarrhea_yn') || $d->diarrhea == 1) ? 'checked' : ''}}>
                              Diarrhea
                            </label>
                          </div>
                          <div id="diarrhea_div" class="d-none">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="bloody_stool" id="bloody_stool" value="checkedValue" {{(old('bloody_stool') || $d->bloody_stool == 1) ? 'checked' : ''}}>
                                Bloody Stool
                              </label>
                            </div>
                            <div class="form-group">
                              <label for="diarrhea_onset">Diarrhea Onset</label>
                              <input type="date" class="form-control" name="diarrhea_onset" id="diarrhea_onset" value="{{old('diarrhea_onset', $d->diarrhea_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="diarrhea_remarks">Diarrhea Remarks</label>
                              <input type="text" class="form-control" name="diarrhea_remarks" id="diarrhea_remarks" value="{{old('diarrhea_remarks', $d->diarrhea_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="anorexia_yn" id="anorexia_yn" value="checkedValue" {{(old('anorexia_yn') || $d->anorexia == 1) ? 'checked' : ''}}>
                              Eating Disorder (Anorexia)
                            </label>
                          </div>
                          <div id="anorexia_div" class="d-none">
                            <div class="form-group">
                              <label for="anorexia_onset">Eating Disorder Onset</label>
                              <input type="date" class="form-control" name="anorexia_onset" id="anorexia_onset" value="{{old('anorexia_onset', $d->anorexia_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="anorexia_remarks">Eating Disorder Remarks</label>
                              <input type="text" class="form-control" name="anorexia_remarks" id="anorexia_remarks" value="{{old('anorexia_remarks', $d->anorexia_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="fatigue_yn" id="fatigue_yn" value="checkedValue" {{(old('fatigue_yn') || $d->fatigue == 1) ? 'checked' : ''}}>
                              Fatigue
                            </label>
                          </div>
                          <div id="fatigue_div" class="d-none">
                            <div class="form-group">
                              <label for="fatigue_onset">Fatigue Onset</label>
                              <input type="date" class="form-control" name="fatigue_onset" id="fatigue_onset" value="{{old('fatigue_onset', $d->fatigue_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="fatigue_remarks">Fatigue Remarks</label>
                              <input type="text" class="form-control" name="fatigue_remarks" id="fatigue_remarks" value="{{old('fatigue_remarks', $d->fatigue_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="fever_yn" id="fever_yn" value="checkedValue" {{(old('fever_yn') || $d->fever == 1)  ? 'checked' : ''}}>
                              Fever
                            </label>
                          </div>
                          <div id="fever_div" class="d-none">
                            <div class="form-group">
                              <label for="fever_onset">Fever Onset</label>
                              <input type="date" class="form-control" name="fever_onset" id="fever_onset" value="{{old('fever_onset', $d->fever_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="fever_remarks">Fever Remarks</label>
                              <input type="text" class="form-control" name="fever_remarks" id="fever_remarks" value="{{old('fever_remarks', $d->fever_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="headache_yn" id="headache_yn" value="checkedValue" {{(old('headache_yn') || $d->headache == 1) ? 'checked' : ''}}>
                              Headache
                            </label>
                          </div>
                          <div id="headache_div" class="d-none">
                            <div class="form-group">
                              <label for="headache_onset">Headache Onset</label>
                              <input type="date" class="form-control" name="headache_onset" id="headache_onset" value="{{old('headache_onset', $d->headache_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="headache_remarks">Headache Remarks</label>
                              <input type="text" class="form-control" name="headache_remarks" id="headache_remarks" value="{{old('headache_remarks', $d->headache_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="jointpain_yn" id="jointpain_yn" value="checkedValue" {{(old('jointpain_yn') || $d->jointpain == 1) ? 'checked' : ''}}>
                                Joint Pain
                              </label>
                            </div>
                            <div id="jointpain_div" class="d-none">
                              <div class="form-group">
                                <label for="jointpain_onset">Joint Pain Onset</label>
                                <input type="date" class="form-control" name="jointpain_onset" id="jointpain_onset" value="{{old('jointpain_onset', $d->jointpain_onset)}}" max="{{date('Y-m-d')}}">
                              </div>
                              <div class="form-group">
                                <label for="jointpain_remarks">Joint Pain Remarks</label>
                                <input type="text" class="form-control" name="jointpain_remarks" id="jointpain_remarks" value="{{old('jointpain_remarks', $d->jointpain_remarks)}}">
                              </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="jaundice_yn" id="jaundice_yn" value="checkedValue" {{(old('jaundice_yn') || $d->jaundice == 1) ? 'checked' : ''}}>
                              Jaundice
                            </label>
                          </div>
                          <div id="jaundice_div" class="d-none">
                            <div class="form-group">
                              <label for="jaundice_onset">Jaundice Onset</label>
                              <input type="date" class="form-control" name="jaundice_onset" id="jaundice_onset" value="{{old('jaundice_onset', $d->jaundice_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="jaundice_remarks">Jaundice Remarks</label>
                              <input type="text" class="form-control" name="jaundice_remarks" id="jaundice_remarks" value="{{old('jaundice_remarks', $d->jaundice_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="lossofsmell_yn" id="lossofsmell_yn" value="checkedValue" {{(old('lossofsmell_yn') || $d->lossofsmell == 1) ? 'checked' : ''}}>
                              Loss of Smell (Anosmia)
                            </label>
                          </div>
                          <div id="lossofsmell_div" class="d-none">
                            <div class="form-group">
                              <label for="lossofsmell_onset">Loss of Smell Onset</label>
                              <input type="date" class="form-control" name="lossofsmell_onset" id="lossofsmell_onset" value="{{old('lossofsmell_onset', $d->lossofsmell_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="lossofsmell_remarks">Loss of Smell Remarks</label>
                              <input type="text" class="form-control" name="lossofsmell_remarks" id="lossofsmell_remarks" value="{{old('lossofsmell_remarks', $d->lossofsmell_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="lossoftaste_yn" id="lossoftaste_yn" value="checkedValue" {{(old('lossoftaste_yn') || $d->lossoftaste == 1) ? 'checked' : ''}}>
                              Loss of Taste (Ageusia)
                            </label>
                          </div>
                          <div id="lossoftaste_div" class="d-none">
                            <div class="form-group">
                              <label for="lossoftaste_onset">Loss of Taste Onset</label>
                              <input type="date" class="form-control" name="lossoftaste_onset" id="lossoftaste_onset" value="{{old('lossoftaste_onset', $d->lossoftaste_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="lossoftaste_remarks">Loss of Taste Remarks</label>
                              <input type="text" class="form-control" name="lossoftaste_remarks" id="lossoftaste_remarks" value="{{old('lossoftaste_remarks', $d->lossoftaste_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="musclepain_yn" id="musclepain_yn" value="checkedValue" {{(old('musclepain_yn') || $d->musclepain == 1) ? 'checked' : ''}}>
                              Muscle Pain
                            </label>
                          </div>
                          <div id="musclepain_div" class="d-none">
                            <div class="form-group">
                              <label for="musclepain_onset">Muscle Pain Onset</label>
                              <input type="date" class="form-control" name="musclepain_onset" id="musclepain_onset" value="{{old('musclepain_onset', $d->musclepain_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="musclepain_remarks">Muscle Pain Remarks</label>
                              <input type="text" class="form-control" name="musclepain_remarks" id="musclepain_remarks" value="{{old('musclepain_remarks', $d->musclepain_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="nausea_yn" id="nausea_yn" value="checkedValue" {{(old('nausea_yn') || $d->nausea == 1) ? 'checked' : ''}}>
                              Nausea
                            </label>
                          </div>
                          <div id="nausea_div" class="d-none">
                            <div class="form-group">
                              <label for="nausea_onset">Nausea Onset</label>
                              <input type="date" class="form-control" name="nausea_onset" id="nausea_onset" value="{{old('nausea_onset', $d->nausea_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="nausea_remarks">Nausea Remarks</label>
                              <input type="text" class="form-control" name="nausea_remarks" id="nausea_remarks" value="{{old('nausea_remarks', $d->nausea_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="paralysis_yn" id="paralysis_yn" value="checkedValue" {{(old('paralysis_yn') || $d->paralysis == 1) ? 'checked' : ''}}>
                              Paralysis
                            </label>
                          </div>
                          <div id="paralysis_div" class="d-none">
                            <div class="form-group">
                              <label for="paralysis_onset">Paralysis Onset</label>
                              <input type="date" class="form-control" name="paralysis_onset" id="paralysis_onset" value="{{old('paralysis_onset', $d->paralysis_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="paralysis_remarks">Paralysis Remarks</label>
                              <input type="text" class="form-control" name="paralysis_remarks" id="paralysis_remarks" value="{{old('paralysis_remarks', $d->paralysis_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="rash_yn" id="rash_yn" value="checkedValue" {{(old('rash_yn') || $d->rash == 1) ? 'checked' : ''}}>
                              Rash
                            </label>
                          </div>
                          <div id="rash_div" class="d-none">
                            <div class="form-group">
                              <label for="rash_onset">Rash Onset</label>
                              <input type="date" class="form-control" name="rash_onset" id="rash_onset" value="{{old('rash_onset', $d->rash_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group" >
                              <label for="rash_remarks">Rash Remarks</label>
                              <input type="text" class="form-control" name="rash_remarks" id="rash_remarks" value="{{old('rash_remarks', $d->rash_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="mouthsore_yn" id="mouthsore_yn" value="checkedValue" {{(old('mouthsore_yn') || $d->mouthsore == 1) ? 'checked' : ''}}>
                              Sore Mouth
                            </label>
                          </div>
                          <div id="mouthsore_div" class="d-none">
                            <div class="form-group">
                              <label for="mouthsore_onset">Mouth Sore Onset</label>
                              <input type="date" class="form-control" name="mouthsore_onset" id="mouthsore_onset" value="{{old('mouthsore_onset', $d->mouthsore_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="mouthsore_remarks">Mouth Sore Remarks</label>
                              <input type="text" class="form-control" name="mouthsore_remarks" id="mouthsore_remarks" value="{{old('mouthsore_remarks', $d->mouthsore_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="sorethroat_yn" id="sorethroat_yn" value="checkedValue" {{(old('sorethroat_yn') || $d->sorethroat == 1) ? 'checked' : ''}}>
                              Sore Throat
                            </label>
                          </div>
                          <div id="sorethroat_div" class="d-none">
                            <div class="form-group">
                              <label for="sorethroat_onset">Sore Throat Onset</label>
                              <input type="date" class="form-control" name="sorethroat_onset" id="sorethroat_onset" value="{{old('sorethroat_onset', $d->sorethroat_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="sorethroat_remarks">Sore Throat Remarks</label>
                              <input type="text" class="form-control" name="sorethroat_remarks" id="sorethroat_remarks" value="{{old('sorethroat_remarks', $d->sorethroat_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="dyspnea_yn" id="dyspnea_yn" value="checkedValue" {{(old('dyspnea_yn') || $d->dyspnea == 1) ? 'checked' : ''}}>
                              Shortness of Breath (Dyspnea)
                            </label>
                          </div>
                          <div id="dyspnea_div" class="d-none">
                            <div class="form-group">
                              <label for="dyspnea_onset">Shortness of Breath Onset</label>
                              <input type="date" class="form-control" name="dyspnea_onset" id="dyspnea_onset" value="{{old('dyspnea_onset', $d->dyspnea_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="dyspnea_remarks">Shortness of Breath Remarks</label>
                              <input type="text" class="form-control" name="dyspnea_remarks" id="dyspnea_remarks" value="{{old('dyspnea_remarks', $d->dyspnea_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="vomiting_yn" id="vomiting_yn" value="checkedValue" {{(old('vomiting_yn') || $d->vomiting == 1) ? 'checked' : ''}}>
                              Vomiting
                            </label>
                          </div>
                          <div id="vomiting_div" class="d-none">
                            <div class="form-group">
                              <label for="vomiting_onset">Vomiting Onset</label>
                              <input type="date" class="form-control" name="vomiting_onset" id="vomiting_onset" value="{{old('vomiting_onset', $d->vomiting_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="vomiting_remarks">Vomiting Remarks</label>
                              <input type="text" class="form-control" name="vomiting_remarks" id="vomiting_remarks" value="{{old('vomiting_remarks', $d->vomiting_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="weaknessofextremities_yn" id="weaknessofextremities_yn" value="checkedValue" {{(old('weaknessofextremities_yn') || $d->weaknessofextremities == 1) ? 'checked' : ''}}>
                              Weakness of Extremities
                            </label>
                          </div>
                          <div id="weaknessofextremities_div" class="d-none">
                            <div class="form-group">
                              <label for="weaknessofextremities_onset">Weakness of Extremities Onset</label>
                              <input type="date" class="form-control" name="weaknessofextremities_onset" id="weaknessofextremities_onset" value="{{old('weaknessofextremities_onset', $d->weaknessofextremities_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="weaknessofextremities_remarks">Weakness of Extremities Remarks</label>
                              <input type="text" class="form-control" name="weaknessofextremities_remarks" id="weaknessofextremities_remarks" value="{{old('weaknessofextremities_remarks', $d->weaknessofextremities_remarks)}}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="other_symptoms_yn" id="other_symptoms_yn" value="checkedValue" {{(old('other_symptoms_yn') || $d->other_symptoms == 1) ? 'checked' : ''}}>
                              Others
                            </label>
                          </div>
                          <div id="other_symptoms_div" class="d-none">
                            <hr>
                            <div class="form-group">
                              <label for="other_symptoms_onset_remarks">Specify <i><small>(Can be separated with commas ",")</small></i></label>
                              <input type="text" class="form-control" name="other_symptoms_onset_remarks" id="other_symptoms_onset_remarks" value="{{old('other_symptoms_onset_remarks', $d->other_symptoms_onset_remarks)}}">
                            </div>
                            <div class="form-group">
                              <label for="other_symptoms_onset">Date of Onset</label>
                              <input type="date" class="form-control" name="other_symptoms_onset" id="other_symptoms_onset" value="{{old('other_symptoms_onset', $d->other_symptoms_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="card mb-3">
                  <div class="card-header"><b>Doctor's Note</b></div>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="dcnote_assessment">Assessment</label>
                      <textarea class="form-control" name="dcnote_assessment" id="dcnote_assessment" rows="3">{{old('dcnote_assessment', $d->dcnote_assessment)}}</textarea>
                    </div>
                    <div class="form-group">
                      <label for="dcnote_plan">Plan of Action</label>
                      <textarea class="form-control" name="dcnote_plan" id="dcnote_plan" rows="3">{{old('dcnote_plan', $d->dcnote_plan)}}</textarea>
                    </div>
                    <div class="form-group">
                      <label for="dcnote_diagprocedure">Diagnostic Procedure</label>
                      <textarea class="form-control" name="dcnote_diagprocedure" id="dcnote_diagprocedure" rows="3">{{old('dcnote_diagprocedure', $d->dcnote_diagprocedure)}}</textarea>
                    </div>
                    <div class="form-group">
                      <label for="rx">RX</label>
                      <input type="text" class="form-control" name="rx" id="rx" value="{{old('rx', $d->rx)}}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
                      <select class="form-control" name="outcome" id="outcome" required>
                        <option value="ALIVE" {{(old('outcome', $d->outcome) == 'ALIVE') ? 'selected' : ''}}>Alive (Active)</option>
                        <option value="RECOVERED" {{(old('outcome', $d->outcome) == 'RECOVERED') ? 'selected' : ''}}>Recovered</option>
                        <option value="DIED" {{(old('outcome', $d->outcome) == 'DIED') ? 'selected' : ''}}>Died</option>
                      </select>
                    </div>
                    <div id="if_recovered" class="d-none">
                      <div class="form-group">
                        <label for="outcome_recovered_date"><span class="text-danger font-weight-bold">*</span>Date Recovered</label>
                        <input type="date" class="form-control" name="outcome_recovered_date" id="outcome_recovered_date" value="{{old('outcome_recovered_date', $d->outcome_recovered_date)}}" max="{{date('Y-m-d')}}">
                      </div>
                    </div>
                    <div id="if_died" class="d-none">
                      <div class="form-group">
                        <label for="outcome_died_date"><span class="text-danger font-weight-bold">*</span>Date Died</label>
                        <input type="date" class="form-control" name="outcome_died_date" id="outcome_died_date" value="{{old('outcome_died_date', $d->outcome_died_date)}}" max="{{date('Y-m-d')}}">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name_of_physician"><span class="text-danger font-weight-bold">*</span>Name of Physician</label>
                      <select class="form-control" name="name_of_physician" id="name_of_physician">
                        <option {{(is_null(old('name_of_physician', $d->name_of_physician))) ? 'selected' : ''}}>None</option>
                        @foreach($doclist as $dr)
                        <option value="{{$dr->doctor_name}}" {{(old('name_of_physician', $d->name_of_physician) == $dr->doctor_name) ? 'selected' : ''}}>{{$dr->doctor_name}} ({{$dr->dru_name}})</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-md-6">
                  <button type="submit" name="" id="" class="btn btn-primary btn-block" {{($d->brgy_verified == 1) ? 'disabled' : ''}} name="submit" value="verify_brgy">Mark as Verified (BRGY)</button>
                  @if($d->brgy_verified == 1)
                  <p class="text-center" style="margin-bottom: 0px;">BRGY Verified at: {{date('m/d/Y h:i A', strtotime($d->brgy_verified_date))}}, by: {{$d->getBrgyVerifiedBy->name}}</p>
                  @endif
                </div>
                <div class="col-md-6">
                  <button type="submit" name="" id="" class="btn btn-primary btn-block" {{(in_array('GLOBAL_ADMIN', explode(",", auth()->user()->permission_list)) || in_array('ITR_ADMIN', explode(",", auth()->user()->permission_list)) || in_array('ITR_ENCODER', explode(",", auth()->user()->permission_list))) ? ($d->cesu_verified == 1) ? 'disabled' : '' : 'disabled'}} name="submit" value="verify_cesu">Mark as Verified (CESU)</button>
                  @if($d->cesu_verified == 1)
                  <p class="text-center" style="margin-bottom: 0px;">CESU Verified at: {{date('m/d/Y h:i A', strtotime($d->cesu_verified_date))}}, by: {{$d->getCesuVerifiedBy->name}}</p>
                  @endif
                </div>
              </div>
              <hr>
                <button type="submit" class="btn btn-success btn-block" name="submit" value="update" id="submitBtn">Update (CTRL + S)</button>
            </div>
        </div>
    </form>
</div>
@if($d->outcome != 'DIED')
@if(in_array('ITR_ENCODER', auth()->user()->getPermissions()) || in_array('ITR_ADMIN', auth()->user()->getPermissions()) || in_array('GLOBAL_ADMIN', auth()->user()->getPermissions()))
<form action="{{route('syndromic_generate_medcert', $d->id)}}" method="POST">
  @csrf
  <div class="modal fade" id="generateMedCert" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><b>Generate Medcert</b></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="medcert_generated_date"><span class="text-danger font-weight-bold">*</span>Date</label>
            <input type="date" class="form-control" value="{{old('medcert_generated_date', ($d->medcert_enabled == 0) ? date('Y-m-d') : $d->medcert_generated_date)}}" name="medcert_generated_date" id="medcert_generated_date" max="{{date('Y-m-d')}}" required>
          </div>
          <div class="form-group">
            <label for="medcert_validity_date"><span class="text-danger font-weight-bold">*</span>Effectivity Date</label>
            <input type="date" class="form-control" value="{{old('medcert_validity_date', ($d->medcert_enabled == 0) ? date('Y-m-d') : $d->medcert_validity_date)}}" name="medcert_validity_date" id="medcert_validity_date" min="{{date('Y-m-d')}}" max="{{'12-31-'.date('Y')}}" required>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="medcert_start_date">Start Date (From)</label>
                <input type="date" class="form-control" value="{{old('medcert_start_date', $d->medcert_start_date)}}" name="medcert_start_date" id="medcert_start_date" max="{{date('Y-m-d')}}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="medcert_end_date">End Date (To)</label>
                <input type="date" class="form-control" value="{{old('medcert_end_date', $d->medcert_end_date)}}" name="medcert_end_date" id="medcert_end_date" max="{{date('Y-m-d')}}">
              </div>
            </div>
          </div>
          @if($d->outcome != 'RECOVERED')
          <div class="alert alert-info" role="alert">
            <b class="text-danger">Note:</b> Generating MedCert will mark the Patient as <b class="text-success">Recovered.</b>
          </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-block">Generate</button>
        </div>
      </div>
    </div>
  </div>
</form>
@endif
@endif

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

  var getage = {{$d->syndromic_patient->getAge()}};

  if(getage < 18) {
    $('#bp_asterisk').addClass('d-none');
    $('#bloodpressure').prop('required', false);
  }
  else {
    $('#bp_asterisk').removeClass('d-none');
    $('#bloodpressure').prop('required', true);
  }

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

  $('#other_symptoms_yn').change(function (e) { 
    e.preventDefault();
    if($(this).prop('checked')) {
      $('#other_symptoms_div').removeClass('d-none');
    }
    else {
      $('#other_symptoms_div').addClass('d-none');
    }
  }).trigger('change');

  $('#outcome').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'RECOVERED') {
      $('#if_recovered').removeClass('d-none');
      $('#if_died').addClass('d-none');
      $('#outcome_recovered_date').prop('required', true);
      $('#outcome_died_date').prop('required', false);
    }
    else if($(this).val() == 'DIED') {
      $('#if_recovered').addClass('d-none');
      $('#if_died').removeClass('d-none');
      $('#outcome_recovered_date').prop('required', false);
      $('#outcome_died_date').prop('required', true);
    }
    else {
      $('#if_recovered').addClass('d-none');
      $('#if_died').addClass('d-none');
      $('#outcome_recovered_date').prop('required', false);
      $('#outcome_died_date').prop('required', false);
    }
  });

  $('#is_hospitalized').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'Y') {
      $('#if_hospitalized').removeClass('d-none');
      $('#hospital_name').prop('required', true);
      $('#date_admitted').prop('required', true);
    }
    else {
      $('#if_hospitalized').addClass('d-none');
      $('#hospital_name').prop('required', false);
      $('#date_admitted').prop('required', false);
    }
  });
</script>
@endsection