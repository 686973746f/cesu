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
                <hr>
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
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="RashSores" id="RashSores" value="Y">
                                    Rash
                                  </label>
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

                                <div class="form-group">
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><b>EXPOSURE HISTORY</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="Travel"><b class="text-danger"></b>Is there a history of travel within 12 weeks to an area with ongoing epidemic of HFMD or EV Disease?</label>
                                    <select class="form-control" name="Travel" id="Travel" required>
                                        <option value="" disabled {{(is_null(old('Travel'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('Travel') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('Travel') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="OthExposure"><b class="text-danger"></b>Are there other known HFMD cases in the community?</label>
                                    <select class="form-control" name="OthExposure" id="OthExposure" required>
                                        <option value="" disabled {{(is_null(old('Travel'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('OthExposure') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('OthExposure') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">

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

@endsection