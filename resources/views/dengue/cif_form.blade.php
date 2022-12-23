@extends('layouts.app')

@section('content')
@if($c->exists)
<!--Edit Page-->
<form action="{{route('mp.updatecif', ['cif_id' => $c->id])}}" method="POST">
@else
<!--Create Page-->
<form action="{{route('mp.storecif', ['record_id' => $d->id])}}" method="POST">
@endif
    <div class="card">
        <div class="card-header">Dengue CIF</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="DateOfEntry"><span class="text-danger font-weight-bold">*</span>Date of Entry</label>
                        <input type="date"class="form-control" name="DateOfEntry" id="DateOfEntry" value="{{old('DateOfEntry', $c)}}" max="{{date('Y-m-d')}}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                      <label for="PatientNum" class="form-label">Patient Number</label>
                      <input type="text" class="form-control" name="PatientNum" id="PatientNum" value="{{old('PatientNum', $c)}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Admitted"><span class="text-danger font-weight-bold">*</span>Patient Admitted?</label>
                        <select class="form-control" name="Admitted" id="Admitted" required>
                            <option value="" disabled {{(is_null(old('Admitted', $c))) ? 'selected' : ''}}>Choose...</option>
                            <option value="1" {{(old('Admitted', $c) == 1) ? 'selected' : ''}}>Yes</option>
                            <option value="0" {{(old('Admitted', $c) == 2) ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="DAdmit"><span class="text-danger font-weight-bold">*</span>Date Admitted/Seen/Consulted</label>
                      <input type="date" class="form-control" name="DAdmit" id="DAdmit" min="{{date('Y-01-01')}}" max="{{date('Y-m-d')}}" value="{{old('DAdmit', $c)}}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="DOnset"><span class="text-danger font-weight-bold">*</span>Date of Onset</label>
                      <input type="date" class="form-control" name="DOnset" id="DOnset" min="{{date('Y-01-01')}}" max="{{date('Y-m-d')}}" value="{{old('DOnset', $c)}}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="LabTest">Lab Test</label>
                      <select class="form-control" name="LabTest" id="LabTest" required>
                        <option value="" disabled {{(is_null(old('LabTest', $c))) ? 'selected' : ''}}>Choose...</option>
                        <option value="lgM Elisa" {{(old('LabTest', $c) == "lgM Elisa") ? 'selected' : ''}}>lgM Elisa</option>
                        <option value="lgG Elisa" {{(old('LabTest', $c) == "lgG Elisa") ? 'selected' : ''}}>lgG Elisa</option>
                        <option value="NS1-Ag" {{(old('LabTest', $c) == "NS1-Ag") ? 'selected' : ''}}>NS1-Ag</option>
                        <option value="PCR" {{(old('LabTest', $c) == "PCR") ? 'selected' : ''}}>PCR</option>
                        <option value="Not Done" {{(old('LabTest', $c) == "Not Done") ? 'selected' : ''}}>Not Done</option>
                        <option value="lgM & lgG EliSA" {{(old('LabTest', $c) == "lgM & lgG EliSA") ? 'selected' : ''}}>lgM & lgG EliSA</option>
                      </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="Type">Type</label>
                      <select class="form-control" name="Type" id="Type" required>
                        <option value="" disabled {{(is_null(old('Type', $c))) ? 'selected' : ''}}>Choose...</option>
                        <option value="DF" {{(old('Type', $c) == "DF") ? 'selected' : ''}}>DF</option>
                        <option value="DHF" {{(old('Type', $c) == "DHF") ? 'selected' : ''}}>DHF</option>
                        <option value="DSS" {{(old('Type', $c) == "DSS") ? 'selected' : ''}}>DSS</option>
                      </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="LabRes">Lab Result</label>
                          <select class="form-control" name="LabRes" id="LabRes">
                          </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="ClinClass"><span class="text-danger font-weight-bold">*</span>Clinical Classification</label>
                      <select class="form-control" name="ClinClass" id="ClinClass" required>
                        <option value="" disabled {{(is_null(old('ClinClass', $c))) ? 'selected' : ''}}>Choose...</option>
                        <option value="WITH WARNING SIGNS" {{(old('ClinClass', $c) == "WITH WARNING SIGNS") ? 'selected' : ''}}>WITH WARNING SIGNS</option>
                        <option value="SEVERE DENGUE" {{(old('ClinClass', $c) == "SEVERE DENGUE") ? 'selected' : ''}}>SEVERE DENGUE</option>
                        <option value="NO WARNING SIGNS" {{(old('ClinClass', $c) == "NO WARNING SIGNS") ? 'selected' : ''}}>NO WARNING SIGNS</option>
                      </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="CaseClassification"><span class="text-danger font-weight-bold">*</span>Laboratory Classification</label>
                        <select class="form-control" name="CaseClassification" id="CaseClassification" required>
                          <option value="Suspect" {{(old('CaseClassification', $c) == "Suspect") ? 'selected' : ''}}>Suspect</option>
                          <option value="Probable" {{(old('CaseClassification', $c) == "Probable") ? 'selected' : ''}}>Probable</option>
                          <option value="Confirmed" {{(old('CaseClassification', $c) == "Confirmed") ? 'selected' : ''}}>Confirmed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
                        <select class="form-control" name="Outcome" id="Outcome" required>
                          <option value="A" {{(old('Outcome', $c) == "A") ? 'selected' : ''}}>Alive</option>
                          <option value="D" {{(old('Outcome', $c) == "D") ? 'selected' : ''}}>Died</option>
                          <option value="U" {{(old('Outcome', $c) == "U") ? 'selected' : ''}}>Unknown</option>
                        </select>
                    </div>
                    <div class="form-group d-none" id="div_died">
                        <label for="DateDied"><span class="text-danger font-weight-bold">*</span>Date Died</label>
                        <input type="date" class="form-control" name="DateDied" id="DateDied" min="{{date('Y-01-01')}}" max="{{date('Y-m-d')}}" value="{{old('DateDied', $c)}}" required>
                      </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block" id="submitBtn">{{($c->exists) ? 'Update' : 'Save'}} (CTRL + S)</button>
        </div>
    </div>
</form>
@endsection