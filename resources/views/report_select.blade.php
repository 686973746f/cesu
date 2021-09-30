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
            <a href="{{route('report.daily')}}" class="btn btn-primary btn-block">Daily Report</a>
            <a href="" class="btn btn-primary btn-block">Barangay Report</a>
            <a href="" class="btn btn-primary btn-block">Company Report</a>
            <hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($activeCount)}}</h4>
                            <p class="card-text">Total Active Cases</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($recoveredCount)}}</h4>
                            <p class="card-text">Total Recoveries</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-dark">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold">{{number_format($deathCount)}}</h4>
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
            <a href="{{route('reportv2.dashboard')}}" class="btn btn-primary btn-block mb-2">List of All Cases</a>
            <a href="{{route('report.DOHExportAll')}}"><button type="button" name="" id="generateExcel" class="btn btn-primary btn-block">Generate COVID-19 Excel Database</button></a>
        </div>
    </div>
</div>
<script>
    $('#generateExcel').click(function (e) { 
        $(this).prop('disabled', true);
    });
</script>
@endsection