@extends('layouts.app')

@section('content')
<form action="{{route('walkin_part2')}}" method="GET">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><b>Anti-Rabies Vaccination - Walk in Registration ({{session('vaccination_site_name')}})</b></div>
                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            <b>Note:</b> All fields marked with an asterisk (<strong class="text-danger">*</strong>) are required fields.
                        </div>
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <div class="mb-3">
                            <label for="fname" class="form-label"><b class="text-danger">*</b>Unang Pangalan/First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="JUAN JR" required>
                        </div>
                        <div class="mb-3">
                            <label for="mname" class="form-label">Gitnang Pangalan/Middle Name <small>(If Applicable)</small></label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="SANCHEZ">
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="lname" class="form-label"><b class="text-danger">*</b>Apelyido/Surname</label>
                                    <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="DELA CRUZ" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="suffix" class="form-label">Suffix <i><small>(If Applicable)</small></i></label>
                                    <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" maxlength="3" placeholder="e.g JR, SR, III, IV">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="bdate" class="form-label"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection