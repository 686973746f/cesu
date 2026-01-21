@extends('layouts.app')

@section('content')
<form action="{{route('edcs_addcase_store', request()->input('disease'))}}" method="POST">
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

            
        </div>
    </div>
</form>
@endsection