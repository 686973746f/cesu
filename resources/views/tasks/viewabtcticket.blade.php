@extends('layouts.app')

@section('content')
    
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <h5><b>View ABTC to iClinicSys Ticket #{{$d->id}}</b></h5>
        </nav>
        <div class="card">
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div id="part1">
                    <div class="card">
                        <div class="card-header"><b>>>Personal Information<<</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lname" class="text-danger">Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname', $d->patient->lname)}}" max="50" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fname" class="text-danger">First Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname', $d->patient->fname)}}" max="50" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="mname" class="text-danger">Middle Name</label>
                                        <input type="text" class="form-control" id="mname" name="mname" value="{{$d->patient->mname ?: 'N/A'}}" max="50" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="suffix" class="text-danger">Suffix</label>
                                        <input type="text" class="form-control" id="suffix" name="suffix" value="{{$d->patient->suffix ?: 'N/A'}}" max="50" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bdate" class="text-danger">Birthdate</label>
                                        <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $d->patient->bdate)}}" readonly>
                                        <small class="">Age: {{$d->patient->getAge()}}</small>
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" class="text-danger">Sex</label>
                                        <input type="text" class="form-control" value="{{$d->patient->gender}}" readonly>
                                      </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cs">Civil Status</label>
                                        <input type="text" class="form-control" name="cs" id="cs" value="{{old('cs', $d->patient->cs)}}" readonly>
                                    </div>
                                    <div class="form-group d-none" id="ifmarried_div">
                                        <label for="spouse_name">Spouse Name</label>
                                        <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{old('spouse_name', $d->patient->spouse_name)}}" style="text-transform: uppercase;">
                                      </div>
                                    
                                </div>
                                <div class="col-md-3">
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header"><b>>>Address and Contact Info<<</b></div>
                        <div class="card-body">
                            <div class="form-group">
                              <label for="">Number/Street Name</label>
                              <input type="text" class="form-control" name="" id="" value="{{$d->patient->getStreetPurok()}}" readonly>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                      <label for="" class="text-danger">Region</label>
                                      <input type="text" class="form-control" value="{{$d->patient->address_region_text}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="text-danger">Province</label>
                                        <input type="text" class="form-control" value="{{$d->patient->address_province_text}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="text-danger">City/Municipality</label>
                                        <input type="text" class="form-control" value="{{$d->patient->address_muncity_text}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="text-danger">Barangay</label>
                                        <input type="text" class="form-control" value="{{$d->patient->address_brgy_text}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="{{old('email', $d->patient->email ?: 'N/A')}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Mobile</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{$d->patient->contact_number ?: 'N/A'}}" pattern="[0-9]{11}" placeholder="09*********" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header"><b>>>Other Info<<</b></div>
                        <div class="card-body">
                            <h5>Sa iClinicSys New Patient Form, Click niyo po muna yung "Search" Icon sa Facility Household Number at pindutin ang "Generate New" Button sa ilalim.</h5>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header"><b>>>PhilHealth Info<<</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="philhealth">Philhealth Number</label>
                                <input type="text" class="form-control" name="philhealth" id="philhealth" value="{{$d->patient->philhealth ?: ''}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="part2" class="d-none">
                    <div class="card mt-3">
                        <div class="card-header"><b>>>Case Information<<</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="">Philhealth Number</label>
                                <input type="text" class="form-control" name="" id="" value="" readonly>
                            </div>

                            <div class="form-group">
                                <label for="" class="text-danger">Anatomical Location</label>
                                <input type="text" class="form-control" name="" id="" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{route('abtctask_close', $d->id)}}" method="POST">
                @csrf
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                    <button type="button" class="btn btn-secondary d-none" id="backBtn">Back</button>
                    <button type="submit" class="btn btn-success d-none" id="submitBtn">Mark as Done</button>
                </div>
            </form>
            
        </div>
        
    </div>

    <script>
        $('#nextBtn').click(function (e) { 
            e.preventDefault();
            
            $('#part1').addClass('d-none');
            $('#part2').removeClass('d-none');
            $('#nextBtn').addClass('d-none');
            $('#backBtn').removeClass('d-none');
            $('#submitBtn').removeClass('d-none');
        });

        $('#backBtn').click(function (e) { 
            e.preventDefault();
            
            $('#part1').removeClass('d-none');
            $('#part2').addClass('d-none');
            $('#nextBtn').removeClass('d-none');
            $('#backBtn').addClass('d-none');
            $('#submitBtn').addClass('d-none');
        });
    </script>
@endsection