@extends('layouts.app')

@section('content')
    <form action="{{route('edcs_facility_weeklysubmission_process', [$f->sys_code1, $year, $mw])}}" method="POST">
        <div class="card">
            <div class="card-header">
                <div>
                    <div><b>Weekly Submission</b></div>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                  <label for=""></label>
                  <select class="form-control" name="" id="">
                    <option></option>
                  </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Submit</button>
            </div>
        </div>
    </form>
@endsection