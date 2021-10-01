@extends('layouts.app')

@section('content')
<div class="container">
    @if(auth()->user()->isCesuAccount())
    <form action="{{route('report.export')}}" method="POST">
        @csrf
        <div class="card mb-3">
            <div class="card-header font-weight-bold">Export to Excel</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="eStartDate">From</label>
                          <input type="date" class="form-control" name="eStartDate" id="eStartDate" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="eEndDate">To</label>
                            <input type="date" class="form-control" name="eEndDate" id="eEndDate" value="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="rType">Report Type</label>
                  <select class="form-control" name="rType" id="rType" required>
                    <option value="" selected disabled>Choose...</option>
                    <option value="DOH">DOH Report Format</option>
                    <option value="CIF">CIF Report Format</option>
                  </select>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Export</button>
            </div>
        </div>
    </form>
    @endif
    <div class="card">
        <div class="card-header font-weight-bold">Reports</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{route('reportv2.dashboard')}}" class="btn btn-primary btn-lg btn-block mb-2">Display List of All Cases</a>
                </div>
                <div class="col-md-6">
                    <a href="{{route('report.DOHExportAll')}}"><button type="button" name="" id="generateExcel" class="btn btn-primary btn-lg btn-block"><i class="fas fa-download mr-2"></i>Generate COVID-19 Excel Database (.XLSX)</button></a>
                    <div class="text-center"><small class="text-muted" id="downloadNotice">Note: Downloading might take a while to finish. Please be patient.</small></div>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($activeCount)}} <small>({{round(($activeCount/$totalCasesCount) * 100, 1)}}%)</small></h4>
                            <p class="card-text">Total Active Cases</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($recoveredCount)}} <small>({{round(($recoveredCount/$totalCasesCount) * 100, 1)}}%)</small></h4>
                            <p class="card-text">Total Recoveries</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-dark">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($deathCount)}} <small>({{round(($deathCount/$totalCasesCount) * 100, 1)}}%)</small></h4>
                            <p class="card-text">Total Deaths</p>
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
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($lateActiveCount)}}</h4>
                            <p class="card-text">Late Cases</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($newRecoveredCount)}}</h4>
                            <p class="card-text">New Recoveries</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($lateRecoveredCount)}}</h4>
                            <p class="card-text">Late Recoveries</p>
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
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold"><i class="fas fa-hotel mr-2"></i>{{number_format($facilityCount)}}</h4>
                            <p class="card-text">Admitted in the City of General Trias Ligtas COVID-19 Facility</p>
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
            <hr>
            <div class="row">
                <div class="col-md-6">
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
                <div class="col-md-6">
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
<script>
    $('#downloadNotice').hide();

    $('#generateExcel').click(function (e) { 
        $(this).prop('disabled', true);
        $('#downloadNotice').show();
    });
</script>
@endsection