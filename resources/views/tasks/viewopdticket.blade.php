@extends('layouts.app')

@section('content')
    
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <h5><b>View OPD to iClinicSys Ticket #{{$d->id}}</b></h5>
        </nav>
        

        <div class="card">
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="alert alert-info" role="alert">
                    <h4>Task: i-encode ang OPD Data na ito papunta sa <a href="https://clinicsys.doh.gov.ph/">iClinicSys</a></h4>
                </div>
                <div id="part1">
                    <div class="card">
                        <div class="card-header"><b>>>Personal Information<<</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="lname" class="text-danger">Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname', $d->syndromic_patient->lname)}}" max="50" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fname" class="text-danger">First Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname', $d->syndromic_patient->fname)}}" max="50" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="mname" class="text-danger">Middle Name</label>
                                        <input type="text" class="form-control" id="mname" name="mname" value="{{$d->syndromic_patient->mname ?: 'N/A'}}" max="50" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="suffix" class="text-danger">Suffix</label>
                                        <input type="text" class="form-control" id="suffix" name="suffix" value="{{$d->syndromic_patient->suffix ?: 'N/A'}}" max="50" style="text-transform: uppercase;" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bdate" class="text-danger">Birthdate</label>
                                        <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $d->syndromic_patient->bdate)}}" readonly>
                                        <small class="">Age: {{$d->syndromic_patient->getAge()}}</small>
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="" class="text-danger">Sex</label>
                                        <input type="text" class="form-control" value="{{$d->syndromic_patient->gender}}" readonly>
                                      </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cs">Civil Status</label>
                                        <input type="text" class="form-control" name="cs" id="cs" value="{{old('cs', $d->syndromic_patient->cs)}}" readonly>
                                    </div>
                                    <div class="form-group d-none" id="ifmarried_div">
                                        <label for="spouse_name">Spouse Name</label>
                                        <input type="text" class="form-control" name="spouse_name" id="spouse_name" value="{{old('spouse_name', $d->syndromic_patient->spouse_name)}}" style="text-transform: uppercase;">
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
                              <input type="text" class="form-control" name="" id="" value="{{$d->syndromic_patient->getStreetPurok()}}" readonly>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                      <label for="" class="text-danger">Region</label>
                                      <input type="text" class="form-control" value="{{$d->syndromic_patient->address_region_text}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="text-danger">Province</label>
                                        <input type="text" class="form-control" value="{{$d->syndromic_patient->address_province_text}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="text-danger">City/Municipality</label>
                                        <input type="text" class="form-control" value="{{$d->syndromic_patient->address_muncity_text}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="text-danger">Barangay</label>
                                        <input type="text" class="form-control" value="{{$d->syndromic_patient->address_brgy_text}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="{{old('email', $d->syndromic_patient->email ?: 'N/A')}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Mobile</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{$d->syndromic_patient->contact_number ?: 'N/A'}}" pattern="[0-9]{11}" placeholder="09*********" readonly>
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
                                <label for="" class="text-danger">Philhealth Number</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->syndromic_patient->philhealth ?: 'NOT FOUND. TRY CLICKING THE PIN SEARCH ICON ON ICLINICSYS.'}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="text-danger">Philhealth Status Type</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->syndromic_patient->philhealth_statustype ?: $d->syndromic_patient->icsGetPhilhealthStatusType()}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="part2" class="d-none">
                    <div class="card">
                        <div class="card-header"><b>>>Add Consultation Record<<</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="" class="text-danger">Nature of Visit</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->nature_of_visit}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="text-danger">Type of Consultation/Purpose of Visit</label>
                                <input type="text" class="form-control" name="" id="" value="{{'GENERAL'}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="text-danger">Consultation Date</label>
                                <input type="date" class="form-control" name="" id="" value="{{date('Y-m-d', strtotime($d->created_at))}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="text-danger">Consultation Time</label>
                                <input type="time" class="form-control" name="" id="" value="{{date('H:i', strtotime($d->created_at))}}" readonly>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="text-danger">Height (cm)</label>
                                        <input type="text" class="form-control" name="" id="" value="{{$d->height ?: 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="" class="text-danger">Weight (kg)</label>
                                        <input type="text" class="form-control" name="" id="" value="{{$d->weight ?: 'N/A'}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="text-danger">Name of Attending Provider</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->name_of_physician}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="text-danger">Chief Complaint</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->chief_complain}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="text-danger">Patient Consent</label>
                                <input type="text" class="form-control" name="" id="" value="{{'YES'}}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header"><b>>>DOCTOR'S ORDER<</b></div>
                        <div class="card-body">
                            <div id="unclickable">
                                <div><label for="">Laboratory Requests</label></div>
                                @foreach(App\Models\SyndromicRecords::refLabRequest() as $ind => $iref)
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="lab_request_type{{$ind}}" name="laboratory_request_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", $d->laboratory_request_list))) ? 'checked' : ''}}>
                                <label class="form-check-label">{{$iref}}</label>
                                </div>
                                @endforeach
                                <hr>
                                <div><label for="">Imaging</label></div>
                                @foreach(App\Models\SyndromicRecords::refImagingRequest() as $ind => $iref)
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="imaging_type{{$ind}}" name="imaging_request_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", $d->imaging_request_list))) ? 'checked' : ''}}>
                                <label class="form-check-label">{{$iref}}</label>
                                </div>
                                @endforeach
                                <hr>
                                <div><label for="">Alert Type</label></div>
                                @foreach(App\Models\SyndromicRecords::refAlert() as $ind => $iref)
                                <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="alert_type{{$ind}}" name="alert_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", $d->alert_list))) ? 'checked' : ''}}>
                                <label class="form-check-label">{{$iref}}</label>
                                </div>
                                @endforeach
                                <div id="disability_div" class="d-none mt-3">
                                <div><label for=""><b class="text-danger">*</b>Type of Disability</label></div>
                                @foreach(App\Models\SyndromicRecords::refAlertDisability() as $ind => $iref)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="disability_type{{$ind}}" name="alert_ifdisability_list[]" value="{{mb_strtoupper($iref)}}" {{(in_array(mb_strtoupper($iref), explode(",", $d->alert_ifdisability_list))) ? 'checked' : ''}}>
                                    <label class="form-check-label">{{$iref}}</label>
                                </div>
                                @endforeach
                                </div>
                            </div>
                            
                            <div class="form-group mt-3">
                                <label for="">Alert Description</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->alert_description ?: 'N/A'}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="text-danger">Diagnosis</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->diagnosis_type}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Diagnosis, Specify</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->dcnote_assessment ?: 'N/A'}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Treatment Plan</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->dcnote_plan ?: 'N/A'}}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Remarks</label>
                                <input type="text" class="form-control" name="" id="" value="{{$d->remarks ?: 'N/A'}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-6">
                        @if($d->ics_ticketstatus == 'PENDING')
                        <form action="{{route('task_cancel', ['id' => $d->id, 'type' => 'opd'])}}" method="POST">
                            @csrf
                            <button type="submit" class="btn text-white" style="background-color: orange" onclick="return confirm('Are you sure you want to cancel?')">Cancel</button>
                        </form>
                        @endif
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                    @if($d->ics_ticketstatus == 'PENDING')
                    <form action="{{route('opdtask_close', $d->id)}}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-secondary d-none" id="backBtn">Back</button>
                        <button type="submit" class="btn btn-success d-none" id="submitBtn" onclick="return confirm('Confirm closing the OPD Ticket #{{$d->id}} - {{$d->syndromic_patient->getName()}}. Paki-sure lang po na na-encode na ang mga detalye ni OPD Patient papuntang iClinicSys bago i-close ang ticket.')">Mark as Done</button>
                    </form>
                    @else
                    <button type="button" class="btn btn-success d-none disabled" id="submitBtn" onclick="alert('This ABTC Ticket was already marked as {{$d->ics_ticketstatus}}.')">Mark as Done</button>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#nextBtn').click(function (e) { 
            e.preventDefault();
            window.scrollTo(0, 0);

            $('#part1').addClass('d-none');
            $('#part2').removeClass('d-none');
            $('#nextBtn').addClass('d-none');
            $('#backBtn').removeClass('d-none');
            $('#submitBtn').removeClass('d-none');
        });

        $('#backBtn').click(function (e) { 
            e.preventDefault();
            window.scrollTo(0, 0);
            
            $('#part1').removeClass('d-none');
            $('#part2').addClass('d-none');
            $('#nextBtn').removeClass('d-none');
            $('#backBtn').addClass('d-none');
            $('#submitBtn').addClass('d-none');
        });

        $('input[name="alert_list[]"][value="DISABILITY"]').change(function (e) { 
            e.preventDefault();
            if ($(this).is(':checked')) {
            $('#disability_div').removeClass('d-none');
            } else {
            $('#disability_div').addClass('d-none');
            }
        }).trigger('change');

        $('#unclickable').click(false);
    </script>
@endsection