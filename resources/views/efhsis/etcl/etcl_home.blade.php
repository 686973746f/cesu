@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>{{ $type }}</b></div>
                        <div><b>Facility:</b> {{auth()->user()->etclBhs->facility_name}}
                        
                        @if(!empty(auth()->user()->getBhsSwitchList()) || auth()->user()->isMasterAdminEtcl())
                        <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#switchBhs"><i class="fa fa-exchange-alt mr-2" aria-hidden="true"></i> Switch BHS</button>
                        @endif
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newPatientModal"><i class="fa fa-user mr-2" aria-hidden="true"></i> New/Search Patient</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#printTcl"><i class="fa fa-print mr-2" aria-hidden="true"></i> Print TCL</button>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}">
                        <div>{{session('msg')}}</div>
                        @if(session('from_etcl'))
                        <hr>
                        <div>To view the patient record, click <a href="{{route('syndromic_viewPatient', session('p'))}}">here</a>.</div>
                        @endif
                    </div>
                @endif

                <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#filterModal"><i class="fa fa-search mr-2" aria-hidden="true"></i> Filter</button>

                <form action="{{ url()->current() }}" method="GET">
                    @foreach(request()->query() as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Filter</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                      <label for="year"><b class="text-warning">*</b>Select Year</label>
                                      <input type="number" class="form-control" name="year" id="year" min="2024" max="{{date('Y')}}" value="{{date('Y')}}" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success btn-block">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
    
                @if($type == 'maternal_care')
                    @include('efhsis.etcl.maternalcare_list')
                @elseif($type == 'child_care')
                    @include('efhsis.etcl.childcare_list')
                @elseif($type == 'child_nutrition')
                    @include('efhsis.etcl.childnutrition_list')
                @elseif($type == 'family_planning')
                    @include('efhsis.etcl.familyplanning_list')
                @else
                    <div class="alert alert-warning">
                        Please select a valid eTCL module from the <a href="{{route('etcl_home')}}">eTCL Home</a>.
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('syndromic.newpatient_modal')

    <form action="{{route('etcl_generatetcl')}}" method="POST">
        @csrf
        <div class="modal fade" id="printTcl" tabindex="-1" role="dialog" aria-labelledby="printTclLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Print TCL</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <input type="hidden" name="etcl_type" value="{{$type}}">
                    @if($type == 'family_planning')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Year</label>
                            <select class="form-control" name="year" id="year" required>
                            @foreach(range(date('Y'), 2026) as $y)
                            <option value="{{$y}}">{{$y}}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="start_date"><b class="text-danger">*</b>Registration Date Start</label>
                                  <input type="date" class="form-control" name="start_date" id="start_date" value="{{date('Y-01-01')}}" max="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date"><b class="text-danger">*</b>Registration Date End</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                        </div>
                        @php
                        $filter = false;
                        @endphp
                        @if(auth()->user()->isMasterAdminEtcl() && $filter)
                        <div class="form-group">
                          <label for="filter_type"><b class="text-danger">*</b>Filter Type</label>
                          <select class="form-control" name="filter_type" id="filter_type" required>
                            <option value="" disabled selected>Choose...</option>
                            <option value="BHS">By BHS</option>
                            <option value="Barangay">By Barangay</option>
                          </select>
                        </div>
                        <div id="generate_bhs_div" class="d-none">
                            <div class="form-group">
                                <label for="selected_bhs_id"><b class="text-danger">*</b>Selected BHS</label>
                                <select class="form-control" name="selected_bhs_id" id="selected_bhs_id">
                                  @foreach(App\Models\DohFacility::where('facility_type', 'Barangay Health Station')
                                  ->where('address_muncity', 'CITY OF GENERAL TRIAS')
                                  ->orderBy('facility_name')
                                  ->get() as $bhs)
                                  <option value="{{$bhs->id}}">{{$bhs->facility_name}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="generate_brgy_div" class="d-none">
                            <div class="form-group">
                                <label for="selected_brgy_id"><b class="text-danger">*</b>Selected Barangay</label>
                                <select class="form-control" name="selected_brgy_id" id="selected_brgy_id">
                                  @foreach(App\Models\EdcsBrgy::where('city_id', 388)
                                  ->orderBy('name')
                                  ->get() as $brgy)
                                  <option value="{{$brgy->id}}">{{$brgy->name}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Generate TCL Excel File</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if(!empty(auth()->user()->getBhsSwitchList()) || auth()->user()->isMasterAdminEtcl())
    <form action="{{ route('etcl_switchbhs') }}" method="POST">
        @csrf
        <div class="modal fade" id="switchBhs" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Switch BHS</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="switch_bhs_list"><b class="text-danger">*</b>Select BHS</label>
                          <select class="form-control" name="switch_bhs_list" id="switch_bhs_list" required>
                            <option value="" disabled selected>Choose...</option>
                            @if(auth()->user()->isMasterAdminEtcl())
                            @foreach(App\Models\DohFacility::where('id', '!=',auth()->user()->etcl_bhs_id)
                            ->where('facility_type', 'Barangay Health Station')
                            ->where('address_muncity', 'CITY OF GENERAL TRIAS')
                            ->get() as $bhs)
                            <option value="{{ $bhs->id }}">{{ $bhs->facility_name }}</option>
                            @endforeach
                            @else
                            @foreach(App\Models\DohFacility::where('id', '!=',auth()->user()->etcl_bhs_id)
                            ->whereIn('id', auth()->user()->getBhsSwitchList())
                            ->get() as $bhs)
                            <option value="{{ $bhs->id }}">{{ $bhs->facility_name }}</option>
                            @endforeach
                            @endif
                          </select>
                        </div>
                        <input type="hidden" name="etcl_type" value="{{$type}}">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endif

    <script>
        $('#start_date').on('change', function () {
            $('#end_date').prop('min', $(this).val());
        });

        @if(auth()->user()->isMasterAdminEtcl())
        $('#filter_type').change(function (e) { 
            e.preventDefault();
            $('#generate_bhs_div').addClass('d-none');
            $('#selected_bhs_id').prop('required', false);
            $('#generate_brgy_div').addClass('d-none');
            $('#selected_brgy_id').prop('required', false);
            
            if($(this).val() == 'BHS') {
                $('#generate_bhs_div').removeClass('d-none');
                $('#selected_bhs_id').prop('required', true);
                $('#generate_brgy_div').addClass('d-none');
                $('#selected_brgy_id').prop('required', false);
            }
            else if($(this).val() == 'Barangay') {
                $('#generate_bhs_div').addClass('d-none');
                $('#selected_bhs_id').prop('required', false);
                $('#generate_brgy_div').removeClass('d-none');
                $('#selected_brgy_id').prop('required', true);
            }
        });
        @endif
    </script>
@endsection