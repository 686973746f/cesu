@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if(!is_null(auth()->user()->itr_medicalevent_id))
    <div class="alert alert-info" role="alert">
        <h5><b><span class="text-danger">NOTE:</span> Medical Event Mode Enabled</b></h5>
        <hr>
        <h6>All patient records that you will encode will automatically link itself on the Channel: <b>{{auth()->user()->getMedicalEvent->name}}</b>.</h6>
    </div>
    @endif
    <div class="text-right mb-3">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#additr"><i class="fa fa-user-plus mr-2" aria-hidden="true"></i>New Patient</button>
        @if(!auth()->user()->isTbdotsEncoder())
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#report"><i class="fa fa-file mr-2" aria-hidden="true"></i>Report</button>
        @endif
        @if(auth()->user()->isAdminSyndromic())
        <a href="{{route('syndromic_map')}}" class="btn btn-primary"><i class="fa fa-map mr-2" aria-hidden="true"></i>Map</a>
        <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#settings"><i class="fa fa-cog mr-2" aria-hidden="true"></i>Settings</button>
        @endif
    </div>
    <form action="" method="GET">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="SEARCH BY NAME/ID/HOSP. NUMBER" style="text-transform: uppercase;" required>
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>

                    <button type="button" class="btn btn-secondary ml-2" data-toggle="modal" data-target="#advanceSearch">
                        Advanced Search
                    </button>
                </div>
            </div>
        </div>
    </form>
    @if(session('msg'))
    <div class="alert alert-{{session('msgtype')}}" role="alert">
        <b>{{session('msg')}}</b>
        @if(session('p'))
        @php $p = session('p') @endphp
        <hr>
            @if(session('p')->userHasPermissionToAccess())
                <div class="alert alert-primary" role="alert">
                    <div><b>Full Name: </b> <a href="{{route('syndromic_viewPatient', session('p')->id)}}"><b><u>{{$p->getName()}}</u></b></a> <= Click to View/Edit Patient Profile</div>
                    <div><b>Birthdate: </b> {{date('m/d/Y', strtotime($p->bdate))}}</div>
                    <div><b>Age/Sex:</b> {{$p->getAge()}} / {{substr($p->gender, 0,1)}}</div>
                    <div><b>Address: </b> {{$p->getFullAddress()}}</div>
                    <div><b>Date Encoded / By: </b> {{date('m/d/Y h:i A', strtotime($p->created_at))}} by {{$p->user->name}}</div>
                    @if($p->getLastCheckup())
                    <hr>
                    <div><b>Last Consultation ID: </b> <b><a href="{{route('syndromic_viewRecord', $p->getLastCheckup()->id)}}">{{$p->getLastCheckup()->opdno}}</a></b> <= Click to View Previous Consultation</div>
                    <div><b>Date:</b> {{date('M. d, Y - D', strtotime($p->getLastCheckup()->consultation_date))}} ({{Carbon\Carbon::parse($p->getLastCheckup()->consultation_date)->diffForHumans()}})</div>
                    <div><b>Facility: </b> {{$p->getLastCheckup()->facility->facility_name}}</div>
                    @endif
                </div>
            </div>
            @else
            Unfortunately, you don't have permission to access the record because it was created by other user on other barangay. You may contact CESU Staff or the Encoder of the record ({{session('p')->user->name}}) to gain rights access for the patient record.
            @endif
        @endif
        @if(session('option_medcert'))
        <hr>
        Options: <a href="{{route('syndromic_view_medcert', session('option_medcert'))}}" class="btn btn-primary">Print MedCert</a> <a href="{{route('pharmacy_print_patient_card', session('option_pharmacy'))}}" class="btn btn-primary">Print Pharmacy Card</a>
        @endif
    </div>
    @endif
    
    @if(auth()->user()->isStaffSyndromic() && request()->input('opd_view') || auth()->user()->isSyndromicHospitalLevelAccess() || auth()->user()->isTbdotsEncoder() && request()->input('opd_view'))
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <b>@if(request()->input('er_view'))
                        ER
                        @else
                        OPD
                        @endif - {{(!(request()->input('d'))) ? date('F d, Y (D)') : date('F d, Y (D)', strtotime(request()->input('d')))}}</b> - Total: {{$list->total()}}
                        
                    @if(auth()->user()->isSyndromicHospitalLevelAccess())
                        @if(request()->input('er_view'))
                        <a href="{{route('syndromic_home')}}" class="btn btn-success ml-2">Switch to OPD View</a>
                        @else
                        <a href="{{route('syndromic_home')}}?er_view=1" class="btn btn-success ml-2">Switch to ER View</a>
                        @endif
                    @else
                    @if(!auth()->user()->isTbdotsEncoder())
                    <a href="{{route('syndromic_home')}}" class="btn btn-outline-secondary ml-2">Switch to BRGY View</a>
                    @endif
                    @endif
                </div>
                <div>
                    <h6><b>Facility: <span class="text-info">{{auth()->user()->opdfacility->facility_name}}</span></b></h6>
                    @if(auth()->user()->isTbdotsEncoder())
                    <h6><b><span class="text-success">TB-DOTS ITR</span></b></h6>
                    @endif
                    @if(auth()->user()->isStaffSyndromic())
                        @if(is_null(auth()->user()->itr_medicalevent_id))
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#joinMedicalEvent">Join Medical Event</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newMedicalEvent">New Medical Event</button>
                        @else
                            <form action="{{route('opd_medicalevent_unjoin')}}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Leave Medical Event</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('syndromic_home')}}" method="GET">
                <input type="hidden" name="opd_view" value="{{request()->input('opd_view')}}">
                <div class="input-group mb-3">
                    <input type="date" class="form-control" name="d" id="d" value="{{(request()->input('d')) ? request()->input('d') : date('Y-m-d')}}" min="2023-01-01" max="{{date('Y-m-d')}}" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" type="submit"><i class="fas fa-calendar-alt mr-2"></i>Date Search</button>
                    </div>
                </div>
            </form>
            @if($list->count() != 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center thead-light">
                        <tr>
                            @if(auth()->user()->isSyndromicHospitalLevelAccess())
                            <th>No.</th>
                            <th>Record (New/Old)</th>
                            <th>Hospital Number</th>
                            @else
                            <th>Line #</th>
                            <th>OPD No.</th>
                            @endif
                            
                            <th>Full Name</th>
                            <th>Age/Sex</th>
                            <th>Date of Birth</th>
                            <th>Complete Address</th>
                            @if(auth()->user()->isStaffSyndromic())
                            <th>Contact Number</th>
                            @endif
                            <th>Chief Complaint</th>
                            <th>Diagnosis</th>
                            @if(auth()->user()->isSyndromicHospitalLevelAccess())
                            <th>Procedure Done</th>
                            <th>Disposition</th>
                            <th>Membership</th>
                            @endif
                            <th>Attending Physician</th>
                            @if(auth()->user()->isStaffSyndromic())
                            <th>List of Suspected Disease/s</th>
                            @endif
                            <th>Encoded At / By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $i)
                        <tr>
                            @if(auth()->user()->isSyndromicHospitalLevelAccess())
                            <td class="text-center">{{$list->firstItem() + $ind}}</td>
                            <td class="text-center">{{$i->getHospRecordTypeSv()}}</td>
                            <td class="text-center">{{$i->syndromic_patient->unique_opdnumber}}</td>
                            @else
                            <td class="text-center"><b>#{{$i->line_number}}</b></td>
                            <td class="text-center">{{$i->opdno}}</td>
                            @endif

                            <td><b><a href="{{route('syndromic_viewRecord', $i->id)}}">{{$i->syndromic_patient->getName()}}</a></b></td>
                            <td class="text-center">{{$i->syndromic_patient->getAge()}} / {{substr($i->syndromic_patient->gender,0,1)}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($i->syndromic_patient->bdate))}}</td>
                            <td class="text-center">
                                <small>{{$i->syndromic_patient->getStreetPurok()}}</small>
                                <h6>{{$i->syndromic_patient->address_brgy_text}}</h6>
                            </td>
                            @if(auth()->user()->isStaffSyndromic())
                            <td class="text-center">{{$i->syndromic_patient->getContactNumber()}}</td>
                            @endif
                            <td class="text-center">{{$i->chief_complain}}</td>
                            <td class="text-center">{{$i->dcnote_assessment}}</td>
                            @if(auth()->user()->isSyndromicHospitalLevelAccess())
                            <td class="text-center">{{$i->procedure_done}}</td>
                            <td class="text-center">{{$i->disposition}}</td>
                            <td class="text-center">{{$i->syndromic_patient->getMembership()}}</td>
                            @endif
                            <td class="text-center">{{$i->name_of_physician}}</td>
                            @if(auth()->user()->isStaffSyndromic())
                            <td class="text-center">{{$i->getListOfSuspDiseases()}}</td>
                            @endif
                            <td class="text-center"><small>{{date('m/d/Y h:i A', strtotime($i->created_at))}} / {{$i->user->name}}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
            @else
            <p class="text-center">No results found.</p>
            @endif
        </div>
    </div>
    @else
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    Showing <b>UNVERIFIED</b> cases by Barangay
                    <a href="{{route('syndromic_home', ['opd_view' => 1])}}" class="btn btn-outline-secondary">Switch to OPD View</a>
                </div>
                @if(request()->input('showVerified'))
                <div>
                    <a href="{{route('syndromic_home')}}" class="btn btn-warning">Show UNVERIFIED CASES</a>
                </div>
                @else
                <div>
                    <a href="{{route('syndromic_home')}}?showVerified=1" class="btn btn-primary">Show VERIFIED CASES</a>
                </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Name / ITR ID</th>
                            <th>Birthdate</th>
                            <th>Age/Sex</th>
                            <th>Lot/Street</th>
                            <th>Barangay</th>
                            <th>Contact Number</th>
                            <th>Symptoms</th>
                            <th>List of Susp. Disease/s</th>
                            <th>Encoded by / At</th>
                            <th>CESU Verified</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$list->firstItem() + $ind}}</td>
                            <td><b><a href="{{route('syndromic_viewRecord', $l->id)}}">{{$l->syndromic_patient->getName()}} <small>(#{{$l->syndromic_patient->id}})</small></a></b></td>
                            <td class="text-center">{{date('m/d/Y', strtotime($l->syndromic_patient->bdate))}}</td>
                            <td class="text-center">{{$l->syndromic_patient->getAge()}} / {{substr($l->syndromic_patient->gender,0,1)}}</td>
                            <td class="text-center"><small>{{$l->syndromic_patient->getStreetPurok()}}</small></td>
                            <td class="text-center">{{$l->syndromic_patient->address_brgy_text}}</td>
                            <td class="text-center">{{$l->syndromic_patient->getContactNumber()}}</td>
                            <td class="text-center">{{$l->listSymptoms()}}</td>
                            <td class="text-center">{{$l->getListOfSuspDiseases()}}</td>
                            <td class="text-center"><small>{{$l->user->name}} @ {{date('m/d/Y h:i A', strtotime($l->created_at))}}</small></td>
                            <td class="text-center"><small>{{$l->getCesuVerified()}}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
    @endif
</div>

<form action="{{route('syndromic_newPatient')}}" method="GET">
    <div class="modal fade" id="additr" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>New ITR - Step 1/3</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="lname"><b class="text-danger">*</b>Surname/Last Name/Apelyido</label>
                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="form-group">
                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mname">Middle Name <i>(If Any)</i></label>
                                <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suffix">Suffix/Name Extension <i>(If Any)</i></label>
                                <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="3" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                        <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">Next</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="report" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="accordianId" role="tablist" aria-multiselectable="true">
                    <form action="{{route('syndromic_download_opd_excel')}}" method="GET">
                        <div class="card">
                            <div class="card-header text-center" role="tab" id="section1HeaderId">
                                <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">
                                    Download OPD Excel Masterlist
                                </a>
                            </div>
                            <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                              <label for=""><b class="text-danger">*</b>Start Date</label>
                                              <input type="date" class="form-control" name="date_from" id="date_from" min="2023-01-01" max="{{date('Y-m-d')}}" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for=""><b class="text-danger">*</b>End Date</label>
                                                <input type="date" class="form-control" name="date_to" id="date_to" min="2023-01-01" max="{{date('Y-m-d')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success btn-block">Download (.XLSX)</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(!(auth()->user()->isSyndromicHospitalLevelAccess()))
                <div id="accordianId2" role="tablist" aria-multiselectable="true">
                    <form action="{{route('syndromic_cho_dashboard_report')}}" method="GET">
                        <div class="card mt-3">
                            <div class="card-header text-center" role="tab" id="section1HeaderId">
                                <a data-toggle="collapse" data-parent="#accordianId2" href="#opdMonthlyReport" aria-expanded="true" aria-controls="opdMonthlyReport">
                                    OPD Summary Report
                                </a>
                            </div>
                            <div id="opdMonthlyReport" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="type"><b class="text-danger">*</b>Type</label>
                                        <select class="form-control" name="type" id="type" required>
                                          <option value="" disabled selected>Choose...</option>
                                          <option value="Daily">Daily</option>
                                          <option value="Monthly">Monthly</option>
                                          <option value="Yearly">Yearly</option>
                                        </select>
                                    </div>
                                    <div id="opdReport_ifDaily" class="d-none">
                                        <div class="form-group">
                                            <label for="sdate"><b class="text-danger">*</b>Date</label>
                                          <input type="date" class="form-control" name="sdate" id="sdate" min="2024-01-01" max="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="d-none" id="opdReport_ifMonthlyOrYearly">
                                        <div class="form-group">
                                            <label for="syear"><b class="text-danger">*</b>Year</label>
                                            <input type="number" class="form-control" name="syear" id="syear" value="{{(request()->input('syear')) ? request()->input('syear') : date('Y')}}">
                                        </div>
                                    </div>
                                    <div id="opdReport_ifMonthly" class="d-none">
                                        <div class="form-group">
                                            <label for="smonth"><b class="text-danger">*</b>Month</label>
                                            <select class="form-control" name="smonth" id="smonth">
                                              <option value="01" {{(date('m') == '01') ? 'selected' : ''}}>January</option>
                                              <option value="02" {{(date('m') == '02') ? 'selected' : ''}}>February</option>
                                              <option value="03" {{(date('m') == '03') ? 'selected' : ''}}>March</option>
                                              <option value="04" {{(date('m') == '04') ? 'selected' : ''}}>April</option>
                                              <option value="05" {{(date('m') == '05') ? 'selected' : ''}}>May</option>
                                              <option value="06" {{(date('m') == '06') ? 'selected' : ''}}>June</option>
                                              <option value="07" {{(date('m') == '07') ? 'selected' : ''}}>July</option>
                                              <option value="08" {{(date('m') == '08') ? 'selected' : ''}}>August</option>
                                              <option value="09" {{(date('m') == '09') ? 'selected' : ''}}>September</option>
                                              <option value="10" {{(date('m') == '10') ? 'selected' : ''}}>October</option>
                                              <option value="11" {{(date('m') == '11') ? 'selected' : ''}}>November</option>
                                              <option value="12" {{(date('m') == '12') ? 'selected' : ''}}>December</option>
                                            </select>
                                          </div>
                                    </div>
                                </div>
                                <div class="card-footer f-none" id="opdReport_submitDiv">
                                    <button type="submit" class="btn btn-success btn-block">Load Report</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="accordianId3" role="tablist" aria-multiselectable="true">
                    <form action="{{route('syndromic_m2brgydashboard')}}" method="GET">
                        <div class="card mt-3">
                            <div class="card-header text-center" role="tab" id="section1HeaderId">
                                <a data-toggle="collapse" data-parent="#accordianId3" href="#brgyM2Report" aria-expanded="true" aria-controls="brgyM2Report">
                                    Barangay M2 Dashboard (for FHSIS)
                                </a>
                            </div>
                            <div id="brgyM2Report" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="type"><b class="text-danger">*</b>Type</label>
                                        <select class="form-control" name="type" id="m2_type" required>
                                          <!-- <option value="" disabled selected>Choose...</option> -->
                                          <!-- <option value="Daily">Daily</option> -->
                                          <option value="Monthly">Monthly</option>
                                          <!--<option value="Yearly">Yearly</option> -->
                                        </select>
                                    </div>
                                    <div id="m2Report_ifDaily" class="d-none">
                                        <div class="form-group">
                                            <label for="date"><b class="text-danger">*</b>Date</label>
                                          <input type="date" class="form-control" name="date" id="m2_sdate" min="2024-01-01" max="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="d-none" id="m2Report_ifMonthlyOrYearly">
                                        <div class="form-group">
                                            <label for="year"><b class="text-danger">*</b>Year</label>
                                            <input type="number" class="form-control" name="year" id="m2_syear" value="{{(request()->input('syear')) ? request()->input('syear') : date('Y')}}">
                                        </div>
                                    </div>
                                    <div id="m2Report_ifMonthly" class="d-none">
                                        <div class="form-group">
                                            <label for="month"><b class="text-danger">*</b>Month</label>
                                            <select class="form-control" name="month" id="m2_smonth">
                                              <option value="01" {{(date('m') == '01') ? 'selected' : ''}}>January</option>
                                              <option value="02" {{(date('m') == '02') ? 'selected' : ''}}>February</option>
                                              <option value="03" {{(date('m') == '03') ? 'selected' : ''}}>March</option>
                                              <option value="04" {{(date('m') == '04') ? 'selected' : ''}}>April</option>
                                              <option value="05" {{(date('m') == '05') ? 'selected' : ''}}>May</option>
                                              <option value="06" {{(date('m') == '06') ? 'selected' : ''}}>June</option>
                                              <option value="07" {{(date('m') == '07') ? 'selected' : ''}}>July</option>
                                              <option value="08" {{(date('m') == '08') ? 'selected' : ''}}>August</option>
                                              <option value="09" {{(date('m') == '09') ? 'selected' : ''}}>September</option>
                                              <option value="10" {{(date('m') == '10') ? 'selected' : ''}}>October</option>
                                              <option value="11" {{(date('m') == '11') ? 'selected' : ''}}>November</option>
                                              <option value="12" {{(date('m') == '12') ? 'selected' : ''}}>December</option>
                                            </select>
                                          </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="brgy"><b class="text-danger">*</b>Barangay</label>
                                        <select class="form-control" name="brgy" id="m2_brgy" required>
                                            <option value="" disabled selected>Choose...</option>
                                            @foreach($brgy_list as $b)
                                            <option value="{{$b->brgyName}}">{{$b->brgyName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer f-none" id="m2Report_submitDiv">
                                    <button type="submit" class="btn btn-success btn-block">Load Report</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- <a href="" class="btn btn-primary btn-block">OPD Daily Report</a> -->
                <!-- <a href="{{route('syndromic_diseasechecker')}}" class="btn btn-primary btn-block">Go to Disease Checker Page</a> -->
                @else
                <hr>
                
                <form action="{{route('opd_hospital_monthlysummaryv2')}}" method="POST">
                    @csrf
                    <div id="accordianId_2" role="tablist" aria-multiselectable="true">
                        <div class="card mb-3">
                            <div class="card-header text-center" role="tab" id="section1HeaderId_2">
                                <a data-toggle="collapse" data-parent="#accordianId_2" href="#section1ContentId_2" aria-expanded="true" aria-controls="section1ContentId_2">
                                    Export OPD/ER Summary V2
                                </a>
                            </div>
                            <div id="section1ContentId_2" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId_2">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="year"><b class="text-danger">*</b>Year</label>
                                        <input type="number" class="form-control" name="year" id="year" min="{{(date('Y')-5)}}" max="{{date('Y')}}" value="{{date('Y')}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="month"><b class="text-danger">*</b>Month</label>
                                        <select class="form-control" name="month" id="month" required>
                                          <option value="" disabled selected>Choose...</option>
                                          <option value="1" {{(date('n') == 1) ? 'selected' : ''}}>January</option>
                                          <option value="2" {{(date('n') == 2) ? 'selected' : ''}}>February</option>
                                          <option value="3" {{(date('n') == 3) ? 'selected' : ''}}>March</option>
                                          <option value="4" {{(date('n') == 4) ? 'selected' : ''}}>April</option>
                                          <option value="5" {{(date('n') == 5) ? 'selected' : ''}}>May</option>
                                          <option value="6" {{(date('n') == 6) ? 'selected' : ''}}>June</option>
                                          <option value="7" {{(date('n') == 7) ? 'selected' : ''}}>July</option>
                                          <option value="8" {{(date('n') == 8) ? 'selected' : ''}}>August</option>
                                          <option value="9" {{(date('n') == 9) ? 'selected' : ''}}>September</option>
                                          <option value="10" {{(date('n') == 10) ? 'selected' : ''}}>October</option>
                                          <option value="11" {{(date('n') == 11) ? 'selected' : ''}}>November</option>
                                          <option value="12" {{(date('n') == 12) ? 'selected' : ''}}>December</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="type"><b class="text-danger">*</b>Select Summary Type</label>
                                        <select class="form-control" name="type" id="type" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="OPD">OPD</option>
                                            <option value="ER">ER</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success btn-block">Generate Excel File</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <a href="{{route('opd_hospital_downloadalphalist')}}" class="btn btn-primary btn-block">Download Alphalist</a>
                <a href="{{route('opd_hospital_dailysummary')}}" class="btn btn-primary btn-block">DAILY REPORTING SUMMARY</a>
                
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="settings" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Settings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="{{route('syndromic_admin_doctors_index')}}" class="btn btn-primary btn-block">Doctors</a>
            </div>
        </div>
    </div>
</div>

@if(session('immediate_notifiable') == 1)
<div class="modal fade border-warning" id="immediate_case" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Immediate Notifiable Disease Detected</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning" role="alert">
                    Based on the details you encoded, the patient might be suspected to a list of Immediate Notifiable Disease/s. <b>Please inform CESU</b> by sending the details below via screenshot or direct message.
                </div>
                <p>Name: {{session('fetchr')->syndromic_patient->getName()}}</p>
                <p>Age/Sex: {{session('fetchr')->syndromic_patient->getAgeInt()}}/{{session('fetchr')->syndromic_patient->sg()}}</p>
                <p>Address: {{session('fetchr')->syndromic_patient->getFullAddress()}}</p>
                <p>Contact Number: {{session('fetchr')->syndromic_patient->getContactNumber()}}</p>
                <p>Consultation Date: {{date('m/d/Y', strtotime(session('fetchr')->consultation_date))}}</p>
                <p>Symptoms: {{session('fetchr')->listSymptoms()}}</p>
                <p>Other Symptoms: {{session('fetchr')->other_symptoms_onset_remarks}}</p>
                <p>List of Suspected Disease/s: {{session('fetchr')->getListOfSuspDiseases()}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">I already informed CESU, Close this window</button>
            </div>
        </div>
    </div>
</div>
@endif

<form action="{{route('opd_medicalevent_join')}}" method="POST">
    @csrf
    <div class="modal fade" id="joinMedicalEvent" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Join Medical Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="medical_event_id"><b class="text-danger">*</b>Select Medical Event Encoding Channel</label>
                      <select class="form-control" name="medical_event_id" id="medical_event_id" required>
                        <option value="" disabled {{(is_null(old('medical_event_id'))) ? 'selected' : ''}}>Choose...</option>
                        @foreach($medical_event_list as $me)
                        <option value="{{$me->id}}">{{$me->name}}</option>
                        @endforeach
                        
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Join</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{route('opd_medicalevent_store')}}" method="POST">
    @csrf
    <div class="modal fade" id="newMedicalEvent" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Medical Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="name"><b class="text-danger">*</b>Event Name</label>
                      <input type="text" class="form-control" name="name" id="name" placeholder="ex. Medical Mission on brgy X" style="text-transform: uppercase;" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" name="description" id="description" style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                      <label for="oneDayEvent">One Day Event?</label>
                      <select class="form-control" name="oneDayEvent" id="oneDayEvent" required>
                        <option value="" disabled {{(is_null(old('oneDayEvent'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                      </select>
                    </div>
                    <div class="form-group">
                        <label for="date_start"><b class="text-danger">*</b>Date Start</label>
                        <input type="date" class="form-control" name="date_start" id="date_start" value="{{old('date_start', date('Y-m-d'))}}" max="{{date('Y-m-d')}}" required>
                    </div>
                    <div id="de_div" class="d-none">
                        <div class="form-group">
                            <label for="date_end"><b class="text-danger">*</b>Date End</label>
                            <input type="date" class="form-control" name="date_end" id="date_end" min="{{date('Y-m-d')}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Create</button>
                </div>
            </div>
        </div>
    </div>
</form>

@include('syndromic.advanced_search_modal')

<script>
    @if(session('immediate_notifiable') == 1)
    $('#immediate_case').modal({backdrop: 'static', keyboard: false});
    $('#immediate_case').modal('show');
    @endif

    $('#medical_event_id').select2({
        theme: 'bootstrap',
    });

    $('#oneDayEvent').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'Y') {
            $('#date_end').prop('required', false);
            $('#de_div').addClass('d-none');
        }
        else {
            $('#date_end').prop('required', true);
            $('#de_div').removeClass('d-none');
        }
    }).trigger('change');

    $('#type').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Daily') {
            $('#opdReport_ifDaily').removeClass('d-none');
            $('#opdReport_ifMonthlyOrYearly').addClass('d-none');
            $('#opdReport_ifMonthly').addClass('d-none');
            $('#opdReport_submitDiv').removeClass('d-none');

            $('#sdate').prop('required', true);
            $('#smonth').prop('required', false);
            $('#syear').prop('required', false);
        }
        else if($(this).val() == 'Monthly') {
            $('#opdReport_ifDaily').addClass('d-none');
            $('#opdReport_ifMonthlyOrYearly').removeClass('d-none');
            $('#opdReport_ifMonthly').removeClass('d-none');
            $('#opdReport_submitDiv').removeClass('d-none');
            
            $('#sdate').prop('required', false);
            $('#smonth').prop('required', true);
            $('#syear').prop('required', true);
        }
        else if($(this).val() == 'Yearly') {
            $('#opdReport_ifDaily').addClass('d-none');
            $('#opdReport_ifMonthlyOrYearly').removeClass('d-none');
            $('#opdReport_ifMonthly').addClass('d-none');
            $('#opdReport_submitDiv').removeClass('d-none');

            $('#sdate').prop('required', false);
            $('#smonth').prop('required', false);
            $('#syear').prop('required', true);
        }
        else {
            $('#opdReport_ifDaily').addClass('d-none');
            $('#opdReport_ifMonthlyOrYearly').addClass('d-none');
            $('#opdReport_ifMonthly').addClass('d-none');
            $('#opdReport_submitDiv').addClass('d-none');

            $('#sdate').prop('required', false);
            $('#smonth').prop('required', false);
            $('#syear').prop('required', false);
        }
    }).trigger('change');

    $('#m2_type').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Daily') {
            $('#m2Report_ifDaily').removeClass('d-none');
            $('#m2Report_ifMonthlyOrYearly').addClass('d-none');
            $('#m2Report_ifMonthly').addClass('d-none');
            $('#m2Report_submitDiv').removeClass('d-none');

            $('#m2_sdate').prop('required', true);
            $('#m2_smonth').prop('required', false);
            $('#m2_syear').prop('required', false);
        }
        else if($(this).val() == 'Monthly') {
            $('#m2Report_ifDaily').addClass('d-none');
            $('#m2Report_ifMonthlyOrYearly').removeClass('d-none');
            $('#m2Report_ifMonthly').removeClass('d-none');
            $('#m2Report_submitDiv').removeClass('d-none');
            
            $('#m2_sdate').prop('required', false);
            $('#m2_smonth').prop('required', true);
            $('#m2_syear').prop('required', true);
        }
        else if($(this).val() == 'Yearly') {
            $('#m2Report_ifDaily').addClass('d-none');
            $('#m2Report_ifMonthlyOrYearly').removeClass('d-none');
            $('#m2Report_ifMonthly').addClass('d-none');
            $('#m2Report_submitDiv').removeClass('d-none');

            $('#m2_sdate').prop('required', false);
            $('#m2_smonth').prop('required', false);
            $('#m2_syear').prop('required', true);
        }
        else {
            $('#m2Report_ifDaily').addClass('d-none');
            $('#m2Report_ifMonthlyOrYearly').addClass('d-none');
            $('#m2Report_ifMonthly').addClass('d-none');
            $('#m2Report_submitDiv').addClass('d-none');

            $('#m2_sdate').prop('required', false);
            $('#m2_smonth').prop('required', false);
            $('#m2_syear').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection