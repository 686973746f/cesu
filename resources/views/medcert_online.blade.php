@extends('layouts.app')

@section('content')
<form action="{{route('onlinemedcert_check')}}" method="POST">
    @csrf
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><b>Online Medical Certificate Checker</b></div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}}" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <div class="alert alert-info" role="alert">
                            Note: All fields marked with an asterisk (<span class="text-danger font-weight-bold">*</span>) are required.
                        </div>
                        <div class="mb-3">
                          <label for="fname" class="form-label"><b class="text-danger">*</b>First Name (and Suffix)</label>
                          <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="JUAN JR" required>
                        </div>
                        <div class="mb-3">
                            <label for="mname" class="form-label">Middle Name <small>(If Applicable)</small></label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="SANCHEZ">
                        </div>
                        <div class="mb-3">
                            <label for="lname" class="form-label"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="DELA CRUZ" required>
                        </div>
                        <div class="mb-3">
                            <label for="bdate" class="form-label"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="date_swabbed" class="form-label"><b class="text-danger">*</b>Date Swabbed</label>
                            <input type="date" class="form-control" name="date_swabbed" id="date_swabbed" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" value="{{old('date_swabbed')}}" required>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Check</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection