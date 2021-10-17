@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('facility.initdischarge', ['id' => $data->id])}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">Discharge Recovered Patient #{{$data->records->id}} <strong>({{$data->records->getName()}})</strong></div>
                <div class="card-body">
                    <div class="form-group">
                      <label for="dispoDate"><span class="text-danger font-weight-bold">*</span>Date of Recovery / Discharge</label>
                      <input type="date" class="form-control" name="dispoDate" id="dispoDate" min="{{date('Y-m-d', strtotime('-14 Days'))}}" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
                    </div>
                    <div class="form-group">
                      <label for="facility_remarks">Remarks <small>(Optional)</small></label>
                      <input type="text" class="form-control" name="facility_remarks" id="facility_remarks">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Note: You cannot revert this process once it is done. Click OK to Proceed.')">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection