@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>List of Doctors</b></div>
                <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#newDoctor"></button></div>
            </div>
            
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Facility</th>
                        <th>License No.</th>
                        <th>Enabled</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $d)
                    <tr>
                        <td></td>
                        <td><a href="">{{$d->doctor_name}}</a></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<form action="{{route('syndromic_admin_doctors_store')}}" method="POST">
    @csrf
    <div class="modal fade" id="newDoctor" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Doctor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="facility_id"><b class="text-danger">*</b>Select Facility</label>
                        <select class="form-control" name="facility_id" id="facility_id" required>
                            <option value="" disabled {{(is_null(old('facility_id'))) ? 'selected' : ''}}>Choose...</option>
                            @foreach($facility_list as $f)
                            <option value="{{$f->id}}">{{$f->facility_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="doctor_name"><b class="text-danger">*</b>Name of Doctor</label>
                      <input type="text" class="form-control" name="doctor_name" id="doctor_name" value="{{old('doctor_name')}}" style="text-transform: uppercase;" required>
                    </div>
                    <div class="form-group">
                        <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                        <select class="form-control" name="gender" id="gender" required>
                            <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="M" {{(old('gender') == 'M') ? 'selected' : ''}}>Male</option>
                            <option value="F" {{(old('gender') == 'F') ? 'selected' : ''}}>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="position"><b class="text-danger">*</b>Position</label>
                        <input type="text" class="form-control" name="position" id="position" value="{{old('position')}}" style="text-transform: uppercase;" required>
                    </div>
                    <div class="form-group">
                        <label for="reg_no"><b class="text-danger">*</b>Registration No.</label>
                        <input type="text" class="form-control" name="reg_no" id="reg_no" value="{{old('reg_no')}}" style="text-transform: uppercase;" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#facility_id').select2({
        theme: 'bootstrap'
    });
</script>
@endsection