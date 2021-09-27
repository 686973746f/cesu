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
            <!--<hr>
            <a href="{{route('report.situational.index')}}" class="btn btn-primary btn-block">COVID-19 Situational Report</a>
            <a href="{{route('report.situationalv2.index')}}" class="btn btn-primary btn-block">COVID-19 Situational Report V2</a>-->
            <hr>
            <a href="{{route('report.DOHExportAll')}}" class="btn btn-primary btn-block">Generate COVID-19 Excel Database</a>
        </div>
    </div>
</div>
@endsection