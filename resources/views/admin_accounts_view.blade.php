@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('admin_account_update', $d->id)}}" method="POST">
        @csrf
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
                            <option value="0" {{(old('enabled', $d->enabled) == '0' ? 'selected' : '')}}>No</option>
                            <option value="1" {{(old('enabled', $d->enabled) == '1' ? 'selected' : '')}}>Yes</option>
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
                        <div class="form-group">
                            <label for="etcl_bhs_id">eTCL BHS ID</label>
                            <select class="form-control" name="etcl_bhs_id" id="etcl_bhs_id">
                                <option value="">N/A</option>
                                @foreach($opd_branches as $b)
                                <option value="{{$b->id}}" {{($d->etcl_bhs_id == $b->id) ? 'selected': ''}}>{{$b->facility_name}} (#{{$b->id}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="switch_bhs_list">Switch BHS List (For multiple BHS Handler)</label>
                            <input type="text" class="form-control" name="switch_bhs_list" id="switch_bhs_list" value="{{old('switch_bhs_list', $d->switch_bhs_list)}}">
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
                <div class="row">
                    <div class="col-6">
                        @if(!is_null($opd_doctors))
                        <div class="form-group">
                            <label for="itr_doctor_id"><b class="text-danger">*</b>Default OPD Physician</label>
                            <select class="form-control" name="itr_doctor_id" id="itr_doctor_id" required>
                                <option value="NONE">N/A</option>
                                @foreach($opd_doctors as $b)
                                <option value="{{$b->id}}" {{($d->itr_doctor_id == $b->id) ? 'selected': ''}}>{{$b->doctor_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <p>Please select OPD Facility ID first to unlock default Physician Selection.</p>
                        @endif
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="abtc_default_vaccinationsite_id"><b class="text-danger">*</b>Link ABTC Branch ID</label>
                            <select class="form-control" name="abtc_default_vaccinationsite_id" id="abtc_default_vaccinationsite_id" required>
                                <option value="NONE">N/A</option>
                                @foreach($abtc_branches as $b)
                                <option value="{{$b->id}}" {{($d->abtc_default_vaccinationsite_id == $b->id) ? 'selected': ''}}>{{$b->site_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="permission_list"><b class="text-danger">*</b>Permission List</label>
                    <select class="form-control" name="permission_list[]" id="permission_list" required multiple>
                        @foreach($perm_list as $b)
                        <option value="{{$b}}" {{(in_array($b, explode(",", $d->permission_list))) ? 'selected': ''}}>{{$b}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
    $('#permission_list').select2({
        theme: 'bootstrap',
    });
</script>
@endsection