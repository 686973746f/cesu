@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center">
                    <img src="{{asset('assets/images/CHO_LETTERHEAD_WITH_CESU.png')}}" class="mb-3 img-fluid" style="width: 50rem;">
                </div>
                <div class="card">
                    <div class="card-header text-center"><b>GenTrias LGU Pharmacy Online Registration</b></div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <button type="button" class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#new_patient"><b>New Patient</b></button>
                        <!--<button type="button" class="btn btn-secondary btn-lg btn-block" data-toggle="modal" data-target="#old_patient">Old Patient (Get your Card)</button>-->
                    </div>
                </div>
                <p class="text-center mt-3">GenTrias LGU Pharmacy Inventory System - Developed and Maintained by <u>CJH</u> for CHO Gen. Trias, Cavite ©{{date('Y')}}</p>
            </div>
        </div>
    </div>

    <form action="{{route('pharmacy_walkin2', $branch->qr)}}" method="GET">
        <div class="modal fade" id="new_patient" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>New Patient</b> (Branch: {{$branch->name}})</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Last Name/Surname/Apelyido</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- ']+" required>
                        </div>
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- ']+" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                    <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- ']+">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="suffix">Suffix <i>(If Applicable)</i></label>
                                    <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="50" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- ']+">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                        </div>
                        <div class="alert alert-info text-center" role="alert">
                            <div>By pressing the [Next] button, you agree to the Republic Act No. 10173 / Data Privacy Act of 2012 (DPA).</div>
                            <div>We enjoin you to provide only true and accurate information to avoid the penalties as provided in the law.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{route('pharmacy_searchcard')}}" method="POST">
        @csrf
        <div class="modal fade" id="old_patient" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Old Patient</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Body
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection