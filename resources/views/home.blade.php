@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>Welcome: {{strtoupper(auth()->user()->name)}}</div>
                        <div>Morbidity Week: {{$currentWeek}}</div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="text-center alert alert-{{session('statustype')}}" role="alert">
                            {{session('status')}}
                        </div>
                    @endif

                    <a href="{{route('records.index')}}" class="btn btn-primary btn-lg btn-block"><i class="fa fa-user mr-2" aria-hidden="true"></i>Patient Information</a>
                    <button class="btn btn-primary btn-lg btn-block" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                        <i class="fa fa-file mr-2" aria-hidden="true"></i>Case Investigation Forms
                    </button>
                    <div class="collapse" id="collapse1">
                        <div class="card card-body">
                            <a href="{{route('forms.index')}}" class="btn btn-primary btn-lg btn-block">View/Create CIFs</a>
                            @if(auth()->user()->isCesuAccount())
                            <a href="{{route('pendingshedchecker.index')}}" class="btn btn-primary btn-lg btn-block">Pending Swab Counter</a>
                            @endif
                            @if(auth()->user()->isCesuAccount() || auth()->user()->isBrgyAccount())
                            <a href="{{route('paswab.view')}}" class="btn btn-primary btn-lg btn-block">Pa-swab List <span class="badge badge-light ml-1">{{number_format($paswabctr)}}</span></a>
                            @endif
                            <a href="{{route('bulkupdate.index')}}" class="btn btn-primary btn-lg btn-block">Bulk Update CIF Status</a>
                        </div>
                    </div>
                    @if(auth()->user()->isCesuAccount())
                    <button class="btn btn-primary btn-lg btn-block mt-2" type="button" data-toggle="collapse" data-target="#ctCollapse" aria-expanded="false" aria-controls="ctCollapse">
                        Contact Tracing
                    </button>
                    <div class="collapse" id="ctCollapse">
                        <div class="card card-body">
                            <a href="{{route('ct.dashboard.index')}}" class="btn btn-primary btn-lg btn-block">Contact Tracing Search</a>
                            <a href="{{route('report.ct.index')}}" class="btn btn-primary btn-lg btn-block">Contact Tracing Report</a>
                        </div>
                    </div>
                    <a href="{{route('selfreport.view')}}" class="btn btn-primary btn-lg btn-block mt-2">Self-Report</a>
                    @endif
                    @if(auth()->user()->canUseLinelist())
                    <a href="{{route('linelist.index')}}" class="btn btn-primary btn-lg btn-block mt-2"><i class="fas fa-archive mr-2"></i>Line List</a>
                    @endif
                    @if(auth()->user()->isCesuAccount() || auth()->user()->isBrgyAccount() && auth()->user()->brgy->displayInList == 1)
                    <hr>
                    <button class="btn btn-primary btn-lg btn-block mt-2" type="button" data-toggle="collapse" data-target="#reportCollapse" aria-expanded="false" aria-controls="reportCollapse"><i class="fas fa-chart-bar mr-2"></i>Reports</button>
                    <div class="collapse" id="reportCollapse">
                        <div class="card card-body">
                            <a href="{{route('report.index')}}" class="btn btn-primary btn-lg btn-block" id="reportsbtn">View Report Dashboard / Summary<i class="fas fa-circle-notch fa-spin ml-2 d-none" id="reportLoading"></i></a>
                            <div id="reportNotice" class="text-center d-none">
                                <small>Note: Loading report might take a while to finish. Please be patient and do not refresh the page immediately.</small>
                            </div>
                            <hr>
                            <form action="{{route('reportv2.dashboard')}}" method="GET">
                                <label for="">Or View Report per List</label>
                                <div class="input-group">
                                    <select class="custom-select" id="getOption" name="getOption" required>
                                    <option value="" disabled selected>Choose...</option>
                                    <option value="1">List of Newly Reported Active Cases</option>
                                    <option value="2">List of Late Reported Active Cases</option>
                                    <option value="3">List of Newly Reported Recovered Cases</option>
                                    <option value="4">List of Late Reported Recovered Cases</option>
                                    <option value="5">List of Newly Reported Death Cases</option>
                                    <option disabled>-----</option>
                                    <option value="6">List of Total Active Cases</option>
                                    <option value="7">List of Total Recoveries</option>
                                    <option value="8">List of Total Deaths</option>
                                    <option disabled>-----</option>
                                    <option value="9">List of Patients Admitted in General Trias Ligtas COVID-19 Facility #1</option>
                                    <option value="12">List of Patients Admitted in General Trias Ligtas COVID-19 Facility #2 (Eagle Ridge, Brgy. Javalera)</option>
                                    <option value="10">List of Patients On Strict Home Quarantine</option>
                                    <option value="11">List of Patients Admitted in the Hospital/Other Isolation Facility</option>
                                    </select>
                                    <div class="input-group-append">
                                    <button class="btn btn-outline-success" type="submit">Go</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    <hr>
                    <a href="{{route('options.index')}}" class="btn btn-secondary btn-lg btn-block"><i class="fa fa-cog mr-2" aria-hidden="true"></i>Options</i></a>
                    @if(auth()->user()->isAdmin == 1)
                    <hr>
                    <a href="{{route('adminpanel.index')}}" class="btn btn-primary btn-lg btn-block"><i class="fas fa-user-cog mr-2"></i>Admin Panel</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#reportsbtn').click(function (e) { 
        $(this).addClass('disabled');
        $('#reportNotice').removeClass('d-none');
        $('#reportLoading').removeClass('d-none');
    });
</script>
@endsection
