@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="text-right mb-3">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#additr">New Patient</button>
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#report">Report</button>
        @if(auth()->user()->isAdminSyndromic())
        <a href="{{route('syndromic_map')}}" class="btn btn-primary">Map</a>
        @endif
    </div>
    <form action="" method="GET">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="SEARCH BY SURNAME, NAME / ID" style="text-transform: uppercase;" required>
                    <div class="input-group-append">
                      <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
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
                    <div><b>Full Name: </b> <b><u>{{$p->getName()}}</u></b></div>
                    <div><b>Birthdate: </b> {{date('m/d/Y', strtotime($p->bdate))}}</div>
                    <div><b>Age/Sex:</b> {{$p->getAge()}} / {{substr($p->gender, 0,1)}}</div>
                    <div><b>Address: </b> {{$p->getFullAddress()}}</div>
                    <div><b>Date Encoded / By: </b> {{date('m/d/Y h:i A', strtotime($p->created_at))}} by {{$p->user->name}}</div>
                    @if($p->getLastCheckup())
                    <hr>
                    <div><b>OPD No.: </b> <b><a href="{{route('syndromic_viewRecord', $p->getLastCheckup()->id)}}">{{$p->getLastCheckup()->opdno}}</a></b></div>
                    <div><b>Date of Last Checkup: </b> {{date('m/d/Y', strtotime($p->getLastCheckup()->consultation_date))}}</div>
                    @endif
                    <hr>
                    > To view/update the Patient details, click <a href="{{route('syndromic_viewPatient', session('p')->id)}}">HERE</a>
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
    
    @if(auth()->user()->isStaffSyndromic() && request()->input('opd_view'))
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>OPD List for {{(!(request()->input('d'))) ? date('F d, Y (D)') : date('F d, Y (D)', strtotime(request()->input('d')))}}</b> - Total: {{$list->total()}} <a href="{{route('syndromic_home')}}" class="btn btn-outline-secondary">Switch to BRGY View</a></div>
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
                            <th>Line #</th>
                            <th>OPD No.</th>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Birthdate</th>
                            <th>House No/Subdivision</th>
                            <th>Barangay</th>
                            <th>City/Province</th>
                            <th>Contact Number</th>
                            <th>List of Suspected Disease/s</th>
                            <th>Encoded At / By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $i)
                        <tr>
                            <td class="text-center"><b>#{{$i->line_number}}</b></td>
                            <td class="text-center">{{$i->opdno}}</td>
                            <td><b><a href="{{route('syndromic_viewRecord', $i->id)}}">{{$i->syndromic_patient->getName()}}</a></b></td>
                            <td class="text-center">{{$i->syndromic_patient->getAge()}} / {{substr($i->syndromic_patient->gender,0,1)}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($i->syndromic_patient->bdate))}}</td>
                            <td class="text-center"><small>{{$i->syndromic_patient->getStreetPurok()}}</small></td>
                            <td class="text-center">{{$i->syndromic_patient->address_brgy_text}}</td>
                            <td class="text-center">{{$i->syndromic_patient->address_muncity_text}}, {{$i->syndromic_patient->address_province_text}}</td>
                            <td class="text-center">{{$i->syndromic_patient->getContactNumber()}}</td>
                            <td class="text-center">{{$i->getListOfSuspDiseases()}}</td>
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
    <div class="modal fade" id="additr" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                        <label for="lname"><b class="text-danger">*</b>Last Name</label>
                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="form-group">
                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suffix">Suffix <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="50" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                        <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">
                                        Download OPD Excel Masterlist
                                    </a>
                                </h5>
                            </div>
                            <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="form-group">
                                          <label for="year"><b class="text-danger">*</b>Select Year</label>
                                          <input type="number" class="form-control" name="year" id="year" min="2023" max="{{date('Y')}}" value="{{date('Y')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-block">Download (.XLSX)</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--
                    <div class="card">
                        <div class="card-header" role="tab" id="section2HeaderId">
                            <h5 class="mb-0">
                                <a data-toggle="collapse" data-parent="#accordianId" href="#section2ContentId" aria-expanded="true" aria-controls="section2ContentId">
                                    Test
                                </a>
                            </h5>
                        </div>
                        <div id="section2ContentId" class="collapse in" role="tabpanel" aria-labelledby="section2HeaderId">
                            <div class="card-body">
                                <p>Test</p>
                            </div>
                        </div>
                    </div>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
        theme: 'bootstrap',
    });

    //Region Select Initialize
    $.getJSON("{{asset('json/refregion.json')}}", function(data) {
        var sorted = data.sort(function(a, b) {
            if (a.regDesc > b.regDesc) {
                return 1;
            }
            if (a.regDesc < b.regDesc) {
                return -1;
            }

            return 0;
        });

        $.each(sorted, function(key, val) {
            $('#address_region_code').append($('<option>', {
                value: val.regCode,
                text: val.regDesc,
                selected: (val.regCode == '04') ? true : false, //default is Region IV-A
            }));
        });
    });

    $('#address_region_code').change(function (e) { 
        e.preventDefault();
        //Empty and Disable
        $('#address_province_code').empty();
        $("#address_province_code").append('<option value="" selected disabled>Choose...</option>');

        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', true);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_region_text').val($('#address_region_code option:selected').text());

        $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.provDesc > b.provDesc) {
                return 1;
                }
                if (a.provDesc < b.provDesc) {
                return -1;
                }
                return 0;
            });

            $.each(sorted, function(key, val) {
                if($('#address_region_code').val() == val.regCode) {
                    $('#address_province_code').append($('<option>', {
                        value: val.provCode,
                        text: val.provDesc,
                        selected: (val.provCode == '0421') ? true : false, //default for Cavite
                    }));
                }
            });
        });
    }).trigger('change');

    $('#address_province_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_province_text').val($('#address_province_code option:selected').text());

        $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.citymunDesc > b.citymunDesc) {
                    return 1;
                }
                if (a.citymunDesc < b.citymunDesc) {
                    return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_province_code').val() == val.provCode) {
                    $('#address_muncity_code').append($('<option>', {
                        value: val.citymunCode,
                        text: val.citymunDesc,
                        selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
                    })); 
                }
            });
        });
    }).trigger('change');

    $('#address_muncity_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_brgy_text').empty();
        $("#address_brgy_text").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', false);

        //Set Values for Hidden Box
        $('#address_muncity_text').val($('#address_muncity_code option:selected').text());

        $.getJSON("{{asset('json/refbrgy.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.brgyDesc > b.brgyDesc) {
                return 1;
                }
                if (a.brgyDesc < b.brgyDesc) {
                return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_muncity_code').val() == val.citymunCode) {
                    $('#address_brgy_text').append($('<option>', {
                        value: val.brgyDesc.toUpperCase(),
                        text: val.brgyDesc.toUpperCase(),
                    }));
                }
            });
        });
    }).trigger('change');

    $('#address_region_text').val('REGION IV-A (CALABARZON)');
    $('#address_province_text').val('CAVITE');
    $('#address_muncity_text').val('GENERAL TRIAS');
</script>
@endsection