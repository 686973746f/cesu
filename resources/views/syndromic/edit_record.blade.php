@extends('layouts.app')

@section('content')
<div class="container">
  <div class="text-right mb-3">
    <div class="btn-group">
      @if(auth()->user()->isGlobalAdmin())
      <button type="button" class="btn btn-warning mr-2" data-toggle="modal" data-target="#adminOptions">Admin Options</button>
      @endif

      @if($d->hasPermissionToDelete())
      <form action="{{route('syndromic_deleteRecord', $d->id)}}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('You cannot undo this process. Are you sure you want to delete this record associated with the Patient?')"><i class="fa fa-trash mr-2" aria-hidden="true"></i>Delete this Record</button>
      </form>
      @endif
    </div>
  </div>

  @if(auth()->user()->isGlobalAdmin())
  <div class="modal fade" id="adminOptions" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Admin Options</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
          <form action="{{route('syndromic_adminoptions_init', $d->id)}}" method="POST">
            @csrf
            <div class="card" id="transferBody">
              <div class="card-header">
                <div><b>Transfer Record to other Patient</b></div>
                <div><i>(Mostly used to delete duplicate records)</i></div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="newList">Select the Patient ID where to transfer the Record</label>
                  <select class="form-control" name="newList" id="newList" required></select>
                </div>
                <div class="form-check">
                  <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="deletePatientAfterTransfer" id="deletePatientAfterTransfer" value="1">
                    Delete this Patient after transfer?
                  </label>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" name="submit" value="transfer_patient_id">Proceed</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $('#newList').select2({
        dropdownParent: $('#transferBody'),
        theme: "bootstrap",
        placeholder: 'Search by Name / Patient ID ...',
        ajax: {
            url: "{{route('syndromic_ajaxListRecords', $d->syndromic_patient->id)}}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id,
                        }
                    })
                };
            },
            cache: true
        }
    });
  </script>
  @endif

    <form action="{{route('syndromic_updateRecord', $d->id)}}" method="POST" onsubmit="return validateForm()">
        @csrf
        <div class="card">
            <div class="card-header">
              <div class="d-flex justify-content-between">
                <div><b>Edit ITR</b></div>
                <div>
                  @if($unlocktoolbar)
                  <div>
                    @if($d->outcome != 'DIED' && $d->outcome != 'DOA')
                    <a href="{{route('syndromic_newRecord', $d->syndromic_patient->id)}}" class="btn btn-success">New Consultation</a>
                    @endif
                    @if($d->getPharmacyDetails())
                    <a href="{{route('pharmacy_print_patient_card', $d->getPharmacyDetails()->id)}}" class="btn btn-primary">Print Pharmacy Card</a>
                    @endif
                    <a href="{{route('syndromic_downloadItr', $d->id)}}" class="btn btn-primary">Download ITR Form</a>
                    @if($d->outcome != 'DIED')
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#generateMedCert">Generate Medical Certificate</button>
                    @endif
                  </div>
                  @if($d->syndromic_patient->getAgeInt() >= 20)
                  <div class="mt-3 text-right">
                    <a class="btn btn-success" href="{{route('syndromic_createRaf', $d->id)}}" role="button">Create Risk Assessment Form (Non-Comm)</a>
                  </div>
                  @endif
                  @else
                  <div class="alert alert-info" role="alert">
                    <b>Please complete the consultation details below.</b>
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td>
                        <div><b>ITR ID:</b></div>
                        <div>#{{$d->id}}</div>
                      </td>
                      <td>
                        <div><b>DATE ENCODED / BY:</b></div>
                        <div>{{date('m/d/Y h:i A', strtotime($d->created_at))}} by {{$d->user->name}}</div>
                      </td>
                      <td>
                        <div><b>DATE UPDATED / BY:</b></div>
                        @if($d->getUpdatedBy())
                        <div>{{date('m/d/Y h:i A', strtotime($d->updated_at))}} by {{$d->getUpdatedBy->name}}</div>
                        @else
                        <div>N/A</div>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div><b>NAME / ID:</b></div>
                        <div><b><a href="{{route('syndromic_viewPatient', $d->syndromic_patient->id)}}">{{$d->syndromic_patient->getName()}} @if($d->isHospitalRecord())<small>(Hospital No. {{$d->syndromic_patient->unique_opdnumber}})</small>@else<small>(#{{$d->syndromic_patient->id}})</small>@endif</a></b></div>
                      </td>
                      <td>
                        <div><b>BIRTHDATE:</b></div>
                        <div>{{date('m/d/Y', strtotime($d->syndromic_patient->bdate))}}</div>
                      </td>
                      <td>
                        <div><b>AGE/SEX:</b></div>
                        <div>{{$d->syndromic_patient->getAge()}} / {{substr($d->syndromic_patient->gender, 0,1)}}</div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <div><b>ADDRESS:</b></div>
                        <div>{{$d->syndromic_patient->getFullAddress()}}</div>
                      </td>
                      <td>
                        <div><b>CONTACT NUMBER/S:</b></div>
                        <div>{{$d->syndromic_patient->getContactNumber()}}</div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for=""><b class="text-danger">*</b>Encoded under Facility</label>
                      <input type="text" class="form-control" name="" id="" value="{{$d->facility->facility_name}}" readonly>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for=""><b class="text-danger">*</b>Nature of Visit</label>
                      <input type="text" class="form-control" name="" id="" value="{{$d->getHospRecordType()}}" readonly>
                    </div>
                  </div>
                </div>
                @if($d->isHospitalRecord())
                <div class="form-group">
                  <label for="hosp_identifier"><b class="text-danger">*</b>Record From</label>
                  <select class="form-control" name="hosp_identifier" id="hosp_identifier" required>
                    <option value="OPD" {{(old('hosp_identifier', $d->hosp_identifier) == 'OPD') ? 'selected' : ''}}>OPD</option>
                    <option value="ER" {{(old('hosp_identifier', $d->hosp_identifier) == 'ER') ? 'selected' : ''}}>ER</option>
                  </select>
                </div>
                @endif
                <div class="form-group {{($d->isHospitalRecord()) ? 'd-none' : ''}}" id="purpose_div">
                  <label class="mr-2"><b class="text-danger">*</b>Purpose:</label>
                  @foreach(App\Models\SyndromicRecords::refConsultationType() as $ind => $ref1)
                  @php
                  //Check Status
                  if($ref1 == 'Prenatal' || $ref1 == 'Post Partum') {
                    if($d->syndromic_patient->gender == 'MALE') {
                      $get_disabled = true; 
                    }
                    else {
                      if($d->age_years <= 12) {
                        $get_disabled = true;
                      }
                      else {
                        $get_disabled = false;
                      }
                    }
                  }
                  else if($ref1 == 'Child Care' || $ref1 == 'Child Immunization' || $ref1 == 'Child Nutrition' || $ref1 == 'Sick Children') {
                    if($d->age_years <= 17) {
                      $get_disabled = false;
                    }
                    else {
                      $get_disabled = true;
                    }
                  }
                  else if($ref1 == 'Adult Immunization' || $ref1 == 'Family Planning') {
                    if($d->age_years <= 17) {
                      $get_disabled = true;
                    }
                    else {
                      $get_disabled = false;
                    }
                  }
                  else {
                    $get_disabled = false;
                  }
                  @endphp
                  <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" id="type_{{$ind}}" name="consultation_type[]" value="{{mb_strtoupper($ref1)}}" {{($get_disabled) ? 'disabled' : ''}} {{in_array(mb_strtoupper($ref1), explode(",", old('consultation_type', $d->consultation_type))) ? 'checked' : ''}}>
                      <label class="form-check-label">{{$ref1}}</label>
                  </div>
                  @endforeach
                </div>
                <div class="form-group {{($d->isHospitalRecord()) ? 'd-none' : ''}}">
                  <label for="checkup_type"><b class="text-danger">*</b>Mode of Transaction</label>
                  <select class="form-control" name="checkup_type" id="checkup_type" required>
                    <option value="" disabled {{is_null(old('checkup_type', $d->checkup_type)) ? 'selected' : ''}}>Choose...</option>
                    <option value="CHECKUP" {{(old('checkup_type', $d->checkup_type) == 'CHECKUP') ? 'selected' : ''}}>From OPD (Walk-In)</option>
                    @if(auth()->user()->isStaffSyndromic())
                    <option value="REQUEST_MEDS" {{(old('checkup_type', $d->checkup_type) == 'REQUEST_MEDS') ? 'selected' : ''}}>From Outside (for Pharmacy Medicine Request)</option>
                    @endif
                  </select>
                </div>
                <div id="if_noncheckup" class="d-none">
                  <div class="form-group">
                    <label for="outsidecho_name"><b class="text-danger">*</b>Name of Hospital/Clinic</label>
                    <input type="text" class="form-control" name="outsidecho_name" id="outsidecho_name" value="{{old('outsidecho_name', $d->outsidecho_name)}}" style="text-transform: uppercase;">
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                          <label for="line_number"><b class="text-danger">*</b>Number in Line</label>
                          <input type="number" class="form-control" name="line_number" id="line_number" value="{{old('line_number', $d->line_number)}}" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="consultation_date">Date and Time of Admitted/Consulted</label>
                            <input type="datetime-local" class="form-control" name="consultation_date" id="consultation_date" value="{{old('consultation_date', \Carbon\Carbon::parse($d->consultation_date)->format('Y-m-d\TH:i'))}}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="temperature"><span class="text-danger font-weight-bold">*</span>Current Temperature</label>
                            <input type="number" step="0.1" pattern="\d+(\.\d{1})?" class="form-control" name="temperature" id="temperature" value="{{old('temperature', $d->temperature)}}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bloodpressure">Blood Pressure (BP)</label>
                            <input type="text" class="form-control" name="bloodpressure" id="bloodpressure" value="{{old('bloodpressure', $d->bloodpressure)}}" {{($required_bp) ? 'required' : ''}}>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                          <label for="weight">
                            @if($required_weight)
                            <b class="text-danger required_before" id="w_ast">*</b>
                            @endif
                            Weight (kg)</label>
                          <input type="number" step="0.1" class="form-control" name="weight" id="weight" min="1" max="900" value="{{old('weight', $d->weight)}}" {{($required_weight) ? 'required' : ''}}>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div><label for="height"><b class="text-danger" id="h_ast">*</b>Height (cm)</label></div>
                      <div class="input-group mb-3">
                        <input type="number" class="form-control" step="0.1" name="height" id="height" min="1" max="600" value="{{old('height', $d->height)}}">
                        <div class="input-group-append">
                          <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#heightConverter">Convert feet to cm</button>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="respiratoryrate">Respiratory Rate (RR)</label>
                        <input type="text" class="form-control" name="respiratoryrate" id="respiratoryrate" value="{{old('respiratoryrate', $d->respiratoryrate)}}">
                    </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="pulserate">Pulse Rate (PR)</label>
                        <input type="text" class="form-control" name="pulserate" id="pulserate" value="{{old('pulserate', $d->pulserate)}}">
                      </div>
                      @if($d->isHospitalRecord())
                      <div class="form-group">
                        <label for="o2sat">O2Sat (%)</label>
                        <input type="number" class="form-control" name="o2sat" id="o2sat" value="{{old('o2sat', $d->o2sat)}}" min="70" max="100">
                      </div>
                      @endif
                        <!--
                        <div class="form-group">
                            <label for="saturationperioxigen">Saturation of Oxygen (SpO2)</label>
                            <input type="text" class="form-control" name="saturationperioxigen" id="saturationperioxigen" value="{{old('saturationperioxigen', $d->saturationperioxigen)}}">
                        </div>
                        -->
                    </div>
                </div>
                @if($d->isHospitalRecord())
                <div class="row">
                  <div class="col-md-4">
                    @if($d->syndromic_patient->gender == 'FEMALE' && $d->syndromic_patient->getAgeInt() > 10)
                    <div class="form-group">
                      <label for="is_pregnant"><b class="text-danger">*</b>Pregnant?</label>
                      <select class="form-control" name="is_pregnant" id="is_pregnant" required>
                        <option value="N" {{(old('is_pregnant') == 'N' || $d->is_pregnant == 0) ? 'selected' : ''}}>No</option>
                        <option value="Y" {{(old('is_pregnant') == 'Y' || $d->is_pregnant == 1) ? 'selected' : ''}}>Yes</option>
                      </select>
                    </div>
                    <div id="ifPregnantDiv" class="d-none">
                      <div class="form-group">
                        <label for="lmp"><b class="text-danger">*</b>LMP</label>
                        <input type="date" class="form-control" name="lmp" id="lmp" min="{{date('Y-m-d', strtotime('-12 Months'))}}" max="{{date('Y-m-d')}}" value="{{old('lmp', $d->lmp)}}">
                      </div>
                      <div class="form-group">
                        <label for="edc">EDC</label>
                        <input type="date" class="form-control" name="edc" id="edc" min="{{date('Y-m-d')}}" max="{{date('Y-m-d', strtotime('+12 Months'))}}" value="{{old('edc', $d->edc)}}">
                      </div>
                    </div>
                    @endif
                  </div>
                  <div class="col-md-4">

                  </div>
                  <div class="col-md-4">

                  </div>
                </div>
                @endif
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="chief_complain"><b><span class="text-danger">*</span>Chief Complaint</b></label>
                      <input type="text" class="form-control" name="chief_complain" id="chief_complain" value="{{old('chief_complain', $d->chief_complain)}}" style="text-transform: uppercase;" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="is_hospitalized"><span class="text-danger font-weight-bold">*</span>From other hospital?</label>
                      <select class="form-control" name="is_hospitalized" id="is_hospitalized" required>
                        <option value="Y" {{(old('is_hospitalized') == 'Y' || $d->is_hospitalized == 1) ? 'selected' : ''}}>YES</option>
                        <option value="N" {{(old('is_hospitalized') == 'N' || $d->is_hospitalized == 0) ? 'selected' : ''}}>NO</option>
                      </select>
                    </div>
                    <div id="if_hospitalized" class="d-none">
                      <div class="form-group">
                        <label for="hospital_name"><span class="text-danger font-weight-bold">*</span>Name of Hospital</label>
                        <input type="text" class="form-control" name="hospital_name" id="hospital_name" value="{{old('hospital_name', $d->hospital_name)}}" style="text-transform: uppercase;">
                      </div>
                      <div class="form-group">
                        <label for="date_admitted"><span class="text-danger font-weight-bold">*</span>Date Admitted/Consulted</label>
                        <input type="date" class="form-control" name="date_admitted" id="date_admitted" value="{{old('date_admitted', $d->date_admitted)}}" max="{{date('Y-m-d')}}">
                      </div>
                      <div class="form-group">
                        <label for="date_released">Date Discharged</label>
                        <input type="date" class="form-control" name="date_released" id="date_released" value="{{old('date_released', $d->date_released)}}" max="{{date('Y-m-d')}}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>Signs and Symptoms</b> (Please check if applicable)</div>
                    <div class="card-body" id="sas_checkboxes">
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="abdominalpain_yn" id="abdominalpain_yn" value="checkedValue" {{(old('abdominalpain_yn') || $d->abdominalpain == 1) ? 'checked' : ''}}>
                              Pananakit ng Tiyan/Abdominal Pain
                            </label>
                          </div>
                          <div id="abdominalpain_div" class="d-none">
                            <div class="form-group">
                              <label for="abdominalpain_onset">Abdominal Pain Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="abdominalpain_onset" id="abdominalpain_onset" value="{{old('abdominalpain_onset', $d->abdominalpain_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="abdominalpain_remarks">Abdominal Pain Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="abdominalpain_remarks" id="abdominalpain_remarks" value="{{old('abdominalpain_remarks', $d->abdominalpain_remarks)}}" style="text-transform: uppercase;">
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
                              <label for="alteredmentalstatus_onset">Altered Mental Status Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="alteredmentalstatus_onset" id="alteredmentalstatus_onset" value="{{old('alteredmentalstatus_onset', $d->alteredmentalstatus_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="alteredmentalstatus_remarks">Altered Mental Status Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="alteredmentalstatus_remarks" id="alteredmentalstatus_remarks" value="{{old('alteredmentalstatus_remarks', $d->alteredmentalstatus_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="animalbite_yn" id="animalbite_yn" value="checkedValue" {{(old('animalbite_yn') || $d->animalbite == 1) ? 'checked' : ''}}>
                                Nakagat ng Hayop/Animal Bite
                              </label>
                            </div>
                            <div id="animalbite_div" class="d-none">
                              <div class="form-group">
                                <label for="animalbite_onset">Animal Bite Onset <small>(Optional)</small></label>
                                <input type="date" class="form-control" name="animalbite_onset" id="animalbite_onset" value="{{old('animalbite_onset', $d->animalbite_onset)}}" max="{{date('Y-m-d')}}">
                              </div>
                              <div class="form-group">
                                <label for="animalbite_remarks">Animal Bite Remarks <small>(Optional)</small></label>
                                <input type="text" class="form-control" name="animalbite_remarks" id="animalbite_remarks" value="{{old('animalbite_remarks', $d->animalbite_remarks)}}" style="text-transform: uppercase;">
                              </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="cough_yn" id="cough_yn" value="checkedValue" {{(old('cough_yn') || $d->cough == 1) ? 'checked' : ''}}>
                              Ubo/Cough
                            </label>
                          </div>
                          <div id="cough_div" class="d-none">
                            <div class="form-group">
                              <label for="cough_onset">Cough Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="cough_onset" id="cough_onset" value="{{old('cough_onset', $d->cough_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="cough_remarks">Cough Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="cough_remarks" id="cough_remarks" value="{{old('cough_remarks', $d->cough_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="colds_yn" id="colds_yn" value="checkedValue" {{(old('colds_yn') || $d->colds == 1) ? 'checked' : ''}}>
                              Sipon/Colds/Runny Nose (Coryza)
                            </label>
                          </div>
                          <div id="colds_div" class="d-none">
                            <div class="form-group">
                              <label for="colds_onset">Colds Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="colds_onset" id="colds_onset" value="{{old('colds_onset', $d->colds_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="colds_remarks">Colds Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="colds_remarks" id="colds_remarks" value="{{old('colds_remarks', $d->colds_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="conjunctivitis_yn" id="conjunctivitis_yn" value="checkedValue" {{(old('conjunctivitis_yn') || $d->conjunctivitis == 1) ? 'checked' : ''}}>
                              Pamulula ng Mata/Conjunctivitis/Red Eyes
                            </label>
                          </div>
                          <div id="conjunctivitis_div" class="d-none">
                            <div class="form-group">
                              <label for="conjunctivitis_onset">Conjunctivitis Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="conjunctivitis_onset" id="conjunctivitis_onset" value="{{old('conjunctivitis_onset', $d->conjunctivitis_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="conjunctivitis_remarks">Conjunctivitis Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="conjunctivitis_remarks" id="conjunctivitis_remarks" value="{{old('conjunctivitis_remarks', $d->conjunctivitis_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="diarrhea_yn" id="diarrhea_yn" value="checkedValue" {{(old('diarrhea_yn') || $d->diarrhea == 1) ? 'checked' : ''}}>
                              Pagdudumi/Diarrhea
                            </label>
                          </div>
                          <div id="diarrhea_div" class="d-none">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="bloody_stool" id="bloody_stool" value="checkedValue" {{(old('bloody_stool') || $d->bloody_stool == 1) ? 'checked' : ''}}>
                                May dugo ang dumi/Bloody Stool
                              </label>
                            </div>
                            <div class="form-group">
                              <label for="diarrhea_onset">Diarrhea Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="diarrhea_onset" id="diarrhea_onset" value="{{old('diarrhea_onset', $d->diarrhea_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="diarrhea_remarks">Diarrhea Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="diarrhea_remarks" id="diarrhea_remarks" value="{{old('diarrhea_remarks', $d->diarrhea_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="anorexia_yn" id="anorexia_yn" value="checkedValue" {{(old('anorexia_yn') || $d->anorexia == 1) ? 'checked' : ''}}>
                              Problema sa pagkain/Eating Disorder (Anorexia)
                            </label>
                          </div>
                          <div id="anorexia_div" class="d-none">
                            <div class="form-group">
                              <label for="anorexia_onset">Eating Disorder Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="anorexia_onset" id="anorexia_onset" value="{{old('anorexia_onset', $d->anorexia_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="anorexia_remarks">Eating Disorder Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="anorexia_remarks" id="anorexia_remarks" value="{{old('anorexia_remarks', $d->anorexia_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="fatigue_yn" id="fatigue_yn" value="checkedValue" {{(old('fatigue_yn') || $d->fatigue == 1) ? 'checked' : ''}}>
                              Pagkapagod/Fatigue
                            </label>
                          </div>
                          <div id="fatigue_div" class="d-none">
                            <div class="form-group">
                              <label for="fatigue_onset">Fatigue Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="fatigue_onset" id="fatigue_onset" value="{{old('fatigue_onset', $d->fatigue_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="fatigue_remarks">Fatigue Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="fatigue_remarks" id="fatigue_remarks" value="{{old('fatigue_remarks', $d->fatigue_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="fever_yn" id="fever_yn" value="checkedValue" {{(old('fever_yn') || $d->fever == 1)  ? 'checked' : ''}}>
                              Lagnat/Fever
                            </label>
                          </div>
                          <div id="fever_div" class="d-none">
                            <div class="form-group">
                              <label for="fever_onset">Fever Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="fever_onset" id="fever_onset" value="{{old('fever_onset', $d->fever_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="fever_remarks">Fever Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="fever_remarks" id="fever_remarks" value="{{old('fever_remarks', $d->fever_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="headache_yn" id="headache_yn" value="checkedValue" {{(old('headache_yn') || $d->headache == 1) ? 'checked' : ''}}>
                              Sakit ng Ulo/Headache
                            </label>
                          </div>
                          <div id="headache_div" class="d-none">
                            <div class="form-group">
                              <label for="headache_onset">Headache Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="headache_onset" id="headache_onset" value="{{old('headache_onset', $d->headache_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="headache_remarks">Headache Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="headache_remarks" id="headache_remarks" value="{{old('headache_remarks', $d->headache_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="jointpain_yn" id="jointpain_yn" value="checkedValue" {{(old('jointpain_yn') || $d->jointpain == 1) ? 'checked' : ''}}>
                                Sakit sa Kasu-kasuan/Joint Pain
                              </label>
                            </div>
                            <div id="jointpain_div" class="d-none">
                              <div class="form-group">
                                <label for="jointpain_onset">Joint Pain Onset <small>(Optional)</small></label>
                                <input type="date" class="form-control" name="jointpain_onset" id="jointpain_onset" value="{{old('jointpain_onset', $d->jointpain_onset)}}" max="{{date('Y-m-d')}}">
                              </div>
                              <div class="form-group">
                                <label for="jointpain_remarks">Joint Pain Remarks <small>(Optional)</small></label>
                                <input type="text" class="form-control" name="jointpain_remarks" id="jointpain_remarks" value="{{old('jointpain_remarks', $d->jointpain_remarks)}}" style="text-transform: uppercase;">
                              </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="jaundice_yn" id="jaundice_yn" value="checkedValue" {{(old('jaundice_yn') || $d->jaundice == 1) ? 'checked' : ''}}>
                              Paninilaw ng Balat/Yellow Skin (Jaundice)
                            </label>
                          </div>
                          <div id="jaundice_div" class="d-none">
                            <div class="form-group">
                              <label for="jaundice_onset">Jaundice Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="jaundice_onset" id="jaundice_onset" value="{{old('jaundice_onset', $d->jaundice_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="jaundice_remarks">Jaundice Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="jaundice_remarks" id="jaundice_remarks" value="{{old('jaundice_remarks', $d->jaundice_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="lossofsmell_yn" id="lossofsmell_yn" value="checkedValue" {{(old('lossofsmell_yn') || $d->lossofsmell == 1) ? 'checked' : ''}}>
                              Walang Pang-amoy/Loss of Smell (Anosmia)
                            </label>
                          </div>
                          <div id="lossofsmell_div" class="d-none">
                            <div class="form-group">
                              <label for="lossofsmell_onset">Loss of Smell Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="lossofsmell_onset" id="lossofsmell_onset" value="{{old('lossofsmell_onset', $d->lossofsmell_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="lossofsmell_remarks">Loss of Smell Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="lossofsmell_remarks" id="lossofsmell_remarks" value="{{old('lossofsmell_remarks', $d->lossofsmell_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="lossoftaste_yn" id="lossoftaste_yn" value="checkedValue" {{(old('lossoftaste_yn') || $d->lossoftaste == 1) ? 'checked' : ''}}>
                              Walang Panlasa/Loss of Taste (Ageusia)
                            </label>
                          </div>
                          <div id="lossoftaste_div" class="d-none">
                            <div class="form-group">
                              <label for="lossoftaste_onset">Loss of Taste Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="lossoftaste_onset" id="lossoftaste_onset" value="{{old('lossoftaste_onset', $d->lossoftaste_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="lossoftaste_remarks">Loss of Taste Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="lossoftaste_remarks" id="lossoftaste_remarks" value="{{old('lossoftaste_remarks', $d->lossoftaste_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="musclepain_yn" id="musclepain_yn" value="checkedValue" {{(old('musclepain_yn') || $d->musclepain == 1) ? 'checked' : ''}}>
                              Sakit ng Katawan/Muscle Pain/Body Pain (Myalgia)
                            </label>
                          </div>
                          <div id="musclepain_div" class="d-none">
                            <div class="form-group">
                              <label for="musclepain_onset">Muscle Pain Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="musclepain_onset" id="musclepain_onset" value="{{old('musclepain_onset', $d->musclepain_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="musclepain_remarks">Muscle Pain Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="musclepain_remarks" id="musclepain_remarks" value="{{old('musclepain_remarks', $d->musclepain_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="nausea_yn" id="nausea_yn" value="checkedValue" {{(old('nausea_yn') || $d->nausea == 1) ? 'checked' : ''}}>
                              Nahihilo/Naduduwal (Nausea)
                            </label>
                          </div>
                          <div id="nausea_div" class="d-none">
                            <div class="form-group">
                              <label for="nausea_onset">Nausea Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="nausea_onset" id="nausea_onset" value="{{old('nausea_onset', $d->nausea_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="nausea_remarks">Nausea Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="nausea_remarks" id="nausea_remarks" value="{{old('nausea_remarks', $d->nausea_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="paralysis_yn" id="paralysis_yn" value="checkedValue" {{(old('paralysis_yn') || $d->paralysis == 1) ? 'checked' : ''}}>
                              Pagka-paralisa/Paralysis
                            </label>
                          </div>
                          <div id="paralysis_div" class="d-none">
                            <div class="form-group">
                              <label for="paralysis_onset">Paralysis Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="paralysis_onset" id="paralysis_onset" value="{{old('paralysis_onset', $d->paralysis_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="paralysis_remarks">Paralysis Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="paralysis_remarks" id="paralysis_remarks" value="{{old('paralysis_remarks', $d->paralysis_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="rash_yn" id="rash_yn" value="checkedValue" {{(old('rash_yn') || $d->rash == 1) ? 'checked' : ''}}>
                              Pangangati ng Balat/Rashes
                            </label>
                          </div>
                          <div id="rash_div" class="d-none">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="rash_isMaculopapular" id="rash_isMaculopapular" value="checkedValue" {{(old('rash_isMaculopapular') || $d->rash_isMaculopapular == 1) ? 'checked' : ''}}>
                                Maculopapular Rash - Mapulang batik (macules) at maliliit na nakaumbok na bukol (papules)
                              </label>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="rash_isPetechia" id="rash_isPetechia" value="checkedValue" {{(old('rash_isPetechia') || $d->rash_isPetechia == 1) ? 'checked' : ''}}>
                                Petechia - Maliliit na pula o violet na tuldok sa balat
                              </label>
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="rash_isPurpura" id="rash_isPurpura" value="checkedValue" {{(old('rash_isPurpura') || $d->rash_isPurpura == 1) ? 'checked' : ''}}>
                                Purpura Rash - Kumpol ng mga purple na batik sa balat
                              </label>
                            </div>
                            <div class="form-group">
                              <label for="rash_onset">Rash Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="rash_onset" id="rash_onset" value="{{old('rash_onset', $d->rash_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group" >
                              <label for="rash_remarks">Rash Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="rash_remarks" id="rash_remarks" value="{{old('rash_remarks', $d->rash_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="mouthsore_yn" id="mouthsore_yn" value="checkedValue" {{(old('mouthsore_yn') || $d->mouthsore == 1) ? 'checked' : ''}}>
                              Pananakit ng Bibig/Sore Mouth
                            </label>
                          </div>
                          <div id="mouthsore_div" class="d-none">
                            <div class="form-group">
                              <label for="mouthsore_onset">Mouth Sore Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="mouthsore_onset" id="mouthsore_onset" value="{{old('mouthsore_onset', $d->mouthsore_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="mouthsore_remarks">Mouth Sore Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="mouthsore_remarks" id="mouthsore_remarks" value="{{old('mouthsore_remarks', $d->mouthsore_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="sorethroat_yn" id="sorethroat_yn" value="checkedValue" {{(old('sorethroat_yn') || $d->sorethroat == 1) ? 'checked' : ''}}>
                              Pananakit o Makating Lalamunan/Sore Throat
                            </label>
                          </div>
                          <div id="sorethroat_div" class="d-none">
                            <div class="form-group">
                              <label for="sorethroat_onset">Sore Throat Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="sorethroat_onset" id="sorethroat_onset" value="{{old('sorethroat_onset', $d->sorethroat_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="sorethroat_remarks">Sore Throat Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="sorethroat_remarks" id="sorethroat_remarks" value="{{old('sorethroat_remarks', $d->sorethroat_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="dyspnea_yn" id="dyspnea_yn" value="checkedValue" {{(old('dyspnea_yn') || $d->dyspnea == 1) ? 'checked' : ''}}>
                              Hirap sa paghinga/Shortness of Breath (Dyspnea)
                            </label>
                          </div>
                          <div id="dyspnea_div" class="d-none">
                            <div class="form-group">
                              <label for="dyspnea_onset">Shortness of Breath Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="dyspnea_onset" id="dyspnea_onset" value="{{old('dyspnea_onset', $d->dyspnea_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="dyspnea_remarks">Shortness of Breath Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="dyspnea_remarks" id="dyspnea_remarks" value="{{old('dyspnea_remarks', $d->dyspnea_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="vomiting_yn" id="vomiting_yn" value="checkedValue" {{(old('vomiting_yn') || $d->vomiting == 1) ? 'checked' : ''}}>
                              Pagsusuka/Vomiting
                            </label>
                          </div>
                          <div id="vomiting_div" class="d-none">
                            <div class="form-group">
                              <label for="vomiting_onset">Vomiting Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="vomiting_onset" id="vomiting_onset" value="{{old('vomiting_onset', $d->vomiting_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="vomiting_remarks">Vomiting Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="vomiting_remarks" id="vomiting_remarks" value="{{old('vomiting_remarks', $d->vomiting_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="weaknessofextremities_yn" id="weaknessofextremities_yn" value="checkedValue" {{(old('weaknessofextremities_yn') || $d->weaknessofextremities == 1) ? 'checked' : ''}}>
                              Panghihina ng kamay at paa/Weakness of Extremities
                            </label>
                          </div>
                          <div id="weaknessofextremities_div" class="d-none">
                            <div class="form-group">
                              <label for="weaknessofextremities_onset">Weakness of Extremities Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="weaknessofextremities_onset" id="weaknessofextremities_onset" value="{{old('weaknessofextremities_onset', $d->weaknessofextremities_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                              <label for="weaknessofextremities_remarks">Weakness of Extremities Remarks <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="weaknessofextremities_remarks" id="weaknessofextremities_remarks" value="{{old('weaknessofextremities_remarks', $d->weaknessofextremities_remarks)}}" style="text-transform: uppercase;">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="other_symptoms_yn" id="other_symptoms_yn" value="checkedValue" {{(old('other_symptoms_yn') || $d->other_symptoms == 1) ? 'checked' : ''}}>
                              Iba pa/Others
                            </label>
                          </div>
                          <div id="other_symptoms_div" class="d-none">
                            <hr>
                            <div class="form-group">
                              <label for="other_symptoms_onset_remarks"><b class="text-danger">*</b>Specify <i><small>(Can be separated with commas ",")</small></i></label>
                              <input type="text" class="form-control" name="other_symptoms_onset_remarks" id="other_symptoms_onset_remarks" value="{{old('other_symptoms_onset_remarks', $d->other_symptoms_onset_remarks)}}" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                              <label for="other_symptoms_onset">Date of Onset <small>(Optional)</small></label>
                              <input type="date" class="form-control" name="other_symptoms_onset" id="other_symptoms_onset" value="{{old('other_symptoms_onset', $d->other_symptoms_onset)}}" max="{{date('Y-m-d')}}">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="card mb-3">
                  <div class="card-header"><b>Risk Assessment</b></div>
                  <div class="card-body">
                    <div class="card mb-3">
                      <div class="card-header">Comorbidities/Past Medical History</div>
                      <div class="card-body">
                        @foreach(App\Models\SyndromicRecords::refComorbidities() as $ind => $iref)
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="como_type{{$ind}}" name="comorbid_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", old('comorbid_list', $d->comorbid_list)))) ? 'checked' : ''}}>
                          <label class="form-check-label">{{$iref}}</label>
                        </div>
                        @endforeach
                      </div>
                    </div>
                    <div id="fam_accord" role="tablist" aria-multiselectable="true">
                      <div class="card mb-3">
                        <div class="card-header" role="tab" id="section1HeaderId">
                          <a data-toggle="collapse" data-parent="#fam_accord" href="#fam1" aria-expanded="true" aria-controls="section1ContentId">
                            Family History - Does patient have 1st degree relative with comorbidities? Click to specify:
                          </a>
                        </div>
                        <div id="fam1" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                          <div class="card-body">
                            @foreach(App\Models\SyndromicRecords::refComorbidities() as $ind => $iref)
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="como_family_type{{$ind}}" name="firstdegree_comorbid_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", old('firstdegree_comorbid_list', $d->firstdegree_comorbid_list)))) ? 'checked' : ''}}>
                              <label class="form-check-label">{{$iref}}</label>
                            </div>
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card mb-3">
                  <div class="card-header"><b>DOCTOR'S ORDER</b></div>
                  <div class="card-body">
                    <div><label for="">Laboratory Requests</label></div>
                    @foreach(App\Models\SyndromicRecords::refLabRequest() as $ind => $iref)
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" id="lab_request_type{{$ind}}" name="laboratory_request_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", old('laboratory_request_list', $d->laboratory_request_list)))) ? 'checked' : ''}}>
                      <label class="form-check-label">{{$iref}}</label>
                    </div>
                    @endforeach
                    <hr>
                    <div><label for="">Imaging</label></div>
                    @foreach(App\Models\SyndromicRecords::refImagingRequest() as $ind => $iref)
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" id="imaging_type{{$ind}}" name="imaging_request_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", old('imaging_request_list', $d->imaging_request_list)))) ? 'checked' : ''}}>
                      <label class="form-check-label">{{$iref}}</label>
                    </div>
                    @endforeach
                    <hr>
                    <div><label for="">Alert Type</label></div>
                    @foreach(App\Models\SyndromicRecords::refAlert() as $ind => $iref)
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="checkbox" id="alert_type{{$ind}}" name="alert_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", old('alert_list', $d->alert_list)))) ? 'checked' : ''}}>
                      <label class="form-check-label">{{$iref}}</label>
                    </div>
                    @endforeach
                    <div id="disability_div" class="d-none mt-3">
                      <div><label for=""><b class="text-danger">*</b>Type of Disability</label></div>
                      @foreach(App\Models\SyndromicRecords::refAlertDisability() as $ind => $iref)
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="disability_type{{$ind}}" name="alert_ifdisability_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", old('alert_ifdisability_list', $d->alert_ifdisability_list)))) ? 'checked' : ''}}>
                        <label class="form-check-label">{{$iref}}</label>
                      </div>
                      @endforeach
                    </div>
                    <div class="form-group mt-3">
                      <label for="alert_description">Alert Description</label>
                      <textarea class="form-control" name="alert_description" id="alert_description" rows="3" style="text-transform: uppercase;">{{old('alert_description', $d->alert_description)}}</textarea>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label for="diagnosis_type"><b class="text-danger">*</b>Diagnosis Type</label>
                      <select class="form-control" name="diagnosis_type" id="diagnosis_type" required>
                        <option value="WORKING DIAGNOSIS" {{(old('diagnosis_type', $d->diagnosis_type) == 'WORKING DIAGNOSIS') ? 'selected' : ''}}>WORKING DIAGNOSIS</option>
                        <option value="ADMITTING DIAGNOSIS" {{(old('diagnosis_type', $d->diagnosis_type) == 'ADMITTING DIAGNOSIS') ? 'selected' : ''}}>ADMITTING DIAGNOSIS</option>
                        <option value="FINAL DIAGNOSIS" {{(old('diagnosis_type', $d->diagnosis_type) == 'FINAL DIAGNOSIS') ? 'selected' : ''}}>FINAL DIAGNOSIS</option>
                        <option value="NOT APPLICABLE" {{(old('diagnosis_type', $d->diagnosis_type) == 'NOT APPLICABLE') ? 'selected' : ''}}>NOT APPLICABLE (N/A)</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="dcnote_assessment"><b>Assessment/Diagnosis</b> <i>(Note: use commas <b>(,)</b> as separator per diagnosis)</i></label>
                      <textarea class="form-control" name="dcnote_assessment" id="dcnote_assessment" rows="3" style="text-transform: uppercase;" {{($required_bp) ? 'required' : ''}}>{{old('dcnote_assessment', $d->dcnote_assessment)}}</textarea>
                    </div>
                    <div class="form-group d-none" id="main_diagdiv">
                      <label for="main_diagnosis"><b class="text-danger">*</b>Main Diagnosis (ICD10)</label>
                      <select class="form-control" name="main_diagnosis[]" id="main_diagnosis" multiple>
                        @if(!is_null($d->main_diagnosis))
                        @foreach(explode("|", $d->main_diagnosis) as $diag_data)
                        <option value="{{$diag_data}}" selected>{{$diag_data}}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="dcnote_plan">Plan of Action / RX</label>
                      <textarea class="form-control" name="dcnote_plan" id="dcnote_plan" rows="3" style="text-transform: uppercase;">{{old('dcnote_plan', $d->dcnote_plan)}}</textarea>
                    </div>
                    <div class="form-group">
                      <label for="dcnote_diagprocedure">Diagnostic Procedure</label>
                      <textarea class="form-control" name="dcnote_diagprocedure" id="dcnote_diagprocedure" rows="3" style="text-transform: uppercase;">{{old('dcnote_diagprocedure', $d->dcnote_diagprocedure)}}</textarea>
                    </div>
                    <!--
                    <div class="form-group d-none" id="other_diagdiv">
                      <hr>
                      <label for="other_diagnosis">Other Diagnosis (ICD10)</label>
                      <select class="form-control" name="other_diagnosis[]" id="other_diagnosis" multiple>
                        @if(!is_null($d->other_diagnosis))
                        @foreach(explode(',', $d->other_diagnosis) as $od)
                        <option value="{{$od}}" selected>{{$d->getIcd10CodeString($od)}}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                    -->
                    @if($d->isHospitalRecord())
                    <div class="form-group">
                      <label for="procedure_done"><b class="text-danger">*</b>Procedure Done</label>
                      <select class="form-control" name="procedure_done" id="procedure_done" required>
                        <option value="" disabled {{(is_null(old('procedure_done', $d->procedure_done))) ? 'selected' : ''}}>Choose...</option>
                        @if($d->syndromic_patient->getAgeInt() >= 20)
                        <option value="MED CHECKUP" {{(old('procedure_done', $d->procedure_done) == 'MED CHECKUP') ? 'selected' : ''}}>MED CHECKUP</option>
                        @endif
                        @if($d->syndromic_patient->getAgeInt() <= 19)
                        <option value="PED CHECKUP" {{(old('procedure_done', $d->procedure_done) == 'PED CHECKUP') ? 'selected' : ''}}>PED CHECKUP</option>
                        @endif
                        <option value="SUTURED" {{(old('procedure_done', $d->procedure_done) == 'SUTURED') ? 'selected' : ''}}>SUTURED</option>
                        <option value="ATS/TT INJ" {{(old('procedure_done', $d->procedure_done) == 'ATS/TT INJ') ? 'selected' : ''}}>ATS/TT INJ</option>
                        <option value="FOR LABS" {{(old('procedure_done', $d->procedure_done) == 'FOR LABS') ? 'selected' : ''}}>FOR LABS</option>
                        <option value="PNCU" {{(old('procedure_done', $d->procedure_done) == 'PNCU') ? 'selected' : ''}}>PNCU</option>
                        <option value="MEDICO LEGAL" {{(old('procedure_done', $d->procedure_done) == 'MEDICO LEGAL') ? 'selected' : ''}}>MEDICO LEGAL</option>
                      </select>
                    </div>
                    @endif
                    <!--
                    <div class="form-group">
                      <label for="rx">RX</label>
                      <input type="text" class="form-control" name="rx" id="rx" value="{{old('rx', $d->rx)}}">
                    </div>
                    -->
                  </div>
                </div>
                <div class="row">
                  @php
                  if($d->isHospitalRecord()) {
                    $colsize1 = 4;
                    $colsize2 = 4;
                    $colsize3 = 4;
                  }
                  else {
                    $colsize1 = 6;
                    $colsize2 = 4;
                    $colsize3 = 6;
                  }
                  @endphp
                  <div class="col-md-{{$colsize1}}">
                    <div class="form-group">
                      <label for="outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
                      <select class="form-control" name="outcome" id="outcome" required>
                        @if($d->isHospitalRecord())
                        <option value="" disabled {{(is_null(old('outcome', $d->outcome))) ? 'selected' : ''}}>Choose...</option>
                        @endif
                        <option value="ALIVE" {{(old('outcome', $d->outcome) == 'ALIVE') ? 'selected' : ''}}>Alive (Active)</option>
                        <option value="RECOVERED" {{(old('outcome', $d->outcome) == 'RECOVERED') ? 'selected' : ''}}>Recovered</option>
                        <option value="DIED" {{(old('outcome', $d->outcome) == 'DIED') ? 'selected' : ''}}>Died</option>
                        <option value="DOA" {{(old('outcome', $d->outcome) == 'DOA') ? 'selected' : ''}}>Dead on Arrival (DOA)</option>
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
                  @if($d->isHospitalRecord())
                  <div class="col-md-{{$colsize2}}">
                    <div class="form-group">
                      <label for="disposition"><b class="text-danger">*</b>Disposition</label>
                      <select class="form-control" name="disposition" id="disposition" required>
                        <option value="" disabled {{(is_null(old('disposition', $d->disposition))) ? 'selected' : ''}}>Choose...</option>
                        <option value="SENT HOME" {{(old('disposition', $d->disposition) == 'SENT HOME') ? 'selected' : ''}}>SENT HOME</option>
                        <option value="THOC" {{(old('disposition', $d->disposition) == 'THOC') ? 'selected' : ''}}>THOC (Transfer to Hospital of Choice)</option>
                        <option value="HAMA" {{(old('disposition', $d->disposition) == 'HAMA') ? 'selected' : ''}}>HAMA (Home Against Medical Advice)</option>
                        <option value="ADMITTED" {{(old('disposition', $d->disposition) == 'ADMITTED') ? 'selected' : ''}}>ADMITTED</option>
                        <option value="TB DOTS" {{(old('disposition', $d->disposition) == 'TB DOTS') ? 'selected' : ''}}>TB-DOTS</option>
                        <option value="SENT TO JAIL" {{(old('disposition', $d->disposition) == 'SENT TO JAIL') ? 'selected' : ''}}>SENT TO JAIL</option>
                      </select>
                    </div>
                    <div id="admitted_div" class="d-none">
                      <div class="form-group">
                        <label for="is_discharged"><b class="text-danger">*</b>Discharged?</label>
                        <select class="form-control" name="is_discharged" id="is_discharged" required>
                          <option value="N" {{(old('is_discharged', $d->is_discharged) == 'N') ? 'selected' : ''}}>No</option>
                          <option value="Y"{{(old('is_discharged', $d->is_discharged) == 'Y') ? 'selected' : ''}}>Yes</option>
                        </select>
                      </div>
                      <div id="discharged_div" class="d-none">
                        <div class="form-group">
                          <label for="date_discharged"><b class="text-danger">*</b>Date Discharged</label>
                          <input type="date" class="form-control" name="date_discharged" id="date_discharged" value="{{old('date_discharged', $d->date_discharged)}}" max="{{date('Y-m-d', strtotime('+1 Day'))}}">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="tags"><b class="text-danger">*</b>Patient Tagging</label>
                      <select class="form-control" name="tags" id="tags" required>
                        <option value="" disabled {{(is_null(old('tags', $d->tags))) ? 'selected' : ''}}>Choose...</option>
                        @if($d->age_years >= 20)
                        <option value="MEDICAL" {{(old('tags', $d->tags) == 'MEDICAL') ? 'selected' : ''}}>Medical</option>
                        @endif
                        @if($d->age_years <= 19)
                        <option value="PEDIATRICS" {{(old('tags', $d->tags) == 'PEDIATRICS') ? 'selected' : ''}}>Pediatrics</option>
                        @endif
                        <option value="SURGICAL" {{(old('tags', $d->tags) == 'SURGICAL') ? 'selected' : ''}}>Surgical</option>
                        @if($d->syndromic_patient->sg() == 'F')
                        <option value="OB" {{(old('tags', $d->tags) == 'OB') ? 'selected' : ''}}>OB</option>
                        <option value="GYNE" {{(old('tags', $d->tags) == 'GYNE') ? 'selected' : ''}}>GYNE</option>
                        @endif
                        <option value="GENITO-URINARY" {{(old('tags', $d->tags) == 'GENITO-URINARY') ? 'selected' : ''}}>Genito-Urinary</option>
                        <option value="ORTHO" {{(old('tags', $d->tags) == 'ORTHO') ? 'selected' : ''}}>Ortho</option>
                        <option value="ENT" {{(old('tags', $d->tags) == 'ENT') ? 'selected' : ''}}>ENT</option>
                        <option value="FAMILY PLANNING" {{(old('tags', $d->tags) == 'FAMILY PLANNING') ? 'selected' : ''}}>Family Planning</option>
                        <option value="OPHTHA" {{(old('tags', $d->tags) == 'OPHTHA') ? 'selected' : ''}}>Ophtha</option>
                        <option value="ANIMAL BITE" {{(old('tags', $d->tags) == 'ANIMAL BITE') ? 'selected' : ''}}>Animal Bite</option>
                        <!-- <option value="MEDICO-LEGAL" {{(old('tags', $d->tags) == 'MEDICO-LEGAL') ? 'selected' : ''}}>Medico-Legal</option> -->
                        <option value="DERMATOLOGY" {{(old('tags', $d->tags) == 'DERMATOLOGY') ? 'selected' : ''}}>Dermatology</option>
                        <option value="DENTAL" {{(old('tags', $d->tags) == 'DENTAL') ? 'selected' : ''}}>Dental</option>
                        <option value="PSYCHIATRY" {{(old('tags', $d->tags) == 'PSYCHIATRY') ? 'selected' : ''}}>Psychiatry</option>
                        <!-- <option value="DOA" {{(old('tags', $d->tags) == 'DOA') ? 'selected' : ''}}>DOA</option> -->
                        <option value="VA" {{(old('tags', $d->tags) == 'VA') ? 'selected' : ''}}>Vehicular Accident (VA)</option>
                      </select>
                    </div>
                  </div>
                  @endif
                  <div class="col-md-{{$colsize3}}">
                    <div class="form-group">
                      <label for="name_of_physician"><span class="text-danger font-weight-bold">*</span>Attending Physician</label>
                      <select class="form-control" name="name_of_physician" id="name_of_physician" required>
                        @if($d->isHospitalRecord())
                        <option value="" disabled {{(is_null(old('name_of_physician', $d->name_of_physician))) ? 'selected' : ''}}>Choose...</option>
                          @foreach($doclist as $dr)
                          <option value="{{$dr->doctor_name}}" {{(old('name_of_physician', $d->name_of_physician) == $dr->doctor_name) ? 'selected' : ''}}>{{$dr->doctor_name}}</option>
                          @endforeach
                        @else
                          @foreach($doclist as $dr)
                          <option value="{{$dr->doctor_name}}" {{(old('name_of_physician', $d->name_of_physician) == $dr->doctor_name) ? 'selected' : ''}} class="{{($dr->dru_name == 'CHO GENERAL TRIAS') ? 'official_drlist' : 'outside_drlist'}}">{{$dr->doctor_name}} ({{$dr->dru_name}})</option>
                          @endforeach
                        @endif
                        <option value="OTHERS" {{(old('name_of_physician', $d->name_of_physician) == 'OTHERS') ? 'selected' : ''}}>OTHERS</option>
                      </select>
                    </div>
                    <div id="ifotherdoctor" class="d-none">
                      <div class="form-group">
                        <label for="other_doctor"><b class="text-danger">*</b>Other Name of Attending Physician</label>
                        <input type="text" class="form-control" name="other_doctor" id="other_doctor" value="{{old('other_doctor')}}" style="text-transform: uppercase;">
                      </div>
                    </div>
                  </div>
                </div>
                @if(!$d->isHospitalRecord())
                <div class="form-group mt-3">
                  <label for="remarks">Remarks</label>
                  <textarea class="form-control" name="remarks" id="remarks" rows="3" style="text-transform: uppercase;">{{old('remarks', $d->remarks)}}</textarea>
                </div>
                @else
                <div class="form-group">
                  <label for="remarks">Remarks</label>
                  <input type="text" class="form-control" name="remarks" id="remarks" value="{{old('remarks', $d->remarks)}}" style="text-transform: uppercase;">
                </div>
                @endif

                <div class="card" id="labResultsCard">
                  <div class="card-header">
                    <div class="d-flex justify-content-between">
                      <div><b>Laboratory Data</b></div>
                      <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#selectLaboratoryModal">Add</button></div>
                    </div>
                  </div>
                  <div class="card-body text-center">
                    @if(session('lab_msg'))
                    <div class="alert alert-{{session('lab_msgtype')}}" role="alert">
                        {{session('lab_msg')}}
                    </div>
                    @endif
                    @if($lab_list->count() != 0)
                    <div>
                      <table class="table table-striped table-bordered">
                        <thead class="text-center thead-light">
                          <tr>
                            <th>#</th>
                            <th>Type of Test</th>
                            <th>Case Code</th>
                            <th>Date Collected / by</th>
                            <th>Result</th>
                            <th>Created At / By</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($lab_list as $ind => $lab)
                          <tr class="text-center">
                            <td>{{$ind+1}}</td>
                            <td>{{$lab->test_type}}</td>
                            <td>{{$lab->case_code}}</td>
                            <td>
                              <div>{{date('M. d, Y (D)', strtotime($lab->date_collected))}}</div>
                            </td>
                            <td>{{$lab->result}}</td>
                            <td>
                              <div>{{date('M. d, Y (D)', strtotime($lab->date_collected))}}</div>
                              <div>by {{$lab->user->name}}</div>
                            </td>
                            <td>
                              <a href="" class="btn btn-primary">View/Edit</a>
                              <a href="{{route('syndromic_print_labresult', $lab->id)}}">Print</a>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                    @else
                    <h6 class="text-center">Laboratory data is currently empty.</h6>
                    @endif
                  </div>
                </div>
            </div>
            <div class="card-footer">
              @if(!$d->isHospitalRecord())
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
              @endif
              @if($d->hasPermissionToUpdate())
                <button type="submit" class="btn btn-success btn-block" name="submit" value="update" id="submitBtn">Update (CTRL + S)</button>
              @else
              <h6 class="text-center"><b class="text-danger">YOU DON'T HAVE PERMISSION TO UPDATE THIS RECORD.</b></h6>
              @endif
            </div>
        </div>
    </form>
</div>

@php
$the_record_id = $d->id;
@endphp
@include('syndromic.laboratory.add_laboratory_modal')

@if($d->outcome != 'DIED')
@if(in_array('ITR_ENCODER', auth()->user()->getPermissions()) || in_array('ITR_ADMIN', auth()->user()->getPermissions()) || in_array('GLOBAL_ADMIN', auth()->user()->getPermissions()) || in_array('ITR_HOSPITAL_ENCODER', auth()->user()->getPermissions()))
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
            <label for="medcert_generated_date"><span class="text-danger font-weight-bold">*</span>{{(!(auth()->user()->isSyndromicHospitalLevelAccess())) ? 'Date' : 'Date Examined/Confined'}}</label>
            <input type="date" class="form-control" value="{{old('medcert_generated_date', ($d->medcert_enabled == 0) ? date('Y-m-d') : $d->medcert_generated_date)}}" name="medcert_generated_date" id="medcert_generated_date" max="{{date('Y-m-d')}}" required>
          </div>
          <div class="form-group">
            <label for="medcert_validity_date"><span class="text-danger font-weight-bold">*</span>{{(!(auth()->user()->isSyndromicHospitalLevelAccess())) ? 'Effectivity Date' : 'Date of Issuance'}}</label>
            <input type="date" class="form-control" value="{{old('medcert_validity_date', ($d->medcert_enabled == 0) ? date('Y-m-d') : $d->medcert_validity_date)}}" name="medcert_validity_date" id="medcert_validity_date" min="{{date('Y-m-d')}}" max="{{'12-31-'.date('Y')}}" required>
          </div>
          @if(!(auth()->user()->isSyndromicHospitalLevelAccess()))
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
                <input type="date" class="form-control" value="{{old('medcert_end_date', $d->medcert_end_date)}}" name="medcert_end_date" id="medcert_end_date" max="{{date('Y-m-t')}}">
              </div>
            </div>
          </div>
          @endif
          <div class="form-group">
            <label for="medcert_purpose">Purpose (Issued upon request of)</label>
            <input type="text" class="form-control" name="medcert_purpose" id="medcert_purpose">
          </div>
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

<div class="modal fade" id="heightConverter" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Foot to Centimeter Converter</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="feet"><b class="text-danger">*</b>Feet</label>
                <input type="number" class="form-control" name="feet" id="feet">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="inches"><b class="text-danger">*</b>Inches</label>
                <input type="number" class="form-control" name="inches" id="inches">
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" name="convertBtn" id="convertBtn" class="btn btn-success btn-block">Convert</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
      $('#convertBtn').click(function () {
          // Get values from input fields
          const feet = parseInt($('#feet').val());
          const inches = parseInt($('#inches').val());

          // Validate input
          if (isNaN(feet) || isNaN(inches) || feet < 0 || inches < 0) {
              alert('Please enter valid values for feet and inches.');
              return;
          }

          // Convert height to centimeters
          const totalInches = (feet * 12) + inches;
          const cm = totalInches * 2.54;

          // Display result
          $('#height').val(cm.toFixed(2));

          $('#heightConverter').modal('toggle');
      });
  });
</script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const hash = window.location.hash;
    if (hash) {
      const element = document.querySelector(hash);
      if (element) {
        element.scrollIntoView({ behavior: "smooth" });
      }
    }
  });

  function validateForm() {
      /*
      var checkboxes = document.querySelectorAll('.form-check-input');
      var isChecked = false;

      checkboxes.forEach(function (checkbox) {
          if (checkbox.checked) {
              isChecked = true;
          }
      });

      if (!isChecked) {
          alert('Please check at least one (1) symptoms before submitting the form.');
          return false; // Prevent form submission
      }

      return true; // Allow form submission if at least one checkbox is checked
      */

      var checkboxesInDiv = document.querySelectorAll('#purpose_div input[type="checkbox"]');
      var checked = false;

      for (var i = 0; i < checkboxesInDiv.length; i++) {
          if (checkboxesInDiv[i].checked) {
              checked = true;
              break;
          }
      }

      if (!checked) {
        alert('Please check at least one (1) PURPOSE before submitting the form.');
          return false; // Prevent form submission
      }

      @if($required_symptoms)
      var checkboxesInDiv2 = document.querySelectorAll('#sas_checkboxes input[type="checkbox"]');
      var checked2 = false;

      for (var i = 0; i < checkboxesInDiv2.length; i++) {
          if (checkboxesInDiv2[i].checked) {
              checked2 = true;
              break;
          }
      }

      if (!checked2) {
          alert('Please check at least one (1) SYMPTOMS before submitting the form.');
          return false; // Prevent form submission
      }
      @endif

      return true; // Allow form submission if at least one checkbox is checked
  }

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

  $('input[name="alert_list[]"][value="DISABILITY"]').change(function (e) { 
    e.preventDefault();
    if ($(this).is(':checked')) {
      $('#disability_div').removeClass('d-none');
    } else {
      $('#disability_div').addClass('d-none');
    }
  }).trigger('change');

  $('#main_diagnosis').select2({
      theme: "bootstrap",
      placeholder: 'Search by ICD10 Code or Description ...',
      ajax: {
          url: "{{route('syndromic_icd10list')}}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
              return {
                  results:  $.map(data, function (item) {
                      return {
                        text: item.desc,
                        id: item.desc,
                      }
                  })
              };
          },
          cache: true
      }
  });

  /*
  $('#other_diagnosis').select2({
      theme: "bootstrap",
      placeholder: 'Search by ICD10 Code or Description ...',
      ajax: {
          url: "{{route('syndromic_icd10list')}}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
              return {
                  results:  $.map(data, function (item) {
                      return {
                          text: item.text,
                          id: item.id,
                          value: item.id,
                      }
                  })
              };
          },
          cache: true
      }
  });
  */

  var getage = {{$d->syndromic_patient->getAgeInt()}};

  $('#name_of_physician').select2({
    theme: 'bootstrap',
  });

  @if($d->isHospitalRecord())
  @if($d->syndromic_patient->gender == 'FEMALE' && $d->syndromic_patient->getAgeInt() > 10)
  $('#is_pregnant').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'Y') {
      $('#ifPregnantDiv').removeClass('d-none');
      $('#lmp').prop('required', true);
    }
    else {
      $('#addClass').removeClass('d-none');
      $('#lmp').prop('required', false);
    }
  }).trigger('change');
  @endif
  @endif

  @if($d->isHospitalRecord())
  $('#disposition').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'ADMITTED') {
      $('#admitted_div').removeClass('d-none');
      $('#is_discharged').prop('required', true);
    }
    else {
      $('#admitted_div').addClass('d-none');
      $('#is_discharged').prop('required', false);
    }
  }).trigger('change');

  $('#is_discharged').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'Y') {
      $('#discharged_div').removeClass('d-none');
      $('#date_discharged').prop('required', true);
    }
    else {
      $('#discharged_div').addClass('d-none');
      $('#date_discharged').prop('required', false);
    }
  }).trigger('change');
  @endif

  $('#diagnosis_type').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'FINAL DIAGNOSIS' || $(this).val() == 'WORKING DIAGNOSIS') {
      //$('#main_diagnosis').prop('required', true);
      $('#main_diagdiv').removeClass('d-none');
      
      //$('#other_diagdiv').removeClass('d-none');
    }
    else if($(this).val() == 'ADMITTING DIAGNOSIS') {
      //$('#main_diagnosis').prop('required', false);
      $('#main_diagdiv').addClass('d-none');

      //$('#other_diagdiv').removeClass('d-none');
    }
    else {
      //$('#main_diagnosis').prop('required', false);
      $('#main_diagdiv').addClass('d-none');

      //$('#other_diagdiv').addClass('d-none');
    }
  }).trigger('change');

  $('#checkup_type').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'REQUEST_MEDS') {
      $('#if_noncheckup').removeClass('d-none');
      $('#outsidecho_name').prop('required', true);
      $('.required_before').addClass('d-none'); //Weight Asterisk
      @if($required_weight)
      $('#weight').prop('required', false);
      @endif
      //$('#name_of_physician').val('').trigger('change');
      //$('#name_of_physician').val('OTHERS').trigger('change');
      $('.official_drlist').prop('disabled', true);
      $('.outside_drlist').prop('disabled', false);
      //$('#ifotherdoctor').removeClass('d-none');
      //$('#other_doctor').prop('required', true);
    } else {
      $('#if_noncheckup').addClass('d-none');
      $('#outsidecho_name').prop('required', false);
      $('.required_before').removeClass('d-none'); //Weight Asterisk
      @if($required_weight)
      $('#weight').prop('required', true);
      @endif
      $('.official_drlist').prop('disabled', false);
      $('.outside_drlist').prop('disabled', true);
      //$('#ifotherdoctor').addClass('d-none');
      //$('#other_doctor').prop('required', false);
    }
  }).trigger('change');

  $('#name_of_physician').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'OTHERS') {
      $('#ifotherdoctor').removeClass('d-none');
      $('#other_doctor').prop('required', true);
    } else {
      $('#ifotherdoctor').addClass('d-none');
      $('#other_doctor').prop('required', false);
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
      $('#other_symptoms_onset_remarks').prop('required', true);
    }
    else {
      $('#other_symptoms_div').addClass('d-none');
      $('#other_symptoms_onset_remarks').prop('required', false);
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
    else if($(this).val() == 'DOA') {
      $('#if_recovered').addClass('d-none');
      $('#if_died').removeClass('d-none');
      $('#outcome_recovered_date').prop('required', false);
      $('#outcome_died_date').prop('required', true);
      $('#outcome_died_date').prop('readonly', true);
      $('#outcome_died_date').val('{{date("Y-m-d")}}');
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

  var rq_height = {{$required_height}};
  var rq_weight = {{$required_weight}};

  let today = new Date();
  let year = today.getFullYear();
  let month = String(today.getMonth() + 1).padStart(2, '0'); // Add leading zero to month
  let day = String(today.getDate()).padStart(2, '0'); // Add leading zero to day

  let currentDate = "{{date('Y-m-d')}}";

  $('input[name="consultation_type[]"][value="DENTAL CARE"]').change(function() {
    if ($(this).is(':checked')) {
      $('#weight').prop('required', false);
      $('#height').prop('required', false);

      if($('#medcert_start_date').val() == '') {
        $('#medcert_start_date').val(currentDate);
      }
      
      $('#w_ast').text('');
      $('#h_ast').text('');
    } else {
      if(rq_height == 1) {
        $('#height').prop('required', true);
        $('#h_ast').text('*');
      }

      if(rq_weight == 1) {
        $('#weight').prop('required', true);
        $('#w_ast').text('*');
      }
    }
  }).trigger('change');
</script>
@endsection