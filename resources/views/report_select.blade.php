@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header font-weight-bold text-center">Reports</div>
        <div class="card-body">
            @if(auth()->user()->isBrgyAccount())
            <div class="text-center">
                <h3 class="font-weight-bold text-primary">BRGY. {{auth()->user()->brgy->brgyName}}</h3>
            </div>
            <div class="alert alert-info text-center" role="alert">
                <strong>Note:</strong> Counting results from BRGY. {{auth()->user()->brgy->brgyName}} Data ONLY.
            </div>
            @endif
            <div id="accordianId" role="tablist" aria-multiselectable="true">
                <div class="card">
                    <div class="card-header text-center" role="tab" id="exportHeader">
                        <a data-toggle="collapse" data-parent="#accordianId" href="#exportContent" aria-expanded="true" aria-controls="exportContent"><i class="fas fa-file-excel mr-2"></i>Export Report to Excel</a>
                    </div>
                    <div id="exportContent" class="collapse in" role="tabpanel" aria-labelledby="exportHeader">
                        <div class="card-body">
                            @if(auth()->user()->isCesuAccount())
                            <form action="{{route('report.DOHExportAll')}}" method="POST" id="reportForm">
                                @csrf
                                <div class="form-group">
                                    <label for="yearSelected">Select Year to Export</label>
                                    <select class="form-control" name="yearSelected" id="yearSelected">
                                        @foreach(range(date('Y'), 2019) as $y)
                                        <option value="{{$y}}">{{$y}}</option>
                                        @endforeach
                                        <option value="">All</option>
                                    </select>
                                </div>
                                <button type="button" id="generateExcel" class="btn btn-primary btn-block"><i class="fas fa-download mr-2"></i>Generate COVID-19 Excel Database (.XLSX)<i class="fas fa-circle-notch fa-spin ml-2 d-none" id="downloadDohLoading"></i></button>
                                <div class="text-center d-none" id="downloadNotice"><small class="text-muted">Note: Downloading might take a while to finish. Please be patient.</small></div>
                            </form>
                            <hr>
                            @if(auth()->user()->ifTopAdmin())
                            <a href="{{route('report.dilgExportAll')}}"><button type="button" name="" id="" class="btn btn-primary btn-block"><i class="fas fa-download mr-2"></i>DILG</button></a>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($activeCount)}} <small>({{round(($activeCount/$totalCasesCount) * 100, 1)}}%)</small></h4>
                            <p class="card-text">Total Active Cases</p>
                            <hr>
                            <p>Partial Vaccinated: {{number_format($totalActive_partialVaccinated)}}</p>
                            <p class="mb-0">Fully Vaccinated: {{number_format($totalActive_fullyVaccinated)}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($recoveredCount)}} <small>({{round(($recoveredCount/$totalCasesCount) * 100, 1)}}%)</small></h4>
                            <p class="card-text">Total Recoveries</p>
                            <hr>
                            <p>Partial Vaccinated: {{number_format($totalRecovered_partialVaccinated)}}</p>
                            <p class="mb-0">Fully Vaccinated: {{number_format($totalRecovered_fullyVaccinated)}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-dark">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($deathCount)}} <small>({{round(($deathCount/$totalCasesCount) * 100, 1)}}%)</small></h4>
                            <p class="card-text">Total Deaths</p>
                            <hr>
                            <p>Partial Vaccinated: {{number_format($totalDeath_partialVaccinated)}}</p>
                            <p class="mb-0">Fully Vaccinated: {{number_format($totalDeath_fullyVaccinated)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-2">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($newActiveCount)}}</h4>
                            <p class="card-text">New Cases</p>
                            <hr>
                            <small>Partial Vaccinated: {{number_format($newActiveCount_partialVaccinated)}}</small>
                            <small>Fully Vaccinated: {{number_format($newActiveCount_fullyVaccinated)}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($lateActiveCount)}}</h4>
                            <p class="card-text">Late Reported Cases</p>
                            <hr>
                            <small>Partial Vaccinated: {{number_format($lateActiveCount_partialVaccinated)}}</small>
                            <small>Fully Vaccinated: {{number_format($lateActiveCount_fullyVaccinated)}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($newRecoveredCount)}}</h4>
                            <p class="card-text">New Recoveries</p>
                            <hr>
                            <small>Partial Vaccinated: {{number_format($newRecoveredCount_partialVaccinated)}}</small>
                            <small>Fully Vaccinated: {{number_format($newRecoveredCount_fullyVaccinated)}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($lateRecoveredCount)}}</h4>
                            <p class="card-text">Late Reported Recoveries</p>
                            <hr>
                            <small>Partial Vaccinated: {{number_format($lateRecoveredCount_partialVaccinated)}}</small>
                            <small>Fully Vaccinated: {{number_format($lateRecoveredCount_fullyVaccinated)}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-dark">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($newDeathCount)}}</h4>
                            <p class="card-text">New Deaths</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card text-white bg-danger mb-3">
                <div class="card-body text-center">
                    <h4 class="card-title font-weight-bold">{{number_format(($totalCasesCount))}}</h4>
                    <p class="card-text">Total Number of Cases</p>
                    <hr>
                    <p>Partial Vaccinated: {{number_format($totalCases_partialVaccinated)}}</p>
                    <p class="mb-0">Fully Vaccinated: {{number_format($totalCases_fullyVaccinated)}}</p>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold"><i class="fas fa-hotel mr-2"></i>{{number_format($facilityCount)}}</h4>
                            <p class="card-text">Admitted in the City of General Trias Ligtas COVID-19 Facility #1</p>
                            <p class="card-text">(Gen. Trias Sports Park, Brgy. Santiago)</p>
                        </div>
                    </div>
                    <div class="card text-white bg-secondary mt-3">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold"><i class="fas fa-hotel mr-2"></i>{{number_format($facilityTwoCount)}}</h4>
                            <p class="card-text">Admitted in the City of General Trias Ligtas COVID-19 Facility #2</p>
                            <p class="card-text">(Eagle Ridge, Brgy. Javalera)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold"><i class="fas fa-home mr-2"></i>{{number_format($hqCount)}}</h4>
                            <p class="card-text">On Strict Home Quarantine</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold"><i class="far fa-hospital mr-2"></i>{{number_format($hospitalCount)}}</h4>
                            <p class="card-text">Admitted in the Hospital/Other Isolation Facility</p>
                        </div>
                    </div>
                </div>
            </div>
            @if(auth()->user()->isCesuAccount())
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Infection Rate</th>
                            <th>Recovery Rate</th>
                            <th>Case Fatality Rate</th>
                            <th>Positivity Rate</th>
                            <th>Home Quarantine</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2" style="vertical-align: middle;">1 per 1,000 pop.</td>
                            <td>{{number_format($recoveredCount)}} / {{number_format($totalCasesCount)}}</td>
                            <td>{{number_format($deathCount)}} / {{number_format($totalCasesCount)}}</td>
                            <td>{{number_format($totalCasesCount)}} / {{number_format($allCasesCount)}}</td>
                            <td>{{number_format($hqCount)}} / {{number_format($activeCount)}}</td>
                        </tr>
                        <tr>
                            <td>{{round(($recoveredCount/$totalCasesCount) * 100, 1)}}%</td>
                            <td>{{round(($deathCount/$totalCasesCount) * 100, 1)}}%</td>
                            <td>{{round(($totalCasesCount/$allCasesCount) * 100, 1)}}%</td>
                            <td>{{round(($hqCount/$activeCount) * 100, 1)}}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="font-weight-bold text-center">
                                <tr class="bg-danger text-white">
                                    <th colspan="5">DATA PER BARANGAY</th>
                                </tr>
                                <tr class="thead-light">
                                    <th>Barangay</th>
                                    <th class="text-danger">Confirmed</th>
                                    <th>Active</th>
                                    <th>Deaths</th>
                                    <th class="text-success">Recoveries</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalConfirmed = 0;
                                $totalActive = 0;
                                $totalDeaths = 0;
                                $totalRecoveries = 0;
                                @endphp
                                @foreach($brgylist as $brgy)
                                <tr>
                                    <td class="font-weight-bold">{{$brgy['name']}}</td>
                                    <td class="text-danger text-center">{{number_format($brgy['confirmed'])}}</td>
                                    <td class="text-center">{{number_format($brgy['active'])}}</td>
                                    <td class="text-center">{{number_format($brgy['deaths'])}}</td>
                                    <td class="text-success text-center">{{number_format($brgy['recoveries'])}}</td>
                                </tr>
                                @php
                                $totalConfirmed += $brgy['confirmed'];
                                $totalActive += $brgy['active'];
                                $totalDeaths += $brgy['deaths'];
                                $totalRecoveries += $brgy['recoveries'];
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light text-center font-weight-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td class="text-danger">{{number_format($totalConfirmed)}}</td>
                                    <td>{{number_format($totalActive)}}</td>
                                    <td>{{number_format($totalDeaths)}}</td>
                                    <td class="text-success">{{number_format($totalRecoveries)}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="font-weight-bold text-center">
                                <tr>
                                    <th colspan="3" class="bg-danger text-white">SUSPECTED/PROBABLE CASES PER BARANGAY</th>
                                </tr>
                                <tr class="thead-light">
                                    <th>Barangay</th>
                                    <th>Suspected</th>
                                    <th>Probable</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalSuspected = 0;
                                $totalProbable = 0;
                                @endphp
                                @foreach($brgylist as $brgy)
                                <tr>
                                    <td class="font-weight-bold">{{$brgy['name']}}</td>
                                    <td class="text-center">{{$brgy['suspected']}}</td>
                                    <td class="text-center">{{$brgy['probable']}}</td>
                                </tr>
                                @php
                                $totalSuspected += $brgy['suspected'];
                                $totalProbable += $brgy['probable'];
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light text-center font-weight-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td>{{number_format($totalSuspected)}}</td>
                                    <td>{{number_format($totalProbable)}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#displayList').click(function (e) { 
        $(this).addClass('disabled');
        $('#displayListNotice').removeClass('d-none');
        $('#displayListLoading').removeClass('d-none');
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