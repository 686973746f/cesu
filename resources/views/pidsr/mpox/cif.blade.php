@extends('layouts.app')

@section('content')
@if($mode == 'EDIT')
<!--Edit Page-->
<form action="#" method="POST">
    @php
    $morbidity_month = $c->morbidity_month;
    $date_reported = $c->date_reported;
    $epid_number = $c->epid_number;

    $dru_name = $c->dru_name;
    $dru_region = $c->dru_region;
    $dru_province = $c->dru_province;
    $dru_muncity = $c->dru_muncity;
    $dru_street = $c->dru_street;
    @endphp
@else
<!--Create Page-->
<form action="#" method="POST">
    @php
    $morbidity_month = date('Y-m-d');
    $date_reported = date('Y-m-d');
    $epid_number = NULL;

    $dru_name = 'CHO GENERAL TRIAS';
    $dru_region = 'IV-A';
    $dru_province = 'CAVITE';
    $dru_muncity = 'GENERAL TRIAS';
    $dru_street = 'PRIA RD';
    @endphp
@endif

    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @if($mode == 'EDIT')
                    <div><b>Edit Monkeypox CIF of <a href="{{route('records.edit', $c->records->id)}}">{{$c->records->getName()}} | {{$c->records->getAge()}}/{{substr($c->records->gender,0,1)}} | {{date('m/d/Y', strtotime($c->records->bdate))}}</a> [ICD 10 - CM Code: B04]</b></div>
                    @else
                    <div><b>New Monkeypox Case Investigation Form (CIF) [ICD 10 - CM Code: B04]</b></div>
                    @endif
                    <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appendix">Appendix</div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="text-center alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dru_name"><span class="text-danger font-weight-bold">*</span>Name of DRU</label>
                            <input type="text"class="form-control" name="dru_name" id="dru_name" value="{{old('dru_name', $dru_name)}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_name"><span class="text-danger font-weight-bold">*</span>Address of DRU</label>
                            <input type="text"class="form-control" name="dru_name" id="dru_name" value="{{old('dru_name', $dru_name)}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_investigation"><span class="text-danger font-weight-bold">*</span>Date of Investigation</label>
                            <input type="date"class="form-control" name="date_investigation" id="date_investigation" value="{{old('date_investigation', $c->date_investigation)}}" max="{{date('Y-m-d')}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', $lname)}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly required>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', $fname)}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly required>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="mname">Middle Name</label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', $mname)}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', $suffix)}}" minlength="2" maxlength="3" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" tabindex="-1" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate', $bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" tabindex="-1" readonly required>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                              <select class="form-control" name="gender" id="gender" required>
                                  <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                  <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                              </select>
                        </div>
                        <div class="d-none" id="ifFemaleDiv">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Pregnant?</label>
                                <select class="form-control" name="is_pregnant" id="is_pregnant">
                                    <option value="" disabled {{(is_null(old('is_pregnant'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_pregnant') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_pregnant') == 'N') ? 'selected' : ''}}>No</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="contact_number"><b class="text-danger">*</b>Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</form>
@endsection