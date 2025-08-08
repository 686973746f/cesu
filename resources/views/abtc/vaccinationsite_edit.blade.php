@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('abtc_vaccinationsite_update', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <b>Edit ABTC Facility</b>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="site_name"><b class="text-danger">*</b>Name of ABTC Facility</label>
                          <input type="text" class="form-control" name="site_name" id="site_name" style="text-transform: uppercase;" max="255" value="{{old('site_name', $d->site_name)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="ph_facility_name"><b class="text-danger">*</b>Philhealth Facility Name</label>
                          <input type="text" class="form-control" name="ph_facility_name" id="ph_facility_name" style="text-transform: uppercase;" max="255" value="{{old('ph_facility_name', $d->ph_facility_name)}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="ph_facility_code"><b class="text-danger">*</b>Philhealth Facility Code</label>
                          <input type="text" class="form-control" name="ph_facility_code" id="ph_facility_code" style="text-transform: uppercase;" max="255" value="{{old('ph_facility_code', $d->ph_facility_code)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="ph_doh_certificate"><b class="text-danger">*</b>DOH Certificate No.</label>
                          <input type="text" class="form-control" name="ph_doh_certificate" id="ph_doh_certificate" style="text-transform: uppercase;" max="255" value="{{old('ph_doh_certificate', $d->ph_doh_certificate)}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="ph_address_houseno"><b class="text-danger">*</b>Facility House/Lot No.</label>
                          <input type="text" class="form-control" name="ph_address_houseno" id="ph_address_houseno" style="text-transform: uppercase;" max="255" value="{{old('ph_address_houseno', $d->ph_address_houseno)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="ph_address_houseno"><b class="text-danger">*</b>Name of Head to Sign Philhealth Claims</label>
                          <select class="form-control" name="ph_head_id" id="ph_head_id">
                            <option value="">N/A</option>
                            @foreach($doctor_list as $d)
                            <option value="{{$d->id}}" {{(old('ph_professional1_id', $d->ph_head_id) == $d->id) ? 'selected' : ''}}>{{$d->getName()}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="ph_professional1_id"><b class="text-danger">*</b>Name of Professional #1</label>
                          <select class="form-control" name="ph_professional1_id" id="ph_professional1_id">
                            <option value="">N/A</option>
                            @foreach($doctor_list as $d)
                            <option value="{{$d->id}}" {{(old('ph_professional1_id', $d->ph_professional1_id) == $d->id) ? 'selected' : ''}}>{{$d->getName()}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="ph_professional2_id"><b class="text-danger">*</b>Name of Professional #2</label>
                          <select class="form-control" name="ph_professional2_id" id="ph_professional2_id">
                            <option value="">N/A</option>
                            @foreach($doctor_list as $d)
                            <option value="{{$d->id}}" {{(old('ph_professional2_id', $d->ph_professional2_id) == $d->id) ? 'selected' : ''}}>{{$d->getName()}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="ph_professional3_id"><b class="text-danger">*</b>Name of Professional #3</label>
                          <select class="form-control" name="ph_professional3_id" id="ph_professional3_id">
                            <option value="">N/A</option>
                            @foreach($doctor_list as $d)
                            <option value="{{$d->id}}" {{(old('ph_professional3_id', $d->ph_professional3_id) == $d->id) ? 'selected' : ''}}>{{$d->getName()}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="ph_accountant_name_position"><b class="text-danger">*</b>Name of Accountant (Format: Name/Designation)</label>
                    <input type="text" class="form-control" name="ph_accountant_name_position" id="ph_accountant_name_position" style="text-transform: uppercase;" max="255" value="{{old('ph_accountant_name_position', $d->ph_accountant_name_position)}}" pattern="^.+/.+$" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Update</button>
            </div>
        </div>
    </form>
</div>
@endsection