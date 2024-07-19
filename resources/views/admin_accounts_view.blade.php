@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('admin_account_update', $d->id)}}" method="POST">
        <div class="card">
            <div class="card-header"><b>Edit User Information</b></div>
            <div class="card-body">
                <div class="form-group">
                  <label for="name"><b class="text-danger">*</b>Name</label>
                  <input type="text" class="form-control" name="name" id="name" value="{{old('name', $d->name)}}" required>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                          <label for="enabled"><b class="text-danger">*</b>Account Enabled</label>
                          <select class="form-control" name="enabled" id="enabled" required>
                            <option value="0" {{(old('enabled', $d->id) == '0' ? 'selected' : '')}}>No</option>
                            <option value="1" {{(old('enabled', $d->id) == '1' ? 'selected' : '')}}>Yes</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="encoder_stats_visible"><b class="text-danger">*</b>Included in Encoder Stats</label>
                            <select class="form-control" name="encoder_stats_visible" id="encoder_stats_visible" required>
                              <option value="0" {{(old('encoder_stats_visible', $d->encoder_stats_visible) == '0' ? 'selected' : '')}}>No</option>
                              <option value="1" {{(old('encoder_stats_visible', $d->encoder_stats_visible) == '1' ? 'selected' : '')}}>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="itr_facility_id"><b class="text-danger">*</b>Link OPD Facility ID</label>
                            <select class="form-control" name="itr_facility_id" id="itr_facility_id" required>
                                <option value="NONE">N/A</option>
                                @foreach($opd_branches as $b)
                                <option value="{{$b->id}}" {{($d->itr_facility_id == $b->id) ? 'selected': ''}}>{{$b->facility_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="pharmacy_branch_id"><b class="text-danger">*</b>Link Pharmacy Branch ID</label>
                            <select class="form-control" name="pharmacy_branch_id" id="pharmacy_branch_id" required>
                                <option value="NONE">N/A</option>
                                @foreach($pharma_branches as $b)
                                <option value="{{$b->id}}" {{($d->pharmacy_branch_id == $b->id) ? 'selected': ''}}>{{$b->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </form>
</div>
@endsection