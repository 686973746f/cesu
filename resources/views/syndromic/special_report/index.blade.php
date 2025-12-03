@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('opd_sp_process')}}" method="GET">
            <div class="card">
                <div class="card-header"><b>OPD SP</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="startDate"><b class="text-danger">*</b>Start Date</label>
                                <input type="date" class="form-control" name="date1" id="date1" min="2020-01-01" max="{{date('Y-m-t')}}" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="endDate"><b class="text-danger">*</b>End Date</label>
                                <input type="date" class="form-control" name="date2" id="date2" min="2020-01-01" max="{{date('Y-m-t')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for=""><b class="text-danger">*</b>Select Barangay</label>
                      <select class="form-control" name="brgy_id" id="brgy_id">
                        <option value="" disabled selected>Choose...</option>
                        <option value="ALL">All Barangay</option>
                        @foreach(App\Models\EdcsBrgy::where('city_id', 388)->orderBy('name', 'ASC')->get() as $b)
                        <option value="{{$b->id}}">{{$b->alt_name ?: $b->name}}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block" name="submit" value="demographic_profile">View Demographic Profile of Patients</button>
                </div>
            </div>
        </form>
    </div>
@endsection