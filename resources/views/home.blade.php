@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>Welcome: {{strtoupper(auth()->user()->name)}}</div>
                        <div>Date: {{date('m/d/Y (D)')}} | Morbidity Week: {{date('W')}}</div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <div><b>COVID-19 Menu</b></div>
                        <div>
                            @if(auth()->user()->canaccess_covid == 1)
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changemenu">Change</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    
                    @if(session('status'))
                        <div class="text-center alert alert-{{session('statustype')}}" role="alert">
                            {{session('status')}}
                        </div>
                    @endif
                    <button type="button" class="btn btn-secondary btn-lg btn-block" data-toggle="modal" data-target="#quicksearch"><i class="fas fa-search mr-2"></i>Patient Quick Search</button>
                    <hr>
                    <a href="{{route('records.index')}}" class="btn btn-primary btn-lg btn-block"><i class="fa fa-user mr-2" aria-hidden="true"></i>Patient Records</a>
                    <button class="btn btn-primary btn-lg btn-block" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                        <i class="fa fa-file mr-2" aria-hidden="true"></i>Case Investigation Forms (CIF)
                    </button>
                    <div class="collapse" id="collapse1">
                        <div class="card card-body border-primary">
                            <a href="{{route('forms.index')}}" class="btn btn-primary btn-block">View Swab Schedule</a>
                            @if(auth()->user()->isCesuAccount())
                            <a href="{{route('pendingshedchecker.index')}}" class="btn btn-primary btn-block">Pending Swab Counter</a>
                            @endif
                            @if(auth()->user()->isCesuAccount() || auth()->user()->isBrgyAccount())
                            <a href="{{route('paswab.view')}}" class="btn btn-primary btn-block">Pa-swab List <span class="badge badge-light ml-1">{{number_format($paswabctr)}}</span></a>
                            @endif
                            <!-- <a href="" class="btn btn-primary btn-block">Bulk Update CIF Status</a> -->
                        </div>
                    </div>
                    @if(auth()->user()->isCesuAccount())
                    <button class="btn btn-primary btn-lg btn-block mt-2" type="button" data-toggle="collapse" data-target="#ctCollapse" aria-expanded="false" aria-controls="ctCollapse">
                        Contact Tracing
                    </button>
                    <div class="collapse" id="ctCollapse">
                        <div class="card card-body border-primary">
                            <a href="{{route('ct.dashboard.index')}}" class="btn btn-primary btn-block">Contact Tracing Search</a>
                            <a href="{{route('ctlgu_report')}}" class="btn btn-primary btn-block">CT Report #2</a>
                            <a href="{{route('report.ct.index')}}" class="btn btn-primary btn-block">Contact Tracing Report</a>
                        </div>
                    </div>
                    <a href="{{route('selfreport.view')}}" class="btn btn-primary btn-lg btn-block mt-2">Self-Report <span class="badge badge-light ml-1">{{number_format($selfreport_count)}}</span></a>
                    @endif
                    @if(auth()->user()->canUseLinelist())
                    <a href="{{route('linelist.index')}}" class="btn btn-primary btn-lg btn-block mt-2"><i class="fas fa-archive mr-2"></i>Line List</a>
                    @endif
                    <button class="btn btn-primary btn-lg btn-block mt-2" type="button" data-toggle="collapse" data-target="#othersCollapse" aria-expanded="false" aria-controls="othersCollapse">
                        Others
                    </button>
                    <div class="collapse" id="othersCollapse">
                        <div class="card card-body border-primary">
                            @if(auth()->user()->ifTopAdmin())
                            <a href="{{route('acceptance.index')}}" class="btn btn-primary btn-block">Acceptance Letter</a>
                            @endif
                            <a href="{{route('casechecker_index')}}" class="btn btn-primary btn-block">Barangay Case Checker</a>
                        </div>
                    </div>
                    @if(auth()->user()->isCesuAccount() || auth()->user()->isBrgyAccount() && auth()->user()->brgy->displayInList == 1)
                    <hr>
                    <button class="btn btn-primary btn-lg btn-block mt-2" type="button" data-toggle="collapse" data-target="#reportCollapse" aria-expanded="false" aria-controls="reportCollapse"><i class="fas fa-chart-bar mr-2"></i>Reports</button>
                    <div class="collapse" id="reportCollapse">
                        <div class="card card-body border-primary">
                            @if(auth()->user()->canExportReport == 1)
                            <a href="{{route('report.index')}}" class="btn btn-primary btn-block" id="reportsbtn">View Report Dashboard / Summary<i class="fas fa-circle-notch fa-spin ml-2 d-none" id="reportLoading"></i></a>
                            <div id="reportNotice" class="text-center d-none">
                                <small>Note: Loading report might take a while to finish. Please be patient and do not refresh the page immediately.</small>
                            </div>
                            @endif
                            @if(auth()->user()->ifTopAdmin())
                            <button type="button" class="btn btn-success btn-block mt-3" data-toggle="modal" data-target="#exportModal"><i class="fas fa-file-excel mr-2"></i>Export Report to Excel</button>
                            <hr>
                            @endif
                            @if(auth()->user()->isCesuAccount())
                            <a href="{{route('report_cm_index')}}" class="btn btn-primary btn-block mt-3">Composite Measure</a>
                            <a href="{{route('clustering_index')}}" class="btn btn-primary btn-block mt-3">Confirmed Cases Clustering</a>
                            @endif
                            @if(auth()->user()->ifTopAdmin())
                            <a href="{{route('mw.index')}}" class="btn btn-primary btn-block mt-3">MW Report</a>
                            <a href="{{route('acceptance.index')}}" class="btn btn-primary btn-block">FHSIS M2 Monthly</a>
                            <a href="{{route('report.accomplishment')}}" class="btn btn-primary btn-block">Accomplishment Report</a>
                            @endif
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
                                    <option value="9">List of Patients Currently Admitted in General Trias Ligtas COVID-19 Facility #1</option>
                                    <option value="12">List of Patients Currently Admitted in General Trias Ligtas COVID-19 Facility #2 (Eagle Ridge, Brgy. Javalera)</option>
                                    <option value="10">List of Patients Currently On Strict Home Quarantine</option>
                                    <option value="11">List of Patients Currently Admitted in the Hospital/Other Isolation Facility</option>
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
                <div class="card-footer">
                    <p class="text-center">Note: If errors/issues has been found or if site not working properly, please contact CESU Staff Immediately.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="quicksearch" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Patient Quick Search</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="newList">Select Patient to Create or Search (If existing)</label>
                <select class="form-control" name="newList" id="newList"></select>
            </div>
        </div>
        </div>
    </div>
</div>

<form action="{{route('report.DOHExportAll')}}" method="POST" id="reportForm">
    @csrf
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Export Report to Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <label for="yearSelected">Select Year to Export</label>
                    <select class="form-control" name="yearSelected" id="yearSelected">
                        @foreach(range(date('Y'), 2019) as $y)
                        <option value="{{$y}}">{{$y}}</option>
                        @endforeach
                        <option value="">All</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" id="generateExcel" class="btn btn-primary btn-block"><i class="fas fa-download mr-2"></i>Generate COVID-19 Excel Database (.XLSX)<i class="fas fa-circle-notch fa-spin ml-2 d-none" id="downloadDohLoading"></i></button>
                    <div class="text-center d-none" id="downloadNotice"><small class="text-muted">Note: Downloading might take a while to finish. Please be patient.</small></div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="changemenu" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="{{route('abtc_home')}}" class="btn btn-primary btn-block">Animal Bite (ABTC)</a>
                <a href="{{route('vaxcert_home')}}" class="btn btn-primary btn-block">VaxCert Concerns</a>
                <a href="{{route('syndromic_home')}}" class="btn btn-primary btn-block">Syndromic (ITR)</a>
                <hr>
                <a href="{{route('pidsr.home')}}" class="btn btn-primary btn-block">PIDSR</a>
                <a href="{{route('fhsis_home')}}" class="btn btn-primary btn-block">eFHSIS</a>
            </div>
        </div>
    </div>
</div>

<script>
    $('#newList').select2({
        theme: "bootstrap",
        placeholder: 'Search by Name / Patient ID ...',
        ajax: {
            url: "{{route('forms.ajaxList')}}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id,
                            class: item.class,
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('#newList').change(function (e) { 
        e.preventDefault();
        var d = $('#newList').select2('data')[0].class;
        if(d == 'cif') {
            var url = "{{route("forms.new", ['id' => ':id']) }}";
        }
        else if (d == 'paswab') {
            var url = "{{route("paswab.viewspecific", ['id' => ':id']) }}";
        }

        url = url.replace(':id', $(this).val());
        window.location.href = url;
    });

    $('#reportsbtn').click(function (e) { 
        $(this).addClass('disabled');
        $('#reportNotice').removeClass('d-none');
        $('#reportLoading').removeClass('d-none');
    });

    $('#generateExcel').click(function (e) { 
        e.preventDefault();
        $('#downloadDohLoading').removeClass('d-none');
        $('#downloadNotice').removeClass('d-none');
        document.getElementById('reportForm').submit();
        $('#generateExcel').prop('disabled', true);
    });

    $('#yearSelected').change(function (e) { 
        e.preventDefault();
        $('#generateExcel').prop('disabled', false);
        $('#downloadDohLoading').addClass('d-none');
        $('#downloadNotice').addClass('d-none');
    });
</script>
@endsection
