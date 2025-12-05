@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('fwri_selfreport_check')}}" method="GET">
        <div class="containerr">
            <div class="card">
                <div class="card-header"><b>Self-report Fireworks-Related Injuries (FWRI)</b></div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="lname"><b class="text-danger">*</b>Last Name</label>
                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" placeholder="DELA CRUZ" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="form-group">
                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" placeholder="JUAN" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" placeholder="SANCHEZ" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suffix">Name Extension <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="3" placeholder="JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="bdate"><b class="text-danger">*</b>Date of Birth</label>
                        <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="alert alert-primary text-center" role="alert">
                        Sa pagpapatuloy, sumasang-ayon ka sa <b>Republic Act 11332</b> at sa <b>Data Privacy Act of 2012</b>, at gagamitin ng City Health Office - General Trias ang iyong impormasyon para sa Online National Electronic Injury Surveillance System (ONEISS) nang may mahigpit na pagiging kumpidensyal.
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Next</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection